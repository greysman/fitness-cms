<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationExpendituresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenditures', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->tinyInteger('type_id')->index();
            $table->text('comment')->nullable();
            $table->foreignId('author_id');
            $table->foreignId('editor_id')->nullable();
            $table->timestamps();

            $table->foreign('author_id')->on('users')->references('id');
            $table->foreign('editor_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operation_expenditures', function(Blueprint $table) {
            $table->dropForeign('operation_expenditures_author_id_foreign');
            $table->dropForeign('operation_expenditures_editor_id_foreign');
        });
        Schema::dropIfExists('operation_expenditures');
    }
}
