<?php

namespace App\Http\Controllers;

use App\Models\Chip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChipController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chips = Chip::with('user')->latest()->take(10)->get();

        return view('home', ['chips' => $chips]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        // Use the authenticated user
        Auth::user()->chips()->create($validated);

        // Redirect back to the feed
        return redirect('/')->with('success', 'Chip has been posted!');
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
    public function edit(Chip $chip)
    {
        $this->authorize('update', $chip);

        // We'll add authorization later
        return view('chips.edit', compact('chip'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chip $chip)
    {
        $this->authorize('update', $chip);

        // validate the request
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        // update the chip
        $chip->update($validated);

        return redirect('/')->with('success', 'Chip has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chip $chip)
    {
        $this->authorize('delete', $chip);
        
        $chip->delete();

        return redirect('/')->with('success', 'Chip has been deleted!');
    }
}
