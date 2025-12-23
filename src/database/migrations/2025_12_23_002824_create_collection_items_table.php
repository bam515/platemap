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
        Schema::create('collection_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('collection_id')->constrained('collections')->cascadeOnDelete();
            $table->foreignId('place_id')->constrained('places')->cascadeOnDelete();

            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('note', 200)->nullable();

            $table->timestamps();

            // 같은 컬렉션에 같은 place 중복 방지
            $table->unique(['collection_id', 'place_id'], 'uq_collection_place');

            $table->index(['collection_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_items');
    }
};
