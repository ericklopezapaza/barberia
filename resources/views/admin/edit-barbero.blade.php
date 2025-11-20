<!-- resources/views/admin/edit-barbero.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Barbero</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        h1 {
            font-weight: 600;
            margin-bottom: 30px;
            color: #333;
            font-size: 24px;
            text-align: center;
        }
        label {
            font-weight: 500;
            color: #555;
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
        }
        .btn-secondary {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Editar Barbero</h1>

    <form action="{{ route('admin.barbero.update', $barbero->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="estado">Estado:</label>
            <select name="estado" id="estado" class="form-select" required>
                <option value="Activo" {{ $barbero->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Permiso" {{ $barbero->estado == 'Permiso' ? 'selected' : '' }}>Permiso</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="fecha_inicio_permiso">Fecha inicio permiso:</label>
            <input type="date" name="fecha_inicio_permiso" id="fecha_inicio_permiso" class="form-control"
                   value="{{ $barbero->fecha_inicio_permiso }}">
        </div>

        <div class="mb-3">
            <label for="fecha_fin_permiso">Fecha fin permiso:</label>
            <input type="date" name="fecha_fin_permiso" id="fecha_fin_permiso" class="form-control"
                   value="{{ $barbero->fecha_fin_permiso }}">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('admin.barbero.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
