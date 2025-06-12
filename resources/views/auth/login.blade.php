<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kang Pesen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-900">
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center relative" 
         style="background-image: url('https://images.unsplash.com/photo-1546069901-ba9599a7e63c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2000&q=80');">
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        
        <!-- Login Form -->
        <div class="relative z-10 bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md mx-4">
            <!-- Tab Navigation -->
            <div class="flex rounded-lg bg-gray-100 p-1 mb-6">
                <button id="loginTab" class="flex-1 text-center py-2 px-4 rounded-md text-sm font-medium transition-colors bg-gray-800 text-white">
                    Log In
                </button>
                <button id="signupTab" class="flex-1 text-center py-2 px-4 rounded-md text-sm font-medium transition-colors text-gray-600 hover:text-gray-800">
                    Sign Up
                </button>
            </div>

            <!-- Login Form -->
            <div id="loginForm">
                <form action="/login" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <input type="text" name="username" placeholder="Enter email or username" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               required>
                    </div>
                    
                    <div class="relative">
                        <input type="password" name="password" placeholder="Password" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               required>
                    </div>
                    
                    <div class="text-right">
                        <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="w-full bg-gray-800 text-white py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                        Log In
                    </button>
                </form>
            </div>

            <!-- Register Form -->
            <div id="registerForm" class="hidden">
                <form action="/register" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <input type="text" name="username" placeholder="Enter email or username" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               required>
                    </div>
                    
                    <div>
                        <input type="password" name="password" placeholder="Password" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               required>
                    </div>
                    
                    <div>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               required>
                    </div>

                    <div>
                        <select name="role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Pilih Role</option>
                            <option value="tamu">Tamu</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-gray-800 text-white py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                        Sign Up
                    </button>
                </form>
            </div>

            @if ($errors->any())
                <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <script>
        const loginTab = document.getElementById('loginTab');
        const signupTab = document.getElementById('signupTab');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');

        loginTab.addEventListener('click', () => {
            loginTab.classList.add('bg-gray-800', 'text-white');
            loginTab.classList.remove('text-gray-600');
            signupTab.classList.remove('bg-gray-800', 'text-white');
            signupTab.classList.add('text-gray-600');
            
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
        });

        signupTab.addEventListener('click', () => {
            signupTab.classList.add('bg-gray-800', 'text-white');
            signupTab.classList.remove('text-gray-600');
            loginTab.classList.remove('bg-gray-800', 'text-white');
            loginTab.classList.add('text-gray-600');
            
            registerForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
        });
    </script>
</body>
</html>