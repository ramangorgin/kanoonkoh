<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
   
    public function read($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    
        $notification->is_read = true;
        $notification->save();
    
        // می‌تونی به لینک خاصی هدایت کنی. فعلاً می‌فرستیم به داشبورد.
        return redirect()->route('dashboard.index');
    }
}
