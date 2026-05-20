<?php

namespace App\Http\Controllers;

use App\Models\Warning;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarningController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:moderator,admin');
    }

    public function index()
    {
        $warnings = Warning::with(['user', 'issuer'])->latest()->paginate(15);
        $users = User::orderBy('username')->limit(250)->get();

        return view('forum.moderation.warnings', compact('warnings', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:1000',
        ]);

        $warning = Warning::create([
            'user_id' => $data['user_id'],
            'issued_by' => Auth::id(),
            'reason' => $data['reason'],
        ]);

        // Create a simple notification for the warned user
        Notification::create([
            'user_id' => $warning->user_id,
            'type' => 'warning_issued',
            'data' => [
                'message' => $warning->reason,
                'issuer_id' => Auth::id(),
            ],
        ]);

        return redirect()->route('moderation.warnings')->with('success', 'Warning issued.');
    }

    public function destroy(Warning $warning)
    {
        $warning->delete();

        return redirect()->route('moderation.warnings')->with('success', 'Warning revoked.');
    }
}
