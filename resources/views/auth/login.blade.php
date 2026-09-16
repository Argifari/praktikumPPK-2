<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-sm mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-xl font-bold mb-4">Masuk</h1>
        <p id="error" class="hidden text-sm text-red-600 mb-3"></p>
        <form id="form" class="space-y-3">
            <input id="email" type="email" required placeholder="Email" class="w-full border rounded px-3 py-2 text-sm">
            <input id="password" type="password" required placeholder="Password" class="w-full border rounded px-3 py-2 text-sm">
            <button class="w-full bg-blue-600 text-white rounded py-2 text-sm">Masuk</button>
        </form>
    </div>
    <script>
        // Kirim login
        document.getElementById('form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const box = document.getElementById('error');
            box.classList.add('hidden');
            const res = await fetch('/api/login', {
                method: 'POST',
                headers: {'Accept': 'application/json', 'Content-Type': 'application/json'},
                body: JSON.stringify({email: email.value, password: password.value})
            });
            const data = await res.json();
            if (!res.ok) {
                box.textContent = data.message ?? 'Gagal masuk';
                box.classList.remove('hidden');
                return;
            }
            localStorage.setItem('token', data.access_token);
            localStorage.setItem('user', JSON.stringify(data.user));
            location.href = '/admin/users';
        });
    </script>
</body>
</html>
