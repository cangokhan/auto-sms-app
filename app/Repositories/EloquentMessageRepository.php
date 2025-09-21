<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Support\Collection;

class EloquentMessageRepository implements MessageRepositoryInterface
{
    /**
     * Gönderilmeyi bekleyen mesajları getirir
     * 
     * @param int $limit
     * @param string|null $segment
     * @return Collection
     */
    public function getPending(int $limit = 10, ?string $segment = null): Collection
    {
        $query = Message::status('pending');
        
        if ($segment) {
            $query->segment($segment);
        }

        return $query->limit($limit)->get();
    }

    /**
     * Mesajı queue’ya atıldı olarak işaretler
     *
     * @param Message $message
     * @return void
     */
    public function markQueued(Message $message): void
    {
        $message->update(['status' => 'queued']);
    }

    /**
     * Mesaj gönderildi olarak işaretler ve external id ile response kaydeder
     *
     * @param Message $message
     * @param string $externalId
     * @param array $response
     * @return void
     */
    public function markSent(Message $message, string $externalId, array $response): void
    {
        $message->update([
            'status' => 'sent',
            'external_message_id' => $externalId,
            'sent_at' => now(),
            'response_payload' => $response,
        ]);
    }

    /**
     * Mesaj gönderimi başarısız ise işaretler
     *
     * @param Message $message
     * @param string|null $reason
     * @return void
     */
    public function markFailed(Message $message, string $reason = null): void
    {
        $message->update([
            'status' => 'failed',
            'response_payload' => ['error' => $reason],
        ]);
    }

    /**
     * Mesajı id ile bulur
     *
     * @param int $id
     * @return Message|null
     */
    public function findById(int $id): ?Message
    {
        return Message::find($id);
    }

    /**
     * Gönderilen mesajları listeler
     *
     * @param array $filters
     * @return Collection
     */
    public function listSent(array $filters = []): Collection
    {
        $query = Message::status('sent');

        if (isset($filters['phone'])) {
            $query->phone($filters['phone']);
        }
        
        if (isset($filters['segment'])) {
            $query->segment($filters['segment']);
        }

        return $query->get();
    }
}
