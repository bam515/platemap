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
        Schema::table('visits', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('published_at');
            $table->index(['is_hidden', 'created_at'], 'idx_visits_hidden_created');
        });

        Schema::table('dish_logs', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('photo_url');
            $table->index(['is_hidden', 'created_at'], 'idx_dish_logs_hidden_created');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false)->after('shared_at');
            $table->index(['is_hidden', 'created_at'], 'idx_collections_hidden_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropIndex('idx_visits_hidden_created');
            $table->dropColumn('is_hidden');
        });

        Schema::table('dish_logs', function (Blueprint $table) {
            $table->dropIndex('idx_dish_logs_hidden_created');
            $table->dropColumn('is_hidden');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->dropIndex('idx_collections_hidden_created');
            $table->dropColumn('is_hidden');
        });
    }
};
