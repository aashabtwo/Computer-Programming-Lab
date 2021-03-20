<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubmittedByToAssignmentSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            // add 'submitted_by' field
            $table->string('submiited_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            // in case this column needs to be dropped
            $table->dropColumn('submitted_by');
        });
    }
}
