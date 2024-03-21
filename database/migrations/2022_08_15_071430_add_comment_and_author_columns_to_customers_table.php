<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentAndAuthorColumnsToContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->text('comment')->nullable();
            $table->foreignId('author_id')->nullable();
            $table->foreignId('editor_id')->nullable();

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
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign('contacts_author_id_foreign');
            $table->dropForeign('contacts_editor_id_foreign');

            $table->dropColumn('author_id');
            $table->dropColumn('editor_id');
        });
    }
}
