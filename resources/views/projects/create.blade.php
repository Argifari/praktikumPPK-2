<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Daftar Tugas Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 class="mb-4">Buat Daftar Tugas (Project) Baru</h3>

            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label">Nama Daftar / Project</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Contoh: Project Redesign Website" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi (Opsional)</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Deskripsi singkat..."></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ url('/projects') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Project</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>