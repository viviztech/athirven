@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="ta">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ? "{$title} — அதிர்வெண்" : 'அதிர்வெண்' }}</title>
        @if ($description)
            <meta name="description" content="{{ $description }}">
        @endif

        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        @vite(['resources/css/app.css'])
        @livewireStyles
    </head>
    <body class="font-tamil min-h-screen bg-white text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">
        <header class="border-b border-gray-200 dark:border-gray-800">
            <div class="mx-auto flex max-w-4xl items-center justify-between gap-4 px-6 py-5">
                <a href="{{ route('home') }}" class="text-2xl font-semibold shrink-0">அதிர்வெண்</a>

                <nav class="hidden sm:flex items-center gap-5 text-sm text-gray-600 dark:text-gray-400">
                    <a href="{{ route('issues.index') }}" class="hover:text-gray-900 dark:hover:text-white">இதழ்கள்</a>
                    <a href="{{ route('categories.index') }}" class="hover:text-gray-900 dark:hover:text-white">பிரிவுகள்</a>
                </nav>

                <div class="flex items-center gap-3">
                    <a href="{{ route('search') }}" aria-label="தேடல்" class="text-gray-500 hover:text-gray-900 dark:hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                    </a>
                    <button
                        type="button"
                        aria-label="இருண்ட பயன்முறை"
                        onclick="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';"
                        class="text-gray-500 hover:text-gray-900 dark:hover:text-white"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-4xl px-6 py-10">
            {{ $slot }}
        </main>

        <footer class="border-t border-gray-200 mt-16 dark:border-gray-800">
            <div class="mx-auto max-w-4xl px-6 py-8 text-sm text-gray-500 dark:text-gray-400">
                &copy; {{ now()->year }} அதிர்வெண். அனைத்து உரிமைகளும் பாதுகாக்கப்பட்டவை.
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
