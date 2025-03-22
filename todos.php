<?php
require_once 'config.php';

// Fetch all clients
$result = $mysqli->query("SELECT * FROM clientes ORDER BY id DESC");



$clientes = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($clientes);
