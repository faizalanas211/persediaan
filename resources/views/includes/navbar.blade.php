<nav class="navbar navbar-expand-xl fixed-top navbar-standard shadow-sm" id="layout-navbar">
    <div class="container-xxl d-flex align-items-center justify-content-between">

        <!-- KIRI: Toggle + Brand -->
        <div class="d-flex align-items-center gap-3">
            <a href="javascript:void(0);" class="layout-menu-toggle nav-link d-xl-none p-2 rounded-circle bg-light">
                <i class="bx bx-menu bx-sm text-primary"></i>
            </a>

            <a href="#" class="app-brand-link d-flex align-items-center text-decoration-none">
                <div class="logo-container me-2">
                    <i class="bx bx-package logo-icon"></i>
                </div>
                <div>
                    <span class="brand-title fw-bold">Sistem Manajemen</span>
                    <span class="brand-subtitle d-block">Persediaan ATK</span>
                </div>
            </a>
        </div>

        @if(session('success'))
        <div id="flash-success"
            class="alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3 px-4 py-2 shadow"
            role="alert"
            style="min-width:260px; z-index:1050; border-radius:8px; opacity:1; transition:opacity .6s;">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(function() {
                const alertBox = document.getElementById('flash-success');
                if (alertBox) {
                    alertBox.style.opacity = "0";  
                    setTimeout(() => alertBox.remove(), 600); 
                }
            }, 2500); 
        </script>
        @endif

        @if(session('error'))
        <div id="flash-error"
            class="alert alert-danger position-fixed top-0 start-50 translate-middle-x mt-3 px-4 py-2 shadow"
            role="alert"
            style="min-width:260px; z-index:1050; border-radius:8px; opacity:1; transition:opacity .6s;">
            {{ session('error') }}
        </div>

        <script>
            setTimeout(function() {
                const alertBox = document.getElementById('flash-error');
                if (alertBox) {
                    alertBox.style.opacity = "0";   
                    setTimeout(() => alertBox.remove(), 600); 
                }
            }, 2500); 
        </script>
        @endif

        <!-- KANAN: User -->
        <div class="dropdown">
            <a href="#"
            class="d-flex align-items-center text-decoration-none dropdown-toggle"
            data-bs-toggle="dropdown">

                @if(auth()->user()->pegawai && auth()->user()->pegawai->foto)
                    <img src="{{ asset('storage/' . auth()->user()->pegawai->foto) }}"
                        alt="Foto Profil"
                        class="rounded-circle me-2"
                        width="32" height="32"
                        style="object-fit: cover;">
                @else
                    <i class="bx bx-user-circle fs-3 text-secondary me-2"></i>
                @endif

                <span class="fw-medium text-dark d-none d-md-inline">
                    {{ Auth::user()->name }}
                </span>
            </a>

                <div class="dropdown-menu dropdown-menu-end mt-2">
                <div class="px-3 py-2 border-bottom d-flex align-items-center gap-2">

                    @if(auth()->user()->pegawai && auth()->user()->pegawai->foto)
                        <img src="{{ asset('storage/' . auth()->user()->pegawai->foto) }}"
                            class="rounded-circle"
                            width="36" height="36"
                            style="object-fit: cover;">
                    @else
                        <i class="bx bx-user-circle fs-2 text-secondary"></i>
                    @endif

                    <div>
                        <div class="fw-semibold">{{ Auth::user()->name }}</div>
                        <small class="text-muted">{{ Auth::user()->role ?? 'User' }}</small>
                    </div>
                </div>

                <a class="dropdown-item" href="{{ route('profil.show', auth()->user()->id) }}">
                    <i class="bx bx-user me-2"></i> Profil
                </a>

                <div class="dropdown-divider"></div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="dropdown-item text-danger" type="submit">
                        <i class="bx bx-log-out me-2"></i> Keluar
                    </button>
                </form>
            </div>
        </div>

    </div>
</nav>


<style>
/* Navbar standar */
.navbar-standard {
    background: #ffffff;
    border-bottom: 1px solid #e0e7ff; /* biru sangat soft */
    height: 64px;
    z-index: 1100;
}


/* Brand */
/* ===== GRADIENT GLASS NAVBAR ===== */
.navbar-gradient-glass {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.98) 100%);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    height: 70px;
    transition: all 0.3s ease;
}

/* Logo Styling */
.logo-container {
    width: 42px;
    height: 42px;
    background: linear-gradient(135deg, #0d6efd, #3b82f6);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.logo-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 50%;
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0.2), transparent);
}

.logo-icon {
    font-size: 1.5rem;
    color: white;
    z-index: 1;
}

.brand-title {
    background: linear-gradient(135deg, #0d6efd, #2563eb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 1rem;
    line-height: 1;
}

.brand-subtitle {
    color: #6b7280;
    font-size: 0.8rem;
    font-weight: 500;
}

.app-brand-link span {
    letter-spacing: .3px;
}

/* Dropdown */
.dropdown-menu {
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
    min-width: 220px;
}

.dropdown-item {
    font-size: 14px;
}

.dropdown-item:hover {
    background: #f8f9fa;
}

/* Icon hover */
.navbar a:hover i {
    color: #0d6efd;
}

</style>

<script>
// Auto hide flash messages
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-flash');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        }, 4000);
    });
});

// Toggle sidebar
document.getElementById('sidebarToggle')?.addEventListener('click', function() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.toggle('collapsed');
    }
});
</script>