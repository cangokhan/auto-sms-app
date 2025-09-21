<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\MessageRepositoryInterface;
use App\Jobs\SendMessageJob;

class DispatchPendingMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * --segment opsiyonel parametre
     * @var string
     */
    protected $signature = 'messages:dispatch {--segment=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pending mesajları queue atar';

    protected MessageRepositoryInterface $repo;

    /**
     * Create a new command instance.
     *
     * @param MessageRepositoryInterface $repo
     */
    public function __construct(MessageRepositoryInterface $repo)
    {
        parent::__construct();
        $this->repo = $repo;
    }

    /**
     * console command
     */
    public function handle()
    {
        $this->info('Mesaj gönderimi başlatıldı...');

        // Filtreler
        $filters = [];
        if ($segment = $this->option('segment')) {
            $filters['segment'] = $segment;
        }

        $batchSize = config('messages.batch_size');
        $batchDelay = config('messages.delay_seconds');
        
        $pendingMessages = $this->repo->getPending(100, $filters);

        if ($pendingMessages->isEmpty()) {
            $this->info('Gönderilecek pending mesaj yok.');
            return 0;
        }

        /* 
         *  Her delay_seconds da batch_size kadar sms gidecek. 
         *  gelen row batch_size kadar böl
        */
        $batches = $pendingMessages->chunk($batchSize);

        foreach ($batches as $batchIndex => $batch) {
            //delay
            $delay = $batchIndex * $batchDelay;
            foreach ($batch as $message) {
                
                SendMessageJob::dispatch($message)->delay(now()->addSeconds($delay));
                $this->repo->markQueued($message);

                $this->info("Mesaj #{$message->id} queue’ya atıldı (batch: {$batchIndex}, delay: {$delay}s)");
            }
        }

        $this->info('Dispatch işlemi tamamlandı.');
        return 0;
    }
}
