<x-guest-layout>
    <div class="w-full max-w-md bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-700 dark:text-white mb-6">Welcome Back</h2>

        @if (session('status'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-white">Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" required autofocus>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-white">Password</label>
                <input type="password" name="password" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white" required>
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-gray-700 dark:text-white text-sm">Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-blue-500 text-sm hover:underline">Forgot your password?</a>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                Log in
            </button>
        </form>

        <p class="text-center text-gray-600 dark:text-white mt-4">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Sign up</a>
        </p>
    </div>
</x-guest-layout>
