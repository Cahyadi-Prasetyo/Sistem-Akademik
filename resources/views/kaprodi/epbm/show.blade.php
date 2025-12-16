@extends('layouts.kaprodi')

@section('title', 'Kelola Pertanyaan')

@section('content')
<div class="mb-6">
    <a href="{{ route('kaprodi.epbm.index') }}" class="text-blue-600 hover:underline">← Kembali ke Daftar Periode</a>
</div>

<div class="px-4 py-5 sm:px-6 flex justify-between items-center bg-white shadow rounded-lg mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Kelola Pertanyaan: {{ $periode->nama_periode }}</h1>
        <p class="text-sm text-gray-500">{{ $periode->tanggal_mulai->format('d M Y') }} s/d {{ $periode->tanggal_selesai->format('d M Y') }}</p>
    </div>
    
    <!-- Button Trigger Modal -->
    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Tambah Pertanyaan
    </button>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">Urutan</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Jenis</th>
                <th scope="col" class="relative px-6 py-3 w-32">
                    <span class="sr-only">Aksi</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($periode->pertanyaan as $item)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->urutan }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->pertanyaan }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->jenis == 'rating' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($item->jenis) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                     <!-- Edit Button -->
                     <button onclick="openEditModal({{ $item->id }}, '{{ $item->pertanyaan }}', '{{ $item->jenis }}')" class="text-indigo-600 hover:text-indigo-900 mr-3">
                        Edit
                    </button>
                    
                    <form action="{{ route('kaprodi.pertanyaan.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">Belum ada pertanyaan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah -->
<div id="modalTambah" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('modalTambah').classList.add('hidden')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('kaprodi.pertanyaan.store', $periode->id) }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Tambah Pertanyaan</h3>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Pertanyaan</label>
                        <textarea name="pertanyaan" rows="3" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
                    </div>
                    <div class="mt-4">
                         <label class="block text-sm font-medium text-gray-700">Jenis</label>
                         <select name="jenis" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                             <option value="rating">Rating (1-5)</option>
                             <option value="text">Isian Teks (Esai)</option>
                         </select>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                    <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('modalEdit').classList.add('hidden')"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Edit Pertanyaan</h3>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Pertanyaan</label>
                        <textarea name="pertanyaan" id="edit_pertanyaan" rows="3" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
                    </div>
                    <div class="mt-4">
                         <label class="block text-sm font-medium text-gray-700">Jenis</label>
                         <select name="jenis" id="edit_jenis" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                             <option value="rating">Rating (1-5)</option>
                             <option value="text">Isian Teks (Esai)</option>
                         </select>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan Perubahan</button>
                    <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(id, pertanyaan, jenis) {
        document.getElementById('formEdit').action = "/kaprodi/pertanyaan/" + id;
        document.getElementById('edit_pertanyaan').value = pertanyaan;
        document.getElementById('edit_jenis').value = jenis;
        document.getElementById('modalEdit').classList.remove('hidden');
    }
</script>
@endsection
