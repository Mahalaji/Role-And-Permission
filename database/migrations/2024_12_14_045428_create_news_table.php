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
        Schema::create('news', function (Blueprint $table) {
            $table->id(); 
            $table->string('title'); 
            $table->string('name'); 
            $table->string('email'); 
            $table->string('slug'); 
            $table->string('news_image'); 
            $table->unsignedBigInteger('category_id'); 
            $table->string('seo_title'); 
            $table->string('meta_keyword'); 
            $table->string('seo_robat'); 
            $table->text('meta_description'); 
            $table->text('description'); 
            $table->timestamps(); 

            $table->foreign('category_id')->references('id')->on('newscategory')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
