    <x-slot name="header">Dashboard</x-slot>
    <x-slot name="subtitle">Ringkasan dan statistik sistem keuangan</x-slot>

    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Saldo Kas -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Saldo Kas</p>
                        <h3 class="text-3xl font-bold mt-2">Rp {{ number_format($saldoKas, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-green-100 text-xs mt-4">💰 Total saldo tersedia</p>
            </div>

            <!-- Total Mahasiswa -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Mahasiswa</p>
                        <h3 class="text-3xl font-bold mt-2">{{ $totalMahasiswa }}</h3>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-blue-100 text-xs mt-4">👥 {{ $mahasiswaAktif }} mahasiswa aktif</p>
            </div>

            <!-- Total Pemasukan -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Pemasukan</p>
                        <h3 class="text-3xl font-bold mt-2">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                        </svg>
                    </div>
                </div>
                <p class="text-purple-100 text-xs mt-4">📈 Bulan ini: Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</p>
            </div>

            <!-- Total Pengeluaran -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Total Pengeluaran</p>
                        <h3 class="text-3xl font-bold mt-2">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                        </svg>
                    </div>
                </div>
                <p class="text-red-100 text-xs mt-4">📉 Bulan ini: Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Jabatan</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalJabatan }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total User</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $totalUser }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-4">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Transaksi Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $transaksiTerakhir->where('tanggal_bayar', now()->format('Y-m-d'))->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaksi Terakhir -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Pemasukan Terakhir -->
            <div class="bg-white rounded-xl shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">💵 Pemasukan Terakhir</h3>
                </div>
                <div class="p-6">
                    @if($transaksiTerakhir->count() > 0)
                    <div class="space-y-4">
                        @foreach($transaksiTerakhir as $transaksi)
                        <div class="flex items-center justify-between pb-4 border-b last:border-0">
                            <div class="flex items-center gap-3">
                                <div class="bg-green-100 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $transaksi->mahasiswa->nama }}</p>
                                    <p class="text-xs text-gray-500">{{ $transaksi->bulan }} {{ $transaksi->tahun }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">+ Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($transaksi->tanggal_bayar)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-8">Belum ada transaksi</p>
                    @endif
                </div>
            </div>

            <!-- Pengeluaran Terakhir -->
            <div class="bg-white rounded-xl shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">💳 Pengeluaran Terakhir</h3>
                </div>
                <div class="p-6">
                    @if($pengeluaranTerakhir->count() > 0)
                    <div class="space-y-4">
                        @foreach($pengeluaranTerakhir as $pengeluaran)
                        <div class="flex items-center justify-between pb-4 border-b last:border-0">
                            <div class="flex items-center gap-3">
                                <div class="bg-red-100 p-2 rounded-lg">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $pengeluaran->nama_pengeluaran }}</p>
                                    <p class="text-xs text-gray-500">{{ $pengeluaran->keterangan ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-red-600">- Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($pengeluaran->tanggal)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-8">Belum ada pengeluaran</p>
                    @endif
                </div>
            </div>
        </div>
    </div>