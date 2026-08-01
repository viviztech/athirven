@props(['title' => null, 'description' => null])
<!DOCTYPE html>
<html lang="ta">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#1636c7">
        <title>{{ $title ? "{$title} — அதிர்வெண்" : 'அதிர்வெண் — தலித் அரசியல் மற்றும் பண்பாட்டு மாத இதழ்' }}</title>
        @if ($description)
            <meta name="description" content="{{ $description }}">
        @endif

        <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        <link rel="icon" href="{{ asset('images/icon-192.png') }}" type="image/png">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

        <x-frontend.json-ld :data="[
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'அதிர்வெண்',
            'url' => route('home'),
        ]" />

        @if (config('services.plausible.domain'))
            <script defer data-domain="{{ config('services.plausible.domain') }}" src="https://plausible.io/js/script.js"></script>
        @endif

        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        @vite(['resources/css/app.css'])
        @livewireStyles
    </head>
    <body class="font-tamil min-h-screen bg-paper text-ink antialiased">
        <header>
            {{-- Utility bar: dateline + reader controls, press-credential register --}}
            <div class="border-b border-hairline">
                <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-2 font-meta text-[11px] tracking-wider text-slate uppercase">
                    <span>{{ now()->translatedFormat('d F Y') }}</span>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('search') }}" aria-label="தேடல்" class="text-slate hover:text-ambedkar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="7" />
                                <path d="m21 21-4.35-4.35" />
                            </svg>
                        </a>
                        @auth
                            <a href="{{ route('account') }}" class="hover:text-ambedkar">என் கணக்கு</a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-ambedkar">உள்நுழையவும்</a>
                        @endauth
                        <button
                            type="button"
                            aria-label="இருண்ட பயன்முறை"
                            onclick="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';"
                            class="text-slate hover:text-ambedkar"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Nameplate --}}
            <div class="relative overflow-hidden border-b border-hairline bg-paper-raised">
                <div class="absolute inset-0 bg-cover bg-center opacity-20 dark:opacity-25" style="background-image: url('{{ asset('images/masthead-bg.webp') }}')" aria-hidden="true"></div>
                <div class="relative mx-auto max-w-6xl px-6 py-10 text-center">
                    <a href="{{ route('home') }}" class="inline-block" aria-label="அதிர்வெண்">
                        <img src="{{ asset('images/logo-transparent.png') }}" alt="அதிர்வெண்" class="h-12 w-auto dark:hidden sm:h-14" width="3377" height="662">
                        <img src="{{ asset('images/logo-transparent-white.png') }}" alt="அதிர்வெண்" class="hidden h-12 w-auto dark:block sm:h-14" width="3377" height="662">
                    </a>
                    <p class="mt-3 font-meta text-[11px] tracking-[0.2em] text-slate uppercase">தலித் அரசியல் மற்றும் பண்பாட்டு மாத இதழ்</p>
                </div>
            </div>

            <nav class="bg-[#821603]">
                <div class="no-scrollbar mx-auto flex max-w-6xl items-center gap-8 overflow-x-auto px-6 py-6 font-meta text-xs tracking-wider whitespace-nowrap uppercase">
                    <a href="{{ route('home') }}" class="shrink-0 text-[#f2e8db] hover:text-gold">முகப்பு</a>
                    <a href="{{ route('issues.index') }}" class="shrink-0 text-[#f2e8db] hover:text-gold">இதழ்கள்</a>
                    @foreach ($navCategories as $category)
                        <a href="{{ route('categories.show', $category) }}" class="shrink-0 text-[#f2e8db] hover:text-gold">{{ $category->name_ta }}</a>
                    @endforeach
                    <a href="{{ route('categories.index') }}" class="shrink-0 text-[#f2e8db] hover:text-gold">அனைத்து பிரிவுகளும்</a>
                </div>
            </nav>
        </header>

        <main class="mx-auto max-w-6xl px-6 py-12">
            {{ $slot }}
        </main>

        <footer class="border-t border-hairline">
            <div class="mx-auto max-w-6xl px-6 py-10">
                @if (session('status'))
                    <p class="mb-4 font-meta text-sm text-ambedkar">{{ session('status') }}</p>
                @endif

                <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="font-tamil text-lg text-ink">அதிர்வெண்</p>
                        <p class="mt-1 max-w-xs text-sm text-slate">
                            தலித் அரசியல் மற்றும் பண்பாட்டு விவாதங்களை ஆவணப்படுத்தும் மாத இதழ்.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex w-full max-w-sm gap-2">
                        @csrf
                        <input
                            type="email"
                            name="email"
                            required
                            placeholder="மின்னஞ்சல் முகவரி"
                            class="w-full rounded-none border border-hairline bg-paper-raised px-3 py-2 text-sm text-ink placeholder:text-slate focus:border-ambedkar focus:outline-none"
                        >
                        <button type="submit" class="shrink-0 bg-ambedkar px-4 py-2 font-meta text-xs tracking-wider text-white uppercase hover:bg-ambedkar-ink">
                            சந்தா
                        </button>
                    </form>
                </div>

                <p class="mt-8 font-meta text-[11px] tracking-wider text-slate uppercase">
                    &copy; {{ now()->year }} அதிர்வெண் — அனைத்து உரிமைகளும் பாதுகாக்கப்பட்டவை
                </p>
            </div>
        </footer>

        <script>
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js');
            }
        </script>

        @livewireScripts
    </body>
</html>
