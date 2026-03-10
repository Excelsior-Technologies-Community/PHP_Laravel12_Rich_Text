<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rich_texts', function (Blueprint $table) {
            $table->id();
            $table->longText('content'); // store rich text
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rich_texts');
    }
};