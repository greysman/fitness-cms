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
        Schema::table('requests', function (Blueprint $table) {
            $table->index('source_id');
            $table->index('gym_id');
            $table->index('responsible_id');
            $table->index('status_id');
            $table->index('stage_id');
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
            $table->dropIndex('requests_source_id_index');
            $table->dropIndex('requests_gym_id_index');
            $table->dropIndex('requests_responsible_id_index');
            $table->dropIndex('requests_status_id_index');
            $table->dropIndex('requests_stage_id_index');
        });
    }
};
