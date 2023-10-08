<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeStatusColumnTypeJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            // $table->enum('status', ['todo', 'in_progress', 'completed', 'archived'])->comment('')->default('todo')->change();
            DB::statement("ALTER TABLE `jobs` CHANGE `status` `status` ENUM('todo', 'in_progress', 'completed', 'archived') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'todo';");
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
            $table->integer('status')->comment('In progress - 1, Finished - 2, Archived - 3')->default(1)->change();
        });
    }
}
