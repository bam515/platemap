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
        Schema::create('place_dishes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('place_id')->constrained('places')->cascadeOnDelete();

            // name = 보여주는 이름, name_norm = 매칭용 (정규화된 키)
            $table->string('name', 100);
            $table->string('name_norm', 120);

            $table->timestamps();

            // 같은 식당에서 같은 메뉴는 한 개만 존재하도록
            $table->unique(['place_id', 'name_norm'], 'uq_place_dishes_place_norm');
            $table->index(['place_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('place_dishes');
    }
};
