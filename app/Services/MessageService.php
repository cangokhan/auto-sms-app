<?php

namespace App\Services;

use App\Models\Message;
use App\Repositories\MessageRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class MessageService
{
    protected MessageRepositoryInterface $repo;

    public function __construct(MessageRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Mesaj gönderimi
     *
     * @param Message $message
     * @return void
     */
    public function send(Message $message): void
    {
        $charLimit = config('messages.char_limit');
        $url = config('messages.webhook_url');
        $authKey = config('messages.auth_key');

        // Karakter limit
        if (strlen($message->content) > $charLimit) {
            $this->repo->markFailed($message, 'Karakter limiti aşıldı');
            return;
        }

        // HTTP request
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-ins-auth-key' => $authKey,
        ])->post($url, [
            'to' => $message->phone,
            'content' => $message->content,
        ])->json();

        $externalId = $response['messageId'] ?? null;

        if ($externalId) {
            $this->repo->markSent($message, $externalId, $response);

            // Redis cache
            Redis::set("message:meta:{$message->id}", json_encode([
                'external_id' => $externalId,
                'sent_at' => now()->toDateTimeString(),
            ]));
        } else {
            $this->repo->markFailed($message, 'MessageId alınamadı');
        }
    }
}
