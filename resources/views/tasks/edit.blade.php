@extends('layouts.app')

@section('title', 'Edit Task - Task Manager')

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-7">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h1 class="h3 mb-1">
                    Edit Task
                </h1>

                <p class="text-muted mb-0">
                    Update task information.
                </p>

            </div>


            <a
                href="{{ route(
                    'tasks.index',
                    ['project_id' => $task->project_id]
                ) }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>


        <div class="card shadow-sm">

            <div class="card-body p-4">

                <form
                    action="{{ route('tasks.update', $task) }}"
                    method="POST"
                >

                    @csrf
                    @method('PUT')


                    <div class="mb-3">

                        <label
                            for="project_id"
                            class="form-label"
                        >
                            Project
                        </label>


                        <select
                            name="project_id"
                            id="project_id"
                            class="form-select"
                            required
                        >

                            @foreach($projects as $project)

                                <option
                                    value="{{ $project->id }}"
                                    @selected(
                                        old(
                                            'project_id',
                                            $task->project_id
                                        ) == $project->id
                                    )
                                >
                                    {{ $project->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="mb-4">

                        <label
                            for="name"
                            class="form-label"
                        >
                            Task Name
                        </label>


                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            value="{{ old('name', $task->name) }}"
                            required
                            autofocus
                        >

                    </div>


                    <div class="d-flex justify-content-end gap-2">

                        <a
                            href="{{ route(
                                'tasks.index',
                                ['project_id' => $task->project_id]
                            ) }}"
                            class="btn btn-light"
                        >
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Update Task
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection