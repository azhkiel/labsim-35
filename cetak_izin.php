<?php
include("config.php");
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['id_login'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID cuti dari URL
$id = $_GET['id'] ?? 0;

// Ambil data dari database berdasarkan ID
$query = mysqli_query($conn, "SELECT c.*, da.nama, da.jabatan, da.departemen 
                              FROM cuti_izin_karyawan c 
                              JOIN detail_akun da ON c.id_detail = da.id_detail 
                              WHERE c.id_cuti = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
  die("Data tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cetak Bukti Izin - Gruduk Cafe</title>
  <style>
    /* Reset untuk print */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      color: #000;
      background: #fff;
      padding: 20px;
    }
    
    .container {
      max-width: 800px;
      margin: 0 auto;
      border: 2px solid #333;
      padding: 30px;
      position: relative;
    }
    
    .header {
      text-align: center;
      margin-bottom: 30px;
      border-bottom: 2px solid #333;
      padding-bottom: 20px;
    }
    
    .header h1 {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 10px;
      color: #5a4336;
    }
    
    .company-info {
      font-size: 14px;
      color: #666;
    }
    
    .content {
      margin-bottom: 30px;
    }
    
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-bottom: 20px;
    }
    
    .info-item {
      margin-bottom: 10px;
    }
    
    .label {
      font-weight: bold;
      color: #333;
      display: block;
      margin-bottom: 5px;
    }
    
    .value {
      color: #000;
    }
    
    .status {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-weight: bold;
      font-size: 12px;
    }
    
    .status.diterima {
      background: #d4edda;
      color: #155724;
    }
    
    .status.ditolak {
      background: #f8d7da;
      color: #721c24;
    }
    
    .status.pending {
      background: #fff3cd;
      color: #856404;
    }
    
    .section {
      margin-bottom: 20px;
      padding: 15px;
      background: #f8f9fa;
      border-radius: 5px;
    }
    
    .section-title {
      font-weight: bold;
      margin-bottom: 10px;
      color: #333;
    }
    
    .signature-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 20px;
      margin-top: 60px;
      text-align: center;
    }
    
    .signature-box {
      padding-top: 60px;
    }
    
    .signature-name {
      font-weight: bold;
      margin-top: 80px;
      border-top: 1px solid #333;
      padding-top: 10px;
    }
    
    .signature-role {
      font-size: 12px;
      color: #666;
      margin-top: 5px;
    }
    
    .footer {
      text-align: center;
      margin-top: 40px;
      font-size: 10px;
      color: #666;
      border-top: 1px solid #ccc;
      padding-top: 10px;
    }
    
    .no-print {
      display: none;
    }
    
    /* Hanya untuk preview di browser */
    @media screen {
      body {
        background: #f0f0f0;
      }
      
      .no-print {
        display: block;
        text-align: center;
        margin-bottom: 20px;
      }
      
      .print-btn {
        background: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin: 10px;
      }
      
      .back-btn {
        background: #6c757d;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin: 10px;
        text-decoration: none;
        display: inline-block;
      }
    }
    
    /* Untuk print */
    @media print {
      .no-print {
        display: none !important;
      }
      
      body {
        background: white !important;
        padding: 0;
      }
      
      .container {
        border: none;
        padding: 0;
        margin: 0;
        max-width: none;
      }
    }
  </style>
</head>
<body>
  <!-- Tombol hanya untuk screen -->
  <div class="no-print">
    <button class="print-btn" onclick="window.print()">🖨️ Cetak Dokumen</button>
    <a href="riwayat cuti izin.php" class="back-btn">← Kembali</a>
    <p style="text-align: center; margin: 10px; color: #666;">Preview dokumen - Klik tombol cetak untuk print</p>
  </div>

  <!-- Konten dokumen -->
  <div class="container">
    <!-- Header -->
    <div class="header">
      <h1>BUKTI IZIN / LEAVE SLIP</h1>
      <div class="company-info">
        <strong>GRUDUK CAFE</strong><br>
        Jl. Contoh Raya No. 123, Jakarta<br>
        Telp: (021) 1234-5678
      </div>
    </div>

    <!-- Informasi Karyawan -->
    <div class="content">
      <div class="info-grid">
        <div class="info-item">
          <span class="label">Nama Karyawan:</span>
          <span class="value"><?= htmlspecialchars($data['nama'] ?? '-') ?></span>
        </div>
        <div class="info-item">
          <span class="label">ID Karyawan:</span>
          <span class="value"><?= htmlspecialchars($data['id_detail'] ?? '-') ?></span>
        </div>
        <div class="info-item">
          <span class="label">Departemen:</span>
          <span class="value"><?= htmlspecialchars($data['departemen'] ?? '-') ?></span>
        </div>
        <div class="info-item">
          <span class="label">Jabatan:</span>
          <span class="value"><?= htmlspecialchars($data['jabatan'] ?? '-') ?></span>
        </div>
        <div class="info-item">
          <span class="label">Jenis Izin:</span>
          <span class="value"><?= htmlspecialchars(ucfirst($data['tipe'] ?? '-')) ?></span>
        </div>
        <div class="info-item">
          <span class="label">Status:</span>
          <span class="status <?= $data['action'] ?? 'pending' ?>">
            <?php 
            switch($data['action'] ?? '') {
              case 'diterima': echo 'DITERIMA'; break;
              case 'ditolak': echo 'DITOLAK'; break;
              default: echo 'PENDING'; break;
            }
            ?>
          </span>
        </div>
        <div class="info-item">
          <span class="label">Tanggal Mulai:</span>
          <span class="value"><?= date('d/m/Y', strtotime($data['tanggal_mulai'])) ?></span>
        </div>
        <div class="info-item">
          <span class="label">Tanggal Akhir:</span>
          <span class="value"><?= date('d/m/Y', strtotime($data['tanggal_akhir'])) ?></span>
        </div>
      </div>

      <div class="info-item">
        <span class="label">Tanggal Pengajuan:</span>
        <span class="value"><?= date('d/m/Y H:i', strtotime($data['tanggal_pengajuan'])) ?></span>
      </div>
    </div>

    <!-- Alasan Izin -->
    <div class="section">
      <div class="section-title">ALASAN IZIN</div>
      <p><?= htmlspecialchars($data['keterangan'] ?? 'Tidak ada alasan yang dicantumkan') ?></p>
    </div>

    <!-- Lampiran -->
    <div class="section">
      <div class="section-title">LAMPIRAN</div>
      <p>
        <?php if (!empty($data['foto_cuti_izin'])): ?>
          Surat keterangan — tersimpan di sistem (File: <?= htmlspecialchars(basename($data['foto_cuti_izin'])) ?>)
        <?php else: ?>
          Tidak ada lampiran
        <?php endif; ?>
      </p>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature-grid">
      <div class="signature-box">
        <div class="signature-name"><?= htmlspecialchars($data['nama'] ?? '-') ?></div>
        <div class="signature-role">Karyawan</div>
      </div>
      <div class="signature-box">
        <div class="signature-name">Fahras Zeilicha</div>
        <div class="signature-role">HRD</div>
      </div>
      <div class="signature-box">
        <div class="signature-name">Firmansyah</div>
        <div class="signature-role">Atasan Langsung</div>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer">
      <p>Dokumen ini dicetak secara elektronik dan tidak memerlukan tanda tangan basah</p>
      <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
    </div>
  </div>

  <script>
    // Auto print jika parameter print=1
    if (window.location.search.includes('print=1')) {
      window.print();
    }
    
    // Force print dengan styling yang proper
    function forcePrint() {
      const originalStyles = document.querySelector('style').innerHTML;
      const printStyles = `
        @page { margin: 20mm; }
        body { margin: 0; padding: 0; background: white !important; }
        .no-print { display: none !important; }
        .container { border: none !important; box-shadow: none !important; }
      `;
      
      document.querySelector('style').innerHTML = printStyles;
      window.print();
      document.querySelector('style').innerHTML = originalStyles;
    }
  </script>
</body>
</html>