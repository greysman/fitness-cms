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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->index();
            $table->dateTime('date_end')->index();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('gym_id')->index();
            $table->foreignId('trainer_id')->index();
            $table->foreignId('author_id')->nullable();
            $table->foreignId('editor_id')->nullable();
            $table->boolean('active')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('gym_id')->references('id')->on('gyms')->cascadeOnDelete();
            $table->foreign('trainer_id')->references('id')->on('users')->cascadeOnDelete();
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
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign('schedules_gym_id_foreign');
            $table->dropForeign('schedules_trainer_id_foreign');
            $table->dropForeign('schedules_author_id_foreign');
            $table->dropForeign('schedules_editor_id_foreign');
        });
        Schema::dropIfExists('schedules');
    }
};
