<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldForPriceToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('gold_weight')->nullable();
            $table->string('silver_weight')->nullable();
            $table->string('va')->nullable();
            $table->string('va_percentage')->nullable();
            $table->string('gst')->nullable();
            $table->string('gold_price')->nullable();
            $table->string('silver_price')->nullable();
            $table->string('stone_price')->nullable();
            $table->string('sub_total')->nullable();
            $table->string('final_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('gold_weight');
            $table->dropColumn('silver_weight');
            $table->dropColumn('va');
            $table->dropColumn('va_percentage');
            $table->dropColumn('gst');
            $table->dropColumn('gold_price');
            $table->dropColumn('silver_price');
            $table->dropColumn('stone_price');
            $table->dropColumn('sub_total');
            $table->dropColumn('final_total');
        });
    }
}
