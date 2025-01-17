<style>
    body {
        display: flex;
        min-height: 100vh;
        flex-direction: column;
    }
    main {
        flex: 1 0 auto;
    }
    h1.title,
    .footer-copyright a {
        font-family: 'Architects Daughter', cursive;
        text-transform: uppercase;
        font-weight: 900;
    }
    /* start welcome animation */
    body.welcome {
        background: #ffffff;
        overflow: hidden;
        -webkit-font-smoothing: antialiased;
    }
    .welcome .splash {
        height: 0px;
        padding: 0px;
        border: 130em solid #019ef7;
        position: fixed;
        left: 50%;
        top: 100%;
        display: block;
        box-sizing: initial;
        overflow: hidden;
        border-radius: 50%;
        transform: translate(-50%, -50%);
        animation: puff 0.5s 1.8s cubic-bezier(0.55, 0.055, 0.675, 0.19) forwards, borderRadius 0.2s 2.3s linear forwards;
    }
    .welcome #welcome {
        background: #ffffff;
        width: 56px;
        height: 56px;
        position: absolute;
        left: 50%;
        top: 50%;
        overflow: hidden;
        opacity: 0;
        transform: translate(-50%, -50%);
        border-radius: 50%;
        animation: init 0.5s 0.2s cubic-bezier(0.55, 0.055, 0.675, 0.19) forwards, moveDown 1s 0.8s cubic-bezier(0.6, -0.28, 0.735, 0.045) forwards, moveUp 1s 1.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards, materia 0.5s 2.7s cubic-bezier(0.86, 0, 0.07, 1) forwards;
    }
    /* moveIn */
    .welcome header,
    .welcome .form {
        opacity: 0;
        animation: moveIn 2s 2.67s ease forwards;
    }
    @keyframes init {
        0% {
            width: 0px;
            height: 0px;
        }
        100% {
            width: 56px;
            height: 56px;
            margin-top: 0px;
            opacity: 1;
        }
    }
    @keyframes puff {
        0% {
            top: 100%;
            height: 0px;
            padding: 0px;
        }
        100% {
            top: 50%;
            height: 100%;
            padding: 0px 100%;
        }
    }
    @keyframes borderRadius {
        0% {
            border-radius: 50%;
        }
        100% {
            border-radius: 0px;
        }
    }
    @keyframes moveDown {
        0% {
            top: 50%;
        }
        50% {
            top: 40%;
        }
        100% {
            top: 100%;
        }
    }
    @keyframes moveUp {
        0% {
            background: #ffffff;
            top: 100%;
        }
        50% {
            top: 40%;
        }
        100% {
            top: 50%;
            background: #019ef7;
        }
    }
    @keyframes materia {
        0% {
            opacity: 0.3;
            height: 0%;
            width: 0%;
        }
        50% {
            opacity: 0.1;
        }
        100% {
            background: #ffffff;
            opacity: 0;
            height: 30%;
            width: 20%;
        }
    }
    @keyframes moveIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }
    @keyframes hide {
        0% {
            opacity: 1;
        }
        100% {
            opacity: 0;
        }
    }
</style>

<body class="welcome">
    <span id="splash-overlay" class="splash"></span>
    <span id="welcome" class="z-depth-4"></span>
    <x-auth-layout>
        <form method="POST" action="{{ theme()->getPageUrl('login') }}" class="form w-100" novalidate="novalidate"
            id="kt_sign_in_form">
            @csrf
            <div class="text-center my-10">
                <h1 class="text-4xl font-bold mb-3">
                    {{ __('Ciptasolutindo') }}
                </h1>
            </div>
            <div class="fv-row mb-10">
                <label class="form-label fs-6 fw-bolder text-dark">{{ __('Username') }}</label>
                <input class="form-control form-control-lg form-control-solid" type="text" name="username"
                    autocomplete="off" value="{{ old('username', 'administrator') }}" required autofocus />
            </div>
            <div class="fv-row mb-10">
                <div class="d-flex flex-stack mb-2">
                    <label class="form-label fw-bolder text-dark fs-6 mb-0">{{ __('Sandi') }}</label>
                    @if (Route::has('password.request'))
                        <a href="{{ theme()->getPageUrl('password.request') }}" class="link-primary fs-6 fw-bolder">
                            {{ __('Lupa Sandi ?') }}
                        </a>
                    @endif
                </div>
                <input class="form-control form-control-lg form-control-solid" type="password" name="password"
                    autocomplete="off" value="{{ old('password') }}" required />
            </div>
            <div class="text-center">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                    @include('partials.general._button-indicator', ['label' => __('Masuk')])
                </button>
            </div>
        </form>
    </x-auth-layout>
    <footer class="page-footer deep-purple darken-3">
    </footer>
    <script>
        $(document).ready(function () {
            setTimeout(() => {
               $('#splash-overlay').hide();
               $('#welcome').hide();
               }, 3000);
        });
    </script>
</body>
