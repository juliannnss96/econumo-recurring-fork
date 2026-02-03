<?php
$pdo = new PDO('sqlite:var/db/db.sqlite');
$stmt = $pdo->query("SELECT * FROM transactions ORDER BY created_at DESC LIMIT 5");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);
