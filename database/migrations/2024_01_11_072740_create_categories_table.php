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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('content')->nullable()->default(null);
            $table->unsignedBigInteger('main_id')->nullable()->default(null);
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('level')->default(0);
            $table->integer('discount')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('main_id')->references('id')->on('categories');
            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
