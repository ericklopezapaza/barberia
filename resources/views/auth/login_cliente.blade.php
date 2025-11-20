<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y registro</title>
    <link rel="stylesheet" href="{{ asset('assets/css/estilos.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <main>
        <div class="contenedor__todo">

            
            @if (session('status'))
    <div id="status-message" style="background-color: #d4edda; color: #155724; padding: 10px 20px; border-radius: 8px; margin-bottom: 15px;">
        {{ session('status') }}
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                var msg = document.getElementById('status-message');
                if (msg) {
                    msg.style.transition = "opacity 1s ease"; // transición suave
                    msg.style.opacity = "0";
                    setTimeout(function() {
                        msg.style.display = "none";
                    }, 1000); // espera la transición
                }
            }, 10000); // 10 segundos
        });
    </script>
@endif

            
            {{-- Caja trasera de login/registro --}}
            <div class="caja__trasera">
                <div class="caja__trasera-login">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Inicia sesion para entrar a la pagina</p>
                    <button id="btn__iniciar-sesion">Iniciar Sesion</button>
                </div>
                <div class="caja__trasera-register">
                    <h3>¿Aun no tienes una cuenta?</h3>
                    <p>Registrate para que puedas iniciar sesion </p>
                    <button id="btn__registrarse" type="button">Registrarse</button>
                </div>
            </div>

            {{-- Formularios login y registro --}}
            <div class="contenedor__login-register">
                <form action="{{ route('login') }}" method="POST" class="formulario__login">
                    @csrf 
                    <h2>Iniciar Sesion</h2>
                    <input type="email" placeholder="Correo Electronico" name="email" required>
                    <input type="password" placeholder="Contraseña" name="password" required>
                    <div class="remember-container">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Recordarme</label>
                    </div>
                    <!-- Olvidaste tu contraseña -->
                    <p class="forgot-password">
                        <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                    </p>
                    <button>Entrar</button>
                </form>
                
                <form action="{{ route('register') }}" method="POST" class="formulario__register">
                    @csrf
                    <h2>Registrarse</h2>
                    <input type="text" placeholder="Nombre" name="name" value="{{ old('name') }}" required>
                    <input type="text" placeholder="Apellido Paterno" name="apellido_paterno" value="{{ old('apellido_paterno') }}" required>
                    <input type="text" placeholder="Apellido Materno" name="apellido_materno" value="{{ old('apellido_materno') }}" required>
                    <input type="text" placeholder="Correo Electronico" name="email" value="{{ old('email') }}" required>                 
                    <input type="password" placeholder="Contraseña" name="password" required>
                    <input type="password" placeholder="Confirmar Contraseña" name="password_confirmation" required>
                                        <!-- Botón Registrarse -->
                    <button id="btn-registrarse" class="btn-registrarse">Registrarse</button>

                    <!-- Botón Google debajo -->
                    <a href="{{ route('login.google') }}" class="btn-google-icon">
                        <svg width="28" height="28" viewBox="0 0 533.5 544.3">
                            <path fill="#4285F4" d="M533.5 278.4c0-17.3-1.5-34-4.3-50.4H272v95.3h146.9c-6.3 33.9-25.1 62.7-53.5 82v68h86.4c50.7-46.7 80-115.4 80-195.9z"/>
                            <path fill="#34A853" d="M272 544.3c72.9 0 134.1-24.2 178.7-65.5l-86.4-68c-24.1 16.2-55 25.7-92.3 25.7-70.9 0-131-47.8-152.5-112.3h-89.3v70.7c44.4 87.9 134.5 149.4 241.8 149.4z"/>
                            <path fill="#FBBC05" d="M119.2 309.9c-10.7-32-10.7-66.5 0-98.5v-70.7h-89.3C3.7 184.5 0 226.2 0 272s3.7 87.5 29.9 121.3l89.3-70.7z"/>
                            <path fill="#EA4335" d="M272 107.3c38.3 0 72.8 13.2 100.1 39.3l75.1-75.1C406.1 24.2 344.9 0 272 0 164.7 0 74.6 61.5 30.2 149.4l89.3 70.7C141 155.1 201.1 107.3 272 107.3z"/>
                        </svg>
                    </a>

                    <style>
                    /* Botón Google con marco dorado y hover animado */
                    .btn-google-icon {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        width: 60px;
                        height: 60px;
                        background: #fff;
                        border: 3px solid #d4af37;
                        border-radius: 50%;
                        margin-top: 15px; /* debajo del botón de registrarse */
                        transition: all 0.3s ease;
                        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                    }

                    .btn-google-icon:hover {
                        transform: translateY(-3px) scale(1.05);
                        box-shadow: 0 8px 16px rgba(0,0,0,0.3);
                        border-color: #fbbc05; /* un efecto dorado más brillante */
                    }

                    .btn-google-icon svg {
                        width: 28px;
                        height: 28px;
                    }
                    </style>    
                </form>
            </div>

        </div>
    </main>

    {{-- Scripts --}}
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- SweetAlert para login/registro --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: true
            });
        </script>
    @endif

    {{-- Mostrar SweetAlert para errores de validación --}}
    @error('email')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ $message }}',
                    confirmButtonText: 'Aceptar'
                });
                register(); // Mostrar formulario de registro
            });
        </script>
    @enderror

    @error('password')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ $message }}',
                    confirmButtonText: 'Aceptar'
                });
                register(); // Mostrar formulario de registro
            });
        </script>
    @enderror
</body>
</html>
