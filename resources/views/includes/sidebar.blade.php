<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bolder ms-2">Persediaan</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Pages</span>
        </li>
        @if (Auth::user()->role == 'admin' || Auth::user()->role == 'Admin')
            <li class="menu-item {{ Request::is('dashboard/barang*') ? 'active' : '' }}">
                <a href="{{ route('barang.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-edit"></i>
                    <div data-i18n="Analytics">Daftar Barang</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('dashboard/mutasi*') ? 'active' : '' }}">
                <a href="{{ route('mutasi.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-edit"></i>
                    <div data-i18n="Analytics">Mutasi Stok</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('dashboard/permintaan*') ? 'active' : '' }}">
                <a href="{{ route('permintaan.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-edit"></i>
                    <div data-i18n="Analytics">Permintaan ATK</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('dashboard/stok-opname*') ? 'active' : '' }}">
                <a href="{{ route('stok-opname.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-edit"></i>
                    <div data-i18n="Analytics">Stok Opname</div>
                </a>
            </li>


        @endif
    </ul>
</aside>