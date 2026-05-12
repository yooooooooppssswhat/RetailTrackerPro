<?php
/**
 * Database Connection
 * 
 * Connects to MySQL database using mysqli.
 * The db() function returns the connection.
 */

$conn = null;

function db()
{
    global $conn;
    if ($conn !== null) {
        return $conn;
    }

    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'retail_tracker_pro';

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die('Database connection failed: ' . $conn->connect_error);
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}

/**
 * Run a prepared query with parameters
 * 
 * Usage:
 *   SELECT: $result = db_query("SELECT * FROM users WHERE username = ?", "s", [$username]);
 *   INSERT: db_query("INSERT INTO products (name, price) VALUES (?, ?)", "sd", [$name, $price]);
 * 
 * Types: s = string, i = integer, d = double/decimal
 */
function db_query($sql, $types = '', $params = [])
{
    if ($types === '' || empty($params)) {
        $result = db()->query($sql);
        if ($result === false) {
            die('Query error: ' . db()->error);
        }
        return $result;
    }

    $stmt = db()->prepare($sql);
    if (!$stmt) {
        die('Query error: ' . db()->error);
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result ?: $stmt;
}