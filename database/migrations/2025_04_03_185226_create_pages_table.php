<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->enum('type', ['about', 'history', 'service', 'terms', 'privacy'])->default('about');
            $table->timestamps();

            // Индексы для оптимизации
            $table->index('slug');
            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }
};
