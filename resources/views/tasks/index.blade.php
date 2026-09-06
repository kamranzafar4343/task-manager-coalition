@extends('layouts.app')

@section('title', 'Tasks - Task Manager')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="h3 mb-1">
            Tasks
        </h1>

        <p class="text-muted mb-0">
            Organize and prioritize your work.
        </p>
    </div>

    <a
        href="{{ route('tasks.create', request()->only('project_id')) }}"
        class="btn btn-primary"
    >
        + New Task
    </a>

</div>


<div class="card shadow-sm mb-4">

    <div class="card-body">

        <form method="GET" action="{{ route('tasks.index') }}">

            <div class="row align-items-end">

                <div class="col-md-6">

                    <label
                        for="project_id"
                        class="form-label fw-semibold"
                    >
                        Select Project
                    </label>

                    <select
                        name="project_id"
                        id="project_id"
                        class="form-select"
                        onchange="this.form.submit()"
                    >
                        <option value="">
                            -- Select a project --
                        </option>

                        @foreach($projects as $project)

                            <option
                                value="{{ $project->id }}"
                                @selected(
                                    $selectedProject
                                    && $selectedProject->id === $project->id
                                )
                            >
                                {{ $project->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-md-3 mt-3 mt-md-0">

                    <a
                        href="{{ route('projects.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        Manage Projects
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>


@if(!$selectedProject)

    <div class="card shadow-sm">
        <div class="card-body text-center py-5">

            <h4>No Project Selected</h4>

            <p class="text-muted">
                Select a project from the dropdown to view its tasks.
            </p>

            @if($projects->isEmpty())

                <a
                    href="{{ route('projects.index') }}"
                    class="btn btn-primary"
                >
                    Create Your First Project
                </a>

            @endif

        </div>
    </div>


@else

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <h4 class="mb-1">
                {{ $selectedProject->name }}
            </h4>

            <small class="text-muted">
                Drag and drop tasks to change their priority.
            </small>

        </div>

        <span class="badge bg-secondary">
            {{ $tasks->count() }}
            {{ Str::plural('Task', $tasks->count()) }}
        </span>

    </div>


    @if($tasks->isEmpty())

        <div class="card shadow-sm">

            <div class="card-body text-center py-5">

                <h5>No tasks yet</h5>

                <p class="text-muted">
                    Create your first task for this project.
                </p>

                <a
                    href="{{ route(
                        'tasks.create',
                        ['project_id' => $selectedProject->id]
                    ) }}"
                    class="btn btn-primary"
                >
                    + Create Task
                </a>

            </div>

        </div>

    @else

        <div
            id="task-list"
            data-project-id="{{ $selectedProject->id }}"
        >

            @foreach($tasks as $task)

                <div
                    class="card shadow-sm mb-3 task-item"
                    data-task-id="{{ $task->id }}"
                >

                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <div
                                class="drag-handle me-3"
                                title="Drag to reorder"
                            >
                                ☰
                            </div>


                            <span
                                class="badge bg-primary priority-badge me-3"
                            >
                                #{{ $task->priority }}
                            </span>


                            <div class="flex-grow-1">

                                <h5 class="mb-1">
                                    {{ $task->name }}
                                </h5>

                                <small class="text-muted">

                                    Created:
                                    {{ $task->created_at->format('M d, Y H:i') }}

                                </small>

                            </div>


                            <div class="d-flex gap-2">

                                <a
                                    href="{{ route('tasks.edit', $task) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    Edit
                                </a>


                                <form
                                    action="{{ route('tasks.destroy', $task) }}"
                                    method="POST"
                                    onsubmit="
                                        return confirm(
                                            'Are you sure you want to delete this task?'
                                        )
                                    "
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

@endif

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const taskList = document.getElementById('task-list');

    if (!taskList) {
        return;
    }

    const projectId = taskList.dataset.projectId;


    new Sortable(taskList, {

        animation: 150,

        handle: '.drag-handle',

        ghostClass: 'sortable-ghost',


        onEnd: function () {

            const taskIds = Array
                .from(taskList.querySelectorAll('.task-item'))
                .map(function (task) {
                    return Number(task.dataset.taskId);
                });


            fetch('{{ route('tasks.reorder') }}', {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN':
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        )?.getAttribute('content')
                        || '{{ csrf_token() }}'
                },

                body: JSON.stringify({
                    project_id: Number(projectId),
                    tasks: taskIds
                })

            })

            .then(function (response) {

                if (!response.ok) {
                    throw new Error('Failed to update task priorities.');
                }

                return response.json();

            })

            .then(function () {

                taskList
                    .querySelectorAll('.task-item')
                    .forEach(function (task, index) {

                        const badge =
                            task.querySelector('.priority-badge');

                        if (badge) {
                            badge.textContent = '#' + (index + 1);
                        }

                    });

            })

            .catch(function (error) {

                console.error(error);

                alert(
                    'Could not update task priorities. Please refresh the page.'
                );

            });

        }

    });

});

</script>

@endpush