<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="{{ asset('assets/css/email.css') }}">
</head>
<body>
    <div class="email-container"> {{-- Contenedor para estilos específicos --}}
        <h2>Recuperar Contraseña</h2>
            @if(session('status'))
                <p style="color:green">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <label for="email">Correo electrónico:</label>
                <input type="email" name="email" id="email" required>
                <button type="submit">Enviar enlace de recuperación</button>
            </form>

            @error('email')
                <p style="color:red">{{ $message }}</p>
            @enderror
            @if(session('status'))
            <script>
                alert("¡Correo enviado! Por favor revisa tu bandeja de entrada y cierra esta ventana.");
            </script>
            @endif
    </div>
</body>
</html>
