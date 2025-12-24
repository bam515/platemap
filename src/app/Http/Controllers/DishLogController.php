<?php

namespace App\Http\Controllers;

use App\Models\DishLog;
use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DishLogController extends Controller
{
    public function store(Request $request, Visit $visit): JsonResponse
    {
        abort_unless($visit->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'dish_name' => ['required', 'string', 'max:100'],
            'memo' => ['nullable', 'string', 'max:500'],
            'would_reorder' => ['nullable', 'boolean'],
            'taste_salty' => ['nullable', 'integer', 'min:0', 'max:5'],
            'taste_bland' => ['nullable', 'integer', 'min:0', 'max:5'],
            'taste_sweet' => ['nullable', 'integer', 'min:0', 'max:5'],
            'taste_spicy' => ['nullable', 'integer', 'min:0', 'max:5'],
            'taste_umami' => ['nullable', 'integer', 'min:0', 'max:5'],
            'taste_texture' => ['nullable', 'integer', 'min:0', 'max:5'],
            'photo_url' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $dishLog = DishLog::create([
            'visit_id' => $visit->id,
            'place_id' => $visit->place_id,
            'place_dish_id' => null,
            'dish_name' => $validated['dish_name'],
            'memo' => $validated['memo'] ?? null,
            'would_reorder' => $validated['would_reorder'] ?? false,
            'taste_salty' => $validated['taste_salty'] ?? null,
            'taste_bland' => $validated['taste_bland'] ?? null,
            'taste_sweet' => $validated['taste_sweet'] ?? null,
            'taste_spicy' => $validated['taste_spicy'] ?? null,
            'taste_umami' => $validated['taste_umami'] ?? null,
            'taste_texture' => $validated['taste_texture'] ?? null,
            'photo_url' => $validated['photo_url'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_hidden' => false
        ]);

        return response()->json(['id' => $dishLog->id], 201);
    }
}
