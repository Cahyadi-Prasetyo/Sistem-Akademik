@extends('layouts.mahasiswa')

@section('title', 'Rekap Absensi')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <h1 class="text-2xl font-bold text-gray-900">Rekap Kehadiran</h1>
    <p class="text-gray-600">Rekapitulasi kehadiran Anda untuk semester ini.</p>
</div>

<div class="px-4 sm:px-6 space-y-6">
    @forelse($krsWithAbsensi as $krs)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ $krs->jadwal->mataKuliah->nama_mata_kuliah }}
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    {{ $krs->jadwal->mataKuliah->kode_mata_kuliah }} | {{ $krs->jadwal->hari }}, {{ $krs->jadwal->jam }}
                </p>
            </div>
            <div class="text-right">
                <div class="text-sm font-medium text-gray-500">Kehadiran</div>
                <div class="text-2xl font-bold {{ $krs->persentase_kehadiran >= 75 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $krs->persentase_kehadiran }}%
                </div>
                <div class="text-xs text-gray-400">{{ $krs->hadir_count }} dari {{ $krs->total_pertemuan }} pertemuan</div>
            </div>
        </div>
        <div class="px-4 py-4 sm:px-6">
            @if($krs->absensi->count() > 0)
            <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                @foreach($krs->absensi as $presensi)
                <div class="relative group">
                    <div class="w-full aspect-w-1 aspect-h-1 rounded-md flex items-center justify-center border {{ $presensi->status == 'hadir' ? 'bg-green-100 border-green-200 text-green-700' : ($presensi->status == 'sakit' ? 'bg-yellow-100 border-yellow-200 text-yellow-700' : ($presensi->status == 'izin' ? 'bg-blue-100 border-blue-200 text-blue-700' : 'bg-red-100 border-red-200 text-red-700')) }}">
                        <span class="font-bold text-sm">P{{ $presensi->pertemuan_ke }}</span>
                    </div>
                    <!-- Tooltip -->
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block bg-gray-900 text-white text-xs rounded py-1 px-2 whitespace-nowrap z-10">
                        {{ \Carbon\Carbon::parse($presensi->tanggal)->format('d M') }}: {{ ucfirst($presensi->status) }}
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-500 italic">Belum ada data absensi untuk mata kuliah ini.</p>
            @endif
        </div>
    </div>
    @empty
    <div class="text-center py-12">
        <p class="text-gray-500">Anda belum mengambil KRS atau KRS belum disetujui.</p>
    </div>
    @endforelse
</div>
@endsection
