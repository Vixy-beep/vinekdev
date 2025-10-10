<?php
// Solución simple y confiable sin dependencias externas
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener datos del formulario
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $company = isset($_POST['company']) ? trim($_POST['company']) : 'No especificada';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : 'No especificado';
    $service = isset($_POST['service']) ? trim($_POST['service']) : 'No especificado';
    $budget = isset($_POST['budget']) ? trim($_POST['budget']) : 'No especificado';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Validaciones básicas
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Por favor completa los campos obligatorios: Nombre, Email y Mensaje.'
        ]);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Por favor ingresa un email válido.'
        ]);
        exit;
    }
    
    // Preparar el email
    $to = "info@vineksec.online";
    $subject = "Nuevo contacto desde VinekDev - " . $name;
    
    $email_body = "🚀 NUEVO CONTACTO DESDE VINEKDEV\n";
    $email_body .= "=====================================\n\n";
    $email_body .= "👤 Nombre: $name\n";
    $email_body .= "🏢 Empresa: $company\n";
    $email_body .= "📧 Email: $email\n";
    $email_body .= "📱 Teléfono: $phone\n";
    $email_body .= "🛠️ Servicio de Interés: $service\n";
    $email_body .= "💰 Presupuesto Estimado: $budget\n\n";
    $email_body .= "💬 Mensaje:\n";
    $email_body .= "--------------------\n";
    $email_body .= "$message\n\n";
    $email_body .= "📅 Fecha: " . date('Y-m-d H:i:s') . "\n";
    $email_body .= "🌐 Enviado desde: VinekDev.com\n";
    $email_body .= "📧 Responder a: $email";
    
    // Headers del email
    $headers = array(
        'From' => 'noreply@vineksec.online',
        'Reply-To' => $email,
        'Return-Path' => 'info@vineksec.online',
        'Content-Type' => 'text/plain; charset=UTF-8',
        'X-Mailer' => 'PHP/' . phpversion()
    );
    
    $header_string = "";
    foreach ($headers as $key => $value) {
        $header_string .= "$key: $value\r\n";
    }
    
    // Intentar enviar el email
    if (mail($to, $subject, $email_body, $header_string)) {
        echo json_encode([
            'status' => 'success', 
            'message' => '¡Mensaje enviado con éxito! Te contactaremos dentro de las próximas 24 horas para iniciar tu transformación digital.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Error al enviar el mensaje. Por favor, escríbenos directamente a info@vineksec.online o inténtalo más tarde.'
        ]);
    }
    
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Método no permitido. Usa POST.'
    ]);
}
?>