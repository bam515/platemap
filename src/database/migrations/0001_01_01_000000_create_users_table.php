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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('nickname', 30)->unique();

            // 이메일 로그인 + 구글 로그인 공동 키
            $table->string('email')->unique();

            // 이메일 가입자는 password 필수로 넣고, 구글 가입자는 NULL 가능
            $table->string('password')->nullable();

            // social login 연결 정보
            $table->string('provider', 20)->nullable();
            $table->string('provider_id', 80)->nullable();

            // 프로필
            $table->string('avatar_url', 500)->nullable();
            $table->string('bio', 200)->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['provider', 'provider_id'], 'uq_users_provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
