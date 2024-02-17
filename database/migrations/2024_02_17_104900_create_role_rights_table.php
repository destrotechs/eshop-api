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
        Schema::create('role_rights', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('right_id');
            $table->unsignedBigInteger('role_id');
            $table->foreign('right_id')
                    ->references('id')->on('rights')
                    ->onDelete('cascade');
            $table->foreign('role_id')
                    ->references('id')->on('roles')
                    ->onDelete('cascade');
            $table->unique(['right_id', 'role_id']);
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_rights');
    }
};
