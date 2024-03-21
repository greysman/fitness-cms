<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id');
            $table->string('title', 255);
            $table->float('price')->default(0);
            $table->integer('quantity')->default(1);
            $table->text('image_url')->nullable();
            $table->longText('payload')->nullable();
            $table->softDeletes();

            $table->foreign('operation_id')->on('operations')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operation_items', function (Blueprint $table) {
            $table->dropForeign('operation_items_operation_id_foreign');
        });
        Schema::dropIfExists('operation_items');
    }
}
