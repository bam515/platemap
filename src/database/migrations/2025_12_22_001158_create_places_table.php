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
        Schema::create('places', function (Blueprint $table) {
            $table->id();

            $table->string('name', 120);

            // 지도용 좌표
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();

            // 주소
            $table->string('address', 255)->nullable();
            $table->string('road_address', 255)->nullable();

            // 외부 장소 데이터 연동 대비
            $table->string('source', 20)->nullable();   // kakao, naver, google
            $table->string('source_place_id', 80)->nullable();

            // 옵션
            $table->string('category', 80)->nullable();
            $table->string('phone', 30)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // 탐색 성능
            $table->index(['lat', 'lng']);
            // 외부 ID로 중복 방지
            $table->unique(['source', 'source_place_id'], 'uq_places_source_place_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
