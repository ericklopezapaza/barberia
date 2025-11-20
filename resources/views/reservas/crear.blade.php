<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/css/reservas.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($reserva) ? 'Reprogramar Reserva' : 'Crear Reserva' }}</title>
</head>
<body>
<div class="contenido-principal" id="reserva">
    <form action="{{ isset($reserva) ? route('reservas.reprogramar', $reserva->id) : route('reservas.store') }}" method="POST">
        @csrf
        <h2>{{ isset($reserva) ? 'Reprogramar cita' : 'Reserve su cita según disponibilidad' }}</h2>

        <label>Nombre Completo:</label>
        <input type="text" value="{{ $user->name }} {{ $user->apellido_paterno }} {{ $user->apellido_materno }}" readonly>
        <input type="hidden" name="nombre_completo" value="{{ $user->name }} {{ $user->apellido_paterno }} {{ $user->apellido_materno }}">

        <label>Email:</label>
        <input type="email" value="{{ $user->email }}" readonly>
        <input type="hidden" name="email" value="{{ $user->email }}">

        <label>Celular:</label>
        <input type="text" name="celular" value="{{ old('celular', $reserva->celular ?? '') }}" placeholder="Ingrese su número de celular" required>

        <label>Fecha de Reserva:</label>
        <input type="date" name="fecha_reserva" id="fecha_reserva" value="{{ old('fecha_reserva', $reserva->fecha_reserva ?? '') }}" required>
        
        <label>Servicio:</label>
        <select name="servicio_id" id="servicio_id" required>
            <option value="">-- Seleccione un servicio --</option>
            @foreach($servicios as $servicio)
                <option value="{{ $servicio->id }}" 
                        data-precio="{{ $servicio->precio }}" 
                        data-duracion="{{ $servicio->duracion }}"
                        {{ (isset($reserva) && $reserva->servicio_id == $servicio->id) ? 'selected' : '' }}>
                    {{ $servicio->nombre }} ({{ $servicio->duracion }} min)
                </option>
            @endforeach
        </select>

        <label>Precio:</label>
        <input type="text" name="precio" id="precio" class="form-control" readonly 
            value="{{ $reserva->precio ?? '' }}">

        <label>Hora de Reserva:</label>
        <input type="time" name="hora_reserva" id="hora_reserva" 
            value="{{ old('hora_reserva', isset($reserva) ? \Carbon\Carbon::parse($reserva->hora_reserva)->format('H:i') : '') }}" 
            required>

        <label>Hora de Fin:</label>
        <input type="time" name="hora_fin" id="hora_fin"  readonly required 
            value="{{ old('hora_fin', isset($reserva) ? \Carbon\Carbon::parse($reserva->hora_fin)->format('H:i') : '') }}">

        <label for="barbero_id">Seleccione Barbero</label>
        <select name="barbero_id" id="barbero_id" required>
            <option value="">-- Seleccione un barbero --</option>
            @foreach($barberos as $barbero)
                <option value="{{ $barbero->id }}"
                    {{ (isset($reserva) && $reserva->barbero_id == $barbero->id) ? 'selected' : '' }}>
                    {{ $barbero->nombre }} {{ $barbero->apellido }}
                </option>
            @endforeach
        </select>

        <button type="submit">{{ isset($reserva) ? 'Reprogramar' : 'Reservar' }}</button>
    </form>
</div>

<script>
const servicioSelect = document.getElementById('servicio_id');
const horaInput = document.getElementById('hora_reserva');
const horaFinInput = document.getElementById('hora_fin');
const barberoSelect = document.getElementById('barbero_id');
const fechaInput = document.getElementById('fecha_reserva');
const precioInput = document.getElementById('precio');

// Calcular automáticamente la hora de fin según duración del servicio
function calcularHoraFin() {
    const duracion = parseInt(servicioSelect.selectedOptions[0]?.dataset.duracion || 0);
    if (!horaInput.value || !duracion) {
        horaFinInput.value = '';
        return;
    }
    let [h, m] = horaInput.value.split(':').map(Number);
    m += duracion;
    h += Math.floor(m / 60);
    m %= 60;
    horaFinInput.value = `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}`;
}

// Actualizar precio al cambiar servicio
servicioSelect.addEventListener('change', () => {
    precioInput.value = servicioSelect.selectedOptions[0]?.dataset.precio || '';
    calcularHoraFin();
});

// Validar si la hora está ocupada
function validarHoraOcupada() {
    const barbero_id = barberoSelect.value;
    const fecha = fechaInput.value;
    const hora = horaInput.value;
    if (!barbero_id || !fecha || !hora) return;

    $.ajax({
        url: "{{ route('reservas.horas.ocupadas') }}",
        method: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            barbero_id: barbero_id,
            fecha_reserva: fecha
        },
        success: function(res) {
            let ocupado = false;
            let horariosOcupados = [];
            const duracion = parseInt(servicioSelect.selectedOptions[0]?.dataset.duracion || 0);

            let [h, m] = hora.split(':').map(Number);
            let horaFin = new Date();
            horaFin.setHours(h, m + duracion, 0, 0);

            res.forEach(r => {
                let [h1, m1] = r.hora_reserva.split(':').map(Number);
                let [h2, m2] = r.hora_fin.split(':').map(Number);

                let inicioOcupado = new Date(); inicioOcupado.setHours(h1, m1, 0, 0);
                let finOcupado = new Date(); finOcupado.setHours(h2, m2, 0, 0);

                if ((horaFin > inicioOcupado) && (horaInput.valueAsDate < finOcupado)) {
                    ocupado = true;
                }
                horariosOcupados.push(`${r.hora_reserva} hasta ${r.hora_fin}`);
            });

            if (ocupado) {
                Swal.fire({
                    icon: 'error',
                    title: 'Hora no disponible',
                    html: `La hora seleccionada está ocupada.<br><strong>Horarios ocupados:</strong><br>${horariosOcupados.join('<br>')}`,
                    background: '#f8d7da',
                    color: '#721c24',
                    confirmButtonText: 'Entendido'
                });
                horaInput.value = '';
                horaFinInput.value = '';
            }
        }
    });
}

fechaInput.addEventListener('change', validarHoraOcupada);
horaInput.addEventListener('change', () => { calcularHoraFin(); validarHoraOcupada(); });
barberoSelect.addEventListener('change', validarHoraOcupada);
</script>
</body>
</html>
