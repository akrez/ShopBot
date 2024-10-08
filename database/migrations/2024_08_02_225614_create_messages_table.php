<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary('id');
            $table->unsignedBigInteger('chat_id')->nullable();
            $table->json('message_json')->nullable();
            $table->longText('message_text')->nullable();
            $table->string('processor')->nullable();
            $table->foreignId('bot_id')->constrained()->cascadeOnDelete();
            $table->softDeletes()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
