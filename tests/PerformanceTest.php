<?php

declare(strict_types=1);

namespace AMgrade\LaravelRoles\Tests;

use Illuminate\Support\Facades\DB;

use const false;
use const true;

class PerformanceTest extends TestCase
{
    protected bool $listenerSetup = false;

    protected int $queryCount = 0;

    public function testRolesCalling(): void
    {
        $user = $this->createUser(); // 1
        $user->attachRole($this->getRoleEditor()); // 2, 3

        $this->assertSame(3, $this->queryCount);

        $user->attachRole([2, 3]); // 4

        $this->assertSame(4, $this->queryCount);
    }

    public function testPermissionsCalling(): void
    {
        $user = $this->createUser(); // 1
        $user->attachRole($this->getRoleAdmin()); // 2, 3

        $user->getPermissions(); // 4, 5
        $user->getPermissions();
        $user->getPermissions();

        $this->assertSame(5, $this->queryCount);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupListener();

        $this->queryCount = 0;
    }

    protected function setupListener(): void
    {
        if (!$this->listenerSetup) {
            DB::listen(function () {
                $this->queryCount++;
            });

            $this->listenerSetup = true;
        }
    }
}
