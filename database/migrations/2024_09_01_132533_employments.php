<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('employments', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_imports_id');
            $table->string('title');
            $table->string('key_skill');

            $table->foreign('user_imports_id')->references('uid')->on('user_imports')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employments');
    }
};
