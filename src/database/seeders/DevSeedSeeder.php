<?php

namespace Database\Seeders;

use App\Models\Place;
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
        User::updateOrCreate(
            ['email' => 'devbam515@gmail.com'],
            [
                'nickname' => 'bam',
                'password' => Hash::make('password'),
                'provider' => null,
                'provider_id' => null,
                'avatar_url' => null,
                'bio' => '음식을 좋아하는 개발자'
            ]
        );

        // 테스트 Place 1개
        Place::updateOrCreate(
            ['source' => 'manual', 'source_place_id' => 'dev-001'],
            [
                'name' => '옥돌현옥',
                'lat' => 37.5015360,
                'lng' => 127.1240850,
                'address' => '서울특별시 송파구 가락동 19-7',
                'road_address' => '서울특별시 송파구 오금로36길 26-1',
                'category' => '냉면',
                'phone' => null
            ]
        );
    }
}
