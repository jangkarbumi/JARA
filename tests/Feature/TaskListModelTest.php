<?php

namespace Tests\Feature;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskListModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_have_owned_lists(): void
    {
        $user = User::create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => bcrypt('password123'),
        ]);

        $list = TaskList::create([
            'name' => 'Sprint 1',
            'user_id' => $user->id,
        ]);

        $this->assertEquals(1, $user->ownedLists()->count());
        $this->assertEquals('Sprint 1', $user->ownedLists->first()->name);
        $this->assertEquals($user->id, $list->owner->id);
    }

    public function test_list_can_have_collaborators_via_pivot(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password123'),
        ]);

        $collaborator = User::create([
            'name' => 'Collaborator',
            'email' => 'collab@example.com',
            'password' => bcrypt('password123'),
        ]);

        $list = TaskList::create([
            'name' => 'Proyek Bersama',
            'user_id' => $owner->id,
        ]);

        $list->collaborators()->attach($collaborator->id, ['role' => 'collaborator']);

        $this->assertTrue($list->collaborators->contains($collaborator));
        $this->assertEquals(1, $collaborator->collaboratingLists()->count());
        $this->assertEquals('collaborator', $list->collaborators->first()->pivot->role);
    }

    public function test_is_accessible_by_checks_owner_and_collaborators(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password123'),
        ]);

        $collaborator = User::create([
            'name' => 'Collaborator',
            'email' => 'collab@example.com',
            'password' => bcrypt('password123'),
        ]);

        $stranger = User::create([
            'name' => 'Stranger',
            'email' => 'stranger@example.com',
            'password' => bcrypt('password123'),
        ]);

        $list = TaskList::create([
            'name' => 'Private List',
            'user_id' => $owner->id,
        ]);

        $list->collaborators()->attach($collaborator->id, ['role' => 'collaborator']);

        $this->assertTrue($list->isAccessibleBy($owner));
        $this->assertTrue($list->isAccessibleBy($collaborator));
        $this->assertFalse($list->isAccessibleBy($stranger));
    }
}
