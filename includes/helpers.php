<?php
/**
 * Helper Functions - Simple utilities used across the project
 */

// --- OUTPUT SECURITY ---
function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function sanitize_input($value)
{
    if ($value === null)
        return '';
    return trim(strip_tags((string) $value));
}

// --- REDIRECT ---
function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

// --- FLASH MESSAGES ---
function flash($type, $message)
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function display_flash()
{
    if (empty($_SESSION['flash']))
        return;
    foreach ($_SESSION['flash'] as $alert) {
        $class = $alert['type'] === 'success' ? 'alert-success' : 'alert-danger';
        $icon = $alert['type'] === 'success' ? 'fa-circle-check' : 'fa-circle-xmark';
        echo '<div class="alert ' . $class . '"><i class="fa-solid ' . $icon . '"></i> ' . e($alert['message']) .
            '<button class="alert-close" onclick="this.parentElement.remove()">&times;</button></div>';
    }
    unset($_SESSION['flash']);
}

// --- FORMATTING ---
function format_currency($amount)
{
    return '₱' . number_format((float) $amount, 2);
}

function format_date($value, $format = 'M d, Y')
{
    if (!$value)
        return '—';
    $ts = strtotime($value);
    return $ts ? date($format, $ts) : '—';
}

function format_datetime($value)
{
    if (!$value)
        return '—';
    $ts = strtotime($value);
    return $ts ? date('M d, Y h:i A', $ts) : '—';
}

function time_ago($datetime)
{
    $ts = strtotime($datetime);
    if (!$ts)
        return '—';
    $diff = time() - $ts;
    if ($diff < 60)
        return 'just now';
    if ($diff < 3600)
        return floor($diff / 60) . 'm ago';
    if ($diff < 86400)
        return floor($diff / 3600) . 'h ago';
    return date('M d', $ts);
}

// --- ORDER NUMBER ---
function generate_order_number()
{
    $year = date('Y');
    $result = db_query('SELECT COUNT(*) AS total FROM orders WHERE YEAR(order_date) = ?', 'i', [$year]);
    $row = $result->fetch_assoc();
    $count = $row['total'] ?? 0;
    return 'ORD-' . $year . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
}
