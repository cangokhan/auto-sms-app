<?php

namespace App\Repositories;

use App\Models\Message;
use Illuminate\Support\Collection;

interface MessageRepositoryInterface
{
    /**
     * Pending mesajları getir
     *
     * @param int   $limit
     * @param array $filters
     * @return Collection
     */
    public function getPending(int $limit = 10, array $filters = []): Collection;

    // Mesajı queued olarak işaretle
    public function markQueued(Message $message): void;

    // Mesajı sent olarak işaretle 
    public function markSent(Message $message, string $externalId, array $response): void;

    // Mesajı failed olarak işaretle
    public function markFailed(Message $message, string $reason = null): void;

    // ID göre mesaj bul
    public function findById(int $id): ?Message;

    /**
     * Gönderilmiş mesajları listele
     *
     * @param array $filters
     * @return Collection
     */
    public function listSent(array $filters = []): Collection;
}
