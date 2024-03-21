<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('comment')->nullable();
            $table->foreignId('contact_id');
            $table->foreignId('gym_id')->nullable();
            $table->tinyInteger('status_id')->default(0);
            $table->foreignId('author_id')->nullable();
            $table->foreignId('editor_id')->nullable();
            $table->foreignId('source_id')->default(0);
            $table->foreignId('pipeline_stage_id');
            $table->dateTime('expected_close_date')->nullable();
            $table->float('expected_profit')->default(0);
            $table->text('lost_reason')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            
            $table->foreign('contact_id')->references('id')->on('contacts')->cascadeOnDelete();
            $table->foreign('gym_id')->references('id')->on('gyms');
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
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign('requests_contact_id_foreign');
            $table->dropForeign('requests_author_id_foreign');
            $table->dropForeign('requests_editor_id_foreign');
            $table->dropForeign('requests_gym_id_foreign');
        });
        Schema::dropIfExists('requests');
    }
}
