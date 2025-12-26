<?php

namespace Tests\Feature;

use App\Models\Place;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FeedHomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_returns_items_and_next_cursor(): void
    {
        // 테스트용 유저 생성
        $user = User::factory()->create();
        // 테스트용 식장 생성
        $place = Place::factory()->create();

        /**
         * 테스트용 방문 기록 데이터 생성
         * published_at가 있어야되고 is_hidden = false여야 노출됨
         */
        Visit::factory()
            ->count(25)
            ->for($user)
            ->for($place)
            ->state(new Sequence(fn($seq) => [
                'published_at' => now()->subSeconds($seq->index + 1),
                'is_hidden' => false
            ]))->create();

        // API 호출: /api/feed/home?limit=20
        // actingAs($user) => "로그인한 사용자처럼" 요청 전송
        $res = $this->actingAs($user)
            ->getJson('/api/feed/home?limit=20');

        // 응답 검증
        $res->assertOk();   // HTTP 200인지 확인
        $res->assertJsonCount(20, 'items');     // items 배열의 길이가 20인지 확인
        $this->assertNotNull($res->json('next_cursor'));   // 다음 페이지 커서가 생겼는지 확인
    }

    public function test_home_cursor_pagination_moves_forward(): void
    {
        // 테스트용 유저 생성
        $user = User::factory()->create();
        // 테스트용 식장 생성
        $place = Place::factory()->create();

        /**
         * 테스트용 방문 기록 데이터 생성
         * published_at가 있어야되고 is_hidden = false여야 노출됨
         */
        Visit::factory()
            ->count(25)
            ->for($user)
            ->for($place)
            ->state(new Sequence(fn($seq) => [
                'published_at' => now()->subSeconds($seq->index + 1),
                'is_hidden' => false
            ]))->create();

        // 첫 페이지 호출
        $first = $this->actingAs($user)->getJson('/api/feed/home?limit=20')->assertOk();

        // 첫 페이지에서 next_cursor 포함
        $cursor = $first->json('next_cursor');
        $this->assertNotNull($cursor);

        // 커서를 붙여서 두 번째 페이지 호출
        $second = $this->actingAs($user)
            ->getJson('/api/feed/home?limit=20&cursor=' . urlencode($cursor));
        $second->assertOk();

        // 25개 중 20개는 이미 가져왔으니 5개인지 확인
        $second->assertJsonCount(5, 'items');

        // 첫 페이지와 두 번째 페이지가 다른 데이터인지 확인
        $this->assertNotEquals(
            $first->json('items.0.visit_id'),
            $second->json('items.0.visit_id')
        );
    }
}
