<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevSeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 테스트 유저 1명
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'nickname' => 'seungbeom',
                'password' => Hash::make('password'),
                'provider' => null,
                'provider_id' => null,
                'avatar_url' => null,
                'bio' => '음식을 좋아하는 개발자'
            ]
        );

        // 테스트 Place 1개

    }
}
