<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth('customer')->user();
        $reservations = $user->reservations()
            ->with(['meetingRoom', 'foodPackage', 'payment'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Tandai semua notifikasi reschedule yang belum dibaca sebagai sudah dibaca
        $user->reservations()
            ->whereIn('reschedule_status', ['approved', 'rejected'])
            ->where('reschedule_notification_read', false)
            ->update(['reschedule_notification_read' => true]);
            
        return view('customer.profile.edit', compact('user', 'reservations'));
    }

    public function update(Request $request)
    {
        $user = auth('customer')->user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('customers')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'], // max 10MB
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
        ];

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $data['photo'] = $request->file('photo')->store('customers', 'public');
        }

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
