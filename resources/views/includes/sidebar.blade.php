<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <ul class="menu-inner py-1 mt-2">
        <!-- Dashboard -->
        <li class="menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <div class="menu-icon-container">
                    <div class="icon-backdrop"></div>
                    <i class="menu-icon tf-icons bx bx-home-alt"></i>
                </div>
                <div class="menu-text">Dashboard</div>
                <div class="menu-arrow">
                    <i class="bx bx-chevron-right"></i>
                </div>
            </a>
        </li>

        <li class="menu-header small text-uppercase mt-4 mb-2">
            <span class="menu-header-text">Manajemen Stok</span>
        </li>
        
        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'Admin')
            <li class="menu-item {{ Request::is('dashboard/barang*') ? 'active' : '' }}">
                <a href="{{ route('barang.index') }}" class="menu-link">
                    <div class="menu-icon-container">
                        <div class="icon-backdrop"></div>
                        <i class="menu-icon tf-icons bx bx-box"></i>
                    </div>
                    <div class="menu-text">Data Barang</div>
                    <div class="menu-arrow">
                        <i class="bx bx-chevron-right"></i>
                    </div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('dashboard/mutasi*') ? 'active' : '' }}">
                <a href="{{ route('mutasi.index') }}" class="menu-link">
                    <div class="menu-icon-container">
                        <div class="icon-backdrop"></div>
                        <i class="menu-icon tf-icons bx bx-transfer"></i>
                    </div>
                    <div class="menu-text">Mutasi Stok</div>
                    <div class="menu-arrow">
                        <i class="bx bx-chevron-right"></i>
                    </div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('dashboard/permintaan*') ? 'active' : '' }}">
                <a href="{{ route('permintaan.index') }}" class="menu-link">
                    <div class="menu-icon-container">
                        <div class="icon-backdrop"></div>
                        <i class="menu-icon tf-icons bx bx-clipboard"></i>
                    </div>
                    <div class="menu-text">Permintaan ATK</div>
                    <div class="menu-arrow">
                        <i class="bx bx-chevron-right"></i>
                    </div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('dashboard/stok-opname*') ? 'active' : '' }}">
                <a href="{{ route('stok-opname.index') }}" class="menu-link">
                    <div class="menu-icon-container">
                        <div class="icon-backdrop"></div>
                        <i class="menu-icon tf-icons bx bx-check-circle"></i>
                    </div>
                    <div class="menu-text">Stok Opname</div>
                    <div class="menu-arrow">
                        <i class="bx bx-chevron-right"></i>
                    </div>
                </a>
            </li>
        @endif
    </ul>

    <!-- User Profile Bottom -->
    <div class="menu-bottom mt-auto p-3">
        <div class="user-profile-card">
            <div class="avatar-container">
                <div class="avatar-ring"></div>
                <span class="avatar-initial">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
            </div>
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ Auth::user()->role ?? 'User' }}</div>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="{{ route('logout') }}" class="logout-btn" title="Logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bx bx-power-off"></i>
            </a>
        </div>
    </div>
</aside>

<style>
/* ===== BLUE THEME VARIABLES ===== */
:root {
    --blue-primary: #3b82f6;
    --blue-secondary: #1d4ed8;
    --blue-light: #60a5fa;
    --blue-lighter: #dbeafe;
    --blue-dark: #1e40af;
    --blue-gradient: linear-gradient(135deg, #3b82f6, #1d4ed8);
    --blue-gradient-light: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(29, 78, 216, 0.1));
    --blue-gradient-medium: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(29, 78, 216, 0.2));
}

/* ===== LAYOUT POSITIONING ===== */
@media (min-width: 1200px) {
    .layout-menu {
        margin-top: 64px;
        height: calc(100vh - 64px);
    }
}

@media (max-width: 1199.98px) {
    .layout-menu {
        position: fixed;
        top: 0;
        height: 100vh;
        margin-top: 0;
        z-index: 1200;
        transform: translateX(-100%);
    }
    
    .layout-menu.show {
        transform: translateX(0);
    }
}

/* ===== BLUE THEME STYLES ===== */
.layout-menu {
    background: rgba(255, 255, 255, 0.95);
    border-right: 1px solid rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    width: 260px;
}

.layout-menu::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.59);
    z-index: -1;
}

/* Menu Header */
.menu-header {
    color: #6b7280;
    font-size: 0.75rem;
    letter-spacing: 1px;
    padding: 0 1.5rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
}

/* Menu Items - NO ANIMATION */
.menu-item .menu-link {
    color: #4b5563;
    padding: 0.875rem 1.5rem;
    margin: 0.125rem 0.75rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    position: relative;
    background: transparent;
    border: 1px solid transparent;
    text-decoration: none;
}

.menu-item .menu-link:hover {
    color: var(--blue-primary);
    background: rgba(59, 130, 246, 0.08);
    border-color: rgba(59, 130, 246, 0.2);
}

.menu-item.active .menu-link {
    color: var(--blue-primary);
    background: rgba(59, 130, 246, 0.12);
    border-color: rgba(59, 130, 246, 0.3);
    font-weight: 500;
}

.menu-item.active .menu-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 40%;
    background: var(--blue-primary);
    border-radius: 0 2px 2px 0;
}

/* Menu Icons */
.menu-icon-container {
    width: 36px;
    height: 36px;
    margin-right: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.icon-backdrop {
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(59, 130, 246, 0.08);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.menu-item:hover .icon-backdrop {
    background: rgba(59, 130, 246, 0.12);
}

.menu-item.active .icon-backdrop {
    background: rgba(59, 130, 246, 0.15);
}

.menu-icon {
    font-size: 1.25rem;
    color: #6b7280;
    position: relative;
    z-index: 1;
}

.menu-item:hover .menu-icon {
    color: var(--blue-primary);
}

.menu-item.active .menu-icon {
    color: var(--blue-primary);
}

.menu-text {
    flex: 1;
    font-weight: 400;
    font-size: 0.9rem;
}

.menu-arrow {
    color: #9ca3af;
    font-size: 0.875rem;
}

.menu-item:hover .menu-arrow {
    color: var(--blue-primary);
}

.menu-item.active .menu-arrow {
    color: var(--blue-primary);
}

/* ===== USER PROFILE CARD ===== */
.user-profile-card {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 10px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

/* Avatar */
.avatar-container {
    position: relative;
    width: 44px;
    height: 44px;
    margin-right: 0.875rem;
    flex-shrink: 0;
}

.avatar-ring {
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    border-radius: 50%;
    background: var(--blue-gradient);
    -webkit-mask: radial-gradient(circle at center, transparent 20px, black 21px);
    mask: radial-gradient(circle at center, transparent 20px, black 21px);
}

.avatar-initial {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: var(--blue-gradient);
    color: white;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 50%;
    position: relative;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
}

/* User Info */
.user-info {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.9rem;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-role {
    color: var(--blue-primary);
    font-size: 0.75rem;
    font-weight: 500;
    background: rgba(59, 130, 246, 0.1);
    padding: 2px 8px;
    border-radius: 10px;
    display: inline-block;
}

/* Logout Button */
.logout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 8px;
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.2);
    transition: all 0.2s ease;
    margin-left: 0.5rem;
    flex-shrink: 0;
}

.logout-btn:hover {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.4);
}

/* ===== SIMPLE SCROLLBAR ===== */
.layout-menu::-webkit-scrollbar {
    width: 6px;
}

.layout-menu::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
}

.layout-menu::-webkit-scrollbar-thumb {
    background: rgba(59, 130, 246, 0.3);
    border-radius: 3px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1199.98px) {
    .layout-menu {
        width: 240px;
    }
    
    .menu-item .menu-link {
        padding: 0.75rem 1.25rem;
        margin: 0.125rem 0.5rem;
    }
    
    .menu-icon-container {
        width: 32px;
        height: 32px;
    }
    
    .avatar-container {
        width: 40px;
        height: 40px;
    }
    
    .avatar-initial {
        width: 36px;
        height: 36px;
        font-size: 0.9rem;
    }
    
    .avatar-ring {
        -webkit-mask: radial-gradient(circle at center, transparent 18px, black 19px);
        mask: radial-gradient(circle at center, transparent 18px, black 19px);
    }
    
    .user-profile-card {
        padding: 0.75rem;
    }
}

@media (max-width: 575.98px) {
    .layout-menu {
        width: 220px;
    }
    
    .menu-item .menu-link {
        padding: 0.625rem 1rem;
    }
    
    .menu-icon-container {
        width: 30px;
        height: 30px;
        margin-right: 0.625rem;
    }
    
    .menu-icon {
        font-size: 1.1rem;
    }
    
    .menu-text {
        font-size: 0.85rem;
    }
}
</style>

<script>
// Toggle sidebar for mobile
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.layout-menu-toggle');
    const layoutMenu = document.getElementById('layout-menu');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            layoutMenu.classList.toggle('show');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 1200) {
            const isClickInsideMenu = layoutMenu.contains(event.target);
            const isClickOnToggle = menuToggle && menuToggle.contains(event.target);
            
            if (!isClickInsideMenu && !isClickOnToggle && layoutMenu.classList.contains('show')) {
                layoutMenu.classList.remove('show');
            }
        }
    });
});
</script>