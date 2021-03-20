<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeadToProblems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problems', function (Blueprint $table) {
            // add column 'head'
            $table->string('head');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problems', function (Blueprint $table) {
            // in case this column needs to dropped
            $table->dropColumn('head');
        });
    }
}
