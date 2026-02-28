<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhook_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('shop_domain');
            $table->string('topic');
            $table->json('payload')->nullable();
            $table->string('shopify_webhook_id')->nullable();
            $table->boolean('processed')->default(false);
            $table->timestamps();

            $table->index(['shop_domain', 'topic']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_receipts');
    }
};
