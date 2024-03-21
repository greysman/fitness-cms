<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->index();
            $table->text('description')->nullable();
            $table->text('image_url')->nullable();
            $table->foreignId('category_id')->nullable();
            $table->string('sku')->nullable()->index();
            $table->tinyInteger('type_id')->default(0)->index();
            $table->boolean('subtract')->default(true);
            $table->json('additional_data')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('published')->default(true);
            $table->float('price')->default(0);
            $table->integer('order')->default(0);
            $table->foreignId('author_id')->nullable();
            $table->foreignId('editor_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unsignedInteger('viewed')->default(0);

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('author_id')->references('id')->on('users')->cascadeOnUpdate();
            $table->foreign('editor_id')->references('id')->on('users')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_author_id_foreign');
            $table->dropForeign('products_editor_id_foreign');
        });
        Schema::dropIfExists('products');
    }
};
