<?php
/**
 * Database Connection
 * 
 * Connects to MySQL database using mysqli.
 * The db() function returns the connection.
 */

$conn = null;

function db() {
    global $conn;

    // If already connected, return existing connection
    if ($conn !== null) {
        return $conn;
    }

    // Database settings
    $host     = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'order_tracker';

    // Connect to MySQL
    $conn = new mysqli($host, $username, $password, $database);

    // Check for connection error
    if ($conn->connect_error) {
        die('Database connection failed: ' . $conn->connect_error);
    }

    // Set character set to UTF-8
    $conn->set_charset('utf8mb4');

    return $conn;
}

/**
 * Run a prepared query with parameters
 * 
 * Usage:
 *   SELECT: $result = db_query("SELECT * FROM users WHERE username = ?", "s", [$username]);
 *           $user = $result->fetch_assoc();
 * 
 *   INSERT: db_query("INSERT INTO products (name, price) VALUES (?, ?)", "sd", [$name, $price]);
 * 
 * Types: s = string, i = integer, d = double/decimal
 */
function db_query($sql, $types = '', $params = []) {
    // If no parameters, run a simple query
    if ($types === '' || empty($params)) {
        $result = db()->query($sql);
        if ($result === false) {
            die('Query error: ' . db()->error);
        }
        return $result;
    }

    // Prepare the statement
    $stmt = db()->prepare($sql);
    if (!$stmt) {
        die('Query error: ' . db()->error);
    }

    // Bind the parameters and execute
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    // Return result set for SELECT queries, or the statement for INSERT/UPDATE/DELETE
    $result = $stmt->get_result();
    return $result ?: $stmt;
}