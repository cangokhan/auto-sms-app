<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Message;
use App\Repositories\MessageRepositoryInterface;
use App\Services\MessageService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_marks_message_sent_and_caches()
    {
        $message = Message::factory()->create([
            'content' => 'Test mesaj',
            'status' => 'pending',
        ]);

        $repo = $this->createMock(MessageRepositoryInterface::class);
        $repo->expects($this->once())
             ->method('markSent');

        $service = new MessageService($repo);

        Http::fake([
            '*' => Http::response(['messageId' => 'TEST123'], 200)
        ]);

        Redis::shouldReceive('set')->once();

        $service->send($message);
    }

    public function test_send_fails_when_char_limit_exceeded()
    {
        $message = Message::factory()->create([
            'content' => str_repeat('a', 500),
            'status' => 'pending',
        ]);

        $repo = $this->createMock(MessageRepositoryInterface::class);
        $repo->expects($this->once())
             ->method('markFailed');

        $service = new MessageService($repo);
        $service->send($message);
    }
}
