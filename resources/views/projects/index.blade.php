@extends('layouts.app')

@section('title', 'Projects - Task Manager')

@section('content')

<div class="row">

    <div class="col-lg-5">

        <div class="card shadow-sm mb-4">

            <div class="card-body p-4">

                <h1 class="h4 mb-3">
                    Create Project
                </h1>


                <form
                    action="{{ route('projects.store') }}"
                    method="POST"
                >

                    @csrf


                    <div class="mb-3">

                        <label
                            for="name"
                            class="form-label"
                        >
                            Project Name
                        </label>


                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            placeholder="e.g. Website Redesign"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        Create Project
                    </button>

                </form>

            </div>

        </div>

    </div>


    <div class="col-lg-7">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h1 class="h3 mb-1">
                    Projects
                </h1>

                <p class="text-muted mb-0">
                    Manage your projects.
                </p>

            </div>

        </div>


        <div class="card shadow-sm">

            @if($projects->isEmpty())

                <div class="card-body text-center py-5">

                    <p class="text-muted mb-0">
                        No projects created yet.
                    </p>

                </div>

            @else

                <div class="list-group list-group-flush">

                    @foreach($projects as $project)

                        <div
                            class="list-group-item d-flex align-items-center justify-content-between py-3"
                        >

                            <div>

                                <h5 class="mb-1">
                                    {{ $project->name }}
                                </h5>

                                <small class="text-muted">

                                    {{ $project->tasks_count }}
                                    {{ Str::plural(
                                        'task',
                                        $project->tasks_count
                                    ) }}

                                </small>

                            </div>


                            <div class="d-flex gap-2">

                                <a
                                    href="{{ route(
                                        'tasks.index',
                                        ['project_id' => $project->id]
                                    ) }}"
                                    class="btn btn-sm btn-primary"
                                >
                                    View Tasks
                                </a>


                                <form
                                    action="{{ route(
                                        'projects.destroy',
                                        $project
                                    ) }}"
                                    method="POST"
                                    onsubmit="
                                        return confirm(
                                            'Deleting this project will also delete all its tasks. Continue?'
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

                    @endforeach

                </div>

            @endif

        </div>

    </div>

</div>

@endsection