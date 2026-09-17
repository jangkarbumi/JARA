<?php

namespace Tests\Feature;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_have_owned_lists(): void
    {
        $user = User::create([
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => bcrypt('password123'),
        ]);

        $list = TodoList::create([
            'name' => 'Sprint 1',
            'description' => 'Tugas sprint 1',
            'owner_id' => $user->id,
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

        $list = TodoList::create([
            'name' => 'Proyek Bersama',
            'description' => 'Ini proyek bersama',
            'owner_id' => $owner->id,
        ]);

        $list->collaborators()->attach($collaborator->id, ['role' => 'collaborator']);

        $this->assertTrue($list->collaborators->contains($collaborator));
        $this->assertEquals(1, $collaborator->collaboratingLists()->count());
        $this->assertEquals('collaborator', $list->collaborators->first()->pivot->role);
    }

    public function test_policy_allows_owner_and_collaborator_but_not_stranger(): void
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

        $list = TodoList::create([
            'name' => 'Private List',
            'description' => 'List pribadi',
            'owner_id' => $owner->id,
        ]);

        $list->collaborators()->attach($collaborator->id, ['role' => 'collaborator']);

        // Acting as owner
        $this->actingAs($owner);
        $this->assertTrue(Gate::allows('view', $list));
        $this->assertTrue(Gate::allows('delete', $list));

        // Acting as collaborator
        $this->actingAs($collaborator);
        $this->assertTrue(Gate::allows('view', $list));
        $this->assertFalse(Gate::allows('delete', $list));

        // Acting as stranger
        $this->actingAs($stranger);
        $this->assertFalse(Gate::allows('view', $list));
        $this->assertFalse(Gate::allows('delete', $list));
    }
}
