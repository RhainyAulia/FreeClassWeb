<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js">

</script>
    <style>
        .table1{
            padding: 15px;
            border-spacing: 20px;
            width: 100%; 
            border-collapse: separate;
            margin-top: 15px;
        }
        .table2{
            border: 5px solid rgb(201, 201, 201);
            border-radius: 8px;
            padding: 20px;
            background-color: #f0f8ff;
        }
        .topdata{
            background-color: white;
            box-shadow: -1px 1px 5px gray;
            border-radius: 8px;
            text-align: center;
        }
        .a{
            background: linear-gradient(to top, #9933ff , #ffffff);
            height: 570px;
        }
    </style>
    <script>
        function updateTime() {
          const date = new Date();
          const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
          const day = date.toLocaleDateString(undefined, options);
          document.getElementById('day').innerHTML = day;
        }
        setInterval(updateTime, 1000);
        updateTime();

         function startTime() {
          const today = new Date();
          let h = today.getHours();
          let m = today.getMinutes();
          let s = today.getSeconds();
          m = checkTime(m);
          s = checkTime(s);
          document.getElementById('txt').innerHTML =  h + ":" + m + ":" + s;
          setTimeout(startTime, 1000);
        }

        function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
        }
  </script>
</head>
<body class="m-3 bg-dark" onload="startTime()">

<div class="row a rounded">
    <div class="col-2 d-flex justify-content-center">
      <img src="{{ asset('assets/FREECLASS-LOGO.png') }}" alt="freeclass" width="200" height="200"> </div>
    <div class="col-10 bg-light">
            <div class="container mt-3">
        <h1><strong>Dashboard</strong></h1>
    <table class="table1">
        <tr>
            <td class="topdata">
                <div>
                    <h2>Kelas Dipakai</h2>
                    <h3>Saat ini</h3>
                    <h1 style="color:#7c3aed;">{{ $kelasDipakai }}/{{ $totalKelas }}</h1>
                </div>
            </td>
            <td class="topdata">
                <h4 id="day"></h4>
                <h1 id="txt" style="color:#7c3aed;"></h1>
            </td>
            <td class="topdata">
                <div>
                    <h2>Pending Request</h2>
                    <h3>Saat ini</h3>
                    <h1 style="color:#7c3aed;">{{ $pendingRequest }}</h1>
                </div>
            </td>
        </tr>
    </table>

    <h2>Permohonan Pinjaman</h2>
    <div class="container p-3 mb-2 bg-light rounded">
    <table class="table table-bordered bg-success text-light rounded-3 table2"> 
    <thead class="table-light">
      <tr>
        <th>Kode</th>
        <th>Nama</th>
        <th >Jabatan</th>
        <th >Tanggal</th>
        <th >Waktu</th>
        <th >Aksi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>FC20250612001</td>
        <td>John Doe</td>
        <td>Mahasiswa</td>
        <td>16/6/2025</td>
        <td>13:00-15:30</td>
        <td>
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#detailPermintaanModal">
            Detail
        </button>
        </td>
      </tr>
    </tbody>
    </table>
    </div>
</div>
</div>
</div> 

<div class="modal fade" id="detailPermintaanModal" tabindex="-1" aria-labelledby="detailPermintaanLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailPermintaanLabel">Detail Permintaan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="list-unstyled">
        <div class="row">
        <div class="col-sm-6 p-3">
            <li><strong>Nama</strong></li>
            <li><strong>Jabatan</strong></li>
            <li><strong>Tanggal</strong></li>
            <li><strong>Waktu</strong></li>
            <li><strong>Tujuan Pinjam</strong></li>
            <li><strong>Jumlah Orang</strong></li>
            <li><strong>Ruangan</strong></li>
        </div>
        <div class="col-sm-6 p-3">
            <li>: John Doe</li>
            <li>: Mahasiswa</li>
            <li>: 12/06/2025</li>
            <li>: 13.00 - 15.30 WIB</li>
            <li>: Belajar Bersama</li>
            <li>: 30</li>
            <li>: 403</li>
        </div>
        </div>
        </ul>

        <div class="mb-3">
          <label for="gantiRuangan" class="form-label"><strong>Ganti Ruangan</strong></label>
          <select class="form-select" id="gantiRuangan">
            <option selected>Ruangan Tersedia</option>
            <option value="401">401</option>
            <option value="402">402</option>
            <option value="404">404</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="catatanPeminjam" class="form-label"><strong>Catatan</strong></label>
          <textarea class="form-control" id="catatanPeminjam" rows="2" placeholder="Catatan Untuk Peminjam"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success">Setujui</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tolak</button>
      </div>
    </div>
  </div>
</div>

@if(isset($error))
    <div class="alert alert-danger mt-3">{{ $error }}</div>
@endif


</html>