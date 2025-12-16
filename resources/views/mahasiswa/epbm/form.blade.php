@extends('layouts.mahasiswa')

@section('title', 'Isi EPBM')

@section('content')
<div class="mb-6">
    <a href="{{ route('mahasiswa.epbm.index') }}" class="text-blue-600 hover:underline">← Kembali ke Daftar Mata Kuliah</a>
</div>

<div class="bg-white shadow rounded-lg p-6 mb-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Evaluasi Proses Belajar Mengajar</h1>
    <div class="grid grid-cols-2 gap-4 text-sm mb-4">
        <div>
            <span class="text-gray-500 block">Mata Kuliah:</span>
            <span class="font-semibold text-lg">{{ $rencanaStudi->jadwal->mataKuliah->nama_mata_kuliah }}</span>
        </div>
        <div>
            <span class="text-gray-500 block">Periode Evaluasi:</span>
            <span class="font-semibold text-lg">{{ $periode->nama_periode }}</span>
        </div>
    </div>
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    Mohon isi evaluasi dengan objektif. Identitas Anda akan dirahasiakan dalam laporan kepada dosen.
                    Anda harus mengisi evaluasi untuk <strong>semua dosen</strong> pengampu mata kuliah ini.
                </p>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('mahasiswa.epbm.submit', $rencanaStudi->id) }}" method="POST">
    @csrf
    
    <div class="space-y-8">
        @foreach($dosenList as $dosenIndex => $dosen)
        <div class="bg-white shadow sm:rounded-lg overflow-hidden border border-gray-200">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Dosen {{ $dosenIndex + 1 }}: {{ $dosen->nama_user }}
                </h3>
                <span class="text-sm text-gray-500">NIDN: {{ $dosen->nidn }}</span>
            </div>
            <div class="p-4 space-y-6">
                @foreach($pertanyaan as $item)
                <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        {{ $item->urutan }}. {{ $item->pertanyaan }}
                    </label>
                    
                    @if($item->isRating())
                        <div class="flex items-center space-x-6 mt-2">
                            @for($i = 1; $i <= 5; $i++)
                            <div class="flex items-center">
                                <input type="radio" 
                                       id="p{{ $item->id }}_d{{ $dosen->nidn }}_{{ $i }}" 
                                       name="jawaban[{{ $item->id }}][{{ $dosen->nidn }}]" 
                                       value="{{ $i }}"
                                       required
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                <label for="p{{ $item->id }}_d{{ $dosen->nidn }}_{{ $i }}" class="ml-2 block text-sm text-gray-700">
                                    {{ $i }}
                                </label>
                            </div>
                            @endfor
                            <span class="text-xs text-gray-400 ml-2">(1=Sangat Buruk ... 5=Sangat Baik)</span>
                        </div>
                    @else
                        <textarea name="jawaban[{{ $item->id }}][{{ $dosen->nidn }}]" 
                                  rows="3" 
                                  required
                                  placeholder="Tuliskan jawaban Anda di sini..."
                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8 flex justify-end">
        <a href="{{ route('mahasiswa.epbm.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">Batal</a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded shadow-lg">
            Kirim Evaluasi
        </button>
    </div>
</form>
@endsection
