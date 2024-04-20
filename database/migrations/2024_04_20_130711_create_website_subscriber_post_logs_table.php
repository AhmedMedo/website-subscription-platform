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
        Schema::create('website_subscriber_post_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('website_subscriber_id')->unsigned();
            $table->unsignedBigInteger('post_id')->unsigned();
            $table->boolean('is_notified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_subscriber_post_logs');
    }
};
