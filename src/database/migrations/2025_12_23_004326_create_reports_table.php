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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // 누가 신고했는지
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // 무엇을 신고했는지 (visit / dish_logs / collections 등)
            $table->morphs('reportable');
            // => reportable_type + reportable_id + index 자동 생성

            // 신고 사요
            $table->enum('reason', ['spam', 'ads', 'abuse', 'illegal', 'other'])->default('other');

            // 주가 설명
            $table->string('detail', 500)->nullable();

            // 운영 처리를 위한 최소 상태값
            $table->enum('status', ['pending', 'auto_hidden', 'resolved', 'rejected'])->default('pending');

            $table->timestamps();

            // 같은 유저가 같은 대상에 중복 신고 방지
            $table->unique(['user_id', 'reportable_type', 'reportable_id'], 'uq_reports');

            // 집계 / 조회 성능
            $table->index(['reportable_type', 'reportable_id', 'created_at'], 'idx_reports_target_created');
            $table->index(['status', 'created_at'], 'idx_reports_status_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
