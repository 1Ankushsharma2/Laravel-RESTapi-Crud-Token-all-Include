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
        Schema::create('api', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("mobile")->unique();
            $table->string('address')->nullable();
            $table->string('pincode',6)->nullable();
            $table->boolean('status')->comment("1:active , 0:inactive")->default(1);
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api');
    }
};
