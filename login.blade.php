<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login | Travel Genius</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
           background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .blob-tl {
            position: fixed; top: -60px; left: -60px;
            width: 220px; height: 220px;
            background: #10b981;
            border-radius: 50%;
            opacity: 0.35;
            filter: blur(10px);
            pointer-events: none;
        }
        .blob-br {
            position: fixed; bottom: -60px; right: -60px;
            width: 200px; height: 200px;
            background: #059669;
            border-radius: 50%;
            opacity: 0.30;
            filter: blur(12px);
            pointer-events: none;
        }
        .blob-tr {
            position: fixed; top: 30px; right: 60px;
            width: 130px; height: 130px;
            background: #34d399;
            border-radius: 50%;
            opacity: 0.40;
            filter: blur(8px);
            pointer-events: none;
        }

 .login-card{
    width:95vw;
    height:90vh;
    max-width:1600px;
    background:#0b1220;
    display:flex;
    overflow:hidden;
}

 .left-panel{
    width:30%;
    background:#071c2c;
    padding:60px;
    color:white;
}
        .left-panel::before {
            content: '';
            position: absolute;
            top: -40px; left: -40px;
            width: 220px; height: 220px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            opacity: 0.22;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -30px; right: -50px;
            width: 180px; height: 180px;
            background: linear-gradient(135deg, #34d399, #10b981);
            border-radius: 50%;
            opacity: 0.18;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }
        .brand-logo-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }
        .brand-logo-text {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 20px;
            color: #ffffff;
        }

        .left-content {
            position: relative;
            z-index: 1;
        }
        .left-content h2 {
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            font-size: 28px;
            color: #ffffff;
            line-height: 1.25;
            margin-bottom: 12px;
        }
        .left-content p {
            font-size: 13.5px;
            color: #94a3b8;
            line-height: 1.6;
            max-width: 240px;
            font-weight: 500;
        }

        .photo-row {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            position: relative;
            z-index: 1;
            margin-top: 28px;
        }
        .photo-circle {
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
            object-fit: cover;
            flex-shrink: 0;
        }
        .photo-circle.sm { width: 64px; height: 64px; }
        .photo-circle.md { width: 80px; height: 80px; margin-bottom: 8px; }
        .photo-circle.lg { width: 72px; height: 72px; margin-bottom: 4px; }

        .dots {
            display: flex;
            gap: 6px;
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }
        .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #6ee7b7;
        }
        .dot.active {
            background: #059669;
            width: 22px;
            border-radius: 4px;
        }

      .right-panel{
    flex:1;
    position:relative;
    padding:0;
    background:url('https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=1600&q=80&auto=format&fit=crop');
    background-size:cover;
    background-position:center;
}
.form-container{
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    width:520px;
    max-width:90%;
    background:rgba(7,28,44,.85);
    backdrop-filter:blur(15px);
    padding:50px;
    border-radius:25px;
    color:white;
    z-index:2;
}
        .right-panel h1 {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 30px;
            color: #ffffff;
            margin-bottom: 6px;
        }
        .right-panel .subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 32px;
            font-weight: 500;
        }
        .right-panel .subtitle a {
            color: #10b981;
            font-weight: 700;
            text-decoration: none;
        }
        .right-panel .subtitle a:hover { text-decoration: underline; }



        .right-panel::before{
    content:'';
    position:absolute;
    inset:0;
    background:rgba(0,0,0,.35);
}

        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color :#ffffff;
            margin-bottom: 7px;
            letter-spacing: 0.02em;
        }
        .form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            color: #111827;
            background: #f9fafb;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .form-group input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
            background: #fff;
        }
        .form-group input::placeholder { color: #9ca3af; }

        /* Validation error border */
        .form-group input.input-error {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.10) !important;
            background: #fff5f5 !important;
        }

        /* Inline field error message */
        .field-error {
            display: none;
            margin-top: 5px;
            font-size: 12px;
            font-weight: 600;
            color: #ef4444;
        }
        .field-error.visible {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .password-wrap {
            position: relative;
        }
        .password-wrap input { padding-right: 44px; }
        .eye-btn {
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            cursor: pointer;
            color: #9ca3af;
            font-size: 15px;
            padding: 0;
        }
        .eye-btn:hover { color: #10b981; }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .remember-row label {
            display: flex; align-items: center; gap: 7px;
            font-size: 13px; color:#cbd5e1; font-weight: 500;
            cursor: pointer;
        }
        .remember-row input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: #10b981;
            cursor: pointer;
        }
        .forgot-link {
            font-size: 13px; font-weight: 600;
            color: #10b981; text-decoration: none;
        }
        .forgot-link:hover { text-decoration: underline; }

        .btn-signin{
    background:#0ea5e9;
    border-radius:6px;
    text-transform:uppercase;
    letter-spacing:1px;
}
    
        .btn-signin:hover { opacity: 0.92; transform: translateY(-1px); }
        .btn-signin:active { transform: translateY(0); }

        /* Shake animation for failed submit */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%       { transform: translateX(-6px); }
            40%       { transform: translateX(6px); }
            60%       { transform: translateX(-4px); }
            80%       { transform: translateX(4px); }
        }
        .shake { animation: shake 0.4s ease; }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0;
            font-size: 12px;
            color: #9ca3af;
            font-weight: 600;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .social-row {
            display: flex;
            justify-content: center;
            gap: 14px;
        }
        .social-btn {
            width: 44px; height: 44px;
            border-radius: 50%;
            border: 1.5px solid #e5e7eb;
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px;
            cursor: pointer;
            transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
            text-decoration: none;
            color: inherit;
        }
        .social-btn:hover {
            border-color: #10b981;
            box-shadow: 0 2px 10px rgba(16,185,129,0.2);
            transform: translateY(-1px);
        }
        .social-btn .fa-google   { color: #EA4335; }
        .social-btn .fa-twitter  { color: #1DA1F2; }
        .social-btn .fa-facebook { color: #1877F2; }

        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 18px;
        }

        @media (max-width: 680px) {
            .left-panel { display: none; }
            .right-panel { padding: 36px 28px; }
            .login-card { width: 100%; border-radius: 20px; }
        }
    </style>
</head>
<body>
    <div class="blob-tl"></div>
    <div class="blob-br"></div>
    <div class="blob-tr"></div>

    <div class="login-card">

        <!-- LEFT BRANDING PANEL -->
        <div class="left-panel">
            <div class="brand-logo">
                <div class="brand-logo-icon">
                    <i class="fa-solid fa-plane-departure"></i>
                </div>
                <span class="brand-logo-text">Travel Genius</span>
            </div>

            <div class="left-content">
                <h2>Welcome to<br>Travel Genius!</h2>
                <p>Discover breathtaking destinations across Pakistan with AI-powered travel planning made just for you.</p>

                <div class="photo-row">
                    <img class="photo-circle sm"
                        src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=120&h=120&fit=crop&auto=format"
                        alt="Mountain">
                    <img class="photo-circle md"
                        src="https://images.unsplash.com/photo-1540379708242-14a809ab46ac?w=140&h=140&fit=crop&auto=format"
                        alt="Valley">
                    <img class="photo-circle lg"
                        src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=130&h=130&fit=crop&auto=format"
                        alt="Scenery">
                </div>

                <div class="dots">
                    <div class="dot active"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
        </div>

        <!-- RIGHT FORM PANEL -->
      <!-- RIGHT FORM PANEL -->
<div class="right-panel">

    <div class="form-container">

        <h1 style="color:white;">Create your account</h1>

        <p class="subtitle" style="color:#cbd5e1;">
            Already have an account?
            <a href="{{ route('login') }}">Sign In here</a>
        </p>

        <form id="loginForm" action="{{ route('login') }}" method="POST" novalidate>

            @csrf

            <!-- Name Field -->
            <div class="form-group">
                <label for="name">Name</label>
                <input id="name" name="name" type="text"
                    autocomplete="name"
                    value="{{ old('name') }}"
                    placeholder="Your full name"
                    class="{{ $errors->has('name') ? 'input-error' : '' }}">
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email"
                    autocomplete="email"
                    value="{{ old('email') }}"
                    placeholder="traveler@gmail.com"
                    class="{{ $errors->has('email') ? 'input-error' : '' }}">
            </div>

            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember">
                    Remember me
                </label>

                <a href="#" class="forgot-link">
                    Forgot password?
                </a>
            </div>

            <button type="submit" class="btn-signin" id="submitBtn">
                Sign In
            </button>

            <div class="divider">Or sign in with</div>

            <div class="social-row">
                <a href="{{ route('social.redirect', 'google') }}" class="social-btn">
                    <i class="fa-brands fa-google"></i>
                </a>

                <a href="{{ route('social.redirect', 'twitter') }}" class="social-btn">
                    <i class="fa-brands fa-twitter"></i>
                </a>

                <a href="{{ route('social.redirect', 'facebook') }}" class="social-btn">
                    <i class="fa-brands fa-facebook"></i>
                </a>
            </div>

            <p style="text-align:center; margin-top:22px; font-size:13px;">
                Don't have an account?
                <a href="{{ route('register') }}"
                   style="color:#10b981;font-weight:700;text-decoration:none;">
                    Create one →
                </a>
            </p>

        </form>

    </div>

</div>

    <!-- ===================== CLIENT-SIDE VALIDATION ===================== -->
    <script>
        const form      = document.getElementById('loginForm');
        const nameInput = document.getElementById('name');
        const emailInput= document.getElementById('email');
        const nameErr   = document.getElementById('name-error');
        const emailErr  = document.getElementById('email-error');
        const emailErrTxt = document.getElementById('email-error-text');

        function isValidEmail(val) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val.trim());
        }

        function showError(input, errEl) {
            input.classList.add('input-error');
            errEl.classList.add('visible');
        }

        function clearError(input, errEl) {
            input.classList.remove('input-error');
            errEl.classList.remove('visible');
        }

        /* Live clear on input */
        nameInput.addEventListener('input', () => {
            if (nameInput.value.trim()) clearError(nameInput, nameErr);
        });

        emailInput.addEventListener('input', () => {
            if (emailInput.value.trim()) clearError(emailInput, emailErr);
        });

        /* On submit validate */
        form.addEventListener('submit', function(e) {
            let valid = true;

            /* --- Name --- */
            if (!nameInput.value.trim()) {
                showError(nameInput, nameErr);
                valid = false;
            } else {
                clearError(nameInput, nameErr);
            }

            /* --- Email --- */
            if (!emailInput.value.trim()) {
                emailErrTxt.textContent = 'Please enter your email';
                showError(emailInput, emailErr);
                valid = false;
            } else if (!isValidEmail(emailInput.value)) {
                emailErrTxt.textContent = 'Please enter a valid email address';
                showError(emailInput, emailErr);
                valid = false;
            } else {
                clearError(emailInput, emailErr);
            }

            if (!valid) {
                e.preventDefault();
                /* Shake the form card to signal error */
                const card = document.querySelector('.right-panel');
                card.classList.add('shake');
                card.addEventListener('animationend', () => card.classList.remove('shake'), { once: true });

                /* Focus first empty field */
                if (!nameInput.value.trim()) nameInput.focus();
                else emailInput.focus();
            }
        });
    </script>
</body>
</html>