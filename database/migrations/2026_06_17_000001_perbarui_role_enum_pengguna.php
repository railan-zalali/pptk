<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * MySQL: first expand enum to include both old AND new values,
     * then update the data, then shrink enum to only new values.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // Step 1: Expand enum to allow ALL values (old + new) so UPDATE won't truncate
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','manager','viewer','admin_pptk','manajemen') NOT NULL DEFAULT 'manajemen'");

            // Step 2: Migrate existing data
            DB::table('users')->where('role', 'admin')->update(['role' => 'admin_pptk']);
            DB::table('users')->whereIn('role', ['manager', 'viewer'])->update(['role' => 'manajemen']);

            // Step 3: Shrink enum to only new roles
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin_pptk','manajemen') NOT NULL DEFAULT 'manajemen'");
        } else {
            // SQLite / other: update data, then change column type
            DB::table('users')->where('role', 'admin')->update(['role' => 'admin_pptk']);
            DB::table('users')->whereIn('role', ['manager', 'viewer'])->update(['role' => 'manajemen']);

            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('manajemen')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            // Step 1: Expand enum
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','manager','viewer','admin_pptk','manajemen') NOT NULL DEFAULT 'viewer'");

            // Step 2: Revert data
            DB::table('users')->where('role', 'admin_pptk')->update(['role' => 'admin']);
            DB::table('users')->where('role', 'manajemen')->update(['role' => 'viewer']);

            // Step 3: Shrink back to old enum
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','manager','viewer') NOT NULL DEFAULT 'viewer'");
        } else {
            DB::table('users')->where('role', 'admin_pptk')->update(['role' => 'admin']);
            DB::table('users')->where('role', 'manajemen')->update(['role' => 'viewer']);

            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('viewer')->change();
            });
        }
    }
};
