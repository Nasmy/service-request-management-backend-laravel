<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('path')->unique();
            $table->timestamps();

            $table->foreignId('job_id')->constrained('jobs')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('created_by')->constrained('users')->onDelete('NO ACTION');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
