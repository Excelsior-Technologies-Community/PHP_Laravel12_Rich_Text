<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rich_texts', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
            $table->string('category')->default('General')->after('content');
            $table->string('tags')->nullable()->after('category');
            $table->string('featured_image')->nullable()->after('tags');
            $table->boolean('is_published')->default(true)->after('featured_image');
        });
    }

    public function down(): void
    {
        Schema::table('rich_texts', function (Blueprint $table) {
            $table->dropColumn(['title', 'category', 'tags', 'featured_image', 'is_published']);
        });
    }
};