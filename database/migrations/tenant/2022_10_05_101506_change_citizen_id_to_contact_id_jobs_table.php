<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (Schema::hasColumn('jobs', 'citizen_id'))
                $table->dropConstrainedForeignId('citizen_id');
            $table->foreignId('contact_id')->after('assigned_to_user')->nullable()->references('id')->on('contacts')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('contact_id');
            $table->foreignId('citizen_id')->after('assigned_to_user')->nullable()->references('id')->on('contacts')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }
};
