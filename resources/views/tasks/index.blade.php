<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>
            @can('create', App\Models\Task::class)
                <a href="{{ route('tasks.create') }}"
                   class="px-4 py-2 bg-gray-800 text-white text-xs uppercase rounded-md">New task</a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <table id="live-content"class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assignee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due date</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($tasks as $task)
                            <tr>
                                <td class="px-6 py-4">{{ $task->title }}</td>
                                <td class="px-6 py-4">{{ $task->assignee->name }}</td>
                                <td class="px-6 py-4"><x-status-badge :status="$task->status" /></td>
                                <td class="px-6 py-4">{{ $task->due_date?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-indigo-600">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No tasks yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>