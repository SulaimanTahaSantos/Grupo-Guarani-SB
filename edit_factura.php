<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

$result = $mysqli->query("SELECT * FROM facturacion WHERE id = $id");
$factura = $result->fetch_assoc();

if (!$factura) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cantidad = $mysqli->real_escape_string($_POST['cantidad']);
    $codigo = $mysqli->real_escape_string($_POST['codigo']);
    $concepto = $mysqli->real_escape_string($_POST['concepto']);
    $precio = $mysqli->real_escape_string($_POST['precio']);
    $importe = $mysqli->real_escape_string($_POST['importe']);
    $observaciones = $mysqli->real_escape_string($_POST['observaciones']);

    $query = "UPDATE facturacion SET cantidad = ?, codigo = ?, concepto = ?, precio = ?, importe = ?, observaciones = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("issddsi", $cantidad, $codigo, $concepto, $precio, $importe, $observaciones, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: edit_factura.php?id=$id");
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Factura - GRUPO GUARANI</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/0787d9ec00.js" crossorigin="anonymous"></script>
    <!-- Fuente Manrope -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <!-- Encabezado -->
    <header class="bg-white text-[#800000] py-4 shadow">
        <div class="container mx-auto flex justify-between items-center px-4">
            <div class="flex items-center">
                <img src="assets/guarani-logo.png" alt="GRUPO GUARANI" class="w-[15%] mr-3">
                <h1 class="text-3xl font-bold">Editar factura</h1>
            </div>
            <a href="index.php" class="bg-red-500 hover:bg-red-600 text-white text-xl font-semibold py-2 px-4 rounded shadow flex items-center">
                <i class="fa-solid fa-arrow-left mr-2"></i> Volver
            </a>
        </div>
    </header>

    <main class="container mx-auto p-4">
        <form action="edit_factura.php?id=<?php echo $id; ?>" method="POST" class="mt-10 bg-white p-8 rounded-lg shadow-md max-w-lg mx-auto">
            <div class="mb-6">
                <label for="cantidad" class="block text-gray-700 font-bold mb-2">Cantidad</label>
                <input type="text" id="cantidad" name="cantidad" value=" <?php echo htmlspecialchars($factura['cantidad']); ?> " required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="codigo" class="block text-gray-700 font-bold mb-2">Codigo</label>
                <input type="text" id="codigo" name="codigo" value="<?php echo htmlspecialchars($factura['codigo']); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="concepto" class="block text-gray-700 font-bold mb-2">Concepto</label>
                <input type="text" id="concepto" name="concepto" value="<?php echo htmlspecialchars($factura['concepto']); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="precio" class="block text-gray-700 font-bold mb-2">Precio</label>
                <input type="text" id="precio" name="precio" value="<?php echo htmlspecialchars($factura['precio']); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="importe" class="block text-gray-700 font-bold mb-2">Importe</label>
                <input type="text" id="importe" name="importe" value="<?php echo htmlspecialchars($factura['importe']); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="observaciones" class="block text-gray-700 font-bold mb-2">Observaciones</label>
                <input type="text" id="observaciones" name="observaciones" value="<?php echo htmlspecialchars($factura['observaciones']); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Actualizar Factura
                </button>
                <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fa-solid fa-times mr-2"></i> Cancelar
                </a>
            </div>
        </form>
    </main>
</body>

</html>