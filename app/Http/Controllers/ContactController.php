<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // 1) ذخیره در دیتابیس
        ContactMessage::create($data);

        // 2) (اختیاری) ایمیل اطلاع‌رسانی به مدیر
        /* Mail::raw(
            "پیام جدید از {$data['name']} ({$data['email']}):\n\n{$data['message']}",
            fn ($mail) => $mail->to('admin@kanoonkoh.ir')->subject('پیام جدید تماس با ما')
        ); */

        return back()->with('success', 'پیام شما با موفقیت ارسال شد. متشکریم!');
    }
}
