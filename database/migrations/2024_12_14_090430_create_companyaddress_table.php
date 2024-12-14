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
        Schema::create('companyaddress', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('mobile'); 
            $table->string('address'); 
            $table->string('latitude'); 
            $table->string('longitude'); 
            $table->unsignedBigInteger('company_id'); 
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companyaddress');
    }
};
