<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cantidad = $mysqli->real_escape_string($_POST['cantidad']);
    $codigo = $mysqli->real_escape_string($_POST['codigo']);
    $concepto = $mysqli->real_escape_string($_POST['concepto']);
    $precio = $mysqli->real_escape_string($_POST['precio']);
    $importe = $mysqli->real_escape_string($_POST['importe']);
    $observaciones = $mysqli->real_escape_string($_POST['observaciones']);
    $cliente_id = $mysqli->real_escape_string($_POST['cliente_id']);

    $query = "INSERT INTO facturacion (cantidad, codigo, concepto, precio, importe, observaciones, cliente_id) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("isssdsi", $cantidad, $codigo, $concepto, $precio, $importe, $observaciones, $cliente_id);
    $stmt->execute();

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Añadir Facturación - GRUPO GUARANI</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/0787d9ec00.js" crossorigin="anonymous"></script>
    <!-- Font family Manrope -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">

</head>

<body class="bg-gray-100 h-[100vh]">
    <!-- Encabezado -->
    <header class="bg-white text-[#800000] py-4 shadow">
        <div class="container mx-auto flex justify-between items-center px-4">
            <div class="flex items-center">
                <img src="assets/guarani-logo.png" alt="GRUPO GUARANI" class="w-[15%] mr-3">
                <h1 class="text-3xl font-bold">Datos de la nueva facturación</h1>
            </div>
            <a href="index.php"
                class="bg-red-500 hover:bg-red-600 text-white text-xl font-semibold py-2 px-4 rounded shadow flex items-center">
                <i class="fa-solid fa-arrow-left mr-2"></i> Volver
            </a>
        </div>
    </header>

    <main class="container mx-auto p-4 h-[100%]">
        <form action="add_facturacion.php" method="POST" class="mt-10 bg-white p-8 rounded-lg shadow-md max-w-lg mx-auto">
            <div class="mb-6">
                <label for="cantidad" class="block text-gray-700 font-bold mb-2">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="codigo" class="block text-gray-700 font-bold mb-2">Código</label>
                <input type="text" id="codigo" name="codigo" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="concepto" class="block text-gray-700 font-bold mb-2">Concepto</label>
                <input type="text" id="concepto" name="concepto" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="precio" class="block text-gray-700 font-bold mb-2">Precio</label>
                <input type="number" step="0.01" id="precio" name="precio" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="importe" class="block text-gray-700 font-bold mb-2">Importe</label>
                <input type="number" step="0.01" id="importe" name="importe" required readonly
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="observaciones" class="block text-gray-700 font-bold mb-2">Observaciones</label>
                <textarea id="observaciones" name="observaciones"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="mb-6">
                <label for="cliente_id" class="block text-gray-700 font-bold mb-2">Cliente</label>
                <select id="cliente_id" name="cliente_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php
                    // Obtén la lista de clientes desde la base de datos
                    $result = $mysqli->query("SELECT id, nombre, apellidos FROM clientes");
                    while ($cliente = $result->fetch_assoc()) {
                        echo "<option value='" . $cliente['id'] . "'>" . $cliente['nombre'] . " " . $cliente['apellidos'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="flex justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar Facturación
                </button>
            </div>
        </form>
    </main>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const cantidadInput = document.getElementById("cantidad");
        const precioInput = document.getElementById("precio");
        const importeInput = document.getElementById("importe");

        function calcularImporte() {
            const cantidad = parseFloat(cantidadInput.value) || 0;
            const precio = parseFloat(precioInput.value) || 0;
            importeInput.value = (cantidad * precio).toFixed(2);
        }

        cantidadInput.addEventListener("input", calcularImporte);
        precioInput.addEventListener("input", calcularImporte);
    });
</script>

</body>

</html>
