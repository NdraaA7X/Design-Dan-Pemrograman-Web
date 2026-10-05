<?php
// includes/helpers.php
// Fungsi helper untuk keamanan output (mencegah XSS)

function e($value)
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}
