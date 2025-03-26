<?php
require_once 'config.php';
require_once 'auth.php';
requireLogin();

$clientesPorPagina = 10; 
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $clientesPorPagina; 

$result = $mysqli->query("SELECT * FROM clientes ORDER BY id DESC LIMIT $offset, $clientesPorPagina");
$clientes = $result->fetch_all(MYSQLI_ASSOC);

$resultTotal = $mysqli->query("SELECT COUNT(*) AS total FROM clientes");
$totalClientes = $resultTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalClientes / $clientesPorPagina); 

$result2 = $mysqli->query("SELECT * FROM facturacion ORDER BY id DESC");
$facturacion = $result2->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GRUPO GUARANI - Gestión de Clientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/0787d9ec00.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <nav class="bg-gray-700 shadow-sm">
        <div class="container mx-auto px-3">
            <div class="flex justify-between items-center py-3">
                <div class="flex items-center">
                    <span class="text-white text-xl font-bold">
                        Bienvenido/a, <?php echo htmlspecialchars($_SESSION['username']); ?>👋
                    </span>
                </div>
                <div>
                    <a href="logout.php" class="text-red-600 hover:text-red-800">
                        <button class="btn d-flex items-center bg-red-800 text-white rounded-lg">
                            <i class="p-3 fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <header class="bg-white text-[#800000] py-4">
        <div class="container mx-auto flex justify-between flex-wrap items-center px-4">
            <div class="flex items-center">
                <img src="assets/guarani-logo.png" alt="GRUPO GUARANI" class="w-[20%] mr-3">
                <h1 class="text-3xl font-bold ms-3">Gestión de clientes</h1>
            </div>
            <a href="add.php" class="bg-white text-xl text-green-600 my-10 hover:bg-green-50 transition-colors font-semibold py-2 px-4 rounded shadow flex items-center">
                <i class="fa-solid px-3 fa-user-plus"></i> Añadir cliente
            </a>
            <a href="add_facturacion.php" class="bg-white text-xl text-green-600 my-10 hover:bg-green-50 transition-colors font-semibold py-2 px-4 rounded shadow flex items-center">
                <i class="fa-solid px-3 fa-file-invoice "></i> Añadir Facturacion
            </a>
        </div>
    </header>

    <main class="container mx-auto p-4 bg-gray-300 min-h-screen">
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center">
            <div class="w-full sm:w-1/2 mb-4 sm:mb-0">
                <input type="text" id="searchInput" placeholder="Buscar clientes..."
                    class="w-full px-4 py-2 border-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Tabla para desktop (oculta en móvil) -->
        <div class="hidden md:block overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white table-hover">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left font-semibold">Nombre</th>
                        <th class="py-3 px-4 text-left font-semibold">Apellidos</th>
                        <th class="py-3 px-4 text-left font-semibold">NIF/NIE</th>
                        <th class="py-3 px-4 text-left font-semibold">Domicilio</th>
                        <th class="py-3 px-4 text-left font-semibold">Población</th>
                        <th class="py-3 px-4 text-left font-semibold">Teléfono</th>
                        <th class="py-3 px-4 text-left font-semibold">CP</th>
                        <th class="py-3 px-4 text-left font-semibold">Comentario</th>
                        <th class="py-3 px-4 text-left font-semibold">Facturas</th>
                        <th class="py-3 px-4 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody id="clientesTableBody">
                    <?php foreach ($clientes as $cliente): ?>
                        <tr class="border-b transition duration-200 ease-in-out hover:bg-gray-50">
                            <td class="py-3 px-4"><?= ($cliente['nombre']); ?></td>
                            <td class="py-3 px-4"><?= ($cliente['apellidos']); ?></td>
                            <td class="py-3 px-4"><?= ($cliente['nif']); ?></td>
                            <td class="py-3 px-4"><?= ($cliente['domicilio']); ?></td>
                            <td class="py-3 px-4"><?= ($cliente['poblacion']); ?></td>
                            <td class="py-3 px-4"><?= ($cliente['telefono']); ?></td>
                            <td class="py-3 px-4"><?= ($cliente['cp']); ?></td>
                            <td class="py-3 px-4">
                                <?php echo !empty($cliente['comentario']) ? ($cliente['comentario']) : '-'; ?>
                            </td>
                             
                            <?php if (!empty($facturacion) && is_array($facturacion)) { ?>
                                <td>
                                    <a href="ver_factura.php?id=<?php echo htmlspecialchars($cliente['id']); ?>" class="inline-block text-green-500 hover:text-green-700 mr-2" title="Ver factura">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="delete_facturacion.php?id=<?php echo htmlspecialchars($cliente['id']); ?>" class="inline-block text-red-500 hover:text-red-700" title="Eliminar factura"
                                        onclick="return confirm('¿Estás seguro de que quieres eliminar esta factura?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            <?php } else { ?>
                                <td>No hay facturas disponibles</td>
                            <?php } ?>

                            <td class="py-3 px-4 actions text-center flex justify-center">
                                <a href="edit.php?id=<?php echo $cliente['id']; ?>" class="inline-block text-blue-500 hover:text-blue-900 mr-2" title="Editar">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="delete.php?id=<?php echo $cliente['id']; ?>" class="inline-block text-red-500 hover:text-red-900" title="Eliminar"
                                    onclick="return confirm('¿Estás seguro de que quieres eliminar este cliente?');">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Cards para móvil (ocultas en desktop) -->
        <div class="md:hidden grid gap-4" id="clientesCards">
            <?php foreach ($clientes as $cliente): ?>
                <div class="bg-white rounded-lg shadow-md p-4 transition duration-200 ease-in-out hover:bg-gray-50">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-bold text-lg"><?= ($cliente['nombre']); ?> <?= ($cliente['apellidos']); ?></h3>
                            <p class="text-gray-600"><?= ($cliente['nif']); ?></p>
                        </div>
                        <div class="flex gap-2">
                            <a href="edit.php?id=<?php echo $cliente['id']; ?>" class="text-blue-500 hover:text-blue-900" title="Editar">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="delete.php?id=<?php echo $cliente['id']; ?>" class="text-red-500 hover:text-red-900" title="Eliminar"
                                onclick="return confirm('¿Estás seguro de que quieres eliminar este cliente?');">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    <div>
                        <p><strong>Domicilio:</strong> <?= ($cliente['domicilio']); ?></p>
                        <p><strong>Población:</strong> <?= ($cliente['poblacion']); ?></p>
                        <p><strong>Teléfono:</strong> <?= ($cliente['telefono']); ?></p>
                        <p><strong>Comentario:</strong> <?= !empty($cliente['comentario']) ? ($cliente['comentario']) : '-'; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Paginación -->
        <div class="pagination flex justify-center mt-6 gap-4">
            <?php if ($paginaActual > 1): ?>
                <a href="?pagina=<?= $paginaActual - 1; ?>" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?pagina=<?= $i; ?>" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 <?= ($i === $paginaActual) ? 'bg-blue-600' : ''; ?>">
                    <?= $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($paginaActual < $totalPaginas): ?>
                <a href="?pagina=<?= $paginaActual + 1; ?>" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800">Siguiente</a>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // Filtro de búsqueda
        document.getElementById('searchInput').addEventListener('input', function () {
            let searchValue = this.value.toLowerCase();
            let rows = document.querySelectorAll('#clientesTableBody tr');
            rows.forEach(function (row) {
                let cells = row.getElementsByTagName('td');
                let match = false;
                for (let i = 0; i < cells.length; i++) {
                    if (cells[i].textContent.toLowerCase().includes(searchValue)) {
                        match = true;
                        break;
                    }
                }
                row.style.display = match ? '' : 'none';
            });
        });
    </script>
</body>

</html>
