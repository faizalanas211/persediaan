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

        {{-- FLASH MESSAGE --}}
        @if(session('success'))
        <div id="flash-success"
            class="alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3 px-4 py-2 shadow"
            role="alert"
            style="min-width:260px; z-index:1050; border-radius:8px; opacity:1; transition:opacity .6s;">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div id="flash-error"
            class="alert alert-danger position-fixed top-0 start-50 translate-middle-x mt-3 px-4 py-2 shadow"
            role="alert"
            style="min-width:260px; z-index:1050; border-radius:8px; opacity:1; transition:opacity .6s;">
            {{ session('error') }}
        </div>
        @endif

        <!-- KANAN KOSONG (profil pindah ke sidebar) -->
        <div></div>

    </div>
</nav>

<style>
.navbar-standard {
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    height: 64px;
    z-index: 1100;
}

/* Logo */
.logo-container {
    width: 42px;
    height: 42px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-icon {
    font-size: 1.5rem;
    color: white;
}

.brand-title {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 1rem;
}

.brand-subtitle {
    color: #6b7280;
    font-size: 0.8rem;
}
</style>

<script>
setTimeout(function() {
    const success = document.getElementById('flash-success');
    if(success){ success.style.opacity="0"; setTimeout(()=>success.remove(),600); }

    const error = document.getElementById('flash-error');
    if(error){ error.style.opacity="0"; setTimeout(()=>error.remove(),600); }
}, 2500);
</script>
