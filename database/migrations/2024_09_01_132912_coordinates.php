<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('coordinates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade');
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coordinates');
    }
};
