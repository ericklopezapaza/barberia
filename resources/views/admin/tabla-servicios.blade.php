<!-- resources/views/admin/tabla-servicios.blade.php -->
<div id="agregar-servicio" class="mt-4">
    <form action="{{ route('servicios.store') }}" method="POST" class="p-4 bg-white shadow-sm rounded">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del servicio</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="duracion" class="form-label">Duración (minutos)</label>
            <input type="number" name="duracion" id="duracion" class="form-control" required min="1">
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio (Bs)</label>
            <input type="number" name="precio" id="precio" step="0.01" class="form-control" required min="0">
        </div>

        <button type="submit" class="btn btn-warning w-100">Guardar Servicio</button>
    </form>
</div>
