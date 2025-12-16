@extends('layouts.admin')

@section('title', 'Edit Jurusan')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Jurusan</h1>
</div>

<div class="px-4 sm:px-6">
    <div class="bg-white shadow sm:rounded-lg p-6 max-w-lg">
        <form action="{{ route('admin.jurusan.update', $jurusan->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="fakultas_id" class="block text-sm font-medium text-gray-700">Fakultas</label>
                <select name="fakultas_id" id="fakultas_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">Pilih Fakultas</option>
                    @foreach($fakultasList as $fakultas)
                        <option value="{{ $fakultas->id }}" {{ old('fakultas_id', $jurusan->fakultas_id) == $fakultas->id ? 'selected' : '' }}>
                            {{ $fakultas->nama_fakultas }}
                        </option>
                    @endforeach
                </select>
                @error('fakultas_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nama_jurusan" class="block text-sm font-medium text-gray-700">Nama Jurusan</label>
                <input type="text" name="nama_jurusan" id="nama_jurusan" value="{{ old('nama_jurusan', $jurusan->nama_jurusan) }}" required
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('nama_jurusan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.jurusan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
