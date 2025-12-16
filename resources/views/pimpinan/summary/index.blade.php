@extends('layouts.pimpinan')

@section('title', 'Summary EPBM')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <h1 class="text-2xl font-bold text-gray-900">Summary EPBM per Jurusan</h1>
    <p class="text-gray-600">Pilih periode EPBM dari masing-masing jurusan untuk melihat laporan.</p>
</div>

<div class="space-y-6 px-4 sm:px-6">
    @foreach($jurusanList as $jurusan)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Jurusan: {{ $jurusan->nama_jurusan }}
                <span class="text-sm text-gray-500 font-normal">({{ $jurusan->fakultas->nama_fakultas }})</span>
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($jurusan->epbmPeriode as $periode)
                <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-indigo-600 truncate">{{ $periode->nama_periode }}</h4>
                            <div class="mt-2 flex">
                                <div class="flex items-center text-sm text-gray-500 mr-6">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $periode->tanggal_mulai->format('d M Y') }} - {{ $periode->tanggal_selesai->format('d M Y') }}
                                </div>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $periode->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $periode->is_active ? 'Aktif' : 'Selesai' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex items-center space-x-2">
                            <a href="{{ route('pimpinan.summary.periode', $periode->id) }}" class="font-medium text-blue-600 hover:text-blue-500">Lihat Summary &rarr;</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('pimpinan.summary.pertanyaan', $periode->id) }}" class="font-medium text-indigo-600 hover:text-indigo-500">Detail per Pertanyaan &rarr;</a>
                        </div>
                    </div>
                </li>
                @empty
                <li class="px-4 py-4 sm:px-6 text-center text-sm text-gray-500">
                    Belum ada data periode EPBM.
                </li>
                @endforelse
            </ul>
        </div>
    </div>
    @endforeach
</div>
@endsection
