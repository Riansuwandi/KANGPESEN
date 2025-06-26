<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan - Kang Pesen</title>
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
                <div class="text-right">
                    <div class="text-sm text-gray-600">Meja Anda:</div>
                    <div class="text-lg font-bold text-blue-600">{{ $pesanan->meja->nomor_meja ?? 'N/A' }}</div>
                </div>
            </div>
        </header>

        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold mb-6 text-center">Status Pesanan</h1>

            <!-- Timer Section -->
            <div class="mb-6 p-4 rounded-lg {{ isset($pesanan->waktu_konfirmasi) && $pesanan->isLate() ? 'bg-red-50 border border-red-200' : 'bg-blue-50 border border-blue-200' }}">
                <div class="text-center">
                    @if(isset($pesanan->waktu_konfirmasi) && $pesanan->isLate())
                        <div class="text-red-600 font-bold text-lg">Pesanan Terlambat!</div>
                        <div class="text-red-500">Waktu tunggu telah melebihi 20 menit</div>
                        @if($pesanan->kompensasi_pudding ?? false)
                            <div class="mt-2 p-3 bg-yellow-100 border border-yellow-300 rounded-lg">
                                <div class="text-yellow-800 font-semibold">🍮 Kompensasi Pudding Gratis!</div>
                                <div class="text-yellow-700 text-sm">Silakan ambil pudding gratis di kasir sebagai kompensasi keterlambatan</div>
                            </div>
                        @endif
                    @elseif(isset($pesanan->waktu_konfirmasi))
                        <div class="text-blue-600 font-bold text-lg">Estimasi Waktu Tunggu</div>
                        <div id="countdown" class="text-2xl font-bold text-blue-800 mt-2">
                            {{ $pesanan->getRemainingTime() ?? 20 }} menit
                        </div>
                        <div class="text-blue-500 text-sm">Jika makanan belum datang dalam waktu ini, Anda akan mendapat pudding gratis!</div>
                    @else
                        <div class="text-gray-600 font-bold text-lg">Pesanan Dikonfirmasi</div>
                        <div class="text-gray-500">Menunggu persiapan makanan...</div>
                    @endif
                </div>
            </div>

            <!-- Order Items with Checkbox -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-4">Pesanan Anda</h2>
                <div class="space-y-3">
                    @foreach($pesanan->items as $item)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $item->menu->foto ? asset('storage/' . $item->menu->foto) : 'https://via.placeholder.com/60' }}"
                                     alt="{{ $item->menu->nama }}"
                                     class="w-16 h-16 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h3 class="font-medium">{{ $item->menu->nama }}</h3>
                                    <p class="text-sm text-gray-600">{{ $item->menu->desc }}</p>
                                    <p class="text-sm text-gray-500">
                                        Rp{{ number_format($item->harga_satuan, 0, ',', '.') }} × {{ $item->jumlah }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <p class="font-semibold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox"
                                           id="item_{{ $item->id }}"
                                           {{ $item->makanan_datang ? 'checked' : '' }}
                                           onchange="updateItemStatus({{ $item->id }}, this.checked)"
                                           class="w-5 h-5 text-green-600 rounded focus:ring-green-500">
                                    <label for="item_{{ $item->id }}" class="text-sm {{ $item->makanan_datang ? 'text-green-600 font-semibold' : 'text-gray-600' }}">
                                        {{ $item->makanan_datang ? 'Sudah Datang' : 'Belum Datang' }}
                                    </label>
                                </div>
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

            <!-- Action Buttons -->
            <div class="flex space-x-4">
                <form action="/complete-order" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full bg-green-500 text-white py-3 rounded-lg font-medium hover:bg-green-600 transition-colors"
                            onclick="return confirm('Apakah Anda yakin ingin menyelesaikan pesanan? Anda akan logout otomatis.')">
                        Complete Order
                    </button>
                </form>
                <a href="/" class="flex-1 bg-gray-500 text-white py-3 rounded-lg font-medium text-center hover:bg-gray-600 transition-colors">
                    Kembali ke Menu
                </a>
            </div>

            <!-- Instructions -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold mb-2">Instruksi:</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Centang makanan yang sudah datang ke meja Anda</li>
                    <li>• Jika makanan terlambat lebih dari 20 menit, Anda akan mendapat pudding gratis</li>
                    <li>• Klik "Complete Order" setelah selesai makan untuk mengakhiri pesanan</li>
                    <li>• Sistem akan logout otomatis setelah pesanan selesai</li>
                </ul>
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
        // Update item status via AJAX
        function updateItemStatus(itemId, isChecked) {
            fetch('/update-item-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    item_id: itemId,
                    makanan_datang: isChecked
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update label text
                    const label = document.querySelector(`label[for="item_${itemId}"]`);
                    if (isChecked) {
                        label.textContent = 'Sudah Datang';
                        label.className = 'text-sm text-green-600 font-semibold';
                    } else {
                        label.textContent = 'Belum Datang';
                        label.className = 'text-sm text-gray-600';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert checkbox if error
                document.getElementById(`item_${itemId}`).checked = !isChecked;
            });
        }

        // Countdown timer
        @if(isset($pesanan->waktu_konfirmasi) && !$pesanan->isLate() && $pesanan->getRemainingTime() > 0)
        let remainingMinutes = {{ $pesanan->getRemainingTime() }};
        const countdownElement = document.getElementById('countdown');

        function updateCountdown() {
            if (remainingMinutes > 0) {
                countdownElement.textContent = remainingMinutes + ' menit';
                remainingMinutes--;
                setTimeout(updateCountdown, 60000); // Update every minute
            } else {
                // Reload page when time is up to show compensation message
                location.reload();
            }
        }

        // Start countdown
        updateCountdown();
        @endif

        // Auto hide notifications after 5 seconds
        setTimeout(() => {
            const notifications = document.querySelectorAll('.fixed.bottom-4.right-4');
            notifications.forEach(notification => {
                notification.style.display = 'none';
            });
        }, 5000);

        // Auto refresh page every 2 minutes to check for late orders
        setInterval(() => {
            @if(!$pesanan->isLate())
            location.reload();
            @endif
        }, 120000); // 2 minutes
    </script>
</body>
</html>
