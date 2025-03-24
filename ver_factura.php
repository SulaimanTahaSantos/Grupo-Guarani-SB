<?php
require_once 'config.php';

require('fpdf/fpdf.php');



if (isset($_GET['factura_id']) && isset($_GET['estado'])) {
    $factura_id = $_GET['factura_id']; 
    $estado = $_GET['estado'];

    if ($estado == 'Pendiente') {
        $nuevoEstado = 'Pagada';
    } else {
        $nuevoEstado = 'Pendiente';
    }

    $updateSql = "UPDATE facturacion SET estado = '$nuevoEstado' WHERE id = $factura_id";
    $mysqli->query($updateSql);

    header('Location: ver_factura.php?id=' . $_GET['id']); 
    exit;
}

if (isset($_GET['id'])) {
    $cliente_id = $_GET['id'];  

    $cliente_sql = "SELECT nombre, domicilio, nif, poblacion, telefono FROM clientes WHERE id = $cliente_id";
    $cliente_result = $mysqli->query($cliente_sql);
    $cliente = [];

    if ($cliente_result && $cliente_result->num_rows > 0) {
        $cliente = $cliente_result->fetch_assoc();
    }

    $limit = 10; 
    $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    $offset = ($paginaActual - 1) * $limit;

    $sql = "SELECT f.*, c.nombre as cliente_nombre 
            FROM facturacion f 
            JOIN clientes c ON f.cliente_id = c.id 
            WHERE f.cliente_id = $cliente_id
            ORDER BY f.id DESC 
            LIMIT $limit OFFSET $offset";

    $result = $mysqli->query($sql);

    $totalSql = "SELECT COUNT(*) as total FROM facturacion WHERE cliente_id = $cliente_id";
    $totalResult = $mysqli->query($totalSql);
    $totalFacturas = $totalResult->fetch_assoc()['total'];
    $totalPaginas = ceil($totalFacturas / $limit);
} else {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Facturas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Facturas de <?php echo htmlspecialchars($cliente['nombre']); ?></h1>
            <a href="index.php" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-300 rounded shadow">
                Volver al inicio
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nº Factura
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Importe
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Observaciones
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Codigo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php 
                    if ($result && $result->num_rows > 0) {
                        while ($factura = $result->fetch_assoc()) {
                            $estado = isset($factura['estado']) ? $factura['estado'] : 'Pendiente';
                            $estadoClass = ($estado == 'Pagada') ? 'bg-green-500' : 'bg-yellow-500';
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($factura['id']); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-gray-100 rounded-full">
                                    <i class="fas fa-building text-gray-500"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($factura['cliente_nombre']); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="far fa-calendar-alt mr-2 text-gray-500"></i>
                                <div class="text-sm text-gray-900">
                                    <?php echo htmlspecialchars(date('d/m/Y', strtotime($factura['created_at']))); ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="fas fa-euro-sign mr-2 text-gray-500"></i>
                                <div class="text-sm text-gray-900">
                                    <?php echo number_format($factura['importe'], 2, ',', '.'); ?> €
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $estadoClass; ?> text-white">
                                <?php echo htmlspecialchars($estado); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($factura['observaciones']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($factura['codigo']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                           <a href="ver_factura.php?id=<?php echo $cliente_id; ?>&estado=<?php echo $estado; ?>&factura_id=<?php echo $factura['id']; ?>" class="text-indigo-600 hover:text-indigo-900">
                               Cambiar estado
                           </a>
                           <a href="descargar_pdf.php?factura_id=<?php echo $factura['id']; ?>" class="ml-2 text-blue-600 hover:text-blue-800">Descargar PDF</a>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                No hay facturas disponibles.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="pagination flex justify-center mt-6 gap-4">
            <?php if ($paginaActual > 1): ?>
                <a href="?id=<?= $cliente_id; ?>&pagina=<?= $paginaActual - 1; ?>" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?id=<?= $cliente_id; ?>&pagina=<?= $i; ?>" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 <?= ($i === $paginaActual) ? 'bg-blue-600' : ''; ?>">
                    <?= $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($paginaActual < $totalPaginas): ?>
                <a href="?id=<?= $cliente_id; ?>&pagina=<?= $paginaActual + 1; ?>" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800">Siguiente</a>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>
