<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->get();
        return view('notifications.index', compact('notifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'keterangan' => 'required',
        ]);

        Notification::create($request->all());

        return redirect('/update-now')->with('success', 'Notifikasi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required',
            'keterangan' => 'required',
        ]);

        $notification = Notification::findOrFail($id);
        $notification->update($request->all());

        return redirect('/update-now')->with('success', 'Notifikasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect('/update-now')->with('success', 'Notifikasi berhasil dihapus.');
    }

    // API to get notifications for the bell overlay
    public function apiGetNotifications()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    // View specific notification (Read only)
    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        return view('notifications.show', compact('notification'));
    }
}
