<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Debug Session</title>
    <style>
        body { font-family: monospace; padding: 2rem; background: #1e1e1e; color: #d4d4d4; }
        pre { background: #252526; padding: 1.5rem; border-radius: 6px; overflow-x: auto; }
        h1 { color: #9cdcfe; margin-bottom: 1rem; }
        .btn { display: inline-block; margin-top: 1rem; padding: 0.5rem 1rem;
               background: #d9534f; color: #fff; text-decoration: none;
               border-radius: 4px; font-family: monospace; }
    </style>
</head>
<body>
    <h1>Debug $_SESSION</h1>
    <pre><?php print_r($_SESSION); ?></pre>
    <a href="reset_session.php" class="btn">Reset / Hapus Semua Data</a>
</body>
</html>
