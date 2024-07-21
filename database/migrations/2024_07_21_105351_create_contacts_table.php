<?php

use App\Enums\Contact\ContactType;
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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->enum('contact_type', [
                ContactType::ADDRESS->value,
                ContactType::TELEGRAM->value,
                ContactType::WHATSAPP->value,
                ContactType::PHONE->value,
                ContactType::EMAIL->value,
                ContactType::INSTAGRAM->value,
            ])->nullable();
            $table->string('contact_key')->nullable();
            $table->string('contact_value', 1023)->nullable();
            $table->string('contact_link', 1023)->nullable();
            $table->decimal('contact_order')->nullable();
            $table->foreignId('blog_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
