<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rich_texts', function (Blueprint $table) {
            $table->integer('version')->default(1);
            $table->unsignedBigInteger('original_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('rich_texts', function (Blueprint $table) {
            $table->dropColumn(['version', 'original_id']);
        });
    }
};