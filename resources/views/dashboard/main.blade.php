@extends('layouts.admin')

@section('breadcrumb')
<li class="breadcrumb-item active text-primary fw-semibold">
    Dashboard Persediaan Barang
</li>
@endsection

@section('content')

<div class="tv-board" id="dashboardArea">

    {{-- HEADER --}}
    <div class="tv-header">
        <div>
            <h2>Dashboard Persediaan Barang</h2>
            <span>Informasi stok inventaris</span>
        </div>

        <button class="btn-fullscreen" onclick="toggleFullscreen()">⛶</button>
    </div>

    {{-- CONTENT --}}
    <div class="tv-content">
        <div class="tv-scroll">

            {{-- GRID --}}
            <div class="tv-grid">

                @foreach($dataBarang as $jenis => $item)
                <div class="tv-card">
                    <div class="tv-card-name">
                        {{ $jenis }}
                    </div>
                    <div class="tv-card-stock">
                        {{ $item['ready'] }}
                        <small>unit</small>
                    </div>
                </div>
                @endforeach

                {{-- DUPLIKASI UNTUK LOOP --}}
                @foreach($dataBarang as $jenis => $item)
                <div class="tv-card">
                    <div class="tv-card-name">
                        {{ $jenis }}
                    </div>
                    <div class="tv-card-stock">
                        {{ $item['ready'] }}
                        <small>unit</small>
                    </div>
                </div>
                @endforeach

            </div>

        </div>
    </div>

</div>

<style>
:root{
    --dark-main:#1f2937;
    --dark-soft:#374151;
    --accent:#6366f1;
    --bg-soft:#f8fafc;
}

/* ROOT */
.tv-board{
    background:var(--bg-soft);
    border-radius:20px;
    box-shadow:0 16px 40px rgba(0,0,0,.12);
    overflow:hidden;
}

/* HEADER */
.tv-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:28px 36px;
    background:#ffffff;
    border-bottom:2px solid #e5e7eb;
}

.tv-header h2{
    margin:0;
    font-size:28px;
    font-weight:800;
    color:var(--dark-main);
}

.tv-header span{
    font-size:14px;
    color:#6b7280;
}

/* FULLSCREEN */
.btn-fullscreen{
    border:none;
    background:#eef2ff;
    color:var(--accent);
    border-radius:12px;
    padding:10px 16px;
    font-size:20px;
    cursor:pointer;
}

/* CONTENT */
.tv-content{
    height:460px;
    overflow:hidden;
    background:var(--bg-soft);
}

/* SCROLL */
.tv-scroll{
    animation: tvScroll 28s linear infinite;
}

/* GRID */
.tv-grid{
    display:grid;
    grid-template-columns: repeat(2, 1fr);
    gap:24px;
    padding:32px;
}

/* CARD */
.tv-card{
    background:#ffffff;
    border-radius:18px;
    padding:26px 32px;
    box-shadow:0 8px 20px rgba(0,0,0,.08);
    display:flex;
    justify-content:space-between;
    align-items:center;
    position:relative;
    overflow:hidden;
}

/* AKSEN STRIP */
.tv-card::before{
    content:'';
    position:absolute;
    left:0;
    top:0;
    bottom:0;
    width:6px;
    background:var(--accent);
    border-radius:18px 0 0 18px;
}

/* NAME */
.tv-card-name{
    font-size:24px;
    font-weight:700;
    color:var(--dark-main);
}

/* STOCK */
.tv-card-stock{
    font-size:56px;
    font-weight:900;
    color:var(--dark-main);
    display:flex;
    align-items:flex-end;
    gap:8px;
}

.tv-card-stock small{
    font-size:15px;
    font-weight:600;
    color:var(--dark-soft);
}

/* ANIMATION */
@keyframes tvScroll{
    0%{ transform:translateY(0); }
    100%{ transform:translateY(-50%); }
}

/* FULLSCREEN MODE */
:fullscreen .tv-content{
    height:calc(100vh - 110px);
}

:fullscreen .tv-card-name{
    font-size:34px;
}

:fullscreen .tv-card-stock{
    font-size:80px;
}
</style>


@endsection
