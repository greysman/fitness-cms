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
        Schema::create('signups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id');
            $table->foreignId('gym_id');
            $table->foreignId('trainer_id')->nullable();
            $table->foreignId('responsible_id')->nullable();
            $table->foreignId('product_id')->nullable();
            $table->unsignedInteger('duration');
            $table->dateTime('date');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('finish_time')->nullable();
            $table->text('comment')->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->text('review')->nullable();
            $table->json('additional_data')->nullable();
            $table->foreignId('author_id')->nullable();
            $table->foreignId('editor_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contact_id')->references('id')->on('contacts')->cascadeOnDelete();
            $table->foreign('gym_id')->references('id')->on('gyms');
            $table->foreign('trainer_id')->references('id')->on('users');
            $table->foreign('responsible_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
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
        Schema::table('signups', function (Blueprint $table) {
            $table->dropForeign('signups_contact_id_foreign');
            $table->dropForeign('signups_gym_id_foreign');
            $table->dropForeign('signups_trainer_id_foreign');
            $table->dropForeign('signups_responsible_id_foreign');
            $table->dropForeign('signups_product_id_foreign');
            $table->dropForeign('signups_author_id_foreign');
            $table->dropForeign('signups_editor_id_foreign');
        });
        Schema::dropIfExists('signups');
    }
};
