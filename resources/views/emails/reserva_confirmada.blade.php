<style>
    /* Contenedor principal con efecto vidrio */
    .container {
        position: relative;
        z-index: 1;
        max-width: 600px;
        margin: auto;
        padding: 20px;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.15); /* Fondo semitransparente */
        backdrop-filter: blur(10px); /* Difuminado tipo vidrio */
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        color: #fff;
    }

    /* Marca de agua detrás */
    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300px;
        height: 300px;
        background: url('https://drive.google.com/uc?export=view&id=1AqNk8Il2-wvwQgDuZTmjC9DM1W_tlMZ3') no-repeat center center;
        background-size: contain;
        opacity: 0.1; /* Transparencia de la marca de agua */
        transform: translate(-50%, -50%);
        z-index: 0;
        pointer-events: none; /* No interfiere con clics */
    }

    body {
        background: linear-gradient(135deg, #2c3e50, #4ca1af);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: Arial, sans-serif;
    }

    h1, p {
        margin: 10px 0;
    }

    .highlight {
        color: #d4af37;
        font-weight: bold;
    }

    .info {
        background: rgba(255, 255, 255, 0.1);
        padding: 10px;
        border-radius: 10px;
        border-left: 4px solid #d4af37;
        margin-bottom: 15px;
    }

    .btn {
        background: #d4af37;
        color: #000;
        padding: 10px 15px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s ease;
    }

    .btn:hover {
        background: #bfa046;
    }

    .footer {
        font-size: 12px;
        opacity: 0.7;
    }
</style>

<div class="container">
    <!-- Marca de agua detrás -->
    <div class="watermark"></div>

    <h1>¡Hola {{ $reserva->usuario->name }} {{$reserva->usuario->apellido_paterno}} {{$reserva->usuario->apellido_materno}}!</h1>
    <p>Tu cita ha sido <span class="highlight">reservada exitosamente en Estilo Urbano Barbería</span>.</p>

    <div class="info">
        <p><strong>Barbero:</strong> {{ $reserva->barbero->nombre }} {{ $reserva->barbero->apellido }}</p>
        <p><strong>Fecha y hora:</strong> {{ $reserva->fecha_reserva }} a las {{ $reserva->hora_reserva }}</p>
        <p><strong>Servicio:</strong> {{ $reserva->servicio->nombre ?? 'N/A' }}</p>
    </div>

    <div style="text-align:center;">
        <a href="{{ url('/perfil') }}" class="btn">Ver mis reservas</a>
    </div>

    <p class="footer">
        Este es un mensaje automático de <strong>Estilo Urbano Barbería</strong>.<br>
        Por favor, no respondas a este correo.
    </p>
</div>
