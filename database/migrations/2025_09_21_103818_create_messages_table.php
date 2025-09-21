<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('segment')->nullable();
            $table->string('phone', 20);
            $table->string('name', 100)->nullable();
            $table->text('content');
            $table->enum('status', ['pending', 'queued', 'sent', 'failed'])->default('pending');
            $table->string('external_message_id')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->json('response_payload')->nullable();
            $table->timestamps();
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
