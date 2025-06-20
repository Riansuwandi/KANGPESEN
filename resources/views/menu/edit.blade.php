<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu - Kang Pesen</title>
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
            <h1 class="text-2xl font-bold mb-6 text-center">Edit Menu</h1>

            <!-- Current Menu Info -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold mb-3">Menu Saat Ini:</h3>
                <div class="flex items-start space-x-4">
                    <img src="{{ $menu->foto ? asset('storage/' . $menu->foto) : 'https://via.placeholder.com/100' }}"
                         alt="{{ $menu->nama }}"
                         class="w-20 h-20 object-cover rounded-lg">
                    <div>
                        <h4 class="font-medium">{{ $menu->nama }}</h4>
                        <p class="text-sm text-gray-600">{{ $menu->desc }}</p>
                        <p class="text-red-500 font-semibold">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">{{ ucfirst($menu->jenis) }}</span>
                    </div>
                </div>
            </div>

            <form action="/menu/{{ $menu->id }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Menu -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Menu</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $menu->nama) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Masukkan nama menu">
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Menu -->
                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Menu (Opsional)</label>
                    <div class="relative">
                        <input type="file" id="foto" name="foto" accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</p>
                    @error('foto')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga -->
                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="number" id="harga" name="harga" value="{{ old('harga', $menu->harga) }}" min="0" step="500" required
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
                              placeholder="Masukkan deskripsi menu">{{ old('desc', $menu->desc) }}</textarea>
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
                        <option value="food" {{ old('jenis', $menu->jenis) == 'food' ? 'selected' : '' }}>Food</option>
                        <option value="drink" {{ old('jenis', $menu->jenis) == 'drink' ? 'selected' : '' }}>Drink</option>
                        <option value="snack" {{ old('jenis', $menu->jenis) == 'snack' ? 'selected' : '' }}>Snack</option>
                    </select>
                    @error('jenis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex space-x-4">
                    <button type="submit"
                            class="flex-1 bg-blue-500 text-white py-3 rounded-lg font-medium hover:bg-blue-600 transition-colors">
                        Update Menu
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
