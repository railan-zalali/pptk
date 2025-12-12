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
        Schema::table('gardens', function (Blueprint $table) {
            $table->string('address')->nullable()->after('location');
            $table->decimal('area', 10, 2)->nullable()->after('area_hectares');
            $table->integer('elevation')->nullable()->after('area');
            $table->integer('rainfall')->nullable()->after('elevation');
            $table->string('tea_variety')->nullable()->after('rainfall');
            $table->string('garden_type')->nullable()->after('tea_variety');
            $table->string('coordinates')->nullable()->after('garden_type');
            $table->decimal('latitude', 10, 6)->nullable()->after('coordinates');
            $table->decimal('longitude', 10, 6)->nullable()->after('latitude');
            $table->decimal('soil_ph', 3, 1)->nullable()->after('longitude');
            $table->string('soil_type')->nullable()->after('soil_ph');
            $table->string('drainage')->nullable()->after('soil_type');
            $table->string('status')->nullable()->after('drainage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gardens', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'area',
                'elevation',
                'rainfall',
                'tea_variety',
                'garden_type',
                'coordinates',
                'latitude',
                'longitude',
                'soil_ph',
                'soil_type',
                'drainage',
                'status',
            ]);
        });
    }
};
