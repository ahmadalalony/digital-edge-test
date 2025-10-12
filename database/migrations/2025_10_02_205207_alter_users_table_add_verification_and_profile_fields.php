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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');

            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');

            $table->string('phone')->nullable()->unique()->after('email');
            $table->string('country')->after('phone');
            $table->string('city')->after('country');

            $table->string('verification_code')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::table('users', function (Blueprint $table) {
                // استرجاع الحقل name
                $table->string('name')->after('id');

                // حذف الحقول المضافة
                $table->dropColumn([
                    'first_name',
                    'last_name',
                    'phone',
                    'country',
                    'city',
                    'verification_code',
                ]);
            });
        });
    }
};
