@extends('layouts.dosen')

@section('title', 'Input Absensi')

@section('content')
<div class="mb-6">
    <a href="{{ route('dosen.absensi.index') }}" class="text-indigo-600 hover:underline">← Kembali ke Daftar Jadwal</a>
</div>

<div class="bg-white shadow rounded-lg p-6 mb-6">
    <div class="md:flex md:justify-between md:items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $jadwal->mataKuliah->nama_mata_kuliah }}</h1>
            <p class="text-gray-600">{{ $jadwal->hari }}, {{ $jadwal->jam }} | Ruang {{ $jadwal->ruangan->nama_ruangan }}</p>
        </div>
        <div class="mt-4 md:mt-0">
             <form action="{{ route('dosen.absensi.jadwal', $jadwal->id) }}" method="GET" class="flex items-center">
                <label for="pertemuan_ke" class="mr-2 text-sm font-medium text-gray-700">Pertemuan Ke:</label>
                <select name="pertemuan_ke" id="pertemuan_ke" onchange="this.form.submit()" class="mt-1 block w-20 py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @for($i = 1; $i <= 16; $i++)
                        <option value="{{ $i }}" {{ $pertemuanKe == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </div>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <form action="{{ route('dosen.absensi.save') }}" method="POST">
        @csrf
        <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
        <input type="hidden" name="pertemuan_ke" value="{{ $pertemuanKe }}">
        
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <div class="max-w-xs">
                <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Pertemuan</label>
                <input type="date" name="tanggal" id="tanggal" 
                       value="{{ $absensi->first()->tanggal ?? date('Y-m-d') }}" 
                       required
                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mahasiswa</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Kehadiran</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($mahasiswaList as $index => $krs)
                @php
                    $status = $absensi[$krs->id]->status ?? 'hadir'; // Default hadir
                    $keterangan = $absensi[$krs->id]->keterangan ?? '';
                @endphp
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $krs->nim }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $krs->mahasiswa->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <input type="hidden" name="absensi[{{ $index }}][rencana_studi_id]" value="{{ $krs->id }}">
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="absensi[{{ $index }}][status]" value="hadir" {{ $status == 'hadir' ? 'checked' : '' }} class="form-radio text-green-600">
                                <span class="ml-2 text-sm text-gray-700">Hadir</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="absensi[{{ $index }}][status]" value="sakit" {{ $status == 'sakit' ? 'checked' : '' }} class="form-radio text-yellow-600">
                                <span class="ml-2 text-sm text-gray-700">Sakit</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="absensi[{{ $index }}][status]" value="izin" {{ $status == 'izin' ? 'checked' : '' }} class="form-radio text-blue-600">
                                <span class="ml-2 text-sm text-gray-700">Izin</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="absensi[{{ $index }}][status]" value="alpha" {{ $status == 'alpha' ? 'checked' : '' }} class="form-radio text-red-600">
                                <span class="ml-2 text-sm text-gray-700">Alpha</span>
                            </label>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <input type="text" name="absensi[{{ $index }}][keterangan]" value="{{ $keterangan }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Opsional">
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                        Tidak ada mahasiswa yang terdaftar di jadwal ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Simpan Absensi
            </button>
        </div>
    </form>
</div>
@endsection
