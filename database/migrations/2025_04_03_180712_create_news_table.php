<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->enum('type', ['news', 'promotion'])->default('news');
            $table->timestamps();

            // Индексы для оптимизации
            $table->index('is_active');
            $table->index('published_at');
            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('news');
    }
};
