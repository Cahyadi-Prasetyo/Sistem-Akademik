@extends('layouts.admin')

@section('title', 'Tambah Prodi')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <h1 class="text-2xl font-bold text-gray-900">Tambah Prodi</h1>
</div>

<div class="px-4 sm:px-6">
    <div class="bg-white shadow sm:rounded-lg p-6 max-w-lg">
        <form action="{{ route('admin.prodi.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="jurusan_id" class="block text-sm font-medium text-gray-700">Jurusan</label>
                <select name="jurusan_id" id="jurusan_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Pilih Jurusan</option>
                    @foreach($jurusanList as $jurusan)
                        <option value="{{ $jurusan->id }}" {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                            {{ $jurusan->fakultas->nama_fakultas }} - {{ $jurusan->nama_jurusan }}
                        </option>
                    @endforeach
                </select>
                @error('jurusan_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nama_prodi" class="block text-sm font-medium text-gray-700">Nama Prodi</label>
                <input type="text" name="nama_prodi" id="nama_prodi" value="{{ old('nama_prodi') }}" required
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('nama_prodi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="kaprodi_user_id" class="block text-sm font-medium text-gray-700">Kaprodi (Opsional)</label>
                <select name="kaprodi_user_id" id="kaprodi_user_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">-- Pilih Kaprodi --</option>
                    @foreach($kaprodiList as $kaprodi)
                        <option value="{{ $kaprodi->id }}" {{ old('kaprodi_user_id') == $kaprodi->id ? 'selected' : '' }}>
                            {{ $kaprodi->nama_user }}
                        </option>
                    @endforeach
                </select>
                @error('kaprodi_user_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.prodi.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
