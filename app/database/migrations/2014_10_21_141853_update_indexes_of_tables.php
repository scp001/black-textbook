<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class UpdateIndexesOfTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Books table.
        Schema::table('books', function (Blueprint $table)
        {
            $table->index('user_id')->unsigned();

        });
        //countries table.
        Schema::table('countries', function (Blueprint $table)
        {
            $table->index('code');

        });

        //message_replies table.
        Schema::table('message_replies', function (Blueprint $table)
        {
            $table->index('mid');
            $table->index('user_id');

        });

        //messages table.
        Schema::table('messages', function (Blueprint $table)
        {
            $table->index('from_user_id')->unsigned();
            $table->index('to_user_id')->unsigned();
            $table->index('new');

        });

        //user_hoppies table.
        Schema::table('user_hoppies', function (Blueprint $table)
        {
            $table->index('user_id')->unsignd();
            $table->index('hoppy_id');

        });
    }


    public function down()
    {

    }
}