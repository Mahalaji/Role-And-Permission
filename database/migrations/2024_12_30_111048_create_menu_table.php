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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('category'); 
            $table->string('permission'); 
            $table->longText('json_output')->default('[{"text":"Blogs","href":"","icon":"fab fa-blogger","target":"_self","title":"","Permission":"","children":[{"text":"BlogList","href":"blog","icon":"far fa-dot-circle","target":"_self","title":"","Permission":""},{"text":"Blog-Categories","href":"blogcategory","icon":"far fa-dot-circle","target":"_self","title":"","Permission":""}]},{"text":"News","href":"","icon":"fas fa-newspaper","target":"_self","title":"","Permission":"","children":[{"text":"NewsList","href":"newss","icon":"far fa-dot-circle","target":"_self","title":"","Permission":""},{"text":"News-Categories","href":"newscategory","icon":"far fa-dot-circle","target":"_self","title":"","Permission":""}]},{"text":"Pages","href":"","icon":"fas fa-book-open","target":"_self","title":"","Permission":"","children":[{"text":"Page","href":"pages","icon":"far fa-dot-circle","target":"_self","title":"","Permission":""}]},{"text":"Setting","href":"","icon":"fas fa-cogs","target":"_top","title":"My Home","Permission":"","children":[{"text":"Company Profile","href":"company","icon":"far fa-dot-circle","target":"_self","title":"","Permission":""},{"text":"Manage Role","href":"roles.index","icon":"far fa-dot-circle","target":"_self","title":"","Permission":""},{"text":"Menu List","href":"menu","icon":"far fa-dot-circle","target":"_self","title":"","Permission":""},{"text":"Modules","href":"module","icon":"far fa-dot-circle","target":"_self","title":"","Permission":""}]},{"text":"Manage User","href":"users.index","icon":"fas fa-users","target":"_self","title":"","Permission":""},{"text":"Manage Product","href":"products.index","icon":"fas fa-list","target":"_self","title":"","Permission":""}]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
