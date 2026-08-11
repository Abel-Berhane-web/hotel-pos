<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomRental;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class RoomRentalController extends Controller
{
    public function index()
    {
        $rentals = RoomRental::with('room', 'receptionist')->latest()->paginate(20);
        return view('rooms.rentals', compact('rentals'));
    }

    public function show(RoomRental $rental)
    {
        $rental->load('room', 'receptionist');
        return view('rooms.rental_show', compact('rental'));
    }

    public function store(Request $request, Room $room)
    {
        $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'nullable|string|max:20',
            'nights' => 'required|integer|min:1',
            'discount_type' => 'required|in:none,percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,bank_transfer,telebirr,cbe_birr,credit',
            'payment_status' => 'required|in:paid,pending',
            'note' => 'nullable|string',
        ]);

        $originalPrice = $room->price_per_night * $request->nights;
        $discountCalc = RoomRental::calculateDiscount(
            $originalPrice,
            $request->discount_type,
            $request->discount_value ?? 0
        );

        $isReservation = $request->has('is_reservation') && $request->is_reservation;
        $checkInDate = $isReservation ? \Carbon\Carbon::parse($request->check_in_date) : now();
        $checkOutDate = (clone $checkInDate)->addDays((int) $request->nights);

        // Check for overlaps
        $overlap = RoomRental::where('room_id', $room->id)
            ->whereIn('status', ['active', 'reserved'])
            ->where(function ($query) use ($checkInDate, $checkOutDate) {
                $query->whereBetween('check_in', [$checkInDate, $checkOutDate])
                      ->orWhereBetween('check_out', [$checkInDate, $checkOutDate])
                      ->orWhere(function ($q) use ($checkInDate, $checkOutDate) {
                          $q->where('check_in', '<=', $checkInDate)
                            ->where('check_out', '>=', $checkOutDate);
                      });
            })->exists();

        if ($overlap) {
            return back()->withErrors(['error' => 'Room is already booked or occupied during these dates.']);
        }

        $rental = RoomRental::create([
            'room_id' => $room->id,
            'status' => $isReservation ? 'reserved' : 'active',
            'guest_name' => $request->guest_name,
            'guest_phone' => $request->guest_phone,
            'check_in' => $checkInDate,
            'check_out' => $checkOutDate,
            'nights' => $request->nights,
            'original_price' => $originalPrice,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value ?? 0,
            'discount_amount' => $discountCalc['discount_amount'],
            'total_price' => $discountCalc['total_price'],
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'receptionist_id' => auth()->id(),
            'note' => $request->note,
        ]);

        if (!$isReservation) {
            $room->update(['status' => 'occupied']);
            AuditLog::log('room_rented', 'Room', $room->id, "Guest: {$request->guest_name}, Room: {$room->room_number}");
        } else {
            AuditLog::log('room_reserved', 'Room', $room->id, "Reserved by: {$request->guest_name} for {$checkInDate->format('Y-m-d')}");
        }

        if ($request->payment_status === 'pending') {
            $admins = \App\Models\User::whereIn('role', ['admin', 'manager'])->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\PendingPaymentNotification($rental));
        }

        return redirect()->route('rooms.index')->with('success', __('m.success'));
    }

    public function checkout(Room $room)
    {
        $rental = $room->currentRental;
        if ($rental) {
            $rental->update([
                'status' => 'completed',
                'check_out' => now(),
                'payment_status' => 'paid',
            ]);
        }
        $room->update(['status' => 'available']);
        AuditLog::log('room_checkout', 'Room', $room->id, "Room {$room->room_number} checked out");

        return redirect()->route('rooms.index')->with('success', __('m.success'));
    }

    public function confirmPayment(RoomRental $rental)
    {
        $rental->update(['payment_status' => 'paid']);
        AuditLog::log('room_payment_confirmed', 'RoomRental', $rental->id, "Payment confirmed for room {$rental->room->room_number}");
        return back()->with('success', __('m.success'));
    }

    public function reservations()
    {
        $reservations = RoomRental::with('room', 'receptionist')
            ->where('status', 'reserved')
            ->orderBy('check_in')
            ->paginate(20);
        return view('rooms.reservations', compact('reservations'));
    }

    public function checkInReservation(RoomRental $rental)
    {
        if ($rental->status !== 'reserved') {
            return back()->withErrors(['error' => 'Only reserved rentals can be checked in.']);
        }
        
        $rental->update(['status' => 'active', 'check_in' => now()]);
        $rental->room->update(['status' => 'occupied']);
        
        AuditLog::log('room_checkin', 'RoomRental', $rental->id, "Guest checked in for reservation: {$rental->guest_name}");
        return back()->with('success', 'Guest checked in successfully.');
    }

    public function cancelReservation(RoomRental $rental)
    {
        if ($rental->status !== 'reserved') {
            return back()->withErrors(['error' => 'Only reserved rentals can be cancelled.']);
        }
        
        $rental->update(['status' => 'cancelled']);
        AuditLog::log('room_cancelled', 'RoomRental', $rental->id, "Reservation cancelled: {$rental->guest_name}");
        return back()->with('success', 'Reservation cancelled.');
    }
}
