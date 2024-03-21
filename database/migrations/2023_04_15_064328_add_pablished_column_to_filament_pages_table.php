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
        Schema::table('filament_pages', function (Blueprint $table) {
            $table->boolean('published')->default(1)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('filament_pages', function (Blueprint $table) {
            $table->dropIndex('filament_pages_published_index');

            $table->dropColumn('published');
        });
    }
};
