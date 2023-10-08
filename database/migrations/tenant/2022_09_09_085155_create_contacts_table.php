<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 50)->nullable()->unique();
            $table->string('mobile', 50)->nullable()->unique();
            $table->string('email', 100)->nullable()->unique();
            $table->string('address', 200)->nullable();
            $table->string('city', 100)->nullable();
            $table->unsignedInteger('zip')->nullable();
            $table->timestamps();

            $table->foreignId('org_id')->nullable()->constrained('organizations')->restrictOnDelete()->restrictOnUpdate();
            $table->foreignId('cit_id')->nullable()->constrained('citizens')->restrictOnDelete()->restrictOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
