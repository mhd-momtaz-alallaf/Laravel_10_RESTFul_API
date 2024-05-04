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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->integer('quantity')->unsigned();
            $table->integer('buyer_id')->unsigned();
            $table->integer('product_id')->unsigned();

            $table->timestamps();

            // $table->foreign('buyer_id')->references('id')->on('users');
            // $table->foreign('product_id')->references('id')->on('products');
            $table->foreignId('buyer_id')->constrained()
                ->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()
                ->cascadeOnDelete();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
