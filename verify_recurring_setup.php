<?php
$pdo = new PDO('sqlite:var/db/db.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get User
$stmt = $pdo->query("SELECT id FROM users LIMIT 1");
$userId = $stmt->fetchColumn();

if (!$userId) {
    echo "No user found. Please create a user first.\n";
    exit(1);
}

// Get Account
$stmt = $pdo->query("SELECT id FROM accounts WHERE user_id = '$userId' LIMIT 1");
$accountId = $stmt->fetchColumn();

if (!$accountId) {
    echo "No account found for user. Please create an account first.\n";
    exit(1);
}

// UUID generation (simple version)
function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

$rtId = gen_uuid();
$amount = "100.00000000"; // Decimal format
$createdAt = date('Y-m-d H:i:s');
$nextRunDate = date('Y-m-d H:i:s', strtotime('-1 day')); // Due yesterday

$sql = "INSERT INTO recurring_transactions 
(id, type, amount, description, interval, next_run_date, is_active, created_at, updated_at, user_id, account_id) 
VALUES 
(:id, :type, :amount, :description, :interval, :nextRun, 1, :created, :updated, :user, :account)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $rtId,
    ':type' => 0, // Expense
    ':amount' => $amount,
    ':description' => 'Netflix Subscription',
    ':interval' => 'monthly',
    ':nextRun' => $nextRunDate,
    ':created' => $createdAt,
    ':updated' => $createdAt,
    ':user' => $userId,
    ':account' => $accountId
]);

echo "Created Recurring Transaction: $rtId\n";
