<?php

use App\Enums\Product\ProductStatus;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable();
            $table->string('code', 32)->nullable();
            $table->foreignId('blog_id')->constrained()->cascadeOnDelete();
            $table->enum('product_status', [
                ProductStatus::ACTIVE->value,
                ProductStatus::DEACTIVE->value,
            ])->nullable();
            $table->decimal('product_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
