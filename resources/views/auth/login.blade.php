<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPAKU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 to-indigo-800 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">SIPAKU</h1>
                <p class="text-gray-500 mt-2">Sistem Informasi Perkuliahan Akademik</p>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.authenticate') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">NIM / NIDN / Username</label>
                    <input 
                        type="text" 
                        id="kode" 
                        name="kode" 
                        value="{{ old('kode') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Masukkan NIM/NIDN/Username"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <div class="mb-6 flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-[1.02]"
                >
                    Masuk
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-500">
                    <strong>Default Login:</strong><br>
                    Admin: admin / admin<br>
                    Dosen: [NIDN] / [NIDN]<br>
                    Mahasiswa: [NIM] / [NIM]
                </p>
            </div>
        </div>
    </div>
</body>
</html>
