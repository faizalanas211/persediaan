<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Manajemen Persediaan ATK</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #7c3aed;        /* ungu utama */
            --primary-dark: #5b21b6;   /* ungu gelap */
            --secondary: #a855f7;      /* ungu muda */
            --accent: #c084fc;         /* aksen */

            --danger: #ef4444;

            --dark-bg: #1e1b4b;
            --dark-surface: #1f1b3a;
            --dark-surface-light: #2e2660;
            --dark-text: #f5f3ff;
            --dark-text-light: #d8b4fe;
            --dark-border: #4c1d95;

            --success: #22c55e;
            --warning: #f59e0b;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #2e1065 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--dark-text);
            position: relative;
        }

        /* Simple Background */
        .simple-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 80%, rgba(168, 85, 247, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(124, 58, 237, 0.06) 0%, transparent 50%);
            z-index: 0;
        }

        /* Main Container */
        .register-container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            background: var(--dark-surface);
            border-radius: 24px;
            overflow: hidden;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            position: relative;
            z-index: 1;
            min-height: 700px;
            border: 1px solid var(--dark-border);
        }

        /* Left Side - Minimal Brand */
        .brand-section {
            flex: 1;
            background: linear-gradient(135deg, var(--dark-surface) 0%, var(--dark-surface-light) 100%);
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .brand-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        }

        .brand-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        /* Simple Logo */
        .simple-logo {
            margin-bottom: 40px;
        }

        .logo-circle {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            box-shadow:
                0 10px 20px rgba(124, 58, 237, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .system-name {
            font-size: 24px;
            font-weight: 700;
            color: white;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .system-tagline {
            color: var(--dark-text-light);
            font-size: 14px;
            font-weight: 300;
            opacity: 0.8;
        }

        /* System Info */
        .system-info {
            margin: 40px 0;
            padding: 30px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .system-title {
            font-size: 20px;
            font-weight: 600;
            color: white;
            margin-bottom: 15px;
            text-align: left;
        }

        .system-description {
            color: var(--dark-text-light);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 25px;
            text-align: left;
            opacity: 0.9;
        }

        /* Simple Features List */
        .features-list {
            list-style: none;
            text-align: left;
        }

        .features-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            color: var(--dark-text-light);
            font-size: 14px;
        }

        .features-list i {
            color: var(--primary);
            font-size: 16px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(124, 58, 237, 0.15);
            border-radius: 6px;
        }

        /* Simple Footer */
        .simple-footer {
            margin-top: 40px;
            color: var(--dark-text-light);
            font-size: 12px;
            opacity: 0.6;
            text-align: center;
        }

        /* Right Side - Register Form */
        .register-section {
            flex: 1.2;
            padding: 50px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--dark-surface);
            overflow-y: auto;
        }

        .register-form-container {
            width: 100%;
            max-width: 450px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .form-header h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 12px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-header p {
            color: var(--dark-text-light);
            font-size: 15px;
            font-weight: 300;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: var(--dark-text-light);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dark-text-light);
            font-size: 18px;
            pointer-events: none;
            z-index: 1;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: var(--dark-surface-light);
            border: 2px solid var(--dark-border);
            border-radius: 12px;
            font-size: 15px;
            color: var(--dark-text);
            transition: all 0.3s ease;
            font-weight: 400;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            background: var(--dark-surface);
            box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.25);
        }

        .form-input::placeholder {
            color: var(--dark-text-light);
            opacity: 0.5;
        }

        /* Error States */
        .form-input.is-invalid {
            border-color: var(--danger);
            background: rgba(239, 68, 68, 0.05);
        }

        .form-input.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 13px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .invalid-feedback i {
            font-size: 14px;
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--dark-text-light);
            cursor: pointer;
            padding: 8px;
            font-size: 18px;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .password-toggle:hover {
            color: var(--primary);
            background: rgba(124, 58, 237, 0.15);
        }

        /* Password Strength */
        .password-strength {
            margin-top: 8px;
        }

        .strength-bar {
            height: 4px;
            background: var(--dark-border);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 4px;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: var(--danger);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-text {
            font-size: 12px;
            color: var(--dark-text-light);
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 30px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(124, 58, 237, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Login Link */
        .login-link {
            text-align: center;
            color: var(--dark-text-light);
            font-size: 14px;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid var(--dark-border);
        }

        .login-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            margin-left: 8px;
            padding: 5px 10px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 30px;
            right: 30px;
            padding: 16px 24px;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            z-index: 1000;
            animation: slideIn 0.4s ease;
            box-shadow:
                0 15px 35px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 15px;
            max-width: 380px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .notification-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.9) 0%, rgba(5, 150, 105, 0.9) 100%);
        }

        .notification-error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.9) 0%, rgba(220, 38, 38, 0.9) 100%);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Mobile Responsive */
        @media (max-width: 992px) {
            .register-container {
                flex-direction: column;
                max-width: 500px;
                min-height: auto;
            }

            .brand-section {
                padding: 40px 30px;
            }

            .register-section {
                padding: 40px 30px;
            }

            .form-header h2 {
                font-size: 28px;
            }

            .system-title {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .register-container {
                border-radius: 16px;
            }

            .brand-section,
            .register-section {
                padding: 30px 20px;
            }

            .logo-circle {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }

            .system-name {
                font-size: 20px;
            }

            .form-header h2 {
                font-size: 24px;
            }

            .system-info {
                padding: 20px;
            }
        }

        /* Animation for form elements */
        .form-group {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }
        .form-group:nth-child(6) { animation-delay: 0.6s; }

        .submit-btn {
            animation: fadeInUp 0.5s ease forwards;
            animation-delay: 0.7s;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form validation animations */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .is-invalid {
            animation: shake 0.3s ease;
        }

        /* Subtle animation for logo */
        @keyframes subtleFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .logo-circle {
            animation: subtleFloat 3s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <!-- Simple Background -->
    <div class="simple-bg"></div>

    <!-- Main Register Container -->
    <div class="register-container">
        <!-- Left Side - Minimal Brand -->
        <div class="brand-section">
            <div class="brand-content">
                <!-- Simple Logo -->
                <div class="simple-logo">
                    <div class="logo-circle">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2 class="system-name">REGISTRASI</h2>
                </div>

                <!-- System Info -->
                <div class="system-info">
                    <h3 class="system-title">Sistem Manajemen Persediaan ATK</h3>

                    <ul class="features-list">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Pencatatan barang ATK</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Mutasi stok barang</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Permintaan barang</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Stok opname</span>
                        </li>
                    </ul>
                </div>

                <!-- Simple Footer -->
                <div class="simple-footer">
                    © 2026 Magang Kemnaker • @fzlns21 | @dhiyaind
                </div>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div class="register-section">
            <div class="register-form-container">
                <!-- Notifications -->
                @if(session('success'))
                <div class="notification notification-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="notification notification-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                <!-- Form Header -->
                <div class="form-header">
                    <h2>Buat Akun Baru</h2>
                    <p>Isi data diri Anda dengan lengkap</p>
                </div>

                <!-- Register Form -->
                <form id="registerForm" action="{{ route('registerAction') }}" method="POST">
                    @csrf

                    <!-- Full Name -->
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-input @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap"
                                autocomplete="off"
                                required
                            />
                        </div>
                        @error('name')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- NIP -->
                    <div class="form-group">
                        <label class="form-label" for="nip">NIP</label>
                        <div class="input-wrapper">
                            <i class="fas fa-id-card input-icon"></i>
                            <input
                                type="number"
                                id="nip"
                                name="nip"
                                class="form-input @error('nip') is-invalid @enderror"
                                value="{{ old('nip') }}"
                                placeholder="Masukkan NIP"
                                autocomplete="off"
                                required
                            />
                        </div>
                        @error('nip')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                   

                    <!-- Position -->
                    <div class="form-group">
                        <label class="form-label" for="jabatan">Jabatan</label>
                        <div class="input-wrapper">
                            <i class="fas fa-briefcase input-icon"></i>
                            <input
                                type="text"
                                id="jabatan"
                                name="jabatan"
                                class="form-input @error('jabatan') is-invalid @enderror"
                                value="{{ old('jabatan') }}"
                                placeholder="Masukkan jabatan"
                                autocomplete="off"
                                required
                            />
                        </div>
                        @error('jabatan')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-input @error('password') is-invalid @enderror"
                                placeholder="Buat password"
                                autocomplete="off"
                                required
                            />
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="passwordIcon"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <div class="strength-text" id="strengthText">Kekuatan password</div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-input"
                                placeholder="Ulangi password"
                                autocomplete="off"
                                required
                            />
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye" id="confirmPasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-user-plus"></i>
                        Daftar Sekarang
                    </button>
                </form>

                <!-- Login Link -->
                <div class="login-link">
                    <span>Sudah memiliki akun?</span>
                    <a href="{{ route('login') }}">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide notifications
        setTimeout(() => {
            document.querySelectorAll('.notification').forEach(notification => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            });
        }, 4000);

        // Toggle password visibility
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId === 'password' ? 'passwordIcon' : 'confirmPasswordIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            // Check password strength
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            // Update strength bar
            const width = strength * 20;
            strengthFill.style.width = width + '%';

            // Update color and text
            let color = '#ef4444'; // red
            let text = 'Sangat lemah';

            if (strength >= 2) {
                color = '#f59e0b'; // yellow
                text = 'Lemah';
            }
            if (strength >= 3) {
                color = '#7c3aed'; // purple
                text = 'Cukup';
            }
            if (strength >= 4) {
                color = '#10b981'; // green
                text = 'Kuat';
            }
            if (strength >= 5) {
                color = '#059669'; // dark green
                text = 'Sangat kuat';
            }

            strengthFill.style.backgroundColor = color;
            strengthText.textContent = text;
        });

        // Add input focus effects
        document.querySelectorAll('.form-input').forEach(input => {
            const wrapper = input.closest('.input-wrapper');

            input.addEventListener('focus', function() {
                wrapper.style.transform = 'scale(1.02)';
                wrapper.style.zIndex = '1';
            });

            input.addEventListener('blur', function() {
                wrapper.style.transform = 'scale(1)';
                wrapper.style.zIndex = '0';
            });
        });

        // Form validation
        const registerForm = document.getElementById('registerForm');
        const submitBtn = registerForm.querySelector('.submit-btn');

        registerForm.addEventListener('submit', function(e) {
            // Validate password match
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            if (password !== confirmPassword) {
                e.preventDefault();

                // Add error class to confirmation field
                const confirmInput = document.getElementById('password_confirmation');
                confirmInput.classList.add('is-invalid');

                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Password tidak sama';

                const existingError = confirmInput.parentElement.nextElementSibling;
                if (existingError && existingError.classList.contains('invalid-feedback')) {
                    existingError.remove();
                }

                confirmInput.parentElement.parentElement.appendChild(errorDiv);
                return;
            }

            // Show loading state
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            submitBtn.disabled = true;

            // Re-enable button after 5 seconds (fallback)
            setTimeout(() => {
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
            }, 5000);
        });

        // Show error function
        function showError(message) {
            // Remove existing error notifications
            document.querySelectorAll('.notification').forEach(n => n.remove());

            // Create new error notification
            const notification = document.createElement('div');
            notification.className = 'notification notification-error';
            notification.innerHTML = `
                <i class="fas fa-exclamation-circle"></i>
                <span>${message}</span>
            `;

            document.body.appendChild(notification);

            // Auto remove
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate form elements
            setTimeout(() => {
                document.querySelectorAll('.form-group, .submit-btn').forEach(el => {
                    el.style.animationPlayState = 'running';
                });
            }, 100);

            // Real-time validation for NIP
            const nipInput = document.getElementById('nip');
            nipInput.addEventListener('input', function() {
                if (this.value.length > 18) {
                    this.value = this.value.slice(0, 18);
                }
            });

            // Real-time validation for email
            const emailInput = document.getElementById('email');
            emailInput.addEventListener('blur', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.value)) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        });

        // Simple typing effect for system description
        const descriptionText = "Daftarkan akun Anda untuk mengakses sistem manajemen persediaan alat tulis kantor yang terintegrasi.";
        const descriptionElement = document.querySelector('.system-description');

        if (descriptionElement) {
            let charIndex = 0;
            descriptionElement.textContent = '';

            function typeWriter() {
                if (charIndex < descriptionText.length) {
                    descriptionElement.textContent += descriptionText.charAt(charIndex);
                    charIndex++;
                    setTimeout(typeWriter, 20);
                }
            }

            // Start typing after page load
            setTimeout(typeWriter, 500);
        }
    </script>
</body>
</html>
