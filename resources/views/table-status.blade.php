<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Meja - Kang Pesen</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto p-4">
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

        <!-- Late Orders Notification -->
        @if(isset($lateOrders) && $lateOrders->count() > 0)
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="text-lg font-semibold text-red-800 mb-3">⚠️ Pesanan Terlambat!</h3>
                <div class="space-y-2">
                    @foreach($lateOrders as $order)
                        <div class="p-3 bg-red-100 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-semibold">{{ $order->user->username }}</span> -
                                    <span class="text-red-600">{{ $order->meja->nomor_meja ?? 'N/A' }}</span>
                                </div>
                                <div class="text-sm text-red-600">
                                    Pesanan sudah lebih dari 20 menit
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">Status Meja</h1>
                <div class="flex space-x-4 text-sm">
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-green-500 rounded"></div>
                        <span>Kosong</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-red-500 rounded"></div>
                        <span>Digunakan</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                        <span>Perlu Dibersihkan</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @forelse($mejas as $meja)
                    <div class="relative p-4 rounded-lg border-2 transition-colors
                        @if($meja->status == 'kosong') border-green-500 bg-green-50
                        @elseif($meja->status == 'digunakan') border-red-500 bg-red-50
                        @else border-yellow-500 bg-yellow-50
                        @endif">

                        <!-- Status Indicator -->
                        <div class="absolute top-2 right-2 w-3 h-3 rounded-full
                            @if($meja->status == 'kosong') bg-green-500
                            @elseif($meja->status == 'digunakan') bg-red-500
                            @else bg-yellow-500
                            @endif">
                        </div>

                        <!-- Table Number -->
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-800 mb-2">
                                {{ $meja->nomor_meja }}
                            </div>
                            <div class="text-sm font-medium capitalize
                                @if($meja->status == 'kosong') text-green-700
                                @elseif($meja->status == 'digunakan') text-red-700
                                @else text-yellow-700
                                @endif">
                                @if($meja->status == 'kosong')
                                    Kosong
                                @elseif($meja->status == 'digunakan')
                                    Digunakan
                                @else
                                    Perlu Dibersihkan
                                @endif
                            </div>
                        </div>

                        <!-- Action Button -->
                        @if($meja->status == 'perluDiBersihkan')
                            <form action="/update-table-status/{{ $meja->id }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="status" value="kosong">
                                <button type="submit"
                                        class="w-full px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600"
                                        onclick="return confirm('Meja sudah dibersihkan?')">
                                    Selesai Bersih
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Meja</h2>
                        <p class="text-gray-500">Belum ada data meja yang terdaftar dalam sistem.</p>
                    </div>
                @endforelse
            </div>

            <!-- Statistics -->
            @if($mejas->count() > 0)
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $mejas->where('status', 'kosong')->count() }}
                        </div>
                        <div class="text-sm text-green-700">Meja Kosong</div>
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-red-600">
                            {{ $mejas->where('status', 'digunakan')->count() }}
                        </div>
                        <div class="text-sm text-red-700">Meja Digunakan</div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-600">
                            {{ $mejas->where('status', 'perluDiBersihkan')->count() }}
                        </div>
                        <div class="text-sm text-yellow-700">Perlu Dibersihkan</div>
                    </div>
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
        // Auto refresh page every 30 seconds
        setTimeout(() => {
            location.reload();
        }, 30000);

        // Auto hide notifications after 5 seconds
        setTimeout(() => {
            const notifications = document.querySelectorAll('.fixed.bottom-4.right-4');
            notifications.forEach(notification => {
                notification.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Meja</h2>
                        <p class="text-gray-500">Belum ada data meja yang terdaftar dalam sistem.</p>
                    </div>
                @endforelse
            </div>

            <!-- Statistics -->
            @if($mejas->count() > 0)
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $mejas->where('status', 'kosong')->count() }}
                        </div>
                        <div class="text-sm text-green-700">Meja Kosong</div>
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-red-600">
                            {{ $mejas->where('status', 'digunakan')->count() }}
                        </div>
                        <div class="text-sm text-red-700">Meja Digunakan</div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-600">
                            {{ $mejas->where('status', 'perluDiBersihkan')->count() }}
                        </div>
                        <div class="text-sm text-yellow-700">Perlu Dibersihkan</div>
                    </div>
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
        // Auto refresh page every 30 seconds
        setTimeout(() => {
            location.reload();
        }, 30000);

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
