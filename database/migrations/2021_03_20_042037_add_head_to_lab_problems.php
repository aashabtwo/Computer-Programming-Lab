<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeadToLabProblems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lab_problems', function (Blueprint $table) {
            // add head column
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
        Schema::table('lab_problems', function (Blueprint $table) {
            // to drop this column (if need be)
            $table->dropColumn('head');
        });
    }
}
