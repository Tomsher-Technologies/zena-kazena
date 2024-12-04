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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('email')->unique(); 
            $table->string('phone')->nullable(); 
            $table->string('password'); 
            $table->string('business_name'); 
            $table->string('business_type')->nullable(); 
            $table->string('registration_number')->nullable(); 
            $table->string('trade_license')->nullable(); 
            $table->text('address')->nullable();
            $table->string('logo')->nullable(); 
            $table->boolean('is_active')->default(false);
            $table->enum('status', ['pending', 'approved', 'cancelled','rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
