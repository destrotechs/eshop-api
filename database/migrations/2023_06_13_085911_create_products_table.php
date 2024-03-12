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
        // 'code_id','brand','model','common_name','img_id'
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->unsignedBigInteger('subcategory_id');
            $table->string('brand');
            $table->string('model');
            $table->float('price',10,2);
            $table->string('product_code')->unique();
            $table->string('bar_code')->nullable();
            $table->string('common_name');
            $table->text('description');
            $table->string('size')->nullable();
            $table->string('warrant')->nullable();
            $table->string('sku')->nullable();
            $table->string('dimension')->nullable();
            $table->string('availability')->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount',5,2)->nullable();
            $table->string('tags')->nullable();
            $table->string('offset_type')->nullable();
            $table->decimal('offset',5,2)->nullable();
            $table->text('options')->nullable();
            $table->foreign('subcategory_id')
                    ->references('id')->on('subcategories')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
