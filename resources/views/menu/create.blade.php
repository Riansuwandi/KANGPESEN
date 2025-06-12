<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu - Kang Pesen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-2xl mx-auto p-4">
        <!-- Header -->
        <header class="mb-6">
            <div class="flex items-center justify-between">
                <div class="text-2xl font-bold text-gray-800">
                    <span class="text-red-500">Kang</span>
                    <span class="text-black">Pesen</span>
                </div>
                <a href="/" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Kembali ke Home
                </a>
            </div>
        </header>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6 text-center">Tambah Menu Baru</h1>

            <form action="/menu" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Nama Menu -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Menu</label>
                    <input type="text" id="nama" name="nama" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan nama menu">
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Menu -->
                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Menu</label>
                    <div class="relative">
                        <input type="file" id="foto" name="foto" accept="image/*" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                    @error('foto')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga -->
                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="number" id="harga" name="harga" min="0" step="500" required
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="0">
                    </div>
                    @error('harga')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="desc" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="desc" name="desc" rows="4" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Masukkan deskripsi menu"></textarea>
                    @error('desc')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Menu -->
                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Kategori Menu</label>
                    <select id="jenis" name="jenis" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Pilih Kategori</option>
                        <option value="food">Food</option>
                        <option value="drink">Drink</option>
                        <option value="snack">Snack</option>
                    </select>
                    @error('jenis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex space-x-4">
                    <button type="submit" 
                            class="flex-1 bg-green-500 text-white py-3 rounded-lg font-medium hover:bg-green-600 transition-colors">
                        Simpan Menu
                    </button>
                    <a href="/" 
                       class="flex-1 bg-gray-500 text-white py-3 rounded-lg font-medium text-center hover:bg-gray-600 transition-colors">
                        Batal
                    </a>
                </div>
            </form>

            @if ($errors->any())
                <div class="mt-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <h4 class="font-medium mb-2">Terdapat kesalahan:</h4>
                    <ul class="text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</body>
</html>