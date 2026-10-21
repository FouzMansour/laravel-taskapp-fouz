<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main>

            @if (session('status'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
                class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                <div class="p-4 bg-green-100 text-green-800 rounded shadow">
                    {{ session('status') }}
                </div>
            </div>
            @endif

            @if (session('danger'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
                class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                <div class="p-4 bg-red-100 text-red-800 rounded shadow">
                    {{ session('danger') }}
                </div>
            </div>
            @endif

            @php $unread = auth()->user()->unreadNotifications; @endphp

            @if ($unread->count())
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
                <div class="p-4 bg-blue-50 text-blue-800 rounded">
                    <strong>You have {{ $unread->count() }} new notification(s):</strong>
                    <ul class="mt-2 list-disc ml-5">
                        @foreach ($unread as $n)
                        <li>
                            <a href="{{ route('tasks.show', $n->data['task_id']) }}">{{ $n->data['message'] }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif


            {{ $slot }}
        </main>
    </div>
  <script>
    window.addEventListener('load', () => {
        const userId = {{ auth()->id() }};

        window.Echo.private('App.Models.User.' + userId)
            .notification((n) => {
                const box = document.createElement('div');
                box.className = 'fixed top-4 right-4 bg-blue-600 text-white p-4 rounded shadow-lg z-50';
                const link = document.createElement('a');
                link.href = '/tasks/' + n.task_id;
                link.textContent = n.message;
                box.appendChild(link);
                document.body.appendChild(box);
                setTimeout(() => box.remove(), 6000);

                fetch('/notifications/' + n.id + '/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
            });

        window.Echo.private('tasks.' + userId)
            .listen('.task.updated', (e) => {
                const path = window.location.pathname;
                const onList = path === '/tasks';
                const onThisTask = path === '/tasks/' + e.task_id;

                if (!onList && !onThisTask) return;

                const current = document.getElementById('live-content');
                if (!current) return;

                fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then((r) => r.text())
                    .then((html) => {
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const fresh = doc.getElementById('live-content');
                        if (fresh) current.innerHTML = fresh.innerHTML;
                    });
            });
    });
</script>

</body>

</html>