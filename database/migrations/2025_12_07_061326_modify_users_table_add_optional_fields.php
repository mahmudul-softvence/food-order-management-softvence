<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->string('phone')->unique()->after('name');
            $table->string('employee_number')->nullable()->after('phone');
            $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->string('floor')->nullable()->after('team_id');
            $table->string('row')->nullable()->after('floor');
            $table->string('seat_number')->nullable()->after('row');
            $table->boolean('status')->default(1)->after('seat_number');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->dropForeign(['team_id']);
            $table->dropColumn([
                'phone',
                'employee_number',
                'team_id',
                'floor',
                'row',
                'seat_number',
                'status'
            ]);
        });
    }
};
