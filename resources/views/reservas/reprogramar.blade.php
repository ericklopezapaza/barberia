<!-- Formulario inline para reprogramar -->
<form action="{{ route('reservas.reprogramar', $reserva->id) }}" method="POST" class="form-reprogramar-inline">
    @csrf
    <div style="
        display: flex; 
        flex-wrap: wrap; 
        gap: 10px; 
        align-items: center; 
        margin-top: 10px;
    ">
        <div style="flex: 1 1 150px; min-width: 120px;">
            <label for="fecha-{{ $reserva->id }}">Fecha:</label>
            <input type="date" id="fecha-{{ $reserva->id }}" name="fecha"
                   value="{{ old('fecha', $reserva->fecha_reserva) }}" 
                   required
                   min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                   style="width:100%; padding:6px 10px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <div style="flex: 1 1 100px; min-width: 100px;">
            <label for="hora-{{ $reserva->id }}">Hora:</label>
            <input type="time" id="hora-{{ $reserva->id }}" name="hora"
                   value="{{ old('hora', $reserva->hora_reserva) }}" required
                   style="width:100%; padding:6px 10px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <div style="flex: 1 1 120px; min-width: 100px;">
            <button type="submit"
                    style="width:100%; padding:8px 14px; border:none; border-radius:10px;
                           background: linear-gradient(90deg, #f5d76e, #d4af37);
                           color:#fff; cursor:pointer; font-weight:600; box-shadow: 0 4px 12px rgba(212,175,55,0.4);">
                Reprogramar
            </button>
        </div>

        <!-- Nuevo botón "Cancelar reprogramación" -->
        <div style="flex: 1 1 120px; min-width: 100px;">
            <button type="button"
                onclick="document.getElementById('reprogramar-{{ $reserva->id }}').style.display='none';"
                style="width:100%; padding:8px 14px; border:none; border-radius:10px;
                    background: linear-gradient(90deg, #f5d76e, #d4af37);
                    color:#fff; cursor:pointer; font-weight:600; box-shadow: 0 4px 12px rgba(212,175,55,0.4);">
                Cerrar
            </button>
        </div>
    </div>
</form>

