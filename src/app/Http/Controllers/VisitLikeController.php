<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Visit;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitLikeController extends Controller
{
    public function store(Request $request, Visit $visit): JsonResponse
    {
        // draft / hidden 좋아요 불가
        if ($visit->published_at === null || $visit->is_hidden) {
            return response()->json(['message' => 'Visit is not available'], 409);
        }

        // private은 작성자만 접근 가능
        if ($visit->visibility === 'private' && $visit->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $userId = $request->user()->id;

        try {
            $like = Like::create([
                'user_id' => $userId,
                'likeable_type' => Visit::class,
                'likeable_id' => $visit->id,
            ]);

            return response()->json([
                'liked' => true,
                'like_id' => $like->id
            ], 201);
        } catch (QueryException $e) {
            // 중복 좋아요는 이미 좋아요 된걸로 처리
            if ($e->getCode() === '23000') {
                return response()->json(['liked' => true], 200);
            }
            throw $e;
        }
    }

    public function destroy(Request $request, Visit $visit): JsonResponse
    {
        $userId = $request->user()->id;

        Like::query()
            ->where('user_id', $userId)
            ->where('likeable_type', Visit::class)
            ->where('likeable_id', $visit->id)
            ->delete();

        return response()->json(['liked' => false], 200);
    }
}
