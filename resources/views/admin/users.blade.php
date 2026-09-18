<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Pengguna</h1>
            <button onclick="keluar()" class="px-3 py-1 bg-gray-200 rounded text-sm">Keluar</button>
        </div>
        <p id="info" class="hidden text-sm mb-3"></p>

        <!-- Tambah user -->
        <form id="tambah" class="grid grid-cols-2 gap-2 mb-6">
            <input id="nama" required placeholder="Nama" class="border rounded px-3 py-2 text-sm">
            <input id="surel" type="email" required placeholder="Email" class="border rounded px-3 py-2 text-sm">
            <input id="sandi" type="password" required minlength="6" placeholder="Password" class="border rounded px-3 py-2 text-sm">
            <select id="peran" class="border rounded px-3 py-2 text-sm">
                <option value="user">user</option>
                <option value="admin">admin</option>
            </select>
            <button class="col-span-2 bg-blue-600 text-white rounded py-2 text-sm">Tambah</button>
        </form>

        <!-- Daftar user -->
        <table class="w-full text-sm">
            <thead><tr class="text-left text-gray-500"><th class="py-1">Nama</th><th>Email</th><th>Peran</th><th></th></tr></thead>
            <tbody id="daftar"></tbody>
        </table>
    </div>
    <script>
        const token = localStorage.getItem('token');
        const saya = JSON.parse(localStorage.getItem('user') ?? '{}');
        if (!token) location.href = '/login';
        const head = {'Accept': 'application/json', 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + token};

        // Info bar
        function pesan(teks, ok) {
            const box = document.getElementById('info');
            box.textContent = teks;
            box.className = 'text-sm mb-3 ' + (ok ? 'text-green-600' : 'text-red-600');
        }

        // Muat daftar
        async function muat() {
            const res = await fetch('/api/admin/users', {headers: head});
            if (res.status === 401 || res.status === 403) { location.href = '/login'; return; }
            const out = await res.json();
            document.getElementById('daftar').innerHTML = out.data.map(u => `
                <tr class="border-t">
                    <td class="py-2">${u.name}</td>
                    <td>${u.email}</td>
                    <td>${u.role}</td>
                    <td class="text-right">${u.id !== saya.id ? `<button onclick="hapus(${u.id})" class="text-red-600">Hapus</button>` : ''}</td>
                </tr>`).join('');
        }

        // Tambah user
        document.getElementById('tambah').addEventListener('submit', async (e) => {
            e.preventDefault();
            const res = await fetch('/api/admin/users', {
                method: 'POST', headers: head,
                body: JSON.stringify({name: nama.value, email: surel.value, password: sandi.value, role: peran.value})
            });
            const data = await res.json();
            if (!res.ok) { pesan(data.message ?? 'Gagal menambah', false); return; }
            e.target.reset();
            pesan('Berhasil ditambah', true);
            muat();
        });

        // Hapus user
        async function hapus(id) {
            if (!confirm('Hapus user ini?')) return;
            const res = await fetch('/api/admin/users/' + id, {method: 'DELETE', headers: head});
            const data = await res.json();
            pesan(data.message, res.ok);
            if (res.ok) muat();
        }

        // Keluar
        async function keluar() {
            await fetch('/api/logout', {method: 'POST', headers: head});
            localStorage.clear();
            location.href = '/login';
        }

        muat();
    </script>
</body>
</html>
