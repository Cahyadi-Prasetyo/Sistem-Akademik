@extends('layouts.kaprodi')

@section('title', 'Detail Jawaban Mahasiswa')

@section('content')
<div class="mb-6">
    <a href="{{ route('kaprodi.jawaban.index', $periode->id) }}" class="text-blue-600 hover:underline">← Kembali ke Daftar Mahasiswa</a>
</div>

<div class="bg-white shadow rounded-lg p-6 mb-6">
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Detail Jawaban Mahasiswa</h1>
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
            <span class="text-gray-500">Periode:</span>
            <span class="font-semibold">{{ $periode->nama_periode }}</span>
        </div>
        <div>
            <span class="text-gray-500">NIM:</span>
            <span class="font-semibold">{{ $nim }}</span>
        </div>
    </div>
</div>

<div class="space-y-6">
    @php
        $groupedByDosen = $jawaban->groupBy('nidn');
    @endphp

    @foreach($groupedByDosen as $nidn => $answers)
    @php
        $dosen = $answers->first()->dosen;
    @endphp
    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Dosen: {{ $dosen->nama_user ?? $nidn }}
            </h3>
        </div>
        <div class="p-4">
            <ul class="space-y-4">
                @foreach($answers as $ans)
                <li class="border-b border-gray-100 pb-2 last:border-0 last:pb-0">
                    <p class="text-sm font-medium text-gray-700 mb-1">{{ $ans->pertanyaan->urutan }}. {{ $ans->pertanyaan->pertanyaan }}</p>
                    @if($ans->pertanyaan->isRating())
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 mr-2">Rating:</span>
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $ans->nilai_rating)
                                        ★
                                    @else
                                        <span class="text-gray-300">★</span>
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-2 text-sm font-bold text-gray-800">{{ $ans->nilai_rating }}/5</span>
                        </div>
                    @else
                        <div class="bg-gray-50 p-2 rounded text-sm text-gray-800 italic">
                            "{{ $ans->jawaban_text }}"
                        </div>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endforeach
</div>
@endsection
