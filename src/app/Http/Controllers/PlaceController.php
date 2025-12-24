<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'address' => ['nullable', 'string', 'max:255'],
            'road_address' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:20'],
            'source_place_id' => ['nullable', 'string', 'max:80'],
            'category' => ['nullable', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        // source / source_place_id 중복 처리
        if (!empty($validated['source']) && !empty($validated['source_place_id'])) {
            $place = Place::updateOrCreate(
                ['source' => $validated['source'], 'source_place_id' => $validated['source_place_id']],
                $validated
            );
        } else {
            // manual 입력이면 create
            $place = Place::create($validated);
        }

        return response()->json([
            'id' => $place->id,
            'name' => $place->name,
            'lat' => $place->lat,
            'lng' => $place->lng,
            'address' => $place->address,
        ], 201);
    }
}
