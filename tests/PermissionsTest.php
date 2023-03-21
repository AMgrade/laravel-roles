<?php

declare(strict_types=1);

namespace McMatters\LaravelRoles\Tests;

use Illuminate\Support\Collection;
use McMatters\LaravelRoles\Tests\Traits\PermissionsTrait;

use function range;

class PermissionsTest extends TestCase
{
    use PermissionsTrait;

    public function testBasic(): void
    {
        $this->assertCount(0, $this->getUserEditor()->getAttribute('permissions'));
        $this->assertCount(3, $this->getUserEditor()->getPermissions());
        $this->assertCount(3, $this->getRoleEditor()->getAttribute('permissions'));
        $this->assertCount(3, $this->getRoleEditor()->getPermissions());

        $this->assertCount(0, $this->getUserModerator()->getAttribute('permissions'));
        $this->assertCount(8, $this->getUserModerator()->getPermissions());
        $this->assertCount(5, $this->getRoleModerator()->getAttribute('permissions'));
        $this->assertCount(8, $this->getRoleModerator()->getPermissions());

        $this->assertCount(0, $this->getUserAdmin()->getAttribute('permissions'));
        $this->assertCount(10, $this->getUserAdmin()->getPermissions());
        $this->assertCount(2, $this->getRoleAdmin()->getAttribute('permissions'));
        $this->assertCount(10, $this->getRoleAdmin()->getPermissions());
    }

    public function testTogglingPermissionInt(): void
    {
        $user = $this->createUser();

        $this->assertEmpty($user->getAttribute('permissions'));
        $this->assertEmpty($user->getPermissions());

        $user->attachRole($this->getRoleEditor());
        $this->assertEmpty($user->getAttribute('permissions'));
        $this->assertCount(3, $user->getPermissions());

        $user->attachPermission(4);
        $this->assertCount(1, $user->getAttribute('permissions'));
        $this->assertCount(4, $user->getPermissions());

        $user->attachPermission([5, 6]);
        $this->assertCount(3, $user->getAttribute('permissions'));
        $this->assertCount(6, $user->getPermissions());

        $user->attachPermission(new Collection([7, 8]));
        $this->assertCount(5, $user->getAttribute('permissions'));
        $this->assertCount(8, $user->getPermissions());

        $user->detachRole();
        $this->assertCount(5, $user->getAttribute('permissions'));
        $this->assertCount(5, $user->getPermissions());

        $user->detachPermission(7);
        $this->assertCount(4, $user->getAttribute('permissions'));
        $this->assertCount(4, $user->getPermissions());

        $user->detachPermission([5, 6]);
        $this->assertCount(2, $user->getAttribute('permissions'));
        $this->assertCount(2, $user->getPermissions());

        $user->detachPermission(new Collection([4, 8]));
        $this->assertCount(0, $user->getAttribute('permissions'));
        $this->assertCount(0, $user->getPermissions());

        $user->syncPermissions([1, 2]);
        $this->assertCount(2, $user->getAttribute('permissions'));
        $this->assertCount(2, $user->getPermissions());

        $user->syncPermissions([3], false);
        $this->assertCount(3, $user->getAttribute('permissions'));
        $this->assertCount(3, $user->getPermissions());

        $user->syncPermissions($this->getPermission('blog.create'));
        $this->assertCount(1, $user->getAttribute('permissions'));
        $this->assertCount(1, $user->getPermissions());

        $user->syncPermissions([$this->getPermission('blog.create')]);
        $this->assertCount(1, $user->getAttribute('permissions'));
        $this->assertCount(1, $user->getPermissions());

        $user->syncPermissions(new Collection([$this->getPermission('blog.create')]));
        $this->assertCount(1, $user->getAttribute('permissions'));
        $this->assertCount(1, $user->getPermissions());
    }

    public function testHasPermissionInt(): void
    {
        $this->assertTrue($this->getUserEditor()->hasPermission(1));
        $this->assertTrue($this->getUserEditor()->hasPermission(2));
        $this->assertTrue($this->getUserEditor()->hasPermission(3));
        $this->assertFalse($this->getUserEditor()->hasPermission(4));
        $this->assertFalse($this->getUserEditor()->hasPermission(5));
        $this->assertFalse($this->getUserEditor()->hasPermission(6));
        $this->assertFalse($this->getUserEditor()->hasPermission(7));
        $this->assertFalse($this->getUserEditor()->hasPermission(8));
        $this->assertFalse($this->getUserEditor()->hasPermission(9));
        $this->assertFalse($this->getUserEditor()->hasPermission(10));

        $this->assertTrue($this->getUserModerator()->hasPermission(1));
        $this->assertTrue($this->getUserModerator()->hasPermission(2));
        $this->assertTrue($this->getUserModerator()->hasPermission(3));
        $this->assertTrue($this->getUserModerator()->hasPermission(4));
        $this->assertTrue($this->getUserModerator()->hasPermission(5));
        $this->assertTrue($this->getUserModerator()->hasPermission(6));
        $this->assertTrue($this->getUserModerator()->hasPermission(7));
        $this->assertTrue($this->getUserModerator()->hasPermission(8));
        $this->assertFalse($this->getUserModerator()->hasPermission(9));
        $this->assertFalse($this->getUserModerator()->hasPermission(10));

        $this->assertTrue($this->getUserAdmin()->hasPermission(1));
        $this->assertTrue($this->getUserAdmin()->hasPermission(2));
        $this->assertTrue($this->getUserAdmin()->hasPermission(3));
        $this->assertTrue($this->getUserAdmin()->hasPermission(4));
        $this->assertTrue($this->getUserAdmin()->hasPermission(5));
        $this->assertTrue($this->getUserAdmin()->hasPermission(6));
        $this->assertTrue($this->getUserAdmin()->hasPermission(7));
        $this->assertTrue($this->getUserAdmin()->hasPermission(8));
        $this->assertTrue($this->getUserAdmin()->hasPermission(9));
        $this->assertTrue($this->getUserAdmin()->hasPermission(10));

        $this->assertTrue($this->getRoleEditor()->hasPermission(1));
        $this->assertTrue($this->getRoleEditor()->hasPermission(2));
        $this->assertTrue($this->getRoleEditor()->hasPermission(3));
        $this->assertFalse($this->getRoleEditor()->hasPermission(4));
        $this->assertFalse($this->getRoleEditor()->hasPermission(5));
        $this->assertFalse($this->getRoleEditor()->hasPermission(6));
        $this->assertFalse($this->getRoleEditor()->hasPermission(7));
        $this->assertFalse($this->getRoleEditor()->hasPermission(8));
        $this->assertFalse($this->getRoleEditor()->hasPermission(9));
        $this->assertFalse($this->getRoleEditor()->hasPermission(10));

        $this->assertTrue($this->getRoleModerator()->hasPermission(1));
        $this->assertTrue($this->getRoleModerator()->hasPermission(2));
        $this->assertTrue($this->getRoleModerator()->hasPermission(3));
        $this->assertTrue($this->getRoleModerator()->hasPermission(4));
        $this->assertTrue($this->getRoleModerator()->hasPermission(5));
        $this->assertTrue($this->getRoleModerator()->hasPermission(6));
        $this->assertTrue($this->getRoleModerator()->hasPermission(7));
        $this->assertTrue($this->getRoleModerator()->hasPermission(8));
        $this->assertFalse($this->getRoleModerator()->hasPermission(9));
        $this->assertFalse($this->getRoleModerator()->hasPermission(10));

        $this->assertTrue($this->getRoleAdmin()->hasPermission(1));
        $this->assertTrue($this->getRoleAdmin()->hasPermission(2));
        $this->assertTrue($this->getRoleAdmin()->hasPermission(3));
        $this->assertTrue($this->getRoleAdmin()->hasPermission(4));
        $this->assertTrue($this->getRoleAdmin()->hasPermission(5));
        $this->assertTrue($this->getRoleAdmin()->hasPermission(6));
        $this->assertTrue($this->getRoleAdmin()->hasPermission(7));
        $this->assertTrue($this->getRoleAdmin()->hasPermission(8));
        $this->assertTrue($this->getRoleAdmin()->hasPermission(9));
        $this->assertTrue($this->getRoleAdmin()->hasPermission(10));
    }

    public function testHasPermissionString(): void
    {
        $this->assertTrue($this->getUserEditor()->hasPermission('blog.create'));
        $this->assertTrue($this->getUserEditor()->hasPermission('blog.update'));
        $this->assertTrue($this->getUserEditor()->hasPermission('blog.delete'));
        $this->assertFalse($this->getUserEditor()->hasPermission('comment.create'));
        $this->assertFalse($this->getUserEditor()->hasPermission('comment.update'));
        $this->assertFalse($this->getUserEditor()->hasPermission('comment.delete'));
        $this->assertFalse($this->getUserEditor()->hasPermission('comment.approve'));
        $this->assertFalse($this->getUserEditor()->hasPermission('comment.disapprove'));
        $this->assertFalse($this->getUserEditor()->hasPermission('settings.create'));
        $this->assertFalse($this->getUserEditor()->hasPermission('settings.update'));

        $this->assertTrue($this->getUserModerator()->hasPermission('blog.create'));
        $this->assertTrue($this->getUserModerator()->hasPermission('blog.update'));
        $this->assertTrue($this->getUserModerator()->hasPermission('blog.delete'));
        $this->assertTrue($this->getUserModerator()->hasPermission('comment.create'));
        $this->assertTrue($this->getUserModerator()->hasPermission('comment.update'));
        $this->assertTrue($this->getUserModerator()->hasPermission('comment.delete'));
        $this->assertTrue($this->getUserModerator()->hasPermission('comment.approve'));
        $this->assertTrue($this->getUserModerator()->hasPermission('comment.disapprove'));
        $this->assertFalse($this->getUserModerator()->hasPermission('settings.create'));
        $this->assertFalse($this->getUserModerator()->hasPermission('settings.update'));

        $this->assertTrue($this->getUserAdmin()->hasPermission('blog.create'));
        $this->assertTrue($this->getUserAdmin()->hasPermission('blog.update'));
        $this->assertTrue($this->getUserAdmin()->hasPermission('blog.delete'));
        $this->assertTrue($this->getUserAdmin()->hasPermission('comment.create'));
        $this->assertTrue($this->getUserAdmin()->hasPermission('comment.update'));
        $this->assertTrue($this->getUserAdmin()->hasPermission('comment.delete'));
        $this->assertTrue($this->getUserAdmin()->hasPermission('comment.approve'));
        $this->assertTrue($this->getUserAdmin()->hasPermission('comment.disapprove'));
        $this->assertTrue($this->getUserAdmin()->hasPermission('settings.create'));
        $this->assertTrue($this->getUserAdmin()->hasPermission('settings.update'));

        $this->assertTrue($this->getRoleEditor()->hasPermission('blog.create'));
        $this->assertTrue($this->getRoleEditor()->hasPermission('blog.update'));
        $this->assertTrue($this->getRoleEditor()->hasPermission('blog.delete'));
        $this->assertFalse($this->getRoleEditor()->hasPermission('comment.create'));
        $this->assertFalse($this->getRoleEditor()->hasPermission('comment.update'));
        $this->assertFalse($this->getRoleEditor()->hasPermission('comment.delete'));
        $this->assertFalse($this->getRoleEditor()->hasPermission('comment.approve'));
        $this->assertFalse($this->getRoleEditor()->hasPermission('comment.disapprove'));
        $this->assertFalse($this->getRoleEditor()->hasPermission('settings.create'));
        $this->assertFalse($this->getRoleEditor()->hasPermission('settings.update'));

        $this->assertTrue($this->getRoleModerator()->hasPermission('blog.create'));
        $this->assertTrue($this->getRoleModerator()->hasPermission('blog.update'));
        $this->assertTrue($this->getRoleModerator()->hasPermission('blog.delete'));
        $this->assertTrue($this->getRoleModerator()->hasPermission('comment.create'));
        $this->assertTrue($this->getRoleModerator()->hasPermission('comment.update'));
        $this->assertTrue($this->getRoleModerator()->hasPermission('comment.delete'));
        $this->assertTrue($this->getRoleModerator()->hasPermission('comment.approve'));
        $this->assertTrue($this->getRoleModerator()->hasPermission('comment.disapprove'));
        $this->assertFalse($this->getRoleModerator()->hasPermission('settings.create'));
        $this->assertFalse($this->getRoleModerator()->hasPermission('settings.update'));

        $this->assertTrue($this->getRoleAdmin()->hasPermission('blog.create'));
        $this->assertTrue($this->getRoleAdmin()->hasPermission('blog.update'));
        $this->assertTrue($this->getRoleAdmin()->hasPermission('blog.delete'));
        $this->assertTrue($this->getRoleAdmin()->hasPermission('comment.create'));
        $this->assertTrue($this->getRoleAdmin()->hasPermission('comment.update'));
        $this->assertTrue($this->getRoleAdmin()->hasPermission('comment.delete'));
        $this->assertTrue($this->getRoleAdmin()->hasPermission('comment.approve'));
        $this->assertTrue($this->getRoleAdmin()->hasPermission('comment.disapprove'));
        $this->assertTrue($this->getRoleAdmin()->hasPermission('settings.create'));
        $this->assertTrue($this->getRoleAdmin()->hasPermission('settings.update'));
    }

    public function testHasPermissionModel(): void
    {
        $this->assertTrue($this->getUserEditor()->hasPermission($this->getPermission('blog.create')));
        $this->assertTrue($this->getUserEditor()->hasPermission($this->getPermission('blog.update')));
        $this->assertTrue($this->getUserEditor()->hasPermission($this->getPermission('blog.delete')));
        $this->assertFalse($this->getUserEditor()->hasPermission($this->getPermission('comment.create')));
        $this->assertFalse($this->getUserEditor()->hasPermission($this->getPermission('comment.update')));
        $this->assertFalse($this->getUserEditor()->hasPermission($this->getPermission('comment.delete')));
        $this->assertFalse($this->getUserEditor()->hasPermission($this->getPermission('comment.approve')));
        $this->assertFalse($this->getUserEditor()->hasPermission($this->getPermission('comment.disapprove')));
        $this->assertFalse($this->getUserEditor()->hasPermission($this->getPermission('settings.create')));
        $this->assertFalse($this->getUserEditor()->hasPermission($this->getPermission('settings.update')));

        $this->assertTrue($this->getUserModerator()->hasPermission($this->getPermission('blog.create')));
        $this->assertTrue($this->getUserModerator()->hasPermission($this->getPermission('blog.update')));
        $this->assertTrue($this->getUserModerator()->hasPermission($this->getPermission('blog.delete')));
        $this->assertTrue($this->getUserModerator()->hasPermission($this->getPermission('comment.create')));
        $this->assertTrue($this->getUserModerator()->hasPermission($this->getPermission('comment.update')));
        $this->assertTrue($this->getUserModerator()->hasPermission($this->getPermission('comment.delete')));
        $this->assertTrue($this->getUserModerator()->hasPermission($this->getPermission('comment.approve')));
        $this->assertTrue($this->getUserModerator()->hasPermission($this->getPermission('comment.disapprove')));
        $this->assertFalse($this->getUserModerator()->hasPermission($this->getPermission('settings.create')));
        $this->assertFalse($this->getUserModerator()->hasPermission($this->getPermission('settings.update')));

        $this->assertTrue($this->getUserAdmin()->hasPermission($this->getPermission('blog.create')));
        $this->assertTrue($this->getUserAdmin()->hasPermission($this->getPermission('blog.update')));
        $this->assertTrue($this->getUserAdmin()->hasPermission($this->getPermission('blog.delete')));
        $this->assertTrue($this->getUserAdmin()->hasPermission($this->getPermission('comment.create')));
        $this->assertTrue($this->getUserAdmin()->hasPermission($this->getPermission('comment.update')));
        $this->assertTrue($this->getUserAdmin()->hasPermission($this->getPermission('comment.delete')));
        $this->assertTrue($this->getUserAdmin()->hasPermission($this->getPermission('comment.approve')));
        $this->assertTrue($this->getUserAdmin()->hasPermission($this->getPermission('comment.disapprove')));
        $this->assertTrue($this->getUserAdmin()->hasPermission($this->getPermission('settings.create')));
        $this->assertTrue($this->getUserAdmin()->hasPermission($this->getPermission('settings.update')));

        $this->assertTrue($this->getRoleEditor()->hasPermission($this->getPermission('blog.create')));
        $this->assertTrue($this->getRoleEditor()->hasPermission($this->getPermission('blog.update')));
        $this->assertTrue($this->getRoleEditor()->hasPermission($this->getPermission('blog.delete')));
        $this->assertFalse($this->getRoleEditor()->hasPermission($this->getPermission('comment.create')));
        $this->assertFalse($this->getRoleEditor()->hasPermission($this->getPermission('comment.update')));
        $this->assertFalse($this->getRoleEditor()->hasPermission($this->getPermission('comment.delete')));
        $this->assertFalse($this->getRoleEditor()->hasPermission($this->getPermission('comment.approve')));
        $this->assertFalse($this->getRoleEditor()->hasPermission($this->getPermission('comment.disapprove')));
        $this->assertFalse($this->getRoleEditor()->hasPermission($this->getPermission('settings.create')));
        $this->assertFalse($this->getRoleEditor()->hasPermission($this->getPermission('settings.update')));

        $this->assertTrue($this->getRoleModerator()->hasPermission($this->getPermission('blog.create')));
        $this->assertTrue($this->getRoleModerator()->hasPermission($this->getPermission('blog.update')));
        $this->assertTrue($this->getRoleModerator()->hasPermission($this->getPermission('blog.delete')));
        $this->assertTrue($this->getRoleModerator()->hasPermission($this->getPermission('comment.create')));
        $this->assertTrue($this->getRoleModerator()->hasPermission($this->getPermission('comment.update')));
        $this->assertTrue($this->getRoleModerator()->hasPermission($this->getPermission('comment.delete')));
        $this->assertTrue($this->getRoleModerator()->hasPermission($this->getPermission('comment.approve')));
        $this->assertTrue($this->getRoleModerator()->hasPermission($this->getPermission('comment.disapprove')));
        $this->assertFalse($this->getRoleModerator()->hasPermission($this->getPermission('settings.create')));
        $this->assertFalse($this->getRoleModerator()->hasPermission($this->getPermission('settings.update')));

        $this->assertTrue($this->getRoleAdmin()->hasPermission($this->getPermission('blog.create')));
        $this->assertTrue($this->getRoleAdmin()->hasPermission($this->getPermission('blog.update')));
        $this->assertTrue($this->getRoleAdmin()->hasPermission($this->getPermission('blog.delete')));
        $this->assertTrue($this->getRoleAdmin()->hasPermission($this->getPermission('comment.create')));
        $this->assertTrue($this->getRoleAdmin()->hasPermission($this->getPermission('comment.update')));
        $this->assertTrue($this->getRoleAdmin()->hasPermission($this->getPermission('comment.delete')));
        $this->assertTrue($this->getRoleAdmin()->hasPermission($this->getPermission('comment.approve')));
        $this->assertTrue($this->getRoleAdmin()->hasPermission($this->getPermission('comment.disapprove')));
        $this->assertTrue($this->getRoleAdmin()->hasPermission($this->getPermission('settings.create')));
        $this->assertTrue($this->getRoleAdmin()->hasPermission($this->getPermission('settings.update')));
    }

    public function testHasPermissionsInt(): void
    {
        $this->assertTrue($this->getUserEditor()->hasPermissions(1));
        $this->assertTrue($this->getUserEditor()->hasPermissions(2));
        $this->assertTrue($this->getUserEditor()->hasPermissions(3));
        $this->assertTrue($this->getUserEditor()->hasPermissions([1, 2, 3]));
        $this->assertTrue($this->getUserEditor()->hasPermissions(new Collection([1, 2, 3])));
        $this->assertFalse($this->getUserEditor()->hasPermissions(4));
        $this->assertFalse($this->getUserEditor()->hasPermissions([4]));
        $this->assertFalse($this->getUserEditor()->hasPermissions([3, 4]));
        $this->assertFalse($this->getUserEditor()->hasPermissions(new Collection([3, 4])));
        $this->assertFalse($this->getUserEditor()->hasPermissions(new Collection([4])));

        $this->assertTrue($this->getUserModerator()->hasPermissions(1));
        $this->assertTrue($this->getUserModerator()->hasPermissions(2));
        $this->assertTrue($this->getUserModerator()->hasPermissions(3));
        $this->assertTrue($this->getUserModerator()->hasPermissions(4));
        $this->assertTrue($this->getUserModerator()->hasPermissions(5));
        $this->assertTrue($this->getUserModerator()->hasPermissions(6));
        $this->assertTrue($this->getUserModerator()->hasPermissions(7));
        $this->assertTrue($this->getUserModerator()->hasPermissions(8));
        $this->assertTrue($this->getUserModerator()->hasPermissions([1, 4, 5, 6]));
        $this->assertTrue($this->getUserModerator()->hasPermissions([3, 7, 8]));
        $this->assertTrue($this->getUserModerator()->hasPermissions(new Collection([2, 4, 5, 6])));
        $this->assertTrue($this->getUserModerator()->hasPermissions(new Collection([3, 6, 7, 8])));
        $this->assertFalse($this->getUserModerator()->hasPermissions(9));
        $this->assertFalse($this->getUserModerator()->hasPermissions([9]));
        $this->assertFalse($this->getUserModerator()->hasPermissions([9, 10]));
        $this->assertFalse($this->getUserModerator()->hasPermissions(new Collection([9, 10])));
        $this->assertFalse($this->getUserModerator()->hasPermissions(new Collection([9])));

        $this->assertTrue($this->getUserAdmin()->hasPermissions(9));
        $this->assertTrue($this->getUserAdmin()->hasPermissions(10));
        $this->assertTrue($this->getUserAdmin()->hasPermissions([9]));
        $this->assertTrue($this->getUserAdmin()->hasPermissions([9, 10]));
        $this->assertTrue($this->getUserAdmin()->hasPermissions(new Collection([9])));
        $this->assertTrue($this->getUserAdmin()->hasPermissions(new Collection([9, 10])));

        $this->assertTrue($this->getRoleEditor()->hasPermissions(1));
        $this->assertTrue($this->getRoleEditor()->hasPermissions(2));
        $this->assertTrue($this->getRoleEditor()->hasPermissions(3));
        $this->assertTrue($this->getRoleEditor()->hasPermissions([1, 2, 3]));
        $this->assertTrue($this->getRoleEditor()->hasPermissions(new Collection([1, 2, 3])));
        $this->assertFalse($this->getRoleEditor()->hasPermissions(4));
        $this->assertFalse($this->getRoleEditor()->hasPermissions([4]));
        $this->assertFalse($this->getRoleEditor()->hasPermissions([3, 4]));
        $this->assertFalse($this->getRoleEditor()->hasPermissions(new Collection([3, 4])));
        $this->assertFalse($this->getRoleEditor()->hasPermissions(new Collection([4])));

        $this->assertTrue($this->getRoleModerator()->hasPermissions(4));
        $this->assertTrue($this->getRoleModerator()->hasPermissions(5));
        $this->assertTrue($this->getRoleModerator()->hasPermissions(6));
        $this->assertTrue($this->getRoleModerator()->hasPermissions(7));
        $this->assertTrue($this->getRoleModerator()->hasPermissions(8));
        $this->assertTrue($this->getRoleModerator()->hasPermissions([4, 5, 6]));
        $this->assertTrue($this->getRoleModerator()->hasPermissions([7, 8]));
        $this->assertTrue($this->getRoleModerator()->hasPermissions(new Collection([4, 5, 6])));
        $this->assertTrue($this->getRoleModerator()->hasPermissions(new Collection([6, 7, 8])));
        $this->assertFalse($this->getRoleModerator()->hasPermissions(9));
        $this->assertFalse($this->getRoleModerator()->hasPermissions([1, 2, 3, 9]));
        $this->assertFalse($this->getRoleModerator()->hasPermissions([9, 10]));
        $this->assertFalse($this->getRoleModerator()->hasPermissions(new Collection([1, 2, 3, 9, 10])));
        $this->assertFalse($this->getRoleModerator()->hasPermissions(new Collection([1, 9])));

        $this->assertTrue($this->getRoleAdmin()->hasPermissions(9));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions(10));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions([9]));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions([9, 10]));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions(new Collection([9])));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions(new Collection([9, 10])));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions(7));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions([1, 2, 3, 7]));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions([4, 5, 6]));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions(new Collection([1, 2, 3])));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions(new Collection([4, 5])));
    }

    public function testHasPermissionsString(): void
    {
        $this->assertTrue($this->getUserEditor()->hasPermissions('blog.create'));
        $this->assertTrue($this->getUserEditor()->hasPermissions('blog.create|blog.update'));
        $this->assertTrue($this->getUserEditor()->hasPermissions(['blog.delete']));
        $this->assertTrue($this->getUserEditor()->hasPermissions(['blog.create', 'blog.delete']));
        $this->assertTrue($this->getUserEditor()->hasPermissions(new Collection(['blog.create'])));
        $this->assertTrue($this->getUserEditor()->hasPermissions(new Collection(['blog.create', 'blog.delete'])));
        $this->assertFalse($this->getUserEditor()->hasPermissions('comment.create'));
        $this->assertFalse($this->getUserEditor()->hasPermissions('comment.create|comment.update'));
        $this->assertFalse($this->getUserEditor()->hasPermissions(['comment.delete']));
        $this->assertFalse($this->getUserEditor()->hasPermissions(['comment.delete', 'comment.approve']));
        $this->assertFalse($this->getUserEditor()->hasPermissions(new Collection(['comment.approve', 'comment.disapprove'])));
        $this->assertFalse($this->getUserEditor()->hasPermissions('settings.create'));
        $this->assertFalse($this->getUserEditor()->hasPermissions('settings.update'));

        $this->assertTrue($this->getUserModerator()->hasPermissions('blog.create'));
        $this->assertTrue($this->getUserModerator()->hasPermissions('blog.update'));
        $this->assertTrue($this->getUserModerator()->hasPermissions('blog.delete'));
        $this->assertTrue($this->getUserModerator()->hasPermissions('comment.create'));
        $this->assertTrue($this->getUserModerator()->hasPermissions('comment.update'));
        $this->assertTrue($this->getUserModerator()->hasPermissions('comment.delete'));
        $this->assertTrue($this->getUserModerator()->hasPermissions('comment.approve'));
        $this->assertTrue($this->getUserModerator()->hasPermissions('comment.disapprove'));
        $this->assertFalse($this->getUserModerator()->hasPermissions('settings.create'));
        $this->assertFalse($this->getUserModerator()->hasPermissions('settings.update'));

        $this->assertTrue($this->getUserAdmin()->hasPermissions('blog.create'));
        $this->assertTrue($this->getUserAdmin()->hasPermissions('blog.update'));
        $this->assertTrue($this->getUserAdmin()->hasPermissions('blog.delete'));
        $this->assertTrue($this->getUserAdmin()->hasPermissions('comment.create'));
        $this->assertTrue($this->getUserAdmin()->hasPermissions('comment.update'));
        $this->assertTrue($this->getUserAdmin()->hasPermissions('comment.delete'));
        $this->assertTrue($this->getUserAdmin()->hasPermissions('comment.approve'));
        $this->assertTrue($this->getUserAdmin()->hasPermissions('comment.disapprove'));
        $this->assertTrue($this->getUserAdmin()->hasPermissions('settings.create'));
        $this->assertTrue($this->getUserAdmin()->hasPermissions('settings.update'));

        $this->assertTrue($this->getRoleEditor()->hasPermissions('blog.create'));
        $this->assertTrue($this->getRoleEditor()->hasPermissions('blog.update'));
        $this->assertTrue($this->getRoleEditor()->hasPermissions('blog.delete'));
        $this->assertFalse($this->getRoleEditor()->hasPermissions('comment.create'));
        $this->assertFalse($this->getRoleEditor()->hasPermissions('comment.update'));
        $this->assertFalse($this->getRoleEditor()->hasPermissions('comment.delete'));
        $this->assertFalse($this->getRoleEditor()->hasPermissions('comment.approve'));
        $this->assertFalse($this->getRoleEditor()->hasPermissions('comment.disapprove'));
        $this->assertFalse($this->getRoleEditor()->hasPermissions('settings.create'));
        $this->assertFalse($this->getRoleEditor()->hasPermissions('settings.update'));

        $this->assertTrue($this->getRoleModerator()->hasPermissions('blog.create'));
        $this->assertTrue($this->getRoleModerator()->hasPermissions('blog.update'));
        $this->assertTrue($this->getRoleModerator()->hasPermissions('blog.delete'));
        $this->assertTrue($this->getRoleModerator()->hasPermissions('comment.create'));
        $this->assertTrue($this->getRoleModerator()->hasPermissions('comment.update'));
        $this->assertTrue($this->getRoleModerator()->hasPermissions('comment.delete'));
        $this->assertTrue($this->getRoleModerator()->hasPermissions('comment.approve'));
        $this->assertTrue($this->getRoleModerator()->hasPermissions('comment.disapprove'));
        $this->assertFalse($this->getRoleModerator()->hasPermissions('settings.create'));
        $this->assertFalse($this->getRoleModerator()->hasPermissions('settings.update'));

        $this->assertTrue($this->getRoleAdmin()->hasPermissions('blog.create'));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions('blog.update'));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions('blog.delete'));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions('comment.create'));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions('comment.update'));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions('comment.delete'));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions('comment.approve'));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions('comment.disapprove'));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions('settings.create'));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions('settings.update'));
    }

    public function testHasPermissionsModel(): void
    {
        $this->assertTrue($this->getUserEditor()->hasPermissions($this->getPermission('blog.create')));
        $this->assertTrue($this->getUserEditor()->hasPermissions([$this->getPermission('blog.delete')]));
        $this->assertTrue($this->getUserEditor()->hasPermissions($this->getPermissions('blog.create', 'blog.delete')->all()));
        $this->assertTrue($this->getUserEditor()->hasPermissions($this->getPermissions('blog.create')));
        $this->assertTrue($this->getUserEditor()->hasPermissions($this->getPermissions('blog.create', 'blog.delete')));
        $this->assertFalse($this->getUserEditor()->hasPermissions($this->getPermission('comment.create')));
        $this->assertFalse($this->getUserEditor()->hasPermissions([$this->getPermission('comment.delete')]));
        $this->assertFalse($this->getUserEditor()->hasPermissions($this->getPermissions('comment.delete', 'comment.approve')->all()));
        $this->assertFalse($this->getUserEditor()->hasPermissions($this->getPermissions('comment.approve', 'comment.disapprove')));
        $this->assertFalse($this->getUserEditor()->hasPermissions($this->getPermission('settings.create')));
        $this->assertFalse($this->getUserEditor()->hasPermissions($this->getPermission('settings.update')));

        $this->assertTrue($this->getUserModerator()->hasPermissions($this->getPermission('blog.create')));
        $this->assertTrue($this->getUserModerator()->hasPermissions($this->getPermission('blog.update')));
        $this->assertTrue($this->getUserModerator()->hasPermissions($this->getPermission('blog.delete')));
        $this->assertTrue($this->getUserModerator()->hasPermissions($this->getPermission('comment.create')));
        $this->assertTrue($this->getUserModerator()->hasPermissions($this->getPermission('comment.update')));
        $this->assertTrue($this->getUserModerator()->hasPermissions($this->getPermission('comment.delete')));
        $this->assertTrue($this->getUserModerator()->hasPermissions($this->getPermission('comment.approve')));
        $this->assertTrue($this->getUserModerator()->hasPermissions($this->getPermission('comment.disapprove')));
        $this->assertFalse($this->getUserModerator()->hasPermissions($this->getPermission('settings.create')));
        $this->assertFalse($this->getUserModerator()->hasPermissions($this->getPermission('settings.update')));

        $this->assertTrue($this->getUserAdmin()->hasPermissions($this->getPermission('blog.create')));
        $this->assertTrue($this->getUserAdmin()->hasPermissions($this->getPermission('blog.update')));
        $this->assertTrue($this->getUserAdmin()->hasPermissions($this->getPermission('blog.delete')));
        $this->assertTrue($this->getUserAdmin()->hasPermissions($this->getPermission('comment.create')));
        $this->assertTrue($this->getUserAdmin()->hasPermissions($this->getPermission('comment.update')));
        $this->assertTrue($this->getUserAdmin()->hasPermissions($this->getPermission('comment.delete')));
        $this->assertTrue($this->getUserAdmin()->hasPermissions($this->getPermission('comment.approve')));
        $this->assertTrue($this->getUserAdmin()->hasPermissions($this->getPermission('comment.disapprove')));
        $this->assertTrue($this->getUserAdmin()->hasPermissions($this->getPermission('settings.create')));
        $this->assertTrue($this->getUserAdmin()->hasPermissions($this->getPermission('settings.update')));

        $this->assertTrue($this->getRoleEditor()->hasPermissions($this->getPermission('blog.create')));
        $this->assertTrue($this->getRoleEditor()->hasPermissions($this->getPermission('blog.update')));
        $this->assertTrue($this->getRoleEditor()->hasPermissions($this->getPermission('blog.delete')));
        $this->assertFalse($this->getRoleEditor()->hasPermissions($this->getPermission('comment.create')));
        $this->assertFalse($this->getRoleEditor()->hasPermissions($this->getPermission('comment.update')));
        $this->assertFalse($this->getRoleEditor()->hasPermissions($this->getPermission('comment.delete')));
        $this->assertFalse($this->getRoleEditor()->hasPermissions($this->getPermission('comment.approve')));
        $this->assertFalse($this->getRoleEditor()->hasPermissions($this->getPermission('comment.disapprove')));
        $this->assertFalse($this->getRoleEditor()->hasPermissions($this->getPermission('settings.create')));
        $this->assertFalse($this->getRoleEditor()->hasPermissions($this->getPermission('settings.update')));

        $this->assertTrue($this->getRoleModerator()->hasPermissions($this->getPermission('blog.create')));
        $this->assertTrue($this->getRoleModerator()->hasPermissions($this->getPermission('blog.update')));
        $this->assertTrue($this->getRoleModerator()->hasPermissions($this->getPermission('blog.delete')));
        $this->assertTrue($this->getRoleModerator()->hasPermissions($this->getPermission('comment.create')));
        $this->assertTrue($this->getRoleModerator()->hasPermissions($this->getPermission('comment.update')));
        $this->assertTrue($this->getRoleModerator()->hasPermissions($this->getPermission('comment.delete')));
        $this->assertTrue($this->getRoleModerator()->hasPermissions($this->getPermission('comment.approve')));
        $this->assertTrue($this->getRoleModerator()->hasPermissions($this->getPermission('comment.disapprove')));
        $this->assertFalse($this->getRoleModerator()->hasPermissions($this->getPermission('settings.create')));
        $this->assertFalse($this->getRoleModerator()->hasPermissions($this->getPermission('settings.update')));

        $this->assertTrue($this->getRoleAdmin()->hasPermissions($this->getPermission('blog.create')));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions($this->getPermission('blog.update')));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions($this->getPermission('blog.delete')));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions($this->getPermission('comment.create')));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions($this->getPermission('comment.update')));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions($this->getPermission('comment.delete')));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions($this->getPermission('comment.approve')));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions($this->getPermission('comment.disapprove')));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions($this->getPermission('settings.create')));
        $this->assertTrue($this->getRoleAdmin()->hasPermissions($this->getPermission('settings.update')));
    }

    public function testHasAnyPermissionInt(): void
    {
        $this->assertTrue($this->getUserEditor()->hasAnyPermission(1));
        $this->assertTrue($this->getUserEditor()->hasAnyPermission([1]));
        $this->assertTrue($this->getUserEditor()->hasAnyPermission([1, 2]));
        $this->assertTrue($this->getUserEditor()->hasAnyPermission([3, 4, 5]));
        $this->assertTrue($this->getUserEditor()->hasAnyPermission(new Collection([3, 4, 5])));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission([4, 5]));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission(new Collection([4, 5])));

        $this->assertTrue($this->getUserModerator()->hasAnyPermission(4));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission([4]));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission([1, 2, 4, 5]));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission([5, 6, 9]));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission(new Collection([5, 6, 9])));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission(new Collection([1, 2, 9])));
        $this->assertFalse($this->getUserModerator()->hasAnyPermission([9, 10]));
        $this->assertFalse($this->getUserModerator()->hasAnyPermission(new Collection([9, 10])));

        $this->assertTrue($this->getUserAdmin()->hasAnyPermission(9));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission([9]));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission([1, 2, 4]));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission([6, 9, 10]));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission(new Collection([6, 9, 10])));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission(new Collection([1, 2])));
        $this->assertFalse($this->getUserAdmin()->hasAnyPermission([11]));

        $this->assertTrue($this->getRoleEditor()->hasAnyPermission(1));
        $this->assertTrue($this->getRoleEditor()->hasAnyPermission([1]));
        $this->assertTrue($this->getRoleEditor()->hasAnyPermission([1, 2, 3]));
        $this->assertTrue($this->getRoleEditor()->hasAnyPermission([6, 9, 1]));
        $this->assertTrue($this->getRoleEditor()->hasAnyPermission(new Collection([6, 9, 1])));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission(range(4, 10)));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission(new Collection(range(4, 10))));

        $this->assertTrue($this->getRoleModerator()->hasAnyPermission(4));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission([4]));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission([4, 5, 6, 7, 8]));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission(new Collection([4, 5, 6, 7, 8])));
        $this->assertFalse($this->getRoleModerator()->hasAnyPermission([9, 10]));
        $this->assertFalse($this->getRoleModerator()->hasAnyPermission(new Collection([9, 10])));

        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission(9));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission([9]));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission([4, 5, 6, 7, 8, 9]));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission(new Collection([4, 5, 6, 7, 8, 9])));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission([1, 2, 3, 6, 7]));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission(new Collection([1, 2, 3, 6, 7])));
    }

    public function testHasAnyRoleString(): void
    {
        $this->assertTrue($this->getUserEditor()->hasAnyPermission('blog.create'));
        $this->assertTrue($this->getUserEditor()->hasAnyPermission('blog.create|blog.update'));
        $this->assertTrue($this->getUserEditor()->hasAnyPermission(['blog.delete']));
        $this->assertTrue($this->getUserEditor()->hasAnyPermission(['blog.create', 'blog.delete']));
        $this->assertTrue($this->getUserEditor()->hasAnyPermission(new Collection(['blog.create'])));
        $this->assertTrue($this->getUserEditor()->hasAnyPermission(new Collection(['blog.create', 'blog.delete'])));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission('comment.create'));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission('comment.create|comment.update'));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission(['comment.delete']));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission(['comment.delete', 'comment.approve']));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission(new Collection(['comment.approve', 'comment.disapprove'])));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission('settings.create'));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission('settings.update'));

        $this->assertTrue($this->getUserModerator()->hasAnyPermission('blog.create'));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission('blog.update'));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission('blog.delete'));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission('comment.create'));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission('comment.update'));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission('comment.delete'));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission('comment.approve'));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission('comment.disapprove'));
        $this->assertFalse($this->getUserModerator()->hasPermissions('settings.create'));
        $this->assertFalse($this->getUserModerator()->hasAnyPermission('settings.update'));

        $this->assertTrue($this->getUserAdmin()->hasAnyPermission('blog.create'));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission('blog.update'));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission('blog.delete'));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission('comment.create'));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission('comment.update'));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission('comment.delete'));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission('comment.approve'));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission('comment.disapprove'));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission('settings.create'));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission('settings.update'));

        $this->assertTrue($this->getRoleEditor()->hasAnyPermission('blog.create|blog.update|blog.delete'));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission(['comment.create', 'comment.update', 'comment.delete']));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission(new Collection(['comment.create', 'comment.update', 'comment.delete'])));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission(new Collection('comment.approve')));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission('comment.disapprove'));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission('settings.create'));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission('settings.create|settings.update'));

        $this->assertTrue($this->getRoleModerator()->hasAnyPermission('blog.create|blog.update|blog.delete'));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission(['comment.create', 'comment.update', 'comment.delete']));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission(new Collection(['comment.create', 'comment.update', 'comment.delete'])));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission(new Collection('comment.approve')));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission('comment.disapprove'));
        $this->assertFalse($this->getRoleModerator()->hasAnyPermission('settings.create'));
        $this->assertFalse($this->getRoleModerator()->hasAnyPermission('settings.create|settings.update'));

        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission('blog.create|blog.update|blog.delete'));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission(['comment.create', 'comment.update', 'comment.delete']));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission(new Collection(['comment.create', 'comment.update', 'comment.delete'])));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission(new Collection('comment.approve')));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission('comment.disapprove'));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission('settings.create'));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission('settings.create|settings.update'));
    }

    public function testHasAnyPermissionModel(): void
    {
        $this->assertTrue($this->getUserEditor()->hasAnyPermission($this->getPermission('blog.create')));
        $this->assertTrue($this->getUserEditor()->hasAnyPermission([$this->getPermission('blog.delete')]));
        $this->assertTrue($this->getUserEditor()->hasAnyPermission($this->getPermissions('blog.create', 'blog.delete')->all()));
        $this->assertTrue($this->getUserEditor()->hasAnyPermission($this->getPermissions('blog.create')));
        $this->assertTrue($this->getUserEditor()->hasAnyPermission($this->getPermissions('blog.create', 'blog.delete')));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission($this->getPermission('comment.create')));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission([$this->getPermission('comment.delete')]));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission($this->getPermissions('comment.delete', 'comment.approve')->all()));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission($this->getPermissions('comment.approve', 'comment.disapprove')));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission($this->getPermission('settings.create')));
        $this->assertFalse($this->getUserEditor()->hasAnyPermission($this->getPermission('settings.update')));

        $this->assertTrue($this->getUserModerator()->hasAnyPermission($this->getPermission('blog.create')));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission($this->getPermission('blog.update')));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission($this->getPermission('blog.delete')));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission($this->getPermission('comment.create')));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission($this->getPermission('comment.update')));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission($this->getPermission('comment.delete')));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission($this->getPermission('comment.approve')));
        $this->assertTrue($this->getUserModerator()->hasAnyPermission($this->getPermission('comment.disapprove')));
        $this->assertFalse($this->getUserModerator()->hasAnyPermission($this->getPermission('settings.create')));
        $this->assertFalse($this->getUserModerator()->hasAnyPermission($this->getPermission('settings.update')));

        $this->assertTrue($this->getUserAdmin()->hasAnyPermission($this->getPermission('blog.create')));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission($this->getPermission('blog.update')));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission($this->getPermission('blog.delete')));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission($this->getPermission('comment.create')));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission($this->getPermission('comment.update')));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission($this->getPermission('comment.delete')));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission($this->getPermission('comment.approve')));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission($this->getPermission('comment.disapprove')));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission($this->getPermission('settings.create')));
        $this->assertTrue($this->getUserAdmin()->hasAnyPermission($this->getPermission('settings.update')));

        $this->assertTrue($this->getRoleEditor()->hasAnyPermission($this->getPermission('blog.create')));
        $this->assertTrue($this->getRoleEditor()->hasAnyPermission($this->getPermission('blog.update')));
        $this->assertTrue($this->getRoleEditor()->hasAnyPermission($this->getPermission('blog.delete')));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission($this->getPermission('comment.create')));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission($this->getPermission('comment.update')));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission($this->getPermission('comment.delete')));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission($this->getPermission('comment.approve')));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission($this->getPermission('comment.disapprove')));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission($this->getPermission('settings.create')));
        $this->assertFalse($this->getRoleEditor()->hasAnyPermission($this->getPermission('settings.update')));

        $this->assertTrue($this->getRoleModerator()->hasAnyPermission($this->getPermission('blog.create')));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission($this->getPermission('blog.update')));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission($this->getPermission('blog.delete')));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission($this->getPermission('comment.create')));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission($this->getPermission('comment.update')));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission($this->getPermission('comment.delete')));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission($this->getPermission('comment.approve')));
        $this->assertTrue($this->getRoleModerator()->hasAnyPermission($this->getPermission('comment.disapprove')));
        $this->assertFalse($this->getRoleModerator()->hasAnyPermission($this->getPermission('settings.create')));
        $this->assertFalse($this->getRoleModerator()->hasAnyPermission($this->getPermission('settings.update')));

        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission($this->getPermission('blog.create')));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission($this->getPermission('blog.update')));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission($this->getPermission('blog.delete')));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission($this->getPermission('comment.create')));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission($this->getPermission('comment.update')));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission($this->getPermission('comment.delete')));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission($this->getPermission('comment.approve')));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission($this->getPermission('comment.disapprove')));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission($this->getPermission('settings.create')));
        $this->assertTrue($this->getRoleAdmin()->hasAnyPermission($this->getPermission('settings.update')));
    }
}
