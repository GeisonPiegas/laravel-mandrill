<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail', function (Blueprint $table) {
            $table->id();
            $table->string("name", 100);
            $table->string("email", 100);
            $table->string("subject", 100);
            $table->text("body");
            $table->datetime("send_date")->nullable();
            $table->datetime("sent_date")->nullable();
            $table->string("sent_id", 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail');
    }
}
