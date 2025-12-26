<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // visits
        if (Schema::hasTable('visits')) {
            $hasHidden = Schema::hasColumn('visits', 'is_hidden');

            Schema::table('visits', function (Blueprint $table) use ($hasHidden) {
                // is_hidden 컬럼이 있으면 그걸 포함해서 최적 인덱스
                if ($hasHidden) {
                    $table->index(['user_id', 'is_hidden', 'published_at', 'id'], 'visits_feed_home_idx');
                } else {
                    // 컬럼 없으면 안전한 대체 인덱스
                    $table->index(['user_id', 'published_at', 'id'], 'visits_feed_home_idx');
                }
            });
        }

        // dish_logs
        if (Schema::hasTable('dish_logs')) {
            $hasHidden = Schema::hasColumn('dish_logs', 'is_hidden');

            Schema::table('dish_logs', function (Blueprint $table) use ($hasHidden) {
                if ($hasHidden) {
                    $table->index(['visit_id', 'is_hidden', 'sort_order'], 'dish_logs_visit_visible_sort_idx');
                } else {
                    $table->index(['visit_id', 'sort_order'], 'dish_logs_visit_sort_idx');
                }
            });
        }

        // likes (프로젝트가 visit_id 방식인지, polymorphic인지 몰라서 안전 분기)
        if (Schema::hasTable('likes')) {
            $hasVisitId = Schema::hasColumn('likes', 'visit_id');
            $hasUserId = Schema::hasColumn('likes', 'user_id');
            $hasLikeable = Schema::hasColumn('likes', 'likeable_type') && Schema::hasColumn('likes', 'likeable_id');

            Schema::table('likes', function (Blueprint $table) use ($hasVisitId, $hasUserId, $hasLikeable) {
                if ($hasVisitId && $hasUserId) {
                    $table->index(['visit_id', 'user_id'], 'likes_visit_user_idx');
                } elseif ($hasLikeable && $hasUserId) {
                    $table->index(['likeable_type', 'likeable_id', 'user_id'], 'likes_likeable_user_idx');
                }
            });
        }
    }

    public function down(): void
    {
        // 테스트에서는 down 거의 안 타지만, 안전하게 try/catch로 감쌈
        try {
            if (Schema::hasTable('visits')) {
                Schema::table('visits', fn (Blueprint $t) => $t->dropIndex('visits_feed_home_idx'));
            }
        } catch (\Throwable $e) {}

        try {
            if (Schema::hasTable('dish_logs')) {
                if (Schema::hasColumn('dish_logs', 'is_hidden')) {
                    Schema::table('dish_logs', fn (Blueprint $t) => $t->dropIndex('dish_logs_visit_visible_sort_idx'));
                } else {
                    Schema::table('dish_logs', fn (Blueprint $t) => $t->dropIndex('dish_logs_visit_sort_idx'));
                }
            }
        } catch (\Throwable $e) {}

        try {
            if (Schema::hasTable('likes')) {
                if (Schema::hasColumn('likes', 'visit_id')) {
                    Schema::table('likes', fn (Blueprint $t) => $t->dropIndex('likes_visit_user_idx'));
                } else {
                    Schema::table('likes', fn (Blueprint $t) => $t->dropIndex('likes_likeable_user_idx'));
                }
            }
        } catch (\Throwable $e) {}
    }
};
