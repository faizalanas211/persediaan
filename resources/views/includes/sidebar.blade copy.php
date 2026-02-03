<aside id="layout-menu" class="layout-menu">
    <!-- Brand Header -->
    <div class="brand-header">
        <div class="brand-logo">
            <div class="logo-icon">
                <i class='bx bx-package'></i>
            </div>
            <span class="brand-name">Persediaan</span>
        </div>
        <button class="menu-toggle">
            <i class='bx bx-chevron-left'></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="menu-nav">
        <!-- Dashboard -->
        <div class="nav-section">
            <a href="{{ route('dashboard') }}" class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class='bx bx-home-alt'></i>
                </div>
                <span class="nav-label">Dashboard</span>
                <div class="nav-indicator">
                    <i class='bx bx-chevron-right'></i>
                </div>
            </a>
        </div>

        <!-- Management -->
        <div class="nav-group">
            <div class="group-header">Manajemen Stok</div>
            
            @if (Auth::user()->role == 'admin' || Auth::user()->role == 'Admin')
            <div class="nav-section">
                <a href="{{ route('barang.index') }}" class="nav-item {{ Request::is('dashboard/barang*') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <i class='bx bx-box'></i>
                    </div>
                    <span class="nav-label">Data Barang</span>
                    <div class="nav-indicator">
                        <i class='bx bx-chevron-right'></i>
                    </div>
                </a>
                
                <a href="{{ route('mutasi.index') }}" class="nav-item {{ Request::is('dashboard/mutasi*') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <i class='bx bx-transfer'></i>
                    </div>
                    <span class="nav-label">Mutasi Stok</span>
                    <div class="nav-indicator">
                        <i class='bx bx-chevron-right'></i>
                    </div>
                </a>
                
                <a href="{{ route('permintaan.index') }}" class="nav-item {{ Request::is('dashboard/permintaan*') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <i class='bx bx-clipboard'></i>
                    </div>
                    <span class="nav-label">Permintaan ATK</span>
                    <div class="nav-indicator">
                        <i class='bx bx-chevron-right'></i>
                    </div>
                </a>
                
                <a href="{{ route('stok-opname.index') }}" class="nav-item {{ Request::is('dashboard/stok-opname*') ? 'active' : '' }}">
                    <div class="nav-icon">
                        <i class='bx bx-check-circle'></i>
                    </div>
                    <span class="nav-label">Stok Opname</span>
                    <div class="nav-indicator">
                        <i class='bx bx-chevron-right'></i>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </nav>

    <!-- User Profile -->
    <div class="user-profile">
        <div class="profile-avatar">
            <div class="avatar-ring">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
        <div class="profile-info">
            <div class="profile-name">{{ Auth::user()->name }}</div>
            <div class="profile-role">{{ Auth::user()->role ?? 'User' }}</div>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class='bx bx-log-out'></i>
            </button>
        </form>
    </div>
</aside>

<style>
/* ===== MODERN LAIKA STYLE ===== */
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --surface: #ffffff;
    --surface-light: #f8fafc;
    --surface-dark: #f1f5f9;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --border: #e2e8f0;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.layout-menu {
    width: 280px;
    height: 100vh;
    background: var(--surface);
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 100;
    box-shadow: var(--shadow-sm);
}

/* Brand Header */
.brand-header {
    padding: 1.5rem 1.5rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--border);
}

.brand-logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.logo-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    position: relative;
    overflow: hidden;
}

.logo-icon::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 50%;
    background: rgba(255, 255, 255, 0.15);
}

.brand-name {
    font-size: 1.25rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: -0.5px;
}

.menu-toggle {
    width: 36px;
    height: 36px;
    border: 1px solid var(--border);
    background: var(--surface-light);
    border-radius: var(--radius-sm);
    color: var(--text-secondary);
    cursor: pointer;
    transition: var(--transition);
    display: none;
}

.menu-toggle:hover {
    background: var(--surface-dark);
    color: var(--text-primary);
}

/* Navigation */
.menu-nav {
    flex: 1;
    padding: 1.5rem 1rem;
    overflow-y: auto;
}

.nav-group {
    margin-bottom: 2rem;
}

.group-header {
    font-size: 0.75rem;
    text-transform: uppercase;
    color: var(--text-secondary);
    letter-spacing: 0.05em;
    font-weight: 600;
    padding: 0 1rem;
    margin-bottom: 0.75rem;
}

.nav-section {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: var(--radius-md);
    color: var(--text-secondary);
    text-decoration: none;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.nav-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: var(--primary);
    opacity: 0;
    transition: var(--transition);
}

.nav-item:hover {
    background: var(--surface-light);
    color: var(--text-primary);
    transform: translateX(2px);
}

.nav-item:hover::before {
    opacity: 0.5;
}

.nav-item.active {
    background: var(--surface-light);
    color: var(--primary);
    font-weight: 500;
}

.nav-item.active::before {
    opacity: 1;
}

.nav-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--surface-light);
    border-radius: var(--radius-sm);
    color: var(--text-secondary);
    transition: var(--transition);
    font-size: 1.125rem;
}

.nav-item:hover .nav-icon {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.nav-item.active .nav-icon {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
}

.nav-label {
    flex: 1;
    font-size: 0.875rem;
}

.nav-indicator {
    opacity: 0;
    transform: translateX(-4px);
    transition: var(--transition);
    color: var(--text-secondary);
}

.nav-item:hover .nav-indicator,
.nav-item.active .nav-indicator {
    opacity: 1;
    transform: translateX(0);
}

/* User Profile */
.user-profile {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: var(--surface-light);
}

.profile-avatar {
    position: relative;
}

.avatar-ring {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    position: relative;
}

.avatar-ring::after {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 50%;
    z-index: -1;
    opacity: 0.3;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.3; }
    50% { transform: scale(1.05); opacity: 0.2; }
}

.profile-info {
    flex: 1;
}

.profile-name {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    line-height: 1.2;
}

.profile-role {
    font-size: 0.75rem;
    color: var(--text-secondary);
    line-height: 1.2;
}

#logout-form {
    display: flex;
}

.logout-btn {
    width: 36px;
    height: 36px;
    border: 1px solid var(--border);
    background: var(--surface);
    border-radius: var(--radius-sm);
    color: var(--text-secondary);
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
}

.logout-btn:hover {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fecaca;
    transform: rotate(12deg);
}

/* Responsive */
@media (max-width: 1199.98px) {
    .layout-menu {
        width: 260px;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        box-shadow: var(--shadow-lg);
    }
    
    .layout-menu.open {
        transform: translateX(0);
    }
    
    .menu-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .brand-header {
        padding: 1.25rem;
    }
}

/* Custom Scrollbar */
.menu-nav::-webkit-scrollbar {
    width: 4px;
}

.menu-nav::-webkit-scrollbar-track {
    background: transparent;
}

.menu-nav::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 2px;
}

.menu-nav::-webkit-scrollbar-thumb:hover {
    background: var(--text-secondary);
}

/* Animation */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.nav-item {
    animation: slideIn 0.3s ease-out;
}

.nav-item:nth-child(1) { animation-delay: 0.1s; }
.nav-item:nth-child(2) { animation-delay: 0.15s; }
.nav-item:nth-child(3) { animation-delay: 0.2s; }
.nav-item:nth-child(4) { animation-delay: 0.25s; }
</style>

<script>
// Add toggle functionality for mobile
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const layoutMenu = document.querySelector('.layout-menu');
    
    if (menuToggle && layoutMenu) {
        menuToggle.addEventListener('click', function() {
            layoutMenu.classList.toggle('open');
            
            // Update icon
            const icon = menuToggle.querySelector('i');
            if (layoutMenu.classList.contains('open')) {
                icon.className = 'bx bx-chevron-right';
            } else {
                icon.className = 'bx bx-chevron-left';
            }
        });
    }
    
    // Close menu when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 1200) {
            if (!layoutMenu.contains(e.target) && !e.target.closest('.menu-toggle')) {
                layoutMenu.classList.remove('open');
            }
        }
    });
});
</script>