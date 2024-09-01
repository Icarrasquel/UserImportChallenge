<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_imports_id');
            $table->string('city');
            $table->string('street_name');
            $table->string('street_address');
            $table->string('zip_code');
            $table->string('state');
            $table->string('country');

            $table->foreign('user_imports_id')->references('uid')->on('user_imports')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
