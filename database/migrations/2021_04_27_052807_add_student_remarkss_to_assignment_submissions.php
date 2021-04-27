<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentRemarkssToAssignmentSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            //
            $table->string('student_remarkss')->nullable();
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
            //
            $table->dropColumn('student_remarkss');
        });
    }
}
