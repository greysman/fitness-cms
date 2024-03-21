<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable();
            $table->string('uid', 50)->index();
            $table->string('hash', 255)->nullable();
            $table->foreignId('expenditure_id');
            $table->float('discount')->nullable()->default(0);
            $table->tinyInteger('discount_type_id')->nullable()->default(0);
            $table->float('total_amount')->nullable()->default(0);
            $table->text('comment')->nullable();
            $table->json('payload')->nullable();
            $table->foreignId('author_id')->nullable();
            $table->foreignId('editor_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('contact_id')->on('contacts')->references('id');
            $table->foreign('author_id')->on('users')->references('id');
            $table->foreign('editor_id')->on('users')->references('id');
            $table->foreign('expenditure_id')->on('expenditures')->references('id')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operations', function(Blueprint $table) {
            $table->dropForeign('operations_contact_id_foreign');
            $table->dropForeign('operations_author_id_foreign');
            $table->dropForeign('operations_editor_id_foreign');
            $table->dropForeign('operations_expenditure_id_foreign');
        });
        Schema::dropIfExists('operations');
    }
}
