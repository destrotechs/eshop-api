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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->softDeletes();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('address_id');
            $table->unsignedBigInteger('payment_mode_id');
            $table->decimal('total_cost',10,2)->nullable();
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
            $table->decimal('vat',10,2)->nullable();
            $table->decimal('discount',10,2)->nullable();
            $table->string('served_by')->nullable();
            $table->string('status')->default('Created');
            $table->foreign('address_id')
                    ->references('id')->on('addresses')
                    ->onDelete('cascade');
            $table->foreign('payment_mode_id')
                    ->references('id')->on('payment_modes')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
