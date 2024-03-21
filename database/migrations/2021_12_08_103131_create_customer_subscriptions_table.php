<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id');
            $table->foreignId('subscription_id');
            $table->integer('used')->default(0);
            $table->timestamps();
            $table->timestamp('canceled_at')->nullable();

            $table->foreign('contact_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_subscriptions');
    }
}
