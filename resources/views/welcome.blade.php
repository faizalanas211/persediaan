@extends('layouts.guest')

@section('content')

<div class="container py-5">

    {{-- HEADER --}}
    <div class="text-center mb-4">
        <h2 class="fw-bold title-gradient">
            Stok Persediaan ATK
        </h2>
        <p class="text-muted">
            Informasi stok barang bulan {{ now()->translatedFormat('F Y') }}
        </p>
    </div>

    {{-- CARD --}}
    <div class="card shadow-sm border-0 main-card">
        <div class="card-body p-4">

            {{-- SEARCH --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <input type="text" id="searchInput" class="form-control w-50"
                    placeholder="🔍 Cari barang...">
                
                <small class="text-muted">Klik header untuk sort</small>
            </div>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table align-middle modern-table" id="stokTable">
                    <thead>
    <tr>
        <th onclick="sortTable(0, this)">
            No <i class="bx bx-sort-alt-2 sort-icon"></i>
        </th>
        <th onclick="sortTable(1, this)">
            Nama Barang <i class="bx bx-sort-alt-2 sort-icon"></i>
        </th>
        <th class="text-center" onclick="sortTable(2, this)">
            Stok <i class="bx bx-sort-alt-2 sort-icon"></i>
        </th>
        <th class="text-center" onclick="sortTable(3, this)">
            Satuan <i class="bx bx-sort-alt-2 sort-icon"></i>
        </th>
    </tr>
</thead>
                    <tbody>
                        @forelse($dataBarang as $jenis => $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $jenis }}</td>
                            <td class="text-center" data-value="{{ $item['ready'] }}">
                                <span class="stock-badge {{ $item['ready'] < 10 ? 'low' : '' }}">
                                    {{ $item['ready'] }}
                                </span>
                            </td>
                            <td class="text-center text-muted">
                                {{ $item['satuan'] }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Data stok belum tersedia
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

{{-- STYLE --}}
<style>
.title-gradient{
    background: linear-gradient(90deg, #7c3aed, #a78bfa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.main-card{
    border-radius: 16px;
}

.modern-table{
    border-collapse: separate;
    border-spacing: 0 10px;
}

.modern-table thead th{
    border: none;
    font-size: 13px;
    text-transform: uppercase;
    color: #6b7280;
    cursor: pointer;
}

.modern-table tbody tr{
    background: #ffffff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    border-radius: 12px;
    transition: .2s;
}

.modern-table tbody tr:hover{
    transform: translateY(-2px);
}

.modern-table td{
    border: none;
    padding: 16px;
}

.stock-badge{
    background: #ede9fe;
    color: #6d28d9;
    padding: 6px 14px;
    border-radius: 999px;
    font-weight: 600;
}

.stock-badge.low{
    background: #fee2e2;
    color: #dc2626;
}

.sort-icon{
    font-size: 14px;
    margin-left: 6px;
    opacity: 0.5;
    transition: 0.2s;
}

th.active .sort-icon{
    opacity: 1;
    color: #7c3aed;
}
</style>

{{-- SCRIPT --}}
<script>
/* SEARCH */
document.getElementById("searchInput").addEventListener("keyup", function() {
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#stokTable tbody tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(value) ? "" : "none";
    });

    updateRowNumber(); // penting
});

/* SORT */
let sortDirection = {};

function sortTable(colIndex, el) {
    let table = document.getElementById("stokTable");
    let tbody = table.querySelector("tbody");
    let rows = Array.from(tbody.querySelectorAll("tr"));

    // reset icon
    document.querySelectorAll("th").forEach(th => {
        th.classList.remove("active");
        th.querySelector(".sort-icon").className = "bx bx-sort-alt-2 sort-icon";
    });

    sortDirection[colIndex] = !sortDirection[colIndex];

    rows.sort((a, b) => {
        let aText = a.children[colIndex].innerText.trim();
        let bText = b.children[colIndex].innerText.trim();

        let aNum = a.children[colIndex].dataset.value;
        let bNum = b.children[colIndex].dataset.value;

        if (!isNaN(aNum) && !isNaN(bNum)) {
            return sortDirection[colIndex] ? aNum - bNum : bNum - aNum;
        }

        return sortDirection[colIndex]
            ? aText.localeCompare(bText)
            : bText.localeCompare(aText);
    });

    rows.forEach(row => tbody.appendChild(row));

    updateRowNumber(); // ✅ ini bikin nomor urut ulang

    el.classList.add("active");
    let icon = el.querySelector(".sort-icon");

    icon.className = sortDirection[colIndex]
        ? "bx bx-up-arrow-alt sort-icon"
        : "bx bx-down-arrow-alt sort-icon";
}

/* RENUMBER */
function updateRowNumber() {
    let rows = document.querySelectorAll("#stokTable tbody tr");
    let index = 1;

    rows.forEach(row => {
        if (row.style.display !== "none") {
            row.children[0].innerText = index++;
        }
    });
}
</script>
@endsection