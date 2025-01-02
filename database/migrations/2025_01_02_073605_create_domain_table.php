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
        Schema::create('domain', function (Blueprint $table) {
            $table->id();
            $table->string('domainname'); 
            $table->string('companyname'); 
            $table->longText('mailheader'); 
            $table->longText('mailfooter'); 
            $table->string('serveraddress'); 
            $table->string('port'); 
            $table->string('authentication'); 
            $table->string('username'); 
            $table->string('password'); 
            $table->string('tomailid'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain');
    }
};
