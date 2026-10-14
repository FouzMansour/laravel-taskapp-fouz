<?php

namespace Database\Factories;

use App\Models\Task;
use App\Enums\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'assigned_to' => User::factory()->state(['role' => 'employee']),
            'created_by' => User::factory()->state(['role' => 'manager']),
            'status' => TaskStatus::Pending,
            'due_date' => null,
            'manager_note' => null,
        ];
    }
}

