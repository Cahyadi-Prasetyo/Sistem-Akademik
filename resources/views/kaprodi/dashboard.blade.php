@extends('layouts.kaprodi')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Kaprodi</h1>
    <p class="text-gray-600">Selamat datang, {{ $user->nama_user }}</p>
    @if($prodi)
    <p class="text-sm text-gray-500">Program Studi: {{ $prodi->nama_prodi }} ({{ $prodi->jurusan->nama_jurusan }})</p>
    @else
    <p class="text-sm text-red-500 font-bold">Anda belum ditugaskan ke Prodi manapun. Hubungi Admin.</p>
    @endif
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Stat Cards -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-500">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
               </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Periode EPBM</p>
                <p class="text-xl font-bold text-gray-800">{{ $statistics['total_periode'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-500">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
               </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Periode Aktif</p>
                <p class="text-lg font-bold text-gray-800">{{ $statistics['periode_aktif'] ?? '-' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Pertanyaan (Aktif)</p>
                <p class="text-xl font-bold text-gray-800">{{ $statistics['total_pertanyaan'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-500">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Total Jawaban</p>
                <p class="text-xl font-bold text-gray-800">{{ $statistics['total_jawaban'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
