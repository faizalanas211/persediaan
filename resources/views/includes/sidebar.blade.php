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
            <a href="{{ route('logout') }}" class="logout-btn" title="Logout">
                <i class="bx bx-power-off"></i>
            </a>
        </div>
    </div>
</aside>

<style>
    /* ===== UNIQUE NEUMORPHISM STYLE ===== */
    .layout-menu {
        background: linear-gradient(145deg, #e6e9f0, #ffffff);
        box-shadow: 
            20px 20px 60px #d9dbe2,
            -20px -20px 60px #ffffff;
        border-right: 1px solid rgba(255, 255, 255, 0.5);
    }
    
    .menu-vertical {
        border-right: none;
    }
    
    .app-brand {
        padding: 1.5rem 1.5rem 1rem;
        position: relative;
    }
    
    .logo-shape {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: linear-gradient(145deg, #1a73e8, #0d47a1);
        border-radius: 12px;
        color: white;
        box-shadow: 
            5px 5px 10px #d9dbe2,
            -5px -5px 10px #ffffff;
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
        background: rgba(255, 255, 255, 0.1);
    }
    
    .app-brand-text {
        background: linear-gradient(45deg, #1a73e8, #0d47a1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
        letter-spacing: -0.5px;
    }
    
    .menu-divider {
        position: relative;
        height: 2px;
        margin: 0 1.5rem;
        background: linear-gradient(90deg, 
            transparent 0%, 
            rgba(26, 115, 232, 0.3) 50%, 
            transparent 100%);
    }
    
    .menu-header {
        color: #5f6368;
        font-size: 0.7rem;
        letter-spacing: 1px;
        padding: 0 1.5rem;
        position: relative;
    }
    
    .menu-header::before {
        content: '';
        position: absolute;
        left: 1.5rem;
        right: 1.5rem;
        bottom: -4px;
        height: 1px;
        background: linear-gradient(90deg, 
            transparent 0%, 
            rgba(26, 115, 232, 0.2) 50%, 
            transparent 100%);
    }
    
    .menu-item .menu-link {
        color: #5f6368;
        padding: 0.75rem 1.5rem;
        margin: 0.25rem 0.75rem;
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        background: linear-gradient(145deg, #f0f2f5, #ffffff);
        box-shadow: 
            5px 5px 10px #e1e3e6,
            -5px -5px 10px #ffffff;
    }
    
    .menu-item .menu-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(to bottom, transparent, #1a73e8, transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .menu-item .menu-link:hover {
        color: #1a73e8;
        transform: translateX(4px);
        box-shadow: 
            3px 3px 6px #e1e3e6,
            -3px -3px 6px #ffffff;
    }
    
    .menu-item .menu-link:hover::before {
        opacity: 1;
    }
    
    .menu-item.active .menu-link {
        color: #1a73e8;
        background: linear-gradient(145deg, #e3f2fd, #ffffff);
        box-shadow: 
            inset 2px 2px 5px #d9dbe2,
            inset -2px -2px 5px #ffffff;
    }
    
    .menu-item.active .menu-link::before {
        opacity: 1;
    }
    
    .menu-icon-container {
        position: relative;
        width: 40px;
        height: 40px;
        margin-right: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .icon-backdrop {
        position: absolute;
        width: 100%;
        height: 100%;
        background: linear-gradient(145deg, #ffffff, #e6e9f0);
        border-radius: 12px;
        box-shadow: 
            2px 2px 4px #e1e3e6,
            -2px -2px 4px #ffffff;
    }
    
    .menu-icon {
        font-size: 1.25rem;
        color: #5f6368;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }
    
    .menu-item.active .menu-icon {
        color: #1a73e8;
        transform: scale(1.1);
    }
    
    .menu-text {
        flex: 1;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .menu-arrow {
        color: #b0b7c3;
        transition: all 0.3s ease;
    }
    
    .menu-item.active .menu-arrow {
        color: #1a73e8;
        transform: translateX(2px);
    }
    
    /* User Profile Card */
    .user-profile-card {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        background: linear-gradient(145deg, #f0f2f5, #ffffff);
        border-radius: 16px;
        box-shadow: 
            5px 5px 10px #e1e3e6,
            -5px -5px 10px #ffffff;
    }
    
    .avatar-container {
        position: relative;
        width: 48px;
        height: 48px;
        margin-right: 0.75rem;
    }
    
    .avatar-ring {
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        border: 2px solid transparent;
        border-radius: 50%;
        background: linear-gradient(145deg, #1a73e8, #0d47a1) border-box;
        -webkit-mask: 
            linear-gradient(#fff 0 0) padding-box, 
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
    }
    
    .avatar-initial {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        background: linear-gradient(145deg, #1a73e8, #0d47a1);
        color: white;
        font-weight: 600;
        font-size: 1.125rem;
        border-radius: 50%;
        position: relative;
        z-index: 1;
        box-shadow: 
            2px 2px 4px #d9dbe2,
            -2px -2px 4px #ffffff;
    }
    
    .user-info {
        flex: 1;
    }
    
    .user-name {
        font-weight: 600;
        color: #1a73e8;
        font-size: 0.875rem;
    }
    
    .user-role {
        color: #5f6368;
        font-size: 0.75rem;
    }
    
    .logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: linear-gradient(145deg, #ffffff, #e6e9f0);
        border-radius: 12px;
        color: #ff4444;
        box-shadow: 
            2px 2px 4px #e1e3e6,
            -2px -2px 4px #ffffff;
        transition: all 0.3s ease;
    }
    
    .logout-btn:hover {
        color: #ff0000;
        transform: scale(1.05);
        box-shadow: 
            3px 3px 6px #e1e3e6,
            -3px -3px 6px #ffffff;
    }
    
    /* Responsive */
    @media (max-width: 1199.98px) {
        .app-brand {
            padding: 1rem;
        }
        
        .menu-item .menu-link {
            padding: 0.625rem 1rem;
            margin: 0.25rem 0.5rem;
        }
        
        .menu-icon-container {
            width: 36px;
            height: 36px;
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
            padding: 0.5rem;
        }
    }
    
    /* Animation */
    @keyframes subtleGlow {
        0%, 100% { 
            box-shadow: 
                5px 5px 10px #d9dbe2,
                -5px -5px 10px #ffffff;
        }
        50% { 
            box-shadow: 
                5px 5px 15px #d9dbe2,
                -5px -5px 15px #ffffff;
        }
    }
    
    .logo-shape {
        animation: subtleGlow 3s ease-in-out infinite;
    }
</style>