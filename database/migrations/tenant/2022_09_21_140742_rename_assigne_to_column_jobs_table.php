<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAssigneToColumnJobsTable extends Migration
{
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign('jobs_assigned_to_foreign');
            $table->dropIndex('jobs_assigned_to_foreign');
            $table->renameColumn('assigned_to', 'assigned_to_user');
            $table->foreign('assigned_to_user')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign('jobs_assigned_to_user_foreign');
            $table->dropIndex('jobs_assigned_to_user_foreign');
            $table->renameColumn('assigned_to_user', 'assigned_to');
            $table->foreign('assigned_to')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }
}
