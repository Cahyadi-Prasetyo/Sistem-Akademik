@extends('layouts.dosen')

@section('title', 'Absensi Mahasiswa')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <h1 class="text-2xl font-bold text-gray-900">Input Absensi Mahasiswa</h1>
    <p class="text-gray-600">Pilih jadwal mata kuliah untuk menginput absensi.</p>
</div>

<div class="px-4 sm:px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($jadwal as $item)
    <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-300">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 truncate">
                {{ $item->mataKuliah->nama_mata_kuliah }}
            </h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
                <p>Kode: {{ $item->mataKuliah->kode_mata_kuliah }}</p>
                <p>Kelas: {{ $item->kelas }}</p>
                <p class="mt-1 font-semibold text-indigo-600">
                    {{ $item->hari }}, {{ $item->jam }}
                </p>
                <p class="text-gray-400 mt-1">{{ $item->ruangan->nama_ruangan }}</p>
            </div>
            <div class="mt-5">
                <a href="{{ route('dosen.absensi.jadwal', $item->id) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 w-full sm:w-auto text-sm">
                    Kelola Absensi
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
        <p class="text-gray-500">Tidak ada jadwal mata kuliah yang diampu.</p>
    </div>
    @endforelse
</div>
@endsection
