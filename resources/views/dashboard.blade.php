<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Stylein.yu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0">
                    <h1 class="text-xl font-light tracking-widest uppercase">Stalein.yu <span class="text-xs text-gray-400 font-bold tracking-normal ml-1">ADMIN</span></h1>
                </div>
                <div>
                    <button onclick="logout()" class="text-sm font-medium text-red-500 hover:text-red-700 transition">Logout</button>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-3xl font-extralight tracking-tight">Manage Collections</h2>
                <p class="text-gray-500 mt-1 text-sm">Add, edit, or remove lifestyle products from your store.</p>
            </div>
            <button onclick="openModal()" class="bg-black text-white px-5 py-2.5 rounded-md text-sm font-medium hover:bg-gray-800 transition shadow-sm">
                + Add New Product
            </button>
        </div>

        <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <p id="loading-text" class="text-gray-400 text-sm tracking-widest col-span-full text-center py-10">LOADING INVENTORY...</p>
        </div>
    </main>

    <div id="productModal" class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50 transition-opacity">
        <div class="bg-white rounded-lg p-8 w-full max-w-md shadow-2xl relative">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <h3 id="modalTitle" class="text-2xl font-light mb-6">Add Product</h3>
            
            <form id="productForm" class="space-y-4">
                <input type="hidden" id="productId">
                
                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide">Product Name</label>
                    <input type="text" id="name" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded focus:ring-black focus:border-black sm:text-sm">
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide">Slug (URL)</label>
                    <input type="text" id="slug" required placeholder="sepatu-putih" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded focus:ring-black focus:border-black sm:text-sm">
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide">Price (IDR)</label>
                    <input type="number" id="price" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded focus:ring-black focus:border-black sm:text-sm">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase tracking-wide">Product Image</label>
                    <input type="file" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-black hover:file:bg-gray-100">
                    <p id="image-hint" class="text-xs text-gray-400 mt-1 hidden">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                </div>

                <button type="submit" class="w-full bg-black text-white py-2.5 rounded text-sm font-medium mt-6 hover:bg-gray-800 transition">
                    Save Product
                </button>
            </form>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('jwt_token');
        if (!token) window.location.href = '/login';

        const headers = { 'Authorization': `Bearer ${token}` };

        // 1. READ
        async function fetchProducts() {
            try {
                const response = await fetch('/api/products', { headers: headers });
                const result = await response.json();
                
                const grid = document.getElementById('product-grid');
                grid.innerHTML = ''; 

                if (result.data.length === 0) {
                    grid.innerHTML = '<p class="text-gray-500 text-sm col-span-full">No products found. Create one!</p>';
                    return;
                }

                result.data.forEach(product => {
                    const imageUrl = product.image ? `/storage/${product.image}` : 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                    const price = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(product.price);

                    // Menambahkan tombol EDIT di sini
                    grid.innerHTML += `
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden group">
                            <img src="${imageUrl}" alt="${product.name}" class="w-full h-56 object-cover object-center group-hover:opacity-80 transition">
                            <div class="p-4">
                                <h3 class="font-medium text-gray-900 truncate">${product.name}</h3>
                                <p class="text-gray-500 text-sm mt-1">${price}</p>
                                <div class="mt-4 flex space-x-4 border-t border-gray-100 pt-3">
                                    <button onclick="editProduct(${product.id})" class="text-xs text-blue-500 hover:text-blue-900 font-medium tracking-wide">EDIT</button>
                                    <button onclick="deleteProduct(${product.id})" class="text-xs text-red-500 hover:text-red-900 font-medium tracking-wide">DELETE</button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } catch (error) {
                console.error('Error:', error);
            }
        }

        document.getElementById('productForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const productId = document.getElementById('productId').value;
            const formData = new FormData();
            
            formData.append('name', document.getElementById('name').value);
            formData.append('slug', document.getElementById('slug').value);
            formData.append('price', document.getElementById('price').value);
            
            const imageFile = document.getElementById('image').files[0];
            if (imageFile) formData.append('image', imageFile);

            let url = '/api/products';
            if (productId) {
                url = `/api/products/${productId}`;
                formData.append('_method', 'PUT'); 
            }

            try {
                const response = await fetch(url, {
                    method: 'POST', 
                    headers: headers,
                    body: formData
                });

                const data = await response.json();
                if (data.status === 'success') {
                    closeModal();
                    fetchProducts();
                } else {
                    alert('Gagal menyimpan produk. Periksa kembali isianmu (Pastikan slug unik).');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        // 3. EDIT (Menarik data ke dalam Modal)
        async function editProduct(id) {
            try {
                // Tarik data spesifik produk dari API
                const response = await fetch(`/api/products/${id}`, { headers: headers });
                const result = await response.json();
                const product = result.data;

                // Isi form dengan data yang ditarik
                document.getElementById('productId').value = product.id;
                document.getElementById('name').value = product.name;
                document.getElementById('slug').value = product.slug;
                document.getElementById('price').value = product.price;
                
                // Ubah tampilan modal
                document.getElementById('modalTitle').textContent = 'Edit Product';
                document.getElementById('image-hint').classList.remove('hidden');
                document.getElementById('productModal').classList.remove('hidden');
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // 4. DELETE
        async function deleteProduct(id) {
            if(confirm('Are you sure you want to delete this aesthetic piece?')) {
                try {
                    await fetch(`/api/products/${id}`, {
                        method: 'DELETE',
                        headers: headers
                    });
                    fetchProducts(); 
                } catch (error) {
                    console.error('Error:', error);
                }
            }
        }

        // 5. LOGOUT
        async function logout() {
            try {
                await fetch('/api/auth/logout', { method: 'POST', headers: headers });
                localStorage.removeItem('jwt_token');
                window.location.href = '/login';
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // FUNGSI UI MODAL
        function openModal() {
            document.getElementById('productForm').reset();
            document.getElementById('productId').value = ''; // Kosongkan ID agar jadi mode CREATE
            document.getElementById('modalTitle').textContent = 'Add Product';
            document.getElementById('image-hint').classList.add('hidden');
            document.getElementById('productModal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('productModal').classList.add('hidden');
        }

        // Jalankan saat pertama kali buka halaman
        document.addEventListener('DOMContentLoaded', fetchProducts);
    </script>
</body>
</html>