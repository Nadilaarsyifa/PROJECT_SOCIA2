<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - PGE SOCIA</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --pge-green: #0f6b45;
            --pge-green-dark: #0b5636;
        }

        body {
            min-height: 100vh;
            background:
                linear-gradient(
                    rgba(15, 107, 69, 0.75),
                    rgba(11, 86, 54, 0.85)
                ),
                url('{{ asset("images/backgroud.jpg") }}');

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .login-wrapper {
            min-height: calc(100vh - 55px);
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            border-radius: 1rem;
        }

        .login-card .card-body {
            padding: 2.75rem 2.5rem;
        }

        .brand-logo {
            max-height: 70px;
            max-width: 100%;
            width: auto;
            object-fit: contain;
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.9rem;
            letter-spacing: 0.5px;
            color: #1c1c1c;
        }

        .brand-subtitle {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 600;
            color: #212529;
        }

        .form-control {
            padding: 0.7rem 0.9rem;
            border-radius: 0.5rem;
            border-color: #dcdfe2;
        }

        .form-control:focus {
            border-color: var(--pge-green);
            box-shadow: 0 0 0 0.2rem rgba(15, 107, 69, 0.15);
        }

        .btn-pge {
            background-color: var(--pge-green);
            border-color: var(--pge-green);
            color: #fff;
            font-weight: 600;
            padding: 0.7rem 0.9rem;
            border-radius: 0.5rem;
        }

        .btn-pge:hover,
        .btn-pge:focus {
            background-color: var(--pge-green-dark);
            border-color: var(--pge-green-dark);
            color: #fff;
        }

        .login-footer {
            color: #fff;
            font-size: 0.8rem;
        }

        @media (max-width: 480px) {
            .login-card .card-body {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>

<div class="container login-wrapper d-flex align-items-center justify-content-center py-4">
    <div class="login-card card shadow border-0">
        <div class="card-body">

            <div class="text-center mb-4">
                <img
                    src="{{ asset('images/Logo PGE.jpeg') }}"
                    alt="Logo PGE"
                    class="brand-logo mb-3"
                >

                <h1 class="brand-title mb-1">PGE-SOCIA</h1>
                <p class="brand-subtitle mb-0">
                    SOC Intelligent Assistant
                </p>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label">
                        Username
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Masukkan username"
                        required
                        autofocus
                        autocomplete="username"
                    >
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-pge">
                        Sign in
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<footer class="text-center pb-3">
    <small class="login-footer">
        PT Pema Global Energi &mdash; Security Operations Center
    </small>
</footer>

</body>
</html>