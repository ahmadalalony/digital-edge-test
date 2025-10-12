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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('title_en');
            $table->string('title_ar');
            $table->string('slug')->unique();

            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();

            $table->decimal('price', 10, 2)->default(0);
            $table->string('primary_image')->nullable();
            $table->json('other_images')->nullable();

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            $table->softDeletes();
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
