<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recordatorio de tu cita</title>
</head>
<body>
    <p>¡Hola {{ $reserva->nombre_completo }}!</p>
    <p>Te recordamos que tienes una cita en Estilo Urbano Barbería:</p>

    <ul>
        <li><strong>Barbero:</strong> {{ $reserva->barbero->nombre }} {{ $reserva->barbero->apellido }}</li>
        <li><strong>Fecha y hora:</strong> {{ $reserva->fecha_reserva }} a las {{ $reserva->hora_reserva }}</li>
        <li><strong>Servicio:</strong> {{ $reserva->servicio->nombre ?? 'N/A' }}</li>
        <li><strong>Precio:</strong> {{ $reserva->precio }}</li>
    </ul>

    <p>Este es un recordatorio automático de Estilo Urbano Barbería.</p>
</body>
</html>
