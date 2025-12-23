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
        Schema::create('dish_logs', function (Blueprint $table) {
            $table->id();

            // 방문(부모) 삭제되면 메뉴로그도 같이 삭제
            $table->foreignId('visit_id')->constrained('visits')->cascadeOnDelete();

            // 집계/조회 편의용
            $table->foreignId('place_id')->constrained('places')->cascadeOnDelete();

            // "식당의 마스터 메뉴(place_dishes)" 가 있으면 연결 (없으면 NULL)
            // TODO place_dishes는 추후 개발
            $table->foreignId('place_dish_id')
                ->nullable()
                ->constrained('place_dishes')
                ->nullOnDelete();

            // 사용자가 입력한 메뉴명 (마스터가 없거나 매칭 실패해도 저장 가능)
            $table->string('dish_name', 100);

            // 한줄 코멘트 (장문 리뷰 금지)
            $table->string('memo', 500)->nullable();

            // "또 시킴?" 신호
            $table->boolean('would_reorder')->default(false);

            // 테이스팅 카드 (0 ~ 5 스케일, 미입력 가능)
            $table->unsignedTinyInteger('taste_salty')->nullable();     // 짠맛
            $table->unsignedTinyInteger('taste_bland')->nullable();     // 싱거움
            $table->unsignedTinyInteger('taste_sweet')->nullable();     // 단맛
            $table->unsignedTinyInteger('taste_spicy')->nullable();     // 매운맛
            $table->unsignedTinyInteger('taste_umami')->nullable();     // 감칠맛
            $table->unsignedTinyInteger('taste_texture')->nullable();   // 식감

            // 사진 (1장)
            $table->string('photo_url', 500)->nullable();

            // 입력 순서
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['visit_id', 'sort_order']);
            $table->index(['place_id', 'created_at']);
            $table->index(['place_dish_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_logs');
    }
};
