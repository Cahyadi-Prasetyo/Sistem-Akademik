@extends('layouts.pimpinan')

@section('title', 'Summary Periode')

@section('content')
<div class="mb-6">
    <a href="{{ route('pimpinan.summary') }}" class="text-blue-600 hover:underline">← Kembali ke Daftar Periode</a>
</div>

<div class="px-4 py-5 sm:px-6 bg-white shadow rounded-lg mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Summary: {{ $periode->nama_periode }}</h1>
    <p class="text-gray-600">Jurusan: {{ $periode->jurusan->nama_jurusan }}</p>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Responden</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Hasil</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($summaryData as $item)
            @php $p = $item['pertanyaan']; @endphp
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->urutan }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    {{ $p->pertanyaan }}
                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $p->jenis == 'rating' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($p->jenis) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    {{ $item['total_responses'] }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    @if($p->isRating())
                        <div class="flex items-center">
                            <span class="text-lg font-bold mr-2 {{ $item['avg_rating'] >= 4 ? 'text-green-600' : ($item['avg_rating'] >= 3 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ number_format($item['avg_rating'], 2) }}
                            </span>
                            <span class="text-xs text-gray-500">/ 5.00</span>
                        </div>
                    @else
                        <span class="text-xs text-gray-500 italic">Lihat detail untuk jawaban teks</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
