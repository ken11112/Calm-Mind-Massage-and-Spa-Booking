<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facebook_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_access_token')->nullable();
            $table->string('page_id')->nullable();
            $table->string('page_username')->nullable();
            $table->string('admin_psid')->nullable();
            $table->string('verify_token')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facebook_settings');
    }
};
