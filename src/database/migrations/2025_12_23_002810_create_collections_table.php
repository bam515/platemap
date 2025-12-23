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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title', 80);
            $table->string('description', 300)->nullable();
            $table->string('cover_url', 500)->nullable();

            // 공유 링크용: 값이 있으면 링크로 외부 공개 (읽기 전용)
            $table->string('share_slug', 32)->nullable()->unique();
            $table->dateTime('shared_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
