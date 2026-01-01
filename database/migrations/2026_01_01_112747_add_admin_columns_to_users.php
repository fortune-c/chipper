<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable();
            }

            if (!Schema::hasColumn('users', 'admin_requested')) {
                $table->boolean('admin_requested')->default(false);
            }

            if (!Schema::hasColumn('users', 'super_admin')) {
                $table->boolean('super_admin')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            if (Schema::hasColumn('users', 'admin_requested')) {
                $table->dropColumn('admin_requested');
            }

            if (Schema::hasColumn('users', 'super_admin')) {
                $table->dropColumn('super_admin');
            }
        });
    }
};
