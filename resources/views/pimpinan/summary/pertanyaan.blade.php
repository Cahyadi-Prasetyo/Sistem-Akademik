@extends('layouts.pimpinan')

@section('title', 'Summary per Pertanyaan')

@section('content')
<div class="mb-6">
    <a href="{{ route('pimpinan.summary') }}" class="text-blue-600 hover:underline">← Kembali ke Daftar Periode</a>
</div>

<div class="px-4 py-5 sm:px-6 bg-white shadow rounded-lg mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Analisis per Pertanyaan</h1>
    <p class="text-gray-600">Periode: {{ $periode->nama_periode }} | Jurusan: {{ $periode->jurusan->nama_jurusan }}</p>
</div>

<div class="space-y-8">
    @foreach($pertanyaanSummary as $item)
    @php 
        $pertanyaan = $item['pertanyaan']; 
        $dosenData = $item['by_dosen'];
    @endphp
    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ $pertanyaan->urutan }}. {{ $pertanyaan->pertanyaan }}
                <span class="ml-2 text-xs font-normal text-gray-500 bg-white px-2 py-0.5 rounded border">
                    {{ ucfirst($pertanyaan->jenis) }}
                </span>
            </h3>
        </div>
        
        @if($pertanyaan->isRating())
        <div class="px-4 py-2">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIDN</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responden</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata Rating</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($dosenData as $nidn => $data)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $data['dosen']->nama_user ?? 'Unknown' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $nidn }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data['total_responses'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <div class="relative w-full max-w-[100px] bg-gray-200 rounded h-2 mr-2">
                                        <div class="absolute h-2 rounded {{ $data['avg_rating'] >= 4 ? 'bg-green-500' : ($data['avg_rating'] >= 3 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                             style="width: {{ ($data['avg_rating'] / 5) * 100 }}%"></div>
                                    </div>
                                    <span class="font-bold">{{ number_format($data['avg_rating'], 2) }}</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="px-4 py-4">
            <p class="text-sm text-gray-500 italic">Pertanyaan isian teks. Silakan lihat detail di menu Summary Periode.</p>
        </div>
        @endif
    </div>
    @endforeach
</div>
@endsection
