<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->longText('overview_html')->nullable()->after('content_html');
            $table->longText('sejarah_html')->nullable()->after('overview_html');
            $table->longText('tujuan_html')->nullable()->after('sejarah_html');
            $table->longText('manfaat_html')->nullable()->after('tujuan_html');
            $table->longText('lokasi_html')->nullable()->after('manfaat_html');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['overview_html', 'sejarah_html', 'tujuan_html', 'manfaat_html', 'lokasi_html']);
        });
    }
};

