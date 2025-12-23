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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('place_id')->constrained()->cascadeOnDelete();

            $table->dateTime('visited_at');

            $table->enum('visibility', ['private', 'followers', 'public'])->default('followers');

            $table->string('memo', 500)->nullable();

            // NULL이면 임시 저장(미개시), 값 있으면 게시됨
            $table->dateTime('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'created_at']);
            $table->index(['place_id', 'visited_at_at']);
            $table->index(['visibility', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
