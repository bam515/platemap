<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function home(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $visits = Visit::query()
            ->with([
                'place:id,name,lat,lng,address',
                'dishLogs' => function ($q) {
                    $q->select('id',
                        'visit_id',
                        'dish_name',
                        'memo',
                        'would_reorder',
                        'photo_url',
                        'sort_order',
                        'is_hidden'
                    )->where('is_hidden', false)
                        ->orderBy('sort_order');
                }
            ])
            ->where('user_id', $userId)
            ->whereNotNull('published_at')
            ->where('is_hidden', false)
            ->orderByDesc('published_at')
            ->limit(20)
            ->get();

        $items = $visits->map(function (Visit $visit) {
            $dishLogs = $visit->dishLogs->map(fn ($d) => [
                'id' => $d->id,
                'dish_name' => $d->dish_name,
                'memo' => $d->memo,
                'would_reorder' => (bool) $d->would_reorder,
                'photo_url' => $d->photo_url,
                'sort_order' => (int) $d->sort_order
            ]);

            return [
                'visit_id' => $visit->id,
                'published_at' => $visit->published_at,
                'visited_at' => $visit->visited_at,
                'visibility' => $visit->visibility,
                'memo' => $visit->memo,
                'place' => [
                    'id' => $visit->place->id,
                    'name' => $visit->place->name,
                    'lat' => $visit->place->lat,
                    'lng' => $visit->place->lng,
                    'address' => $visit->place->address
                ],
                'dish_logs' => $dishLogs,
                'liked_by_me' => false,
                'like_count' => 0   // TODO likes 기능 개발 필요
            ];
        });

        return response()->json([
            'items' => $items,
            'next_cursor' => null   // TODO cursor pagination 개발 필요
        ]);
    }
}
