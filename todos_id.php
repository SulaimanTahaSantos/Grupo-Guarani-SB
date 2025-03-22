<?php
require_once 'config.php';

// Fetch all clients
$result = $mysqli->query("SELECT * FROM clientes WHERE id = '2'");

$clientes = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($clientes);
