<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();

            // 누가 좋아요를 눌렀는지
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // 무엇을 좋아요 눌렀는지 (visit / dish_logs / collections 등)
            $table->morphs('likeable');
            // => likable_type (string) + likeable_id (unsignedBigInteger) + index 생성

            $table->timestamps();

            // 같은 유저가 같은 대상에 중복 좋아요 못 누르게
            $table->unique(['user_id', 'likeable_type', 'likeable_id'], 'uq_likes');

            // 피드 / 상세에서 성능
            $table->index(['user_id', 'created_at'], 'idx_likes_user_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
