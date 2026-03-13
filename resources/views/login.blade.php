<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lifestyle E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased h-screen flex items-center justify-center">

    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-light tracking-widest uppercase mb-2">stalein.yu</h1>
            <p class="text-gray-500 text-sm">Sign in to manage your collections.</p>
        </div>

        <form id="loginForm" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" id="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-black focus:border-black sm:text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-black focus:border-black sm:text-sm">
            </div>

            <div id="errorMessage" class="text-red-500 text-sm hidden text-center"></div>

            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition">
                Sign In
            </button>
        </form>
        
        <div class="mt-6 text-center text-sm">
            <p class="text-gray-500">Gunakan akun admin@toko.com / password123</p>
        </div>

        <div class="mt-6 text-center text-sm flex flex-col space-y-2">
            <p class="text-gray-500">Or <a href="/register" class="font-medium text-black hover:underline">create a new account</a></p>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault(); // Mencegah form reload halaman
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorDiv = document.getElementById('errorMessage');
            
            errorDiv.classList.add('hidden');

            try {
                // Tembak API Login yang kita buat di backend
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: email, password: password })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    // SIMPAN TOKEN KE BROWSER (localStorage)
                    localStorage.setItem('jwt_token', data.authorisation.token);
                    
                    // Arahkan ke halaman Dashboard Admin
                    window.location.href = '/dashboard';
                } else {
                    errorDiv.textContent = data.message || 'Login failed. Please check your credentials.';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                errorDiv.textContent = 'Terjadi kesalahan pada server.';
                errorDiv.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>