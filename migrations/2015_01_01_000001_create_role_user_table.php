<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRoleUserTable
 */
class CreateRoleUserTable extends Migration
{
    /**
     * @var string
     */
    protected $table;

    /**
     * CreateRoleUserTable constructor.
     */
    public function __construct()
    {
        $this->table = Config::get('roles.tables.role_user');
    }

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->index();
            $table->unsignedBigInteger('user_id')->index();

            $table->unique(['role_id', 'user_id']);

            $table->foreign('role_id')
                ->references('id')
                ->on(Config::get('roles.tables.roles'))
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on(Config::get('roles.tables.users'))
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
