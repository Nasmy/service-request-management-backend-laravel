<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCitOrgFkColumnsContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign('contacts_cit_id_foreign');
            $table->dropForeign('contacts_org_id_foreign');

            $table->dropIndex('contacts_cit_id_foreign');
            $table->dropIndex('contacts_org_id_foreign');

            $table->renameColumn('cit_id', 'citizen_id');
            $table->renameColumn('org_id', 'organization_id');


            $table->foreign('citizen_id')->references('id')->on('citizens')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign('contacts_citizen_id_foreign');
            $table->dropForeign('contacts_organization_id_foreign');

            $table->dropIndex('contacts_citizen_id_foreign');
            $table->dropIndex('contacts_organization_id_foreign');

            $table->renameColumn('citizen_id', 'cit_id');
            $table->renameColumn('organization_id', 'org_id');

            $table->foreign('cit_id')->references('id')->on('citizens')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('org_id')->references('id')->on('organizations')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }
}
