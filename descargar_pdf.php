<?php
require_once 'config.php';  // Conexión a la base de datos
require('fpdf/fpdf.php');  // Incluir la librería FPDF

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

        // Crear instancia del objeto FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Establecer fuente
        $pdf->SetFont('Arial', 'B', 16);
        
        // Título
        $pdf->Cell(200, 10, 'Factura ' . $factura['id'], 0, 1, 'C');
        $pdf->Ln(10);

        // Datos del cliente
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(100, 10, 'Cliente: ' . $factura['cliente_nombre'], 0, 1);
        $pdf->Cell(100, 10, 'Domicilio: ' . $factura['domicilio'], 0, 1);
        $pdf->Cell(100, 10, 'NIF: ' . $factura['nif'], 0, 1);
        $pdf->Cell(100, 10, 'Poblacion: ' . $factura['poblacion'], 0, 1);
        $pdf->Cell(100, 10, 'Telefono: ' . $factura['telefono'], 0, 1);
        $pdf->Ln(10);
        
        // Datos de la factura
        $pdf->Cell(100, 10, 'Fecha: ' . date('d/m/Y', strtotime($factura['created_at'])), 0, 1);
        $pdf->Cell(100, 10, 'Importe: ' . number_format($factura['importe'], 2, ',', '.') . ' €', 0, 1);
        $pdf->Cell(100, 10, 'Estado: ' . $factura['estado'], 0, 1);
        $pdf->Ln(10);

        // Observaciones
        $pdf->Cell(100, 10, 'Observaciones: ' . $factura['observaciones'], 0, 1);

        // Salida del PDF
        $pdf->Output('D', 'factura_' . $factura['id'] . '.pdf');
    } else {
        echo 'Factura no encontrada.';
    }
}

// Verificar si se pasa una factura_id por GET
if (isset($_GET['factura_id'])) {
    generarPDF($_GET['factura_id']);
} else {
    echo 'Factura no encontrada.';
}
?>
