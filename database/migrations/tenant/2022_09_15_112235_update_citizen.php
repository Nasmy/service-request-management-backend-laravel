<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('citizens', function (Blueprint $table) {
            $table->date('birthdate')->nullable()->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('citizens', function (Blueprint $table) {

        });
    }
};
