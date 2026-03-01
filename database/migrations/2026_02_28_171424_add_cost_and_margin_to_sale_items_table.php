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
        Schema::table('sale_items', function (Blueprint $table) {
            $table->decimal('cost_price', 15, 2)->default(0)->after('price');
            $table->decimal('subtotal_cost', 15, 2)->default(0)->after('subtotal');
            $table->decimal('subtotal_margin', 15, 2)->default(0)->after('subtotal_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropColumn(['cost_price', 'subtotal_cost', 'subtotal_margin']);
        });
    }
};
