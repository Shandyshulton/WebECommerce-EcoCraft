<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Tambah hirarki peran admin: super_admin & admin.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin'])->default('admin')->after('email');
        });

        // Admin pertama (id 1) dijadikan super admin.
        DB::table('admins')->where('id_admins', 1)->update(['role' => 'super_admin']);
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
