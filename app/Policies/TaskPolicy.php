<?php

namespace App\Policies;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Task $task): bool
    {
        return $user->isManager() || $task->assigned_to === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isManager();
    }

    public function update(User $user, Task $task): bool
    {
        return $user->isManager() && $task->status === TaskStatus::Pending;
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->update($user, $task);
    }

    public function complete(User $user, Task $task): bool
    {
        return $task->assigned_to === $user->id
            && in_array($task->status, [TaskStatus::Pending, TaskStatus::Rejected]);
    }

    public function review(User $user, Task $task): bool
    {
        return $user->isManager() && $task->status === TaskStatus::Completed;
    }
}
