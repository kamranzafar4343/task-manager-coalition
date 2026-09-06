@extends('layouts.app')

@section('title', 'Create Task - Task Manager')

@section('content')

<div class="row justify-content-center">

    <div class="col-lg-7">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h1 class="h3 mb-1">
                    Create Task
                </h1>

                <p class="text-muted mb-0">
                    Add a new task to a project.
                </p>
            </div>

            <a
                href="{{ route('tasks.index') }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>


        <div class="card shadow-sm">

            <div class="card-body p-4">

                <form
                    action="{{ route('tasks.store') }}"
                    method="POST"
                >

                    @csrf


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

                            <option value="">
                                -- Select Project --
                            </option>

                            @foreach($projects as $project)

                                <option
                                    value="{{ $project->id }}"
                                    @selected(
                                        old(
                                            'project_id',
                                            $selectedProjectId
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
                            value="{{ old('name') }}"
                            placeholder="Enter task name"
                            required
                            autofocus
                        >

                    </div>


                    <div class="d-flex justify-content-end gap-2">

                        <a
                            href="{{ route('tasks.index') }}"
                            class="btn btn-light"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Create Task
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection