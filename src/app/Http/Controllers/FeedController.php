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

        $limit = (int) $request->input('limit', 20);
        $limit = max(1, min($limit, 50));

        $cursor = $request->input('cursor');

        $paginator = Visit::query()
            ->select([
                'id',
                'user_id',
                'place_id',
                'published_at',
                'visited_at',
                'visibility',
                'memo',
                'is_hidden'
            ])
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
            ])->withCount('likes')
            ->withExists([
                'likes as liked_by_me' => fn ($q) => $q->where('user_id', $userId)
            ])->where('user_id', $userId)
            ->whereNotNull('published_at')
            ->where('is_hidden', false)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->cursorPaginate($limit, ['*'], 'cursor', $cursor);

        $items = collect($paginator->items())->map(function (Visit $visit) {
            return [
                'visit_id' => $visit->id,
                'published_at' => $visit->published_at,
                'visited_at' => $visit->visited_at,
                'visibility' => $visit->visibility,
                'memo' => $visit->memo,
                'place' => $visit->place ? [
                    'id' => $visit->place->id,
                    'name' => $visit->place->name,
                    'lat' => $visit->place->lat,
                    'lng' => $visit->place->lng,
                    'address' => $visit->place->address
                ] : null,
                'dish_logs' => $visit->dishLogs->map(fn ($d) => [
                    'id' => $d->id,
                    'dish_name' => $d->dish_name,
                    'would_reorder' => (bool) $d->would_reorder,
                    'photo_url' => $d->photo_url,
                    'sort_order' => (int) $d->sort_order,
                ]),
                'liked_by_me' => (bool) $visit->liked_by_me,
                'like_count' => (int) $visit->likes_count
            ];
        })->values();

        return response()->json([
            'items' => $items,
            'next_cursor' => $paginator->nextCursor()?->encode()
        ]);
    }
}
