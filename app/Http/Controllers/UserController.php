<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::when($request->input('search'), function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('nickname', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(50)
        ->withQueryString();

        return Inertia::render('user/index', [
            'users' => $users,
            'filters' => [
                'search' => $request->input('search')
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('user/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'prefix' => ['nullable', 'string', 'max:10'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'nickname' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:4', 'max:255', 'unique:users,username'],
        ]);

        // Create user with defaults
        $user = User::create([
            'prefix' => $validated['prefix'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'suffix' => $validated['suffix'],
            'nickname' => $validated['nickname'] ?? $validated['first_name'],
            'username' => $validated['username'],
            'password' => '12345',
            'account_status' => 'active',
            'user_level' => 5,
            'pdoho_province_id' => null,
            'program_id' => null,
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
