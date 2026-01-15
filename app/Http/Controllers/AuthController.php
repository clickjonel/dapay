<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Get the authenticated user
            $user = Auth::user();

            // Redirect based on user level
            switch ($user->user_level) {
                //admin
                case 1:
                    return redirect()->intended('/dashboard-geographic');

                //secretariat
                case 2:
                    return redirect()->intended('/dashboard-geographic');
                
                //secretariat
                case 3:
                    return redirect()->intended('/barangay');

                //secretariat
                case 4:
                    return redirect()->intended('/program');

                // default:
                //     return redirect()->intended('/dashboard');
            }
        }

        throw ValidationException::withMessages([
            'username' => 'The provided credentials are incorrect.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
