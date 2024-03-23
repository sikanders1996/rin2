<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function create(){
        return view('users.notification.create');
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|in:marketing,invoices,system',
            'text' => 'required|string',
            'expiration' => 'required|date',
            'destination_type' => 'required|string',
        ]);

        $notification = new Notification();
        $notification->type = $validatedData['type'];
        $notification->text = $validatedData['text'];
        $notification->expiration = $validatedData['expiration'];
        $notification->destination_type = $validatedData['destination_type'];
        $notification->user_id = 1;

        $notification->save();

        return redirect()->back()->with('success', 'Notification posted successfully.');
    }

}
