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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->index('orders_customer_id_index');

            // Monetary fields
            $table->decimal('sub_total', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('shipping_cost', 10, 2);
            $table->decimal('total', 10, 2);

            $table->string('currency_code', 3);

            $table->string('payment_method_code')->nullable();
            $table->string('payment_method_title')->nullable();

            $table->string('shipping_method')->nullable();
            $table->string('shipping_method_title')->nullable();

            $table->unsignedTinyInteger('status')->unsigned()->default(0);

            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
