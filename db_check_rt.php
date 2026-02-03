<?php
$pdo = new PDO('sqlite:var/db/db.sqlite');
$stmt = $pdo->query("SELECT * FROM recurring_transactions");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);
