<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <div class="logo-shape">
                    <i class="bx bx-package fs-4"></i>
                </div>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2 fs-5">Persediaan</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-divider my-3"></div>

    <ul class="menu-inner py-1">
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
    /* ===== GLASS MORPHISM THEME ===== */
    .layout-menu {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border-right: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 
            inset 1px 0 0 rgba(255, 255, 255, 0.8),
            0 8px 32px rgba(31, 38, 135, 0.15);
        position: relative;
        overflow: hidden;
    }
    
    .layout-menu::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(168, 85, 247, 0.05) 100%);
        z-index: -1;
    }
    
    .app-brand {
        padding: 1.5rem 1.5rem 1.25rem;
        position: relative;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.5);
    }
    
    .logo-shape {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.9), rgba(168, 85, 247, 0.9));
        border-radius: 10px;
        color: white;
        box-shadow: 
            0 4px 6px rgba(99, 102, 241, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .logo-shape::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0.2), transparent);
    }
    
    .app-brand-text {
        background: linear-gradient(135deg, #6366f1, #a855f7);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 700;
        font-size: 1.2rem;
        letter-spacing: -0.5px;
    }
    
    .menu-divider {
        height: 1px;
        margin: 0.75rem 1.5rem;
        background: linear-gradient(90deg, 
            transparent 0%, 
            rgba(99, 102, 241, 0.2) 50%, 
            transparent 100%);
    }
    
    .menu-header {
        color: #6b7280;
        font-size: 0.7rem;
        letter-spacing: 1.5px;
        padding: 0 1.5rem;
        font-weight: 600;
        text-transform: uppercase;
        backdrop-filter: blur(5px);
    }
    
    .menu-item .menu-link {
        color: #4b5563;
        padding: 0.875rem 1.5rem;
        margin: 0.25rem 0.75rem;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        position: relative;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 
            0 2px 4px rgba(0, 0, 0, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }
    
    .menu-item .menu-link:hover {
        color: #6366f1;
        background: rgba(255, 255, 255, 0.9);
        border-color: rgba(99, 102, 241, 0.3);
        transform: translateY(-1px);
        box-shadow: 
            0 8px 16px rgba(99, 102, 241, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }
    
    .menu-item.active .menu-link {
        color: #6366f1;
        background: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.3);
        box-shadow: 
            inset 0 2px 4px rgba(99, 102, 241, 0.1),
            0 4px 8px rgba(99, 102, 241, 0.1);
    }
    
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
        background: rgba(99, 102, 241, 0.08);
        border-radius: 8px;
        backdrop-filter: blur(2px);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }
    
    .menu-item.active .icon-backdrop {
        background: rgba(99, 102, 241, 0.15);
        border-color: rgba(99, 102, 241, 0.3);
    }
    
    .menu-icon {
        font-size: 1.2rem;
        color: #6b7280;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }
    
    .menu-item:hover .menu-icon {
        color: #6366f1;
    }
    
    .menu-item.active .menu-icon {
        color: #6366f1;
    }
    
    .menu-text {
        flex: 1;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .menu-arrow {
        color: #9ca3af;
        transition: all 0.3s ease;
    }
    
    .menu-item:hover .menu-arrow {
        color: #6366f1;
    }
    
    .menu-item.active .menu-arrow {
        color: #6366f1;
    }
    
    /* User Profile Card */
    .user-profile-card {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 
            0 4px 6px rgba(0, 0, 0, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }
    
    .avatar-container {
        position: relative;
        width: 44px;
        height: 44px;
        margin-right: 0.75rem;
    }
    
    .avatar-ring {
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        -webkit-mask: radial-gradient(circle at center, transparent 18px, black 19px);
        mask: radial-gradient(circle at center, transparent 18px, black 19px);
    }
    
    .avatar-initial {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        color: white;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 50%;
        position: relative;
        box-shadow: 
            0 4px 6px rgba(99, 102, 241, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }
    
    .user-info {
        flex: 1;
    }
    
    .user-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 0.9rem;
    }
    
    .user-role {
        color: #6366f1;
        font-size: 0.75rem;
        font-weight: 500;
        background: rgba(99, 102, 241, 0.1);
        padding: 2px 8px;
        border-radius: 10px;
        display: inline-block;
    }
    
    .logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 8px;
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
        box-shadow: 
            0 2px 4px rgba(239, 68, 68, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.5);
        transition: all 0.3s ease;
    }
    
    .logout-btn:hover {
        background: rgba(239, 68, 68, 0.1);
        border-color: rgba(239, 68, 68, 0.3);
        transform: scale(1.05);
    }
    
    /* Floating Elements */
    .layout-menu {
        position: relative;
    }
    
    .layout-menu::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 30%, rgba(99, 102, 241, 0.03), transparent 50%);
        pointer-events: none;
    }
    
    /* Scrollbar */
    .layout-menu::-webkit-scrollbar {
        width: 6px;
    }
    
    .layout-menu::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.5);
        border-radius: 3px;
    }
    
    .layout-menu::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.5), rgba(168, 85, 247, 0.5));
        border-radius: 3px;
    }
    
    /* Responsive */
    @media (max-width: 1199.98px) {
        .layout-menu {
            backdrop-filter: blur(8px);
        }
        
        .app-brand {
            padding: 1.25rem 1.25rem 1rem;
        }
        
        .menu-item .menu-link {
            padding: 0.75rem 1.25rem;
            margin: 0.25rem 0.5rem;
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
        }
        
        .user-profile-card {
            padding: 0.875rem;
        }
    }
</style>