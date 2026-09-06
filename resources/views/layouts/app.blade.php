<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Task Manager')
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f8f9fa;
        }

        .navbar-brand {
            font-weight: 700;
        }

        .task-item {
            cursor: grab;
            transition: 0.2s ease;
        }

        .task-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        }

        .task-item.sortable-ghost {
            opacity: 0.4;
        }

        .drag-handle {
            cursor: grab;
            font-size: 1.3rem;
            color: #6c757d;
        }

        .priority-badge {
            min-width: 45px;
        }
    </style>

    @stack('styles')
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">

        <a
            class="navbar-brand"
            href="{{ route('tasks.index') }}"
        >
            Task Manager
        </a>

        <div class="navbar-nav ms-auto">
            <a
                class="nav-link"
                href="{{ route('tasks.index') }}"
            >
                Tasks
            </a>

            <a
                class="nav-link"
                href="{{ route('projects.index') }}"
            >
                Projects
            </a>
        </div>

    </div>
</nav>


<main class="container pb-5">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif


    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    @yield('content')

</main>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

@stack('scripts')

</body>
</html>