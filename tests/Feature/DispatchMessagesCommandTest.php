<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendMessageJob;

class DispatchMessagesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_dispatch_command_sends_pending_messages()
    {
        Message::factory()->count(3)->create([
            'status' => 'pending',
            'segment' => 'vip'
        ]);

        Queue::fake();

        Artisan::call('messages:dispatch --segment=vip');

        Queue::assertPushed(SendMessageJob::class, 3);
    }
}
