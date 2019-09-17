<?php

declare(strict_types = 1);

namespace McMatters\LaravelRoles\Tests;

use Illuminate\Support\Facades\DB;

use const false, true;

/**
 * Class PerformanceTest
 *
 * @package McMatters\LaravelRoles\Tests
 */
class PerformanceTest extends TestCase
{
    /**
     * @var bool
     */
    protected $listenerSetup = false;

    /**
     * @var int
     */
    protected $queryCount = 0;

    /**
     * @return void
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testRolesCalling()
    {
        $user = $this->createUser(); // 1
        $user->attachRole($this->getRoleEditor()); // 2, 3

        $this->assertSame(3, $this->queryCount);

        $user->attachRole([2, 3]); // 4

        $this->assertSame(4, $this->queryCount);
    }

    /**
     * @return void
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testPermissionsCalling()
    {
        $user = $this->createUser(); // 1
        $user->attachRole($this->getRoleAdmin()); // 2, 3

        $user->getPermissions(); // 4, 5
        $user->getPermissions();
        $user->getPermissions();

        $this->assertSame(5, $this->queryCount);
    }

    /**
     * @return void
     *
     * @throws \Mockery\Exception\NoMatchingExpectationException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setupListener();

        $this->queryCount = 0;
    }

    /**
     * @return void
     */
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
