<?php
// Asegúrate de haber instalado Dompdf con Composer
require_once 'vendor/autoload.php';
require_once 'config.php';  // Conexión a la base de datos

use Dompdf\Dompdf;
use Dompdf\Options;

// Función para generar el PDF
function generarPDF($factura_id) {
    global $mysqli;

    // Obtener los datos de la factura
    $factura_sql = "SELECT f.*, c.nombre as cliente_nombre, c.domicilio, c.nif, c.poblacion, c.telefono
                    FROM facturacion f
                    JOIN clientes c ON f.cliente_id = c.id
                    WHERE f.id = $factura_id";
    $factura_result = $mysqli->query($factura_sql);
    
    if ($factura_result && $factura_result->num_rows > 0) {
        $factura = $factura_result->fetch_assoc();

        // Configurar las opciones de Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);  // Habilitar el parseador HTML5
        $options->set('isPhpEnabled', true);          // Habilitar PHP dentro del HTML
        $options->set('isRemoteEnabled', true);       // Permitir imágenes remotas

        // Crear una instancia de Dompdf
        $dompdf = new Dompdf($options);

        // Formatear la fecha
        $fecha = date('d/m/Y', strtotime($factura['created_at']));
        
        // HTML con CSS y datos dinámicos
        $html = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Factura - Grupo Guarani</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    font-size: 12px;
                }
                .container {
                    width: 100%;
                    max-width: 800px;
                    margin: 0 auto;
                    border: 1px solid #000;
                }
                .header {
                    display: flex;
                    justify-content: space-between;
                    padding: 10px;
                    border-bottom: 1px solid #000;
                    min-height: 80px; /* Altura fija para el encabezado */
                }
                .logo-section {
                    display: flex;
                    flex-direction: column;
                }
                .logo-title {
                    font-size: 24px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .logo-subtitle {
                    font-size: 10px;
                    background-color: #333;
                    color: white;
                    padding: 2px 5px;
                    width: fit-content;
                }
                .contact-info {
                    text-align: right;
                    font-size: 10px;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                }
                .contact-info a {
                    color: blue;
                    text-decoration: none;
                }
                .invoice-info {
                    display: flex;
                    justify-content: space-between;
                    padding: 10px;
                    border-bottom: 1px solid #000;
                    height: 20px; /* Altura fija */
                    align-items: center;
                }
                .date {
                    background-color: #d9e1f2;
                    padding: 5px 10px;
                    width: 40%;
                    text-align: center;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .invoice-number {
                    width: 40%;
                    text-align: center;
                    height: 100%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .nif-row {
                    border-bottom: 1px solid #000;
                    padding: 5px 10px;
                    text-align: right;
                    height: 20px; /* Altura fija */
                    display: flex;
                    align-items: center;
                    justify-content: flex-end;
                }
                .customer-info {
                    display: flex;
                    border-bottom: 1px solid #000;
                    height: 25px; /* Altura fija */
                }
                .customer-info div {
                    padding: 5px 10px;
                    flex: 1;
                    border-right: 1px solid #000;
                    display: flex;
                    align-items: center;
                }
                .customer-info div:last-child {
                    border-right: none;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                th, td {
                    border: 1px solid #000;
                    padding: 5px;
                    text-align: center;
                    height: 25px; /* Altura fija para todas las celdas */
                }
                th {
                    background-color: #f2f2f2;
                }
                .concept-header {
                    text-align: center;
                    font-weight: bold;
                    background-color: #f2f2f2;
                }
                .amount-cell {
                    text-align: right;
                }
                .observations {
                    padding: 5px 10px;
                    border-bottom: 1px solid #000;
                }
                .observations-label {
                    font-weight: bold;
                    height: 25px; /* Altura fija */
                    display: flex;
                    align-items: center;
                }
                .observations-content {
                    height: 50px; /* Altura fija para el contenido de observaciones */
                }
                .consignee-section {
                    display: flex;
                    border-bottom: 1px solid #000;
                }
                .consignee-left {
                    width: 50%;
                    border-right: 1px solid #000;
                }
                .consignee-right {
                    width: 50%;
                }
                .consignee-row {
                    display: flex;
                    border-bottom: 1px solid #000;
                    height: 25px; /* Altura fija */
                }
                .consignee-row:last-child {
                    border-bottom: none;
                }
                .consignee-label {
                    width: 30%;
                    padding: 5px;
                    border-right: 1px solid #000;
                    font-weight: bold;
                    display: flex;
                    align-items: center;
                }
                .consignee-value {
                    width: 70%;
                    padding: 5px;
                    display: flex;
                    align-items: center;
                }
                .remission-note {
                    text-align: right;
                    padding: 5px;
                    font-weight: bold;
                    border-bottom: 1px solid #000;
                    height: 25px; /* Altura fija */
                    display: flex;
                    align-items: center;
                    justify-content: flex-end;
                }
                .paddingTop{
                    padding-top: 50px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <!-- Header Section -->
                <div class="header">
                    <div class="logo-section">
                        <div class="logo-title">GRUPO GUARANI</div>
                        <div class="logo-subtitle">Tu encomienda directo a Paraguay</div>
                        <div style="margin-top: 10px;">NIF: 27636455Q</div>
                    </div>
                    <div class="contact-info">
                        <div>Tel. Oficina: 931844501</div>
                        <div>Móviles: 622817045</div>
                        <div>Móviles: 665319693</div>
                        <div><a href="mailto:grupoguarani5@gmail.com">grupoguarani5@gmail.com</a></div>
                    </div>
                </div>

                <!-- Invoice Info Section -->
                <div class="invoice-info">
                    <div class="date">FECHA: ' . $fecha . '</div>
                    <div class="invoice-number">FACTURA:' . $factura['id'] . '</div>
                </div>

                <!-- NIF Row -->
                <div class="nif-row">
                    NIF: ' . $factura['nif'] . '
                </div>

                <!-- Customer Info -->
                <div class="customer-info">
                    <div>Domicilio: ' . $factura['domicilio'] . '</div>
                    <div>Población: ' . $factura['poblacion'] . '</div>
                    <div>Teléfono: ' . $factura['telefono'] . '</div>
                </div>

                <!-- Invoice Table -->
                <table class="paddingTop">
                    <tr>
                        <th>CANTIDAD</th>
                        <th>CODIGO</th>
                        <th colspan="3" class="concept-header">CONCEPTO</th>
                        <th>PRECIO</th>
                        <th>IMPORTE</th>
                    </tr>';

        // Obtener los detalles de la factura (líneas de factura)
        $detalles_sql = "SELECT * FROM facturacion WHERE id = $factura_id";
        $detalles_result = $mysqli->query($detalles_sql);
        
        if ($detalles_result && $detalles_result->num_rows > 0) {
            while ($detalle = $detalles_result->fetch_assoc()) {
                $html .= '
                    <tr>
                        <td>' . $detalle['cantidad'] . '</td>
                        <td>' . $detalle['codigo'] . '</td>
                        <td colspan="3">' . $detalle['concepto'] . '</td>
                        <td>' . number_format($detalle['precio'], 2, ',', '.') . ' €</td>
                        <td class="amount-cell">' . number_format($detalle['importe'], 2, ',', '.') . ' €</td>
                    </tr>';
            }
        } else {
            // Si no hay detalles, mostrar filas vacías
            for ($i = 0; $i < 9; $i++) {
                $html .= '
                    <tr>
                        <td></td>
                        <td></td>
                        <td colspan="3"></td>
                        <td></td>
                        <td class="amount-cell">' . ($i < 5 ? '' : '0,00 €') . '</td>
                    </tr>';
            }
        }

        $html .= '
                </table>

                <!-- Observations Section -->
                <div class="observations">
                    <div class="observations-label">OBSERVACIONES:</div>
                    <div class="observations-content">' . $factura['observaciones'] . '</div>
                </div>

                <!-- Consignee Section -->
                <div class="consignee-section">
                    <div class="consignee-left">
                        <div class="consignee-row">
                            <div class="consignee-label">CONSIGNATARIO:</div>
                            <div class="consignee-value">' . ($factura['consignatario'] ?? '') . '</div>
                        </div>
                        <div class="consignee-row">
                            <div class="consignee-label">DIRECCION:</div>
                            <div class="consignee-value">' . ($factura['direccion_entrega'] ?? '') . '</div>
                        </div>
                        <div class="consignee-row">
                            <div class="consignee-label">TELEFONO:</div>
                            <div class="consignee-value">' . ($factura['telefono_consignatario'] ?? '') . '</div>
                        </div>
                    </div>
                    <div class="consignee-right">
                        <div class="remission-note">NOTA DE REMISION: ' . ($factura['nota_remision'] ?? '') . '</div>
                        <div class="consignee-row">
                            <div class="consignee-label">FCH DE ENTREGA:</div>
                            <div class="consignee-value">' . (isset($factura['fecha_entrega']) ? date('d/m/Y', strtotime($factura['fecha_entrega'])) : '') . '</div>
                        </div>
                        <div class="consignee-row">
                            <div class="consignee-label">CIUDAD:</div>
                            <div class="consignee-value">' . ($factura['ciudad_entrega'] ?? '') . '</div>
                        </div>
                        <div class="consignee-row">
                            <div class="consignee-label">DNI:</div>
                            <div class="consignee-value">' . ($factura['dni_consignatario'] ?? '') . '</div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>';

        // Cargar el HTML
        $dompdf->loadHtml($html);

        // Definir el tamaño de página
        $dompdf->setPaper('A4', 'portrait');  // Tamaño A4 y orientación vertical

        // Renderizar el PDF
        $dompdf->render();

        // Enviar el PDF generado al navegador para descargarlo
        $dompdf->stream("factura_" . $factura['id'] . ".pdf", array("Attachment" => 0)); // 'Attachment' => 0 para abrir en navegador
    } else {
        echo 'Factura no encontrada.';
    }
}

// Verificar si se pasa una factura_id por GET
if (isset($_GET['factura_id'])) {
    generarPDF($_GET['factura_id']);
} else {
    echo 'Factura no encontrada. Por favor, especifique un ID de factura.';
}
?>