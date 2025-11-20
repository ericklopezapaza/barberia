<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifica tu correo electrónico</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f5f8fa; color: #333; line-height: 1.6; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .container { background-color: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 30px; width: 100%; max-width: 500px; text-align: center; }
        h1 { color: #2c3e50; margin-bottom: 20px; font-size: 28px; }
        p { margin-bottom: 20px; color: #555; font-size: 16px; }
        .alert { background-color: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
        button { background-color: #3490dc; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; transition: background-color 0.3s; width: 100%; margin-bottom: 10px; }
        button:hover { background-color: #2779bd; }
        .logout-btn { background-color: #e3342f; }
        .logout-btn:hover { background-color: #cc1f1a; }
        .email-icon { font-size: 50px; color: #3490dc; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-icon">✉️</div>
        <h1>Verifica tu correo electrónico</h1>
        <p>Antes de continuar, revisa tu correo y haz clic en el enlace de verificación.</p>

        @if (session('message'))
            <div class="alert">{{ session('message') }}</div>
        @endif

        {{-- Formulario para reenviar correo de verificación --}}
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">Reenviar correo de verificación</button>
        </form>

        {{-- Formulario para cerrar sesión --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Cerrar sesión</button>
        </form>
    </div>
</body>
</html>
