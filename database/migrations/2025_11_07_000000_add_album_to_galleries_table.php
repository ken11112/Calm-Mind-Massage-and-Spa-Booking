<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('galleries') && !Schema::hasColumn('galleries', 'album')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->string('album')->nullable()->after('title');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('galleries') && Schema::hasColumn('galleries', 'album')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->dropColumn('album');
            });
        }
    }
};
