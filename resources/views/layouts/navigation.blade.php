<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">
                        💬 Discussion Forum
                    </a>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 transition">
                    Home
                </a>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @guest
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Log in</a>
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-600 hover:text-gray-900">Register</a>
                @else
                    <div class="flex items-center">
                        <span class="text-sm text-gray-700 mr-4">
                            Hi, {{ Auth::user()->name }}
                        </span>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-900">
                                Log out
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>