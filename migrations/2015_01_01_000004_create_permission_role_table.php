<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreatePermissionRoleTable
 */
class CreatePermissionRoleTable extends Migration
{
    /**
     * @var string
     */
    protected $table;

    /**
     * CreatePermissionRoleTable constructor.
     */
    public function __construct()
    {
        $this->table = Config::get('roles.tables.permission_role');
    }

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->unsignedInteger('permission_id')->index();
            $table->unsignedInteger('role_id')->index();

            $table->unique(['permission_id', 'role_id']);

            $table->foreign('permission_id')
                ->references('id')
                ->on(Config::get('roles.tables.permissions'))
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on(Config::get('roles.tables.roles'))
                ->onDelete('cascade');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
}
