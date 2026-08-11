<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('user_id')) $query->where('user_id', $request->user_id);
        if ($request->filled('action')) $query->where('action', 'like', '%' . $request->action . '%');
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('created_at', '<=', $request->date_to);

        $logs = $query->paginate(30);
        $users = User::orderBy('name')->get();

        return view('audit.index', compact('logs', 'users'));
    }
}
