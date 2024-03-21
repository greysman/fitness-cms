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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->nullable();
            $table->string('uid')->index();
            $table->string('gateway');
            $table->string('description')->nullable();
            $table->string('status')->index();
            $table->boolean('paid')->index();
            $table->float('amount')->default(0);
            $table->json('payload')->nullable();
            $table->text('confirmation_url')->nullable();
            $table->dateTime('paid_at')->index()->nullable();
            $table->dateTime('canceled_at')->index()->nullable();
            $table->timestamps();

            $table->foreign('operation_id')->references('id')->on('operations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
