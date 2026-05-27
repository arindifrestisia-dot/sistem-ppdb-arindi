<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->after('name');
            });
        }

        DB::table('users')
            ->whereNull('username')
            ->orderBy('id')
            ->get(['id', 'name', 'email'])
            ->each(function (object $user) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'username' => $this->makeUsername($user),
                    ]);
            });

        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_email_unique');
            });
        } catch (Throwable) {
            //
        }

        try {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('username');
            });
        } catch (Throwable) {
            //
        }
    }

    public function down(): void
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_username_unique');
            });
        } catch (Throwable) {
            //
        }

        try {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('email');
            });
        } catch (Throwable) {
            //
        }

        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('username');
            });
        }
    }

    private function makeUsername(object $user): string
    {
        $source = (string) ($user->email ?: $user->name ?: 'user');
        $source = Str::before($source, '@');
        $base = Str::slug($source, '_') ?: 'user';
        $username = Str::lower($base);
        $suffix = 1;

        while (DB::table('users')->where('username', $username)->where('id', '<>', $user->id)->exists()) {
            $username = Str::lower($base . '_' . $suffix);
            $suffix++;
        }

        return $username;
    }
};
