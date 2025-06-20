<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kang Pesen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto p-4">
        <!-- Header -->
        <header class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-8">
                    <!-- Logo -->
                    <div class="text-2xl font-bold text-gray-800">
                        <span class="text-red-500">Kang</span><br>
                        <span class="text-black">Pesen</span>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex space-x-4">
                        <a href="/?kategori=food" class="px-6 py-2 rounded-full {{ $kategori == 'food' ? 'bg-red-400 text-white' : 'bg-gray-200 text-gray-600' }}">
                            Food
                        </a>
                        <a href="/?kategori=drink" class="px-6 py-2 rounded-full {{ $kategori == 'drink' ? 'bg-red-400 text-white' : 'bg-gray-200 text-gray-600' }}">
                            Drink
                        </a>
                        <a href="/?kategori=snack" class="px-6 py-2 rounded-full {{ $kategori == 'snack' ? 'bg-red-400 text-white' : 'bg-gray-200 text-gray-600' }}">
                            Snack
                        </a>
                    </nav>
                </div>

                <!-- Auth Section -->
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->isStaff())
                            <a href="/table-status" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Status Meja</a>
                            <a href="/menu/create" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Tambah Menu</a>
                        @endif
                        <span class="text-gray-600">Hello, {{ auth()->user()->username }}</span>
                        <form action="/logout" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Logout</button>
                        </form>
                    @else
                        <a href="/login" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">Sign in</a>
                    @endauth
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Menu Section -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg border border-blue-300 p-6">
                    <h2 class="text-xl font-semibold mb-4 capitalize">{{ $kategori }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @forelse($menus as $menu)
                            <div class="border border-gray-200 rounded-lg p-4 relative">
                                <div class="flex items-start space-x-3">
                                    <img src="{{ $menu->foto ? asset('storage/' . $menu->foto) : 'https://via.placeholder.com/80' }}"
                                         alt="{{ $menu->nama }}"
                                         class="w-20 h-20 object-cover rounded-lg">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">{{ $menu->nama }}</h3>
                                        <p class="text-sm text-gray-600 mb-2">{{ $menu->desc }}</p>
                                        <p class="text-red-500 font-semibold">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <!-- Add Button -->
                                @auth
                                    <form action="/add-to-order" method="POST" class="absolute top-2 right-2">
                                        @csrf
                                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                        <button type="submit" class="w-8 h-8 bg-black text-white rounded-full flex items-center justify-center text-lg hover:bg-gray-800">
                                            +
                                        </button>
                                    </form>
                                @endauth

                                <!-- Edit Button (Staff Only) -->
                                @auth
                                    @if(auth()->user()->isStaff())
                                        <a href="/menu/{{ $menu->id }}/edit"
                                           class="absolute bottom-2 left-2 w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm hover:bg-blue-600"
                                           title="Edit Menu">
                                            ✎
                                        </a>
                                    @endif
                                @endauth

                                <!-- Delete Button (Staff Only) -->
                                @auth
                                    @if(auth()->user()->isStaff())
                                        <form action="/menu/{{ $menu->id }}" method="POST" class="absolute bottom-2 right-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-sm hover:bg-red-600"
                                                    onclick="return confirm('Yakin ingin menghapus menu ini?')">
                                                ×
                                            </button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8 text-gray-500">
                                Belum ada menu untuk kategori ini
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Order Section -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">My Order</h2>

                    @if($pesanan && $pesanan->items->count() > 0)
                        <div class="space-y-3">
                            @foreach($pesanan->items as $item)
                                <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <img src="{{ $item->menu->foto ? asset('storage/' . $item->menu->foto) : 'https://via.placeholder.com/50' }}"
                                         alt="{{ $item->menu->nama }}"
                                         class="w-12 h-12 object-cover rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-sm">{{ $item->menu->nama }}</h4>
                                        <p class="text-sm text-gray-600">{{ $item->menu->desc }}</p>
                                        <p class="text-sm text-red-500">Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500">Jumlah: {{ $item->jumlah }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="border-t pt-3">
                                <div class="flex justify-between font-semibold">
                                    <span>Total:</span>
                                    <span>Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-6 space-y-3">
                            @if($pesanan->status == 'confirmed')
                                <form action="/finish-order" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-500 text-white py-3 rounded-lg font-medium hover:bg-green-600">
                                        Finish Order
                                    </button>
                                </form>
                            @endif

                            <a href="/confirm-order" class="block w-full bg-red-500 text-white py-3 rounded-lg font-medium text-center hover:bg-red-600">
                                Confirm Order
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>Belum ada pesanan</p>
                            @guest
                                <p class="text-sm mt-2">
                                    <a href="/login" class="text-blue-500 hover:underline">Login</a> untuk mulai memesan
                                </p>
                            @endguest
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <script>
        // Auto hide notifications after 5 seconds
        setTimeout(() => {
            const notifications = document.querySelectorAll('.fixed.bottom-4.right-4');
            notifications.forEach(notification => {
                notification.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>
