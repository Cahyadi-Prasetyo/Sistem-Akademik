@extends('layouts.kaprodi')

@section('title', 'Detail Jawaban EPBM')

@section('content')
<div class="mb-6">
    <a href="{{ route('kaprodi.summary') }}" class="text-blue-600 hover:underline">← Kembali ke Summary</a>
</div>

<div class="px-4 py-5 sm:px-6 bg-white shadow rounded-lg mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Hasil EPBM: {{ $periode->nama_periode }}</h1>
    <p class="text-gray-600">Daftar mahasiswa yang telah mengisi EPBM.</p>
</div>

<div class="px-4 sm:px-6">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Aksi</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jawabanGrouped as $nim => $items)
                @php
                    $mahasiswa = $items->first()->rencanaStudi->mahasiswa;
                @endphp
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $nim }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $mahasiswa->nama ?? 'Unknown' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('kaprodi.jawaban.detail', ['periodeId' => $periode->id, 'nim' => $nim]) }}" class="text-blue-600 hover:text-blue-900">Lihat Jawaban</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">Belum ada mahasiswa yang mengisi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
