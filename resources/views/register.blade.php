<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Katalóg</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased h-screen flex items-center justify-center">

    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-light tracking-widest uppercase mb-2">Katalóg</h1>
            <p class="text-gray-500 text-sm">Create an account to start curating.</p>
        </div>

        <form id="registerForm" class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" id="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-black focus:border-black sm:text-sm">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" id="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-black focus:border-black sm:text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" required placeholder="Minimum 6 characters" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-black focus:border-black sm:text-sm">
            </div>

            <div id="errorMessage" class="text-red-500 text-sm hidden text-center bg-red-50 p-2 rounded"></div>

            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition">
                Create Account
            </button>
        </form>
        
        <div class="mt-6 text-center text-sm">
            <p class="text-gray-500">Already have an account? <a href="/login" class="font-medium text-black hover:underline">Sign in here</a></p>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault(); 
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorDiv = document.getElementById('errorMessage');
            
            errorDiv.classList.add('hidden');

            // Validasi manual sederhana
            if(password.length < 6) {
                errorDiv.textContent = 'Password must be at least 6 characters.';
                errorDiv.classList.remove('hidden');
                return;
            }

            try {
                // Tembak API Register
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name: name, email: email, password: password })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    // Simpan token JWT yang baru didapat
                    localStorage.setItem('jwt_token', data.authorisation.token);
                    
                    // Langsung arahkan ke Dashboard
                    window.location.href = '/dashboard';
                } else {
                    // Menangkap pesan error dari validasi Laravel (misal: email sudah dipakai)
                    errorDiv.textContent = data.message || 'Email already exists or invalid data.';
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