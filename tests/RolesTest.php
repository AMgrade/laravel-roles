<?php

declare(strict_types=1);

namespace AMgrade\Roles\Tests;

use Illuminate\Database\Eloquent\Collection;

class RolesTest extends TestCase
{
    public function testHasRoleInt(): void
    {
        $this->assertTrue($this->getUserEditor()->hasRole(1));
        $this->assertFalse($this->getUserEditor()->hasRole(2));
        $this->assertFalse($this->getUserEditor()->hasRole(3));
        $this->assertFalse($this->getUserEditor()->hasRole(4));

        $this->assertTrue($this->getUserModerator()->hasRole(2));
        $this->assertFalse($this->getUserModerator()->hasRole(1));
        $this->assertFalse($this->getUserModerator()->hasRole(3));
        $this->assertFalse($this->getUserModerator()->hasRole(4));

        $this->assertTrue($this->getUserAdmin()->hasRole(3));
        $this->assertFalse($this->getUserAdmin()->hasRole(1));
        $this->assertFalse($this->getUserAdmin()->hasRole(2));
        $this->assertFalse($this->getUserAdmin()->hasRole(4));
    }

    public function testHasRoleString(): void
    {
        $this->assertTrue($this->getUserEditor()->hasRole('editor'));
        $this->assertFalse($this->getUserEditor()->hasRole('admin'));
        $this->assertFalse($this->getUserEditor()->hasRole('moderator'));

        $this->assertTrue($this->getUserModerator()->hasRole('moderator'));
        $this->assertFalse($this->getUserModerator()->hasRole('admin'));
        $this->assertFalse($this->getUserModerator()->hasRole('editor'));

        $this->assertTrue($this->getUserAdmin()->hasRole('admin'));
        $this->assertFalse($this->getUserAdmin()->hasRole('moderator'));
        $this->assertFalse($this->getUserAdmin()->hasRole('editor'));
    }

    public function testHasRoleModel(): void
    {
        $this->assertTrue($this->getUserEditor()->hasRole($this->getRoleEditor()));
        $this->assertFalse($this->getUserEditor()->hasRole($this->getRoleAdmin()));
        $this->assertFalse($this->getUserEditor()->hasRole($this->getRoleModerator()));

        $this->assertTrue($this->getUserModerator()->hasRole($this->getRoleModerator()));
        $this->assertFalse($this->getUserModerator()->hasRole($this->getRoleAdmin()));
        $this->assertFalse($this->getUserModerator()->hasRole($this->getRoleEditor()));

        $this->assertTrue($this->getUserAdmin()->hasRole($this->getRoleAdmin()));
        $this->assertFalse($this->getUserAdmin()->hasRole($this->getRoleModerator()));
        $this->assertFalse($this->getUserAdmin()->hasRole($this->getRoleEditor()));
    }

    public function testHasRolesInt(): void
    {
        $this->assertTrue($this->getUserEditor()->hasRoles(1));
        $this->assertTrue($this->getUserEditor()->hasRoles([1]));
        $this->assertTrue($this->getUserEditor()->hasRoles(new Collection([1])));

        $this->assertTrue($this->getUserModerator()->hasRoles(2));
        $this->assertTrue($this->getUserModerator()->hasRoles([2]));
        $this->assertTrue($this->getUserModerator()->hasRoles(new Collection([2])));

        $this->assertTrue($this->getUserAdmin()->hasRoles(3));
        $this->assertTrue($this->getUserAdmin()->hasRoles([3]));
        $this->assertTrue($this->getUserAdmin()->hasRoles(new Collection([3])));
    }

    public function testHasRolesString(): void
    {
        $this->assertTrue($this->getUserEditor()->hasRoles('editor'));
        $this->assertTrue($this->getUserEditor()->hasRoles(['editor']));
        $this->assertTrue($this->getUserEditor()->hasRoles(new Collection(['editor'])));

        $this->assertTrue($this->getUserModerator()->hasRoles('moderator'));
        $this->assertTrue($this->getUserModerator()->hasRoles(['moderator']));
        $this->assertTrue($this->getUserModerator()->hasRoles(new Collection(['moderator'])));

        $this->assertTrue($this->getUserAdmin()->hasRoles('admin'));
        $this->assertTrue($this->getUserAdmin()->hasRoles(['admin']));
        $this->assertTrue($this->getUserAdmin()->hasRoles(new Collection(['admin'])));
    }

    public function testHasRolesModel(): void
    {
        $this->assertTrue($this->getUserEditor()->hasRoles($this->getRoleEditor()));
        $this->assertTrue($this->getUserEditor()->hasRoles([$this->getRoleEditor()]));
        $this->assertTrue($this->getUserEditor()->hasRoles(new Collection([$this->getRoleEditor()])));

        $this->assertTrue($this->getUserModerator()->hasRoles($this->getRoleModerator()));
        $this->assertTrue($this->getUserModerator()->hasRoles([$this->getRoleModerator()]));
        $this->assertTrue($this->getUserModerator()->hasRoles(new Collection([$this->getRoleModerator()])));

        $this->assertTrue($this->getUserAdmin()->hasRoles($this->getRoleAdmin()));
        $this->assertTrue($this->getUserAdmin()->hasRoles([$this->getRoleAdmin()]));
        $this->assertTrue($this->getUserAdmin()->hasRoles(new Collection([$this->getRoleAdmin()])));
    }

    public function testHasAnyRoleInt(): void
    {
        $this->assertTrue($this->getUserEditor()->hasAnyRole(1));
        $this->assertTrue($this->getUserEditor()->hasAnyRole([1]));
        $this->assertTrue($this->getUserEditor()->hasAnyRole([2, 3, 1]));
        $this->assertTrue($this->getUserEditor()->hasAnyRole([3, 1, 2]));
        $this->assertTrue($this->getUserEditor()->hasAnyRole(new Collection([1])));
        $this->assertTrue($this->getUserEditor()->hasAnyRole(new Collection([1, 2, 3])));
        $this->assertFalse($this->getUserEditor()->hasAnyRole(2));
        $this->assertFalse($this->getUserEditor()->hasAnyRole([2]));
        $this->assertFalse($this->getUserEditor()->hasAnyRole([2, 3, 4]));
        $this->assertFalse($this->getUserEditor()->hasAnyRole(new Collection([2, 3, 4])));
        $this->assertFalse($this->getUserEditor()->hasAnyRole(new Collection([4, 5, 6])));

        $this->assertTrue($this->getUserModerator()->hasAnyRole(2));
        $this->assertTrue($this->getUserModerator()->hasAnyRole([2]));
        $this->assertTrue($this->getUserModerator()->hasAnyRole([2, 3, 1]));
        $this->assertTrue($this->getUserModerator()->hasAnyRole([3, 1, 2]));
        $this->assertTrue($this->getUserModerator()->hasAnyRole(new Collection([2])));
        $this->assertTrue($this->getUserModerator()->hasAnyRole(new Collection([1, 2, 3])));
        $this->assertFalse($this->getUserModerator()->hasAnyRole(1));
        $this->assertFalse($this->getUserModerator()->hasAnyRole([1]));
        $this->assertFalse($this->getUserModerator()->hasAnyRole([1, 3, 4]));
        $this->assertFalse($this->getUserModerator()->hasAnyRole(new Collection([1, 3, 4])));
        $this->assertFalse($this->getUserModerator()->hasAnyRole(new Collection([4, 5, 6])));

        $this->assertTrue($this->getUserAdmin()->hasAnyRole(3));
        $this->assertTrue($this->getUserAdmin()->hasAnyRole([3]));
        $this->assertTrue($this->getUserAdmin()->hasAnyRole([3, 2, 1]));
        $this->assertTrue($this->getUserAdmin()->hasAnyRole([1, 2, 3]));
        $this->assertTrue($this->getUserAdmin()->hasAnyRole(new Collection([3])));
        $this->assertTrue($this->getUserAdmin()->hasAnyRole(new Collection([1, 2, 3])));
        $this->assertFalse($this->getUserAdmin()->hasAnyRole(2));
        $this->assertFalse($this->getUserAdmin()->hasAnyRole([2]));
        $this->assertFalse($this->getUserAdmin()->hasAnyRole([1, 2, 4]));
        $this->assertFalse($this->getUserAdmin()->hasAnyRole(new Collection([1, 2, 4])));
        $this->assertFalse($this->getUserAdmin()->hasAnyRole(new Collection([4, 5, 6])));
    }

    public function testHasAnyRoleString(): void
    {
        $this->assertTrue($this->getUserEditor()->hasAnyRole('editor'));
        $this->assertTrue($this->getUserEditor()->hasAnyRole('admin|editor'));
        $this->assertTrue($this->getUserEditor()->hasAnyRole('editor|admin'));
        $this->assertTrue($this->getUserEditor()->hasAnyRole(['editor']));
        $this->assertTrue($this->getUserEditor()->hasAnyRole(['editor', 'admin']));
        $this->assertTrue($this->getUserEditor()->hasAnyRole(['admin', 'editor']));
        $this->assertTrue($this->getUserEditor()->hasAnyRole(new Collection(['editor'])));
        $this->assertTrue($this->getUserEditor()->hasAnyRole(new Collection(['admin', 'editor'])));
        $this->assertFalse($this->getUserEditor()->hasAnyRole('admin'));
        $this->assertFalse($this->getUserEditor()->hasAnyRole('admin|moderator'));
        $this->assertFalse($this->getUserEditor()->hasAnyRole(['admin', 'moderator']));
        $this->assertFalse($this->getUserEditor()->hasAnyRole(new Collection(['admin', 'moderator'])));

        $this->assertTrue($this->getUserModerator()->hasAnyRole('moderator'));
        $this->assertTrue($this->getUserModerator()->hasAnyRole('admin|moderator'));
        $this->assertTrue($this->getUserModerator()->hasAnyRole('moderator|admin'));
        $this->assertTrue($this->getUserModerator()->hasAnyRole(['moderator']));
        $this->assertTrue($this->getUserModerator()->hasAnyRole(['moderator', 'admin']));
        $this->assertTrue($this->getUserModerator()->hasAnyRole(['admin', 'moderator']));
        $this->assertTrue($this->getUserModerator()->hasAnyRole(new Collection(['moderator'])));
        $this->assertTrue($this->getUserModerator()->hasAnyRole(new Collection(['admin', 'moderator'])));
        $this->assertFalse($this->getUserModerator()->hasAnyRole('admin'));
        $this->assertFalse($this->getUserModerator()->hasAnyRole('admin|editor'));
        $this->assertFalse($this->getUserModerator()->hasAnyRole(['admin', 'editor']));
        $this->assertFalse($this->getUserModerator()->hasAnyRole(new Collection(['admin', 'editor'])));

        $this->assertTrue($this->getUserAdmin()->hasAnyRole('admin'));
        $this->assertTrue($this->getUserAdmin()->hasAnyRole('admin|editor'));
        $this->assertTrue($this->getUserAdmin()->hasAnyRole('editor|admin'));
        $this->assertTrue($this->getUserAdmin()->hasAnyRole(['admin']));
        $this->assertTrue($this->getUserAdmin()->hasAnyRole(['editor', 'admin']));
        $this->assertTrue($this->getUserAdmin()->hasAnyRole(['admin', 'editor']));
        $this->assertTrue($this->getUserAdmin()->hasAnyRole(new Collection(['admin'])));
        $this->assertTrue($this->getUserAdmin()->hasAnyRole(new Collection(['admin', 'editor'])));
        $this->assertFalse($this->getUserAdmin()->hasAnyRole('editor'));
        $this->assertFalse($this->getUserAdmin()->hasAnyRole('editor|moderator'));
        $this->assertFalse($this->getUserAdmin()->hasAnyRole(['editor', 'moderator']));
        $this->assertFalse($this->getUserAdmin()->hasAnyRole(new Collection(['editor', 'moderator'])));
    }

    public function testTogglingRolesInt(): void
    {
        $user = $this->createUser();

        $this->assertEmpty($user->getAttribute('roles'));
        $this->assertEmpty($user->getRoles());

        $user->attachRole($this->getRoleEditor()->getKey());
        $this->assertNotEmpty($user->getAttribute('roles'));
        $this->assertNotEmpty($user->getRoles());
        $this->assertCount(1, $user->getRoles());

        $user->attachRole($this->getRoleAdmin()->getKey());
        $this->assertCount(2, $user->getRoles());

        $user->detachRole($this->getRoleEditor()->getKey());
        $this->assertCount(1, $user->getRoles());

        $user->detachRole($this->getRoleAdmin()->getKey());
        $this->assertEmpty($user->getAttribute('roles'));
        $this->assertEmpty($user->getRoles());
    }

    public function testTogglingRolesString(): void
    {
        $user = $this->createUser();

        $this->assertEmpty($user->getAttribute('roles'));
        $this->assertEmpty($user->getRoles());

        $user->attachRole('editor');
        $this->assertNotEmpty($user->getAttribute('roles'));
        $this->assertCount(1, $user->getRoles());

        $user->attachRole('admin');
        $this->assertCount(2, $user->getRoles());

        $user->detachRole('editor');
        $this->assertCount(1, $user->getRoles());

        $user->detachRole('admin');
        $this->assertEmpty($user->getAttribute('roles'));
        $this->assertEmpty($user->getRoles());

        $user->attachRole('admin|editor');
        $this->assertCount(2, $user->getRoles());
        $this->assertCount(2, $user->getAttribute('roles'));

        $user->detachRole('admin|editor');
        $this->assertEmpty($user->getAttribute('roles'));
        $this->assertEmpty($user->getRoles());

        $user->attachRole(['admin', 'editor']);
        $this->assertCount(2, $user->getRoles());
        $this->assertCount(2, $user->getAttribute('roles'));

        $user->detachRole(['admin', 'editor']);
        $this->assertEmpty($user->getAttribute('roles'));
        $this->assertEmpty($user->getRoles());

        $user->attachRole(['admin', 'editor']);
        $user->detachRole(['admin']);
        $this->assertCount(1, $user->getRoles());
        $this->assertCount(1, $user->getAttribute('roles'));
        $this->assertEquals('editor', $user->getAttribute('roles')->first()->getAttribute('name'));
        $user->detachRole('editor');

        $user->attachRole(new Collection(['admin', 'editor']));
        $this->assertCount(2, $user->getRoles());
        $this->assertCount(2, $user->getAttribute('roles'));

        $user->detachRole(new Collection(['admin', 'editor']));
        $this->assertEmpty($user->getAttribute('roles'));
        $this->assertEmpty($user->getRoles());
    }

    public function testTogglingRolesModel(): void
    {
        $user = $this->createUser();

        $this->assertEmpty($user->getAttribute('roles'));
        $this->assertEmpty($user->getRoles());

        $user->attachRole($this->getRoleEditor());
        $this->assertNotEmpty($user->getAttribute('roles'));
        $this->assertCount(1, $user->getRoles());
        $this->assertEquals('editor', $user->getAttribute('roles')->first()->getAttribute('name'));

        $user->attachRole($this->getRoleAdmin());
        $this->assertCount(2, $user->getRoles());

        $user->detachRole($this->getRoleEditor());
        $this->assertCount(1, $user->getRoles());

        $user->detachRole($this->getRoleAdmin());
        $this->assertEmpty($user->getAttribute('roles'));
        $this->assertEmpty($user->getRoles());

        $user->attachRole([$this->getRoleEditor(), $this->getRoleAdmin()]);
        $this->assertCount(2, $user->getRoles());
        $user->detachRole();
        $this->assertEmpty($user->getRoles());
    }

    public function testLevelAccess(): void
    {
        $this->assertEquals(1, $this->getUserEditor()->levelAccess());
        $this->assertEquals(2, $this->getUserModerator()->levelAccess());
        $this->assertEquals(3, $this->getUserAdmin()->levelAccess());

        $this->getUserEditor()->attachRole($this->getRoleModerator());
        $this->assertEquals(2, $this->getUserEditor()->levelAccess());
        $this->getUserEditor()->attachRole($this->getRoleAdmin());
        $this->assertEquals(3, $this->getUserEditor()->levelAccess());
        $this->getUserEditor()->detachRole($this->getRoleModerator());
        $this->assertEquals(3, $this->getUserEditor()->levelAccess());
    }
}
