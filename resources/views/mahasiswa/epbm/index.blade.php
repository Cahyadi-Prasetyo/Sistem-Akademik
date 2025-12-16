@extends('layouts.mahasiswa')

@section('title', 'Evaluasi Proses Belajar Mengajar (EPBM)')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <h1 class="text-2xl font-bold text-gray-900">Evaluasi Proses Belajar Mengajar (EPBM)</h1>
    <p class="text-gray-600">Silakan isi EPBM untuk setiap mata kuliah yang telah mendapatkan nilai.</p>
</div>

<div class="px-4 sm:px-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen Pengampu</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Aksi</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($krsWithStatus as $krs)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $krs->jadwal->mataKuliah->nama_mata_kuliah }}</div>
                        <div class="text-sm text-gray-500">{{ $krs->jadwal->mataKuliah->kode_mata_kuliah }} | {{ $krs->jadwal->mataKuliah->sks }} SKS</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ $krs->jadwal->dosen->pluck('nama_user')->join(', ') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                         <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $krs->epbm_status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $krs->epbm_status == 'completed' ? 'Sudah Diisi' : 'Belum Diisi' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @if($krs->epbm_status == 'pending')
                        <a href="{{ route('mahasiswa.epbm.form', $krs->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                            Isi EPBM
                        </a>
                        @else
                        <button disabled class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                            Selesai
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">tidak ada mata kuliah yang perlu dievaluasi saat ini (Hanya mata kuliah dengan nilai yang dapat dievaluasi).</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
