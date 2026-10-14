<?php

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->manager = User::factory()->create(['role' => 'manager']);
    $this->employee = User::factory()->create(['role' => 'employee']);
});

it('1. A manager can create a task and it appears in the database.', function () {
    $this->actingAs($this->manager)
        ->post(route('tasks.store'), [
            'title' => 'Write report',
            'assigned_to' => $this->employee->id,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('tasks', [
        'title' => 'Write report',
        'assigned_to' => $this->employee->id,
        'created_by' => $this->manager->id,
        'status' => 'pending',
    ]);
});

it('2. An employee cannot create a task (gets 403).', function () {
    $this->actingAs($this->employee)
        ->post(route('tasks.store'), [
            'title' => 'Sneaky task',
            'assigned_to' => $this->employee->id,
        ])
        ->assertForbidden();
});

it('3. An employee sees only tasks assigned to them on the index page.', function () {
    $other = User::factory()->create(['role' => 'employee']);

    Task::factory()->for($this->employee, 'assignee')->create(['title' => 'Mine']);
    Task::factory()->for($other, 'assignee')->create(['title' => 'Not mine']);

    $this->actingAs($this->employee)
        ->get(route('tasks.index'))
        ->assertOk()
        ->assertSee('Mine')
        ->assertDontSee('Not mine');
});

it('4. An employee can mark their own pending task as completed.', function () {
    $task = Task::factory()->for($this->employee, 'assignee')->create();

    $this->actingAs($this->employee)
        ->patch(route('tasks.complete', $task))
        ->assertRedirect(route('tasks.show', $task));

    expect($task->fresh()->status)->toBe(TaskStatus::Completed);
});

it('5. An employee cannot complete a task assigned to someone else.', function () {
    $other = User::factory()->create(['role' => 'employee']);
    $task = Task::factory()->for($other, 'assignee')->create();

    $this->actingAs($this->employee)
        ->patch(route('tasks.complete', $task))
        ->assertForbidden();

    expect($task->fresh()->status)->toBe(TaskStatus::Pending);
});

it('6. A manager can approve a completed task.', function () {
    $task = Task::factory()->for($this->employee, 'assignee')
        ->create(['status' => TaskStatus::Completed]);

    $this->actingAs($this->manager)
        ->patch(route('tasks.approve', $task))
        ->assertRedirect(route('tasks.show', $task));

    expect($task->fresh()->status)->toBe(TaskStatus::Approved);
});

it('7. A manager cannot approve a pending task (gets 403).', function () {
    $task = Task::factory()->for($this->employee, 'assignee')->create();

    $this->actingAs($this->manager)
        ->patch(route('tasks.approve', $task))
        ->assertForbidden();

    expect($task->fresh()->status)->toBe(TaskStatus::Pending);
});

it('8. Rejecting without a note fails validation; with a note the status becomes rejected and
the note is saved.', function () {
    $task = Task::factory()->for($this->employee, 'assignee')
        ->create(['status' => TaskStatus::Completed]);

    $this->actingAs($this->manager)
        ->patch(route('tasks.reject', $task), [])
        ->assertSessionHasErrors('manager_note');

    expect($task->fresh()->status)->toBe(TaskStatus::Completed);

    $this->actingAs($this->manager)
        ->patch(route('tasks.reject', $task), ['manager_note' => 'Please fix the totals'])
        ->assertRedirect(route('tasks.show', $task));

    expect($task->fresh()->status)->toBe(TaskStatus::Rejected);
    expect($task->fresh()->manager_note)->toBe('Please fix the totals');
});




