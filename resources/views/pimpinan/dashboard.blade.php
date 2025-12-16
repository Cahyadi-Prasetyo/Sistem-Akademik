@extends('layouts.pimpinan')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Pimpinan</h1>
    <p class="text-gray-600">Selamat datang, {{ auth()->user()->nama_user }}</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Total Jurusan</h3>
        <p class="text-3xl font-bold text-gray-800">{{ $statistics['total_jurusan'] }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Total Periode EPBM</h3>
        <p class="text-3xl font-bold text-gray-800">{{ $statistics['total_periode'] }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Total Pertanyaan</h3>
        <p class="text-3xl font-bold text-gray-800">{{ $statistics['total_pertanyaan'] }}</p>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-gray-500 text-sm font-medium">Total Jawaban Masuk</h3>
        <p class="text-3xl font-bold text-gray-800">{{ $statistics['total_jawaban'] }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Statistik Per Jurusan</h3>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Periode EPBM</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($jurusanList as $jurusan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $jurusan->nama_jurusan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $jurusan->epbm_periode_count }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
