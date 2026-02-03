<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Persediaan ATK</title>
    
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
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #10b981;
            --accent: #f59e0b;
            --dark-bg: #0f172a;
            --dark-surface: #1e293b;
            --dark-surface-light: #334155;
            --dark-text: #f1f5f9;
            --dark-text-light: #cbd5e1;
            --dark-border: #475569;
            --success: #10b981;
            --warning: #f59e0b;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--dark-text);
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background */
        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            overflow: hidden;
            opacity: 0.5;
        }

        .bg-particle {
            position: absolute;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 50%;
        }

        .bg-particle:nth-child(1) {
            width: 400px;
            height: 400px;
            top: -200px;
            left: -200px;
            animation: float-1 20s infinite ease-in-out;
        }

        .bg-particle:nth-child(2) {
            width: 300px;
            height: 300px;
            bottom: -150px;
            right: -150px;
            animation: float-2 25s infinite ease-in-out;
        }

        @keyframes float-1 {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(50px, 80px) rotate(120deg); }
            66% { transform: translate(-30px, 40px) rotate(240deg); }
        }

        @keyframes float-2 {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(-60px, 30px) rotate(90deg); }
            50% { transform: translate(40px, -40px) rotate(180deg); }
            75% { transform: translate(-20px, 60px) rotate(270deg); }
        }

        /* Main Container */
        .login-container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            background: var(--dark-surface);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 
                0 30px 60px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            position: relative;
            z-index: 1;
            min-height: 700px;
            border: 1px solid var(--dark-border);
        }

        /* Left Side - Brand & System Info */
        .brand-section {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .brand-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 30%, rgba(255,255,255,0.1) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(255,255,255,0.05) 0%, transparent 40%);
        }

        .brand-content {
            position: relative;
            z-index: 2;
        }

        /* Logo Section */
        .logo-container {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 15px 35px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .logo-text h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .logo-text p {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
            letter-spacing: 0.5px;
        }

        /* System Title */
        .system-title {
            margin-bottom: 40px;
        }

        .system-title h2 {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #fff 0%, #e2e8f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .system-title p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-top: 30px;
        }

        .feature-card {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding: 25px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: 0.5s;
        }

        .feature-card:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateX(10px);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .feature-card:hover::before {
            left: 100%;
        }

        .feature-icon {
            width: 55px;
            height: 55px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            background: var(--accent);
            transform: scale(1.1);
        }

        .feature-content h4 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: white;
        }

        .feature-content p {
            font-size: 14px;
            line-height: 1.5;
            opacity: 0.8;
            font-weight: 300;
        }

        /* Status Badge */
        .status-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        /* Brand Footer */
        .brand-footer {
            position: relative;
            z-index: 2;
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            opacity: 0.7;
        }

        .stats {
            display: flex;
            gap: 20px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-item i {
            font-size: 16px;
        }

        /* Right Side - Login Form */
        .login-section {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--dark-surface);
        }

        .login-form-container {
            width: 100%;
            max-width: 400px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .form-header h2 {
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 15px;
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
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            color: var(--dark-text-light);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--dark-text-light);
            font-size: 18px;
            pointer-events: none;
            z-index: 1;
        }

        .form-input {
            width: 100%;
            padding: 16px 16px 16px 55px;
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
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .form-input::placeholder {
            color: var(--dark-text-light);
            opacity: 0.5;
        }

        .password-toggle {
            position: absolute;
            right: 20px;
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
            background: rgba(59, 130, 246, 0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--dark-text-light);
            font-size: 14px;
            cursor: pointer;
            user-select: none;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 5px 10px;
            border-radius: 6px;
        }

        .forgot-link:hover {
            color: var(--primary-dark);
            background: rgba(59, 130, 246, 0.1);
        }

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
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.3);
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: var(--dark-text-light);
            font-size: 14px;
            opacity: 0.7;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--dark-border), transparent);
        }

        .divider::before {
            margin-right: 20px;
        }

        .divider::after {
            margin-left: 20px;
        }

        .register-link {
            text-align: center;
            color: var(--dark-text-light);
            font-size: 14px;
        }

        .register-link a {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            margin-left: 8px;
            padding: 5px 10px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
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
            animation: slideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
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
            .login-container {
                flex-direction: column;
                max-width: 500px;
                min-height: auto;
            }
            
            .brand-section {
                padding: 40px 30px;
            }
            
            .login-section {
                padding: 40px 30px;
            }
            
            .system-title h2 {
                font-size: 28px;
            }
            
            .form-header h2 {
                font-size: 28px;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .login-container {
                border-radius: 16px;
            }
            
            .brand-section,
            .login-section {
                padding: 30px 20px;
            }
            
            .logo-icon {
                width: 60px;
                height: 60px;
                font-size: 24px;
            }
            
            .logo-text h1 {
                font-size: 22px;
            }
            
            .system-title h2 {
                font-size: 24px;
            }
            
            .form-header h2 {
                font-size: 24px;
            }
            
            .feature-card {
                padding: 20px;
            }
            
            .brand-footer {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .stats {
                justify-content: center;
            }
        }

        /* Animation for form elements */
        .form-group {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .form-group:nth-child(1) {
            animation-delay: 0.1s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.2s;
        }

        .form-options {
            animation: fadeInUp 0.5s ease forwards;
            animation-delay: 0.3s;
            opacity: 0;
            transform: translateY(20px);
        }

        .submit-btn {
            animation: fadeInUp 0.5s ease forwards;
            animation-delay: 0.4s;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Feature card animations */
        .feature-card {
            animation: slideInLeft 0.6s ease forwards;
            opacity: 0;
            transform: translateX(-20px);
        }

        .feature-card:nth-child(1) { animation-delay: 0.1s; }
        .feature-card:nth-child(2) { animation-delay: 0.2s; }
        .feature-card:nth-child(3) { animation-delay: 0.3s; }
        .feature-card:nth-child(4) { animation-delay: 0.4s; }

        @keyframes slideInLeft {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="bg-particle"></div>
        <div class="bg-particle"></div>
    </div>

    <!-- Main Login Container -->
    <div class="login-container">
        <!-- Left Side - System Information -->
        <div class="brand-section">
            <div class="brand-content">
                <!-- Logo -->
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div class="logo-text">
                        <h1>Sistem Manajemen Barang ATK</h1>
                    </div>
                </div>

                <!-- Features Grid -->
                <div class="features-grid">
                    <!-- Feature 1 -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Pencatatan Barang ATK</h4>
                            <p>Kelola data barang ATK secara lengkap dengan informasi stok.</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Mutasi Stok Barang</h4>
                            <p>Lacak perpindahan barang masuk dan keluar.</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Permintaan Barang</h4>
                            <p>Proses permintaan ATK dari pegawai atau unit kerja.</p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="feature-content">
                            <h4>Stok Opname</h4>
                            <p>Verifikasi fisik stok secara berkala untuk menjaga akurasi data persediaan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Stats -->
            
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-section">
            <div class="login-form-container">
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
                    <h2>Masuk Sistem</h2>
                    <p>Silakan login untuk mengakses dashboard</p>
                </div>

                <!-- Login Form -->
                <form action="{{ route('loginAction') }}" method="POST" id="loginForm">
                    @csrf
                    
                    <!-- Username/Email -->
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user input-icon"></i>
                            <input 
                                type="text" 
                                name="login" 
                                class="form-input" 
                                placeholder="Masukkan username / email / NIP"
                                autocomplete="off"
                                required
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon"></i>
                            <input 
                                type="password" 
                                name="password" 
                                class="form-input" 
                                placeholder="Masukkan password"
                                id="passwordField"
                                required
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="passwordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk
                    </button>
                </form>

                <!-- Divider -->
                <div class="divider">Pengguna baru?</div>

                <!-- Register Link -->
                <div class="register-link">
                    <span>Belum memiliki akses?</span>
                    <a href="{{ route('register') }}">Hubungi Admin</a>
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
        function togglePassword() {
            const passwordField = document.getElementById('passwordField');
            const icon = document.getElementById('passwordIcon');
            
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

        // Form submission loading state
        const loginForm = document.getElementById('loginForm');
        const submitBtn = loginForm.querySelector('.submit-btn');
        
        loginForm.addEventListener('submit', function(e) {
            // Basic validation
            const username = this.querySelector('[name="login"]').value.trim();
            const password = this.querySelector('[name="password"]').value;
            
            if (!username || !password) {
                e.preventDefault();
                showError('Harap isi semua bidang yang diperlukan');
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

        // Feature card hover effects
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(10px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
            
            // Click effect
            card.addEventListener('click', function() {
                this.style.transform = 'translateX(5px) scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'translateX(10px)';
                }, 150);
            });
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
                document.querySelectorAll('.form-group, .form-options, .submit-btn').forEach(el => {
                    el.style.animationPlayState = 'running';
                });
                
                // Animate feature cards
                document.querySelectorAll('.feature-card').forEach(card => {
                    card.style.animationPlayState = 'running';
                });
            }, 100);

            // Update live stats (simulated)
            updateLiveStats();
        });

        // Simulate live stats update
        function updateLiveStats() {
            const stats = [
                { icon: 'fa-database', text: 'Data Real-time' },
                { icon: 'fa-users', text: 'Multi-user' },
                { icon: 'fa-boxes', text: '500+ Items' },
                { icon: 'fa-check-circle', text: '100% Accurate' }
            ];
            
            let currentStat = 0;
            setInterval(() => {
                const statsContainer = document.querySelector('.stats');
                if (statsContainer) {
                    currentStat = (currentStat + 1) % stats.length;
                    const nextStat = stats[(currentStat + 1) % stats.length];
                    
                    statsContainer.innerHTML = `
                        <div class="stat-item">
                            <i class="fas ${nextStat.icon}"></i>
                            <span>${nextStat.text}</span>
                        </div>
                    `;
                }
            }, 5000);
        }

        // Add typing effect to system description
        const descriptionText = "Sistem terintegrasi untuk mengelola persediaan alat tulis kantor secara efisien dan real-time.";
        const descriptionElement = document.querySelector('.system-title p');
        
        if (descriptionElement) {
            let charIndex = 0;
            descriptionElement.textContent = '';
            
            function typeWriter() {
                if (charIndex < descriptionText.length) {
                    descriptionElement.textContent += descriptionText.charAt(charIndex);
                    charIndex++;
                    setTimeout(typeWriter, 30);
                }
            }
            
            // Start typing after page load
            setTimeout(typeWriter, 1000);
        }
    </script>
</body>
</html>