<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Order - Kang Pesen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto p-4">
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
            <h1 class="text-2xl font-bold mb-6 text-center">Konfirmasi Pesanan</h1>

            @if($pesanan && $pesanan->items->count() > 0)
                <!-- Order Details -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Detail Pesanan</h2>
                    <div class="space-y-3">
                        @foreach($pesanan->items as $item)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ $item->menu->foto ? asset('storage/' . $item->menu->foto) : 'https://via.placeholder.com/60' }}"
                                         alt="{{ $item->menu->nama }}"
                                         class="w-16 h-16 object-cover rounded-lg">
                                    <div>
                                        <h3 class="font-medium">{{ $item->menu->nama }}</h3>
                                        <p class="text-sm text-gray-600">{{ $item->menu->desc }}</p>
                                        <p class="text-sm text-gray-500">
                                            Rp{{ number_format($item->harga_satuan, 0, ',', '.') }} × {{ $item->jumlah }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total -->
                    <div class="border-t mt-4 pt-4">
                        <div class="flex justify-between text-xl font-bold">
                            <span>Total Pembayaran:</span>
                            <span class="text-red-500">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Table Selection -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Pilih Meja</h2>
                    @if(isset($availableTables) && $availableTables->count() > 0)
                        <form action="/process-confirm-order" method="POST" id="confirmForm">
                            @csrf
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                @foreach($availableTables as $table)
                                    <div class="relative">
                                        <input type="radio" name="meja_id" value="{{ $table->id }}"
                                               id="table_{{ $table->id }}"
                                               class="sr-only peer" required>
                                        <label for="table_{{ $table->id }}"
                                               class="block p-4 text-center border-2 border-gray-200 rounded-lg cursor-pointer
                                                      hover:border-blue-500 peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                            <div class="text-lg font-semibold">{{ $table->nomor_meja }}</div>
                                            <div class="text-sm text-green-600">Tersedia</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Payment Notice -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-yellow-800">Perhatian!</h3>
                                        <p class="text-yellow-700 mt-1">
                                            Setelah mengkonfirmasi pesanan, silakan lakukan pembayaran di kasir.
                                            Anda akan mendapatkan akses ke meja yang dipilih dan dapat memantau status pesanan.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Button -->
                            <div class="text-center">
                                <button type="submit"
                                        class="px-8 py-3 bg-red-500 text-white text-lg font-medium rounded-lg hover:bg-red-600 transition-colors">
                                    Konfirmasi Pesanan
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-8">
                            <div class="text-red-500 text-lg font-semibold mb-2">Maaf, Semua Meja Sedang Terisi</div>
                            <p class="text-gray-600 mb-4">Silakan tunggu hingga ada meja yang tersedia</p>
                            <a href="/" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Kembali ke Menu
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-600 mb-2">Tidak Ada Pesanan</h2>
                    <p class="text-gray-500 mb-6">Belum ada pesanan yang ditemukan. Silakan pesan terlebih dahulu.</p>
                    <a href="/" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Mulai Memesan
                    </a>
                </div>
            @endif
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
