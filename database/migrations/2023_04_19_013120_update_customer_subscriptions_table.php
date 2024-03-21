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
        Schema::rename('customer_subscriptions', 'contact_subscriptions');

        Schema::table('contact_subscriptions', function (Blueprint $table) {
            $table->dropForeign('customer_subscriptions_customer_id_foreign');
            
            $table->dropColumn('customer_id');
            $table->dropColumn('subscription_id');

            $table->string('title', 255)->after('id');
            $table->foreignId('contact_id')->after('title');
            $table->foreignId('product_id')->nullable()->after('contact_id');
            $table->json('payload')->nullable();

            $table->foreign('contact_id')->references('id')->on('contacts')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Nothing to do with it
    }
};
