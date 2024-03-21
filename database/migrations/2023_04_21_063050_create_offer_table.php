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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id');
            $table->foreignId('product_id');
            $table->foreignId('author_id')->nullable();
            $table->foreignId('editor_id')->nullable();
            $table->tinyInteger('discount_type')->default(0);
            $table->float('discount_value')->default(0);
            $table->tinyInteger('status_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('request_id')->references('id')->on('requests')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('editor_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropForeign('offers_request_id_foreign');
            $table->dropForeign('offers_product_id_foreign');
            $table->dropForeign('offers_author_id_foreign');
            $table->dropForeign('offers_editor_id_foreign');
        });
        Schema::dropIfExists('offers');
    }
};
