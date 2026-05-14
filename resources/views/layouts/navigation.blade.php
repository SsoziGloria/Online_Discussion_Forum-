<nav class="sticky top-0 z-40 border-b border-[var(--color-border)] bg-[color:rgba(255,249,235,0.92)] backdrop-blur">
    <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3 md:px-6">
        <div class="flex items-center gap-6">
            <a href="{{ route('home') }}" class="text-2xl font-extrabold tracking-[-0.04em] text-[var(--color-primary)]">
                DevDen
            </a>
            <div class="hidden items-center gap-5 text-sm font-semibold text-[var(--color-muted)] md:flex">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home', 'categories.show', 'threads.show') ? 'text-[var(--color-primary)]' : 'hover:text-[var(--color-primary)]' }}">Discussions</a>
                <a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories') ? 'text-[var(--color-primary)]' : 'hover:text-[var(--color-primary)]' }}">Categories</a>
                <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users', 'members.show', 'settings.profile') ? 'text-[var(--color-primary)]' : 'hover:text-[var(--color-primary)]' }}">Members</a>
                <a href="{{ route('moderation.flags') }}" class="{{ request()->routeIs('moderation.flags') ? 'text-[var(--color-primary)]' : 'hover:text-[var(--color-primary)]' }}">Moderation</a>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <form action="{{ route('search') }}" method="GET" class="hidden md:block">
                <label for="global-search" class="sr-only">Search</label>
                <div class="forum-input-wrap w-72">
                    <span class="material-symbols-outlined text-[20px] text-[var(--color-muted)]">search</span>
                    <input
                        id="global-search"
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Search discussions"
                        class="forum-input border-0 bg-transparent px-0 py-0 focus:ring-0"
                    >
                </div>
            </form>

            <a href="{{ route('notifications.index') }}" class="forum-icon-button" aria-label="Notifications">
                <span class="material-symbols-outlined {{ request()->routeIs('notifications.index') ? 'fill-1 text-[var(--color-primary)]' : '' }}">notifications</span>
            </a>

            @php($navUser = auth()->user())
            @if($navUser)
                <form action="{{ route('logout') }}" method="POST" class="hidden md:block">
                    @csrf
                    <button type="submit" class="forum-nav-action">Log out</button>
                </form>
                <a href="{{ route('members.show', $navUser->username) }}" class="forum-avatar">
                    {{ strtoupper(substr($navUser->display_name ?: $navUser->username, 0, 2)) }}
                </a>
            @else
                <a href="{{ route('login') }}" class="forum-btn-secondary hidden md:inline-flex">Log in</a>
                <a href="{{ route('register') }}" class="forum-btn hidden md:inline-flex">Register</a>
            @endif
        </div>
    </div>
</nav>
