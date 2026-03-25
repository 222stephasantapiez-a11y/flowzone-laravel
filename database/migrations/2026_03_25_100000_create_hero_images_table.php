<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_images', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->nullable();
            $table->string('url');                          // URL o path storage
            $table->string('seccion')->default('hero');     // hero | destacadas | cards
            $table->boolean('activa')->default(true);
            $table->integer('orden')->default(0);
            $table->string('tipo')->default('url');         // url | upload
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_images');
    }
};
