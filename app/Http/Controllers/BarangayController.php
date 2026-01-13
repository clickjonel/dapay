<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\OrganizationalIndicators;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BarangayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $barangays = Barangay::with(['province', 'municipality','organizationalIndicators'])
        ->when($request->user()->pdoho_province_id !== null, function($query) use ($request){
            $query->where('province_id', $request->user()->pdoho_province_id);
        })
        ->when($request->input('search'), function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('province', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('municipality', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        })
        ->simplePaginate(50)
        ->withQueryString();

        $organizational_indicators = OrganizationalIndicators::get();

        return Inertia::render('barangay/index',[
            'barangays' => $barangays,
            'organizational_indicators' => $organizational_indicators
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Barangay $barangay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barangay $barangay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barangay $barangay)
    {
        Barangay::find($barangay->id)->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barangay $barangay)
    {
        //
    }
}
