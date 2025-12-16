@extends('layouts.kaprodi')

@section('title', 'Summary EPBM')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <h1 class="text-2xl font-bold text-gray-900">Summary Hasil EPBM</h1>
    <p class="text-gray-600">Pilih periode untuk melihat hasil evaluasi.</p>
</div>

<div class="px-4 sm:px-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pertanyaan</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Aksi</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($periodeList as $periode)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $periode->nama_periode }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $periode->tanggal_mulai->format('d M Y') }} - {{ $periode->tanggal_selesai->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $periode->pertanyaan_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('kaprodi.jawaban.index', $periode->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Lihat Detail &raquo;</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">Tidak ada data periode.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
