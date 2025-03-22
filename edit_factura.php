<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

$result = $mysqli->query("SELECT * FROM facturacion WHERE id = $id");
$cliente = $result->fetch_assoc();

if (!$cliente) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $mysqli->real_escape_string($_POST['nombre']);
    $apellidos = $mysqli->real_escape_string($_POST['apellidos']);
    $nif = $mysqli->real_escape_string($_POST['nif']);
    $domicilio = $mysqli->real_escape_string($_POST['domicilio']);
    $poblacion = $mysqli->real_escape_string($_POST['poblacion']);
    $telefono = $mysqli->real_escape_string($_POST['telefono']);
    $cp = $mysqli->real_escape_string($_POST['cp']);
    $comentario = $mysqli->real_escape_string($_POST['comentario']);

    $query = "UPDATE clientes SET nombre = ?, apellidos = ?, nif = ?, domicilio = ?, poblacion = ?, telefono = ?, cp = ?, comentario = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ssssssssi", $nombre, $apellidos, $nif, $domicilio, $poblacion, $telefono, $cp, $comentario, $id);
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
    <title>Editar Cliente - GRUPO GUARANI</title>
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
                <h1 class="text-3xl font-bold">Editar cliente</h1>
            </div>
            <a href="index.php" class="bg-red-500 hover:bg-red-600 text-white text-xl font-semibold py-2 px-4 rounded shadow flex items-center">
                <i class="fa-solid fa-arrow-left mr-2"></i> Volver
            </a>
        </div>
    </header>

    <main class="container mx-auto p-4">
        <form action="edit.php?id=<?php echo $id; ?>" method="POST" class="mt-10 bg-white p-8 rounded-lg shadow-md max-w-lg mx-auto">
            <div class="mb-6">
                <label for="nombre" class="block text-gray-700 font-bold mb-2">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="apellidos" class="block text-gray-700 font-bold mb-2">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($cliente['apellidos']); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="nif" class="block text-gray-700 font-bold mb-2">NIF/NIE</label>
                <input type="text" id="nif" name="nif" value="<?php echo htmlspecialchars($cliente['nif']); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="domicilio" class="block text-gray-700 font-bold mb-2">Domicilio</label>
                <input type="text" id="domicilio" name="domicilio" value="<?php echo htmlspecialchars($cliente['domicilio']); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="poblacion" class="block text-gray-700 font-bold mb-2">Población</label>
                <input type="text" id="poblacion" name="poblacion" value="<?php echo htmlspecialchars($cliente['poblacion']); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="telefono" class="block text-gray-700 font-bold mb-2">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="cp" class="block text-gray-700 font-bold mb-2">CP</label>
                <input type="text" id="cp" name="cp" value="<?php echo htmlspecialchars($cliente['cp']); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-6">
                <label for="comentario" class="block text-gray-700 font-bold mb-2">Comentario</label>
                <input type="text" id="comentario" name="comentario" value="<?php echo htmlspecialchars($cliente['comentario']); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Actualizar Cliente
                </button>
                <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fa-solid fa-times mr-2"></i> Cancelar
                </a>
            </div>
        </form>
    </main>
</body>

</html>