<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lab_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->longText('objective');
            $table->longText('task');
            $table->string('input_content');
            $table->string('output_content');
            $table->string('iter_num');
            $table->string('marks');
            $table->string('lab_name');
            $table->foreignId('lab_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            // $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('assigned_by');
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
        Schema::dropIfExists('lab_assignments');
    }
}
