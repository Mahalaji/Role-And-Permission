<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_blogs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id(); 
            $table->string('title'); 
            $table->string('name'); 
            $table->string('slug'); 
            $table->string('image'); 
            $table->unsignedBigInteger('category_id'); 
            $table->string('seo_title'); 
            $table->string('meta_keyword'); 
            $table->string('seo_robat'); 
            $table->text('meta_description'); 
            $table->text('description'); 
            $table->timestamps(); 

            $table->foreign('category_id')->references('id')->on('blogcategory')->onDelete('cascade'); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}