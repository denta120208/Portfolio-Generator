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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->string('project_title');
            $table->text('description');
            $table->string('project_image')->nullable();
            $table->json('additional_images')->nullable();
            $table->string('template_id');
            $table->json('custom_data')->nullable(); // untuk data tambahan sesuai template
            $table->string('status')->default('draft'); // draft, published
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
