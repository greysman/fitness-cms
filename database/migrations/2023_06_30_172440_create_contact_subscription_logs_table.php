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
        Schema::create('contact_subscription_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_subscription_id');
            $table->foreignId('author_id')->nullable();
            $table->foreignId('editor_id')->nullable();
            $table->json('data_before')->nullable();
            $table->json('data_after')->nullable();
            $table->timestamps();

            $table->foreign('contact_subscription_id')->references('id')->on('contact_subscriptions')->cascadeOnDelete();
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
        Schema::table('contact_subscription_logs', function (Blueprint $table) {
            $table->dropForeign('contact_subscription_logs_contact_subscription_id_foreign');
            $table->dropForeign('contact_subscription_logs_author_id_foreign');
            $table->dropForeign('contact_subscription_logs_editor_id_foreign');
        });

        Schema::dropIfExists('contact_subscription_logs');
    }
};
