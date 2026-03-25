<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 200);
            $table->text('contenido');
            $table->string('imagen')->nullable();
            $table->enum('tipo', ['evento', 'noticia'])->default('noticia');
            $table->string('autor', 150)->nullable();
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->nullOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('publicado')->default(true);
            $table->timestamp('fecha_publicacion')->nullable();
            $table->string('slug', 220)->unique()->nullable();
            $table->timestamps();

            $table->index(['tipo', 'publicado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
