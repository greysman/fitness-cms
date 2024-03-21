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
        Schema::dropIfExists('profiles');
        Schema::table('users', function (Blueprint $table) {
            $table->string('surname', 255)->nullable()->after('name');
            $table->string('patronymic', 255)->nullable()->after('surname');
            $table->date('birthday')->nullable()->after('patronymic');
            $table->string('phone', 20)->unique()->after('birthday')->nullable();
            $table->string('telegram_user_id', 20)->unique()->after('phone')->nullable();
            $table->text('avatar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
