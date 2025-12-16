@extends('layouts.admin')

@section('title', 'Edit Fakultas')

@section('content')
<div class="px-4 py-5 sm:px-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Fakultas</h1>
</div>

<div class="px-4 sm:px-6">
    <div class="bg-white shadow sm:rounded-lg p-6 max-w-lg">
        <form action="{{ route('admin.fakultas.update', $fakultas->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="nama_fakultas" class="block text-sm font-medium text-gray-700">Nama Fakultas</label>
                <input type="text" name="nama_fakultas" id="nama_fakultas" value="{{ old('nama_fakultas', $fakultas->nama_fakultas) }}" required
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('nama_fakultas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.fakultas.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded mr-2">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
