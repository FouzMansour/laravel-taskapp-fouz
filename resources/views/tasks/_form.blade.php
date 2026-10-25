<div>
    <x-input-label for="title" value="{{ __('Title') }}" />
    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $task?->title)" />
    <x-input-error :messages="$errors->get('title')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="description" value="{{ __('Description') }}" />
    <textarea id="description" name="description"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $task?->description) }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="assigned_to" value="{{ __('Assignee') }}" />
    <select id="assigned_to" name="assigned_to" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        <option value="">{{ __('Choose an employee') }}</option>
        @foreach ($employees as $employee)
        <option value="{{ $employee->id }}" @selected(old('assigned_to', $task?->assigned_to) == $employee->id)>
            {{ $employee->name }}
        </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="due_date" value="{{ __('Due date') }}" />
    <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full"
        :value="old('due_date', $task?->due_date?->format('Y-m-d'))" />
    <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
</div>