<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Room;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RoomRental;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RealisticDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users
        $cashier = User::create([
            'name' => 'Sara (Cashier)',
            'email' => 'cashier@hotel.com',
            'password' => Hash::make('password123'),
            'role' => 'cashier',
            'is_active' => true,
        ]);

        $receptionist = User::create([
            'name' => 'Dawit (Reception)',
            'email' => 'reception@hotel.com',
            'password' => Hash::make('password123'),
            'role' => 'receptionist',
            'is_active' => true,
        ]);

        $employee1 = User::create([
            'name' => 'Abebe (Waiter)',
            'email' => 'abebe@hotel.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'is_active' => true,
        ]);

        $employee2 = User::create([
            'name' => 'Tigist (Waitress)',
            'email' => 'tigist@hotel.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'is_active' => true,
        ]);

        // 2. Products
        $catBeer = Category::where('name_en', 'Beer')->first();
        $catFood = Category::where('name_en', 'Food')->first();
        $catSoft = Category::where('name_en', 'Soft Drink')->first();
        $catWater = Category::where('name_en', 'Water')->first();
        $catWhisky = Category::where('name_en', 'Whisky')->first();
        
        $products = [
            ['name_en' => 'St. George Beer', 'name_am' => 'ቅዱስ ጊዮርጊስ ቢራ', 'category_id' => $catBeer->id, 'selling_price' => 70, 'cost_price' => 50, 'track_stock' => true, 'stock_quantity' => 100],
            ['name_en' => 'Habesha Beer', 'name_am' => 'ሀበሻ ቢራ', 'category_id' => $catBeer->id, 'selling_price' => 75, 'cost_price' => 55, 'track_stock' => true, 'stock_quantity' => 150],
            ['name_en' => 'Walia Beer', 'name_am' => 'ዋልያ ቢራ', 'category_id' => $catBeer->id, 'selling_price' => 70, 'cost_price' => 50, 'track_stock' => true, 'stock_quantity' => 80],
            
            ['name_en' => 'Tibs', 'name_am' => 'ጥብስ', 'category_id' => $catFood->id, 'selling_price' => 450, 'cost_price' => 250, 'track_stock' => false, 'stock_quantity' => 0],
            ['name_en' => 'Shiro Tegabino', 'name_am' => 'ሽሮ ተጋቢኖ', 'category_id' => $catFood->id, 'selling_price' => 180, 'cost_price' => 80, 'track_stock' => false, 'stock_quantity' => 0],
            ['name_en' => 'Beyaynetu', 'name_am' => 'በየአይነቱ', 'category_id' => $catFood->id, 'selling_price' => 250, 'cost_price' => 120, 'track_stock' => false, 'stock_quantity' => 0],
            ['name_en' => 'Pasta with Meat', 'name_am' => 'ፓስታ በስጋ', 'category_id' => $catFood->id, 'selling_price' => 300, 'cost_price' => 150, 'track_stock' => false, 'stock_quantity' => 0],
            
            ['name_en' => 'Coca Cola', 'name_am' => 'ኮካ ኮላ', 'category_id' => $catSoft->id, 'selling_price' => 35, 'cost_price' => 25, 'track_stock' => true, 'stock_quantity' => 200],
            ['name_en' => 'Sprite', 'name_am' => 'ስፕራይት', 'category_id' => $catSoft->id, 'selling_price' => 35, 'cost_price' => 25, 'track_stock' => true, 'stock_quantity' => 200],
            
            ['name_en' => 'Highland Water (L)', 'name_am' => 'ሃይላንድ ውሃ (ትልቅ)', 'category_id' => $catWater->id, 'selling_price' => 30, 'cost_price' => 15, 'track_stock' => true, 'stock_quantity' => 300],
            ['name_en' => 'Highland Water (S)', 'name_am' => 'ሃይላንድ ውሃ (ትንሽ)', 'category_id' => $catWater->id, 'selling_price' => 20, 'cost_price' => 10, 'track_stock' => true, 'stock_quantity' => 300],

            ['name_en' => 'Black Label', 'name_am' => 'ብላክ ሌብል', 'category_id' => $catWhisky->id, 'selling_price' => 4500, 'cost_price' => 3500, 'track_stock' => true, 'stock_quantity' => 10],
            ['name_en' => 'Red Label', 'name_am' => 'ሬድ ሌብል', 'category_id' => $catWhisky->id, 'selling_price' => 3000, 'cost_price' => 2000, 'track_stock' => true, 'stock_quantity' => 15],
        ];

        foreach ($products as $p) {
            Product::create($p + ['is_active' => true, 'unit' => 'piece']);
        }

        // 3. Rooms
        $rooms = [
            ['room_number' => '101', 'name_en' => 'Standard Single Bed', 'name_am' => 'መደበኛ ነጠላ አልጋ', 'price_per_night' => 500, 'status' => 'available'],
            ['room_number' => '102', 'name_en' => 'Standard Single Bed', 'name_am' => 'መደበኛ ነጠላ አልጋ', 'price_per_night' => 500, 'status' => 'occupied'],
            ['room_number' => '103', 'name_en' => 'Standard Double Bed', 'name_am' => 'መደበኛ ድርብ አልጋ', 'price_per_night' => 800, 'status' => 'available'],
            ['room_number' => '201', 'name_en' => 'Deluxe Room', 'name_am' => 'ዲለክስ ክፍል', 'price_per_night' => 1200, 'status' => 'available'],
            ['room_number' => '202', 'name_en' => 'Deluxe Room', 'name_am' => 'ዲለክስ ክፍል', 'price_per_night' => 1200, 'status' => 'maintenance'],
        ];

        foreach ($rooms as $r) {
            Room::create($r);
        }

        // 4. Room Rentals
        $room102 = Room::where('room_number', '102')->first();
        RoomRental::create([
            'room_id' => $room102->id,
            'guest_name' => 'Kebede',
            'guest_phone' => '0911223344',
            'check_in' => now()->subHours(5),
            'nights' => 2,
            'original_price' => 1000,
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'discount_amount' => 100,
            'total_price' => 900,
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'receptionist_id' => $receptionist->id,
            'note' => 'Regular customer',
        ]);

        // 5. Orders (Past 3 days)
        $productsList = Product::all();
        $employees = [$employee1->id, $employee2->id];
        $paymentMethods = ['cash', 'bank_transfer', 'telebirr'];

        for ($i = 3; $i >= 0; $i--) {
            for ($j = 0; $j < 15; $j++) { // 15 orders per day
                $subtotal = 0;
                $date = now()->subDays($i)->subMinutes(rand(10, 800));
                
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber() . '-' . rand(100, 999),
                    'cashier_id' => $cashier->id,
                    'employee_id' => $employees[array_rand($employees)],
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'subtotal' => 0, // will update
                    'tax' => 0,
                    'total' => 0,
                    'order_date' => $date,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // 1-4 items per order
                $numItems = rand(1, 4);
                for ($k = 0; $k < $numItems; $k++) {
                    $prod = $productsList->random();
                    $qty = rand(1, 3);
                    $lineTotal = $prod->selling_price * $qty;
                    $subtotal += $lineTotal;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $prod->id,
                        'quantity' => $qty,
                        'unit_price' => $prod->selling_price,
                        'line_total' => $lineTotal,
                    ]);
                }
                
                $order->update([
                    'subtotal' => $subtotal,
                    'total' => $subtotal,
                ]);
            }
        }
    }
}
