<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailing_list', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('website_id');
            $table->string('email', 255);
            $table->longText('name', 255)->nullable(true);
            $table->timestamp('subscribed_at', 0)->useCurrent();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mailing_list');
    }
}
