@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
    Dashboard Persediaan Barang
</li>
@endsection

@section('content')

<div class="neo-dashboard" id="dashboardArea">

    {{-- ===== HEADER ===== --}}
    <div class="neo-header">
        <div>
            <h2>Dashboard Persediaan Barang</h2>
            <span>Informasi stok inventaris</span>
        </div>

        <button class="neo-fullscreen" onclick="toggleFullscreen()" title="Fullscreen">
            <i class="bx bx-fullscreen"></i>
        </button>
    </div>

    {{-- ===== CONTENT ===== --}}
    <div class="neo-content">
        <div class="neo-scroll">

            <div class="neo-grid">
                @foreach($dataBarang as $jenis => $item)
                <div class="neo-card">
                    <div class="neo-card-left">
                        <div class="neo-icon">
                            <i class="bx bx-package"></i>
                        </div>
                        <div class="neo-name">{{ $jenis }}</div>
                    </div>

                    <div class="neo-stock">
                        {{ $item['ready'] }}
                        <small>unit</small>
                    </div>
                </div>
                @endforeach

                {{-- DUPLIKASI UNTUK LOOP --}}
                @foreach($dataBarang as $jenis => $item)
                <div class="neo-card">
                    <div class="neo-card-left">
                        <div class="neo-icon">
                            <i class="bx bx-package"></i>
                        </div>
                        <div class="neo-name">{{ $jenis }}</div>
                    </div>

                    <div class="neo-stock">
                        {{ $item['ready'] }}
                        <small>unit</small>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

</div>

{{-- ================= STYLE ================= --}}
<style>
:root{
    --neo-bg: linear-gradient(145deg, #e6e9f0, #ffffff);
    --neo-shadow:
        8px 8px 16px #d9dbe2,
        -8px -8px 16px #ffffff;
    --neo-shadow-inset:
        inset 3px 3px 6px #d9dbe2,
        inset -3px -3px 6px #ffffff;
    --primary:#1a73e8;
    --text-dark:#1f2937;
    --text-soft:#5f6368;
}

/* ROOT */
.neo-dashboard{
    border-radius:22px;
    background:var(--neo-bg);
    box-shadow:var(--neo-shadow);
    overflow:hidden;
}

/* HEADER */
.neo-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:26px 34px;
    background:var(--neo-bg);
    box-shadow:var(--neo-shadow-inset);
}

.neo-header h2{
    margin:0;
    font-weight:800;
    color:var(--text-dark);
}

.neo-header span{
    font-size:14px;
    color:var(--text-soft);
}

/* FULLSCREEN */
.neo-fullscreen{
    border:none;
    background:var(--neo-bg);
    box-shadow:var(--neo-shadow);
    border-radius:14px;
    width:46px;
    height:46px;
    font-size:22px;
    color:var(--primary);
    cursor:pointer;
    transition:.25s;
}
.neo-fullscreen:hover{
    transform:scale(1.05);
}

/* CONTENT */
.neo-content{
    height:480px;
    overflow:hidden;
}

/* SCROLL */
.neo-scroll{
    animation: scrollUp 28s linear infinite;
}

/* GRID */
.neo-grid{
    padding:32px;
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:24px;
}

/* CARD */
.neo-card{
    background:var(--neo-bg);
    box-shadow:var(--neo-shadow);
    border-radius:20px;
    padding:26px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    transition:.3s;
}

.neo-card:hover{
    transform:translateY(-4px);
}

/* LEFT */
.neo-card-left{
    display:flex;
    align-items:center;
    gap:18px;
}

.neo-icon{
    width:54px;
    height:54px;
    border-radius:16px;
    background:linear-gradient(145deg, #1a73e8, #0d47a1);
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:26px;
    box-shadow:
        4px 4px 8px #d9dbe2,
        -4px -4px 8px #ffffff;
}

.neo-name{
    font-size:24px;
    font-weight:800;
    color:var(--text-dark);
}

/* STOCK */
.neo-stock{
    font-size:64px;
    font-weight:900;
    color:var(--text-dark);
    display:flex;
    align-items:flex-end;
    gap:8px;
}

.neo-stock small{
    font-size:15px;
    color:var(--text-soft);
    font-weight:600;
}

/* ANIMATION */
@keyframes scrollUp{
    0%{ transform:translateY(0); }
    100%{ transform:translateY(-50%); }
}

/* FULLSCREEN MODE */
:fullscreen .neo-content{
    height:calc(100vh - 120px);
}

:fullscreen .neo-name{
    font-size:34px;
}

:fullscreen .neo-stock{
    font-size:96px;
}
</style>

{{-- ================= SCRIPT ================= --}}
<script>
function toggleFullscreen() {
    const el = document.getElementById('dashboardArea');
    if (!document.fullscreenElement) {
        el.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}
</script>

@endsection
