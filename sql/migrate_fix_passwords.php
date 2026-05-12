<?php
/**
 * Password Setup - Run once to set user passwords
 * URL: http://localhost/RetailTrackerPro/sql/migrate_fix_passwords.php
 */
require_once __DIR__ . '/../includes/bootstrap.php';

echo "<pre>\n";
echo "=== RetailTracker Pro: Password Setup ===\n\n";

$passwords = [
    'admin' => 'Admin@2026',
    'manager' => 'Manager@2026',
    'cashier1' => 'Staff@2026',
    'inventory1' => 'Staff@2026',
];

$updated = 0;

foreach ($passwords as $username => $password) {
    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = db()->prepare('UPDATE users SET password_hash = ? WHERE username = ?');
    $stmt->bind_param('ss', $hash, $username);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "✓ {$username} → password set to: {$password}\n";
        $updated++;
    } else {
        echo "⚠ {$username} → user not found, skipped\n";
    }
}

echo "\n✓ Done! Updated {$updated} user(s).\n";
echo "\nYou can now login with:\n";
echo "  Username: admin\n";
echo "  Password: Admin@2026\n";
echo "</pre>\n";
