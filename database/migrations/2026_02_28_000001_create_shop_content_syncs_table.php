<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_content_syncs', function (Blueprint $table) {
            $table->id();
            $table->string('shop_domain');
            $table->unsignedBigInteger('contentpulse_id');
            $table->unsignedBigInteger('shopify_article_id')->nullable();
            $table->unsignedBigInteger('shopify_blog_id')->nullable();
            $table->string('shopify_handle')->nullable();
            $table->string('status')->default('synced');
            $table->timestamp('last_synced_at')->nullable();
            $table->json('sync_metadata')->nullable();
            $table->timestamps();

            $table->unique(['shop_domain', 'contentpulse_id']);
            $table->index('shop_domain');
            $table->index('shopify_article_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_content_syncs');
    }
};
