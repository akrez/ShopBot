<?php

use App\Enums\Package\PackageStatus;
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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->enum('package_status', [
                PackageStatus::ACTIVE->value,
                PackageStatus::DEACTIVE->value,
                PackageStatus::OUT_OF_STOCK->value,
            ]);
            $table->decimal('price', 24, 0)->unsigned();
            $table->foreignId('color_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blog_id')->constrained()->cascadeOnDelete();
            $table->string('guaranty', 256)->nullable();
            $table->string('description', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
