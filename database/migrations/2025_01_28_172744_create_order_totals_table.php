<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_totals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->index('order_totals_order_id_index');

            $table->string('code');
            $table->string('title');
            $table->decimal('value', 10, 2);
            $table->unsignedTinyInteger('sort_order');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_totals');
    }
};
