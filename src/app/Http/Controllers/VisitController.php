<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'place_id' => ['required', 'integer', 'exists:places,id'],
            'visited_at' => ['required', 'date'],
            'visibility' => ['nullable', 'in:private,followers,public'],
            'memo' => ['nullable', 'string', 'max:500']
        ]);

        $visit = Visit::create([
            'user_id' => $request->user()->id,
            'place_id' => $validated['place_id'],
            'visited_at' => $validated['visited_at'],
            'visibility' => $validated['visibility'] ?? 'followers',
            'memo' => $validated['memo'] ?? null,
            'published_at' => null,
            'is_hidden' => false
        ]);

        return response()->json([
            'id' => $visit->id,
            'status' => 'draft'
        ], 201);
    }

    public function publish(Request $request, Visit $visit): JsonResponse
    {
        // 권한 체크 (내 visit만)
        abort_unless($visit->user_id === $request->user()->id, 403);

        // 트랜잭션 + row lock (동시 publish / 수정 방지)
        return DB::transaction(function () use ($visit) {
            // visit을 잠그고 최신 상태로 다시 조회
            $lockedVisit = Visit::query()
                ->whereKey($visit->id)
                ->lockForUpdate()
                ->firstOrFail();

            // 이미 숨김 / 삭제된 건 게시 불가
            if ($lockedVisit->is_hidden) {
                return response()->json(['message' => 'This visit is hidden'], 400);
            }

            // 이미 publish 된 경우: 상태전이 충돌 (409)
            if ($lockedVisit->published_at !== null) {
                return response()->json(['message' => 'Already published'], 409);
            }

            // dish_logs가 최소 1개 이상 있어야 publish 가능
            $dishLogCount = $lockedVisit->dishLogs()
                ->where('is_hidden', false)
                ->count();

            if ($dishLogCount < 1) {
                return response()->json(['message' => 'At least 1 dish log is required to publish'], 422);
            }

            // 상태 전이
            $lockedVisit->update([
                'published_at' => now(),
            ]);

            return response()->json([
                'id' => $lockedVisit->id,
                'published_at' => $lockedVisit->published_at,
            ]);
        }, 3); // 데드락 대비 재시도 3회
    }
}
