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
        Schema::table('contact_subscriptions', function (Blueprint $table) {
            $table->timestamp('expiration_reminded_at')->nullable()->after('expiring_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_subscriptions', function (Blueprint $table) {
            $table->dropColumn('expiration_reminded_at');
        });
    }
};
