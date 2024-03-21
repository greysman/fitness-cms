<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('subtitle')->nullable();
            $table->text('description');
            $table->text('link')->nullable();
            $table->string('link_text', 50)->nullable();
            $table->dateTime('available_from')->nullable();
            $table->dateTime('available_to')->nullable();
            $table->float('price');
            $table->float('special_price')->nullable();
            $table->dateTime('special_price_available_from')->nullable();
            $table->dateTime('special_price_available_to')->nullable();
            $table->integer('trainings_count')->default(1);
            $table->integer('days')->default(30);
            $table->tinyInteger('active')->default(0);
            $table->foreignId('author_id')->nullable();
            $table->foreignId('editor_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign('subscriptions_author_id_foreign');
            $table->dropForeign('subscriptions_editor_id_foreign');
        });
        Schema::dropIfExists('subscriptions');
    }
}
