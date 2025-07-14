<!-- // ======= Summary Card (Kelas Dipakai, Tanggal, Pending) ======= -->
<div class="container mt-1">
    <h1 class="mb-3"><strong>Dashboard</strong></h1>
    <table class="table1 mb-4" style="margin-top: 0;">
        <tr>
            <td class="topdata align-middle">
                <div>
                    <h2>Kelas Dipakai</h2>
                    <h3>Saat ini</h3>
                    <h1 style="color:#7c3aed;">{{ $kelasDipakai }}/{{ $totalKelas }}</h1>
                </div>
            </td>
            <td class="topdata align-middle">
                <h4 id="day"></h4>
                <h1 id="txt" style="color:#7c3aed;"></h1>
            </td>
            <td class="topdata align-middle">
                <div>
                    <h2>Pending Request</h2>
                    <h3>Saat ini</h3>
                    <h1 style="color:#7c3aed;">{{ $pendingRequest }}</h1>
                </div>
            </td>
        </tr>
    </table>
</div>
