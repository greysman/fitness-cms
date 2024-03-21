<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGymsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gyms', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('address', 255);
            $table->string('phone', 20);
            $table->json('messangers')->nullable();
            $table->foreignId('author_id')->nullable();
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
        Schema::table('gyms', function(Blueprint $table) {
            $table->dropForeign('gyms_author_id_foreign');
            $table->dropForeign('gyms_editor_id_foreign');
        });
        Schema::dropIfExists('gyms');
    }
}
