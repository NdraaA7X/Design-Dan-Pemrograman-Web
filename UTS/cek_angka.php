<?php
// Cek apakah ada data yang dikirim via metode POST
$inputDiberikan = $_POST['inputData'] ?? '';
$sudahDiperiksa = isset($_POST['submitCek']);

$isAngka       = false;
$statusHasil   = '';
$pesanDetail   = '';
$statusGanjilGenap = '';
$badgeClass    = '';

if ($sudahDiperiksa) {
    $trimmed = trim($inputDiberikan);

    if ($trimmed === '') {
        $statusHasil = 'SALAH';
        $pesanDetail = 'Input tidak boleh kosong! Harap masukkan angka atau teks.';
    } elseif (is_numeric($trimmed)) {
        $isAngka     = true;
        $statusHasil = 'BENAR';
        $numericVal  = $trimmed + 0; // Otomatis cast ke int atau float

        if (is_int($numericVal) || floor($numericVal) == $numericVal) {
            $intVal = (int) $numericVal;
            if ($intVal % 2 === 0) {
                $statusGanjilGenap = 'GENAP';
                $badgeClass        = 'badge-genap';
            } else {
                $statusGanjilGenap = 'GANJIL';
                $badgeClass        = 'badge-ganjil';
            }

            $sifat = ($intVal > 0) ? 'Positif' : (($intVal < 0) ? 'Negatif' : 'Nol (Netral)');
            $pesanDetail = "Bilangan Bulat ($sifat)";
        } else {
            $statusGanjilGenap = 'DESIMAL';
            $badgeClass        = 'badge-desimal';
            $pesanDetail       = "Bilangan Desimal / Pecahan (Aturan Ganjil/Genap hanya berlaku untuk bilangan bulat).";
        }
    } else {
        $statusHasil = 'SALAH';
        $pesanDetail = 'Input mengandung huruf, simbol, atau karakter yang bukan angka numerik valid.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengecek Angka & Ganjil Genap (PHP)</title>
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --success-bg: #dcfce7;
            --success-border: #86efac;
            --success-text: #166534;
            --error-bg: #fee2e2;
            --error-border: #fca5a5;
            --error-text: #991b1b;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #e0e7ff 0%, #f1f5f9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--text-main);
        }

        .container {
            background: #ffffff;
            max-width: 520px;
            width: 100%;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 32px;
            border: 1px solid #e2e8f0;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
        }

        .header h1 {
            font-size: 1.55rem;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 16px;
            font-size: 1.05rem;
            border: 2px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            background: #f8fafc;
        }

        input[type="text"]:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .btn-group {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        button, .btn-link {
            flex: 1;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #475569;
        }

        .result-box {
            border-radius: 12px;
            padding: 20px;
            margin-top: 10px;
            border: 1.5px solid transparent;
        }

        .result-box.valid {
            background: var(--success-bg);
            border-color: var(--success-border);
            color: var(--success-text);
        }

        .result-box.invalid {
            background: var(--error-bg);
            border-color: var(--error-border);
            color: var(--error-text);
        }

        .result-header {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px dashed rgba(0, 0, 0, 0.1);
            font-size: 0.95rem;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 0.85rem;
            color: white;
        }

        .badge-genap { background: #3b82f6; }
        .badge-ganjil { background: #f97316; }
        .badge-desimal { background: #8b5cf6; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>🔍 Pengecek Angka & Ganjil Genap (PHP)</h1>
        <p>Memvalidasi input numerik dan klasifikasi ganjil / genap via PHP server-side.</p>
    </div>

    <form method="post" action="cek_angka.php">
        <div class="form-group">
            <label for="inputData">Masukkan Data / Nilai:</label>
            <input type="text" id="inputData" name="inputData" 
                   value="<?php echo htmlspecialchars($inputDiberikan, ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="Contoh: 14, 25, 3.14, atau abc" autofocus required>
        </div>

        <div class="btn-group">
            <button type="submit" name="submitCek" value="1" class="btn-primary">Periksa Sekarang</button>
            <a href="cek_angka.php" class="btn-link btn-secondary">Reset</a>
        </div>
    </form>

    <?php if ($sudahDiperiksa): ?>
        <?php if ($isAngka): ?>
            <div class="result-box valid">
                <div class="result-header">
                    <span>✅</span>
                    <span>HASIL: BENAR (ANGKA)</span>
                </div>
                <div class="result-details">
                    <div class="detail-item">
                        <span>Input Diterima:</span>
                        <strong><?php echo htmlspecialchars($inputDiberikan, ENT_QUOTES, 'UTF-8'); ?></strong>
                    </div>
                    <div class="detail-item">
                        <span>Status Input:</span>
                        <strong><span style="color:#166534;">BENAR (Valid Angka)</span></strong>
                    </div>
                    <div class="detail-item">
                        <span>Kategori:</span>
                        <strong><?php echo htmlspecialchars($pesanDetail, ENT_QUOTES, 'UTF-8'); ?></strong>
                    </div>
                    <?php if ($statusGanjilGenap !== 'DESIMAL'): ?>
                    <div class="detail-item">
                        <span>Klasifikasi:</span>
                        <span><span class="badge <?php echo $badgeClass; ?>"><?php echo $statusGanjilGenap; ?></span></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="result-box invalid">
                <div class="result-header">
                    <span>❌</span>
                    <span>HASIL: SALAH (BUKAN ANGKA)</span>
                </div>
                <div class="result-details">
                    <div class="detail-item">
                        <span>Input Diberikan:</span>
                        <strong><?php echo htmlspecialchars($inputDiberikan, ENT_QUOTES, 'UTF-8'); ?></strong>
                    </div>
                    <div class="detail-item">
                        <span>Status Input:</span>
                        <strong><span style="color:#991b1b;">SALAH</span></strong>
                    </div>
                    <div style="margin-top: 10px; font-size: 0.9rem;">
                        <?php echo htmlspecialchars($pesanDetail, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
