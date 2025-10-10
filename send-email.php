<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Función para enviar respuesta JSON
function sendResponse($status, $message) {
    echo json_encode(['status' => $status, 'message' => $message]);
    exit;
}

// Log de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Cargar configuración
        if (!file_exists('config.php')) {
            sendResponse('error', 'Archivo de configuración no encontrado. Contacta al administrador.');
        }
        
        $config = include 'config.php';
        
        // Validar configuración
        if (!$config || !isset($config['smtp_user'])) {
            sendResponse('error', 'Configuración SMTP inválida. Contacta al administrador.');
        }
        
        // Datos del formulario con validación
        $name = isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'])) : '';
        $company = isset($_POST['company']) ? trim(htmlspecialchars($_POST['company'])) : '';
        $email = isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : '';
        $phone = isset($_POST['phone']) ? trim(htmlspecialchars($_POST['phone'])) : '';
        $service = isset($_POST['service']) ? trim(htmlspecialchars($_POST['service'])) : '';
        $budget = isset($_POST['budget']) ? trim(htmlspecialchars($_POST['budget'])) : '';
        $message = isset($_POST['message']) ? trim(htmlspecialchars($_POST['message'])) : '';
        
        // Validar campos requeridos
        if (empty($name) || empty($email) || empty($message)) {
            sendResponse('error', 'Por favor completa todos los campos requeridos (Nombre, Email y Mensaje).');
        }
        
        // Validar email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sendResponse('error', 'Por favor ingresa un email válido.');
        }
        
        // Verificar si PHPMailer está disponible
        if (file_exists('vendor/autoload.php')) {
            // Usar PHPMailer si está disponible
            require_once 'vendor/autoload.php';
            
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            // Configuración SMTP
            $mail->isSMTP();
            $mail->Host = $config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['smtp_user'];
            $mail->Password = $config['smtp_pass'];
            
            if ($config['smtp_secure'] === 'ssl') {
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            }
            
            $mail->Port = $config['smtp_port'];
            $mail->CharSet = 'UTF-8';
            
            // Configuración del email
            $mail->setFrom($config['smtp_user'], $config['from_name']);
            $mail->addAddress($config['to_email'], 'VinekDev');
            $mail->addReplyTo($email, $name);
            
            // Contenido
            $mail->isHTML(true);
            $mail->Subject = "Nuevo contacto desde VinekDev - " . $name;
            
            $mail->Body = generateEmailHTML($name, $company, $email, $phone, $service, $budget, $message);
            $mail->AltBody = generateEmailText($name, $company, $email, $phone, $service, $budget, $message);
            
            $mail->send();
            sendResponse('success', '¡Mensaje enviado con éxito! Te contactaremos pronto.');
            
        } else {
            // Fallback: usar mail() de PHP
            $to = $config['to_email'];
            $subject = "Nuevo contacto desde VinekDev - " . $name;
            $body = generateEmailText($name, $company, $email, $phone, $service, $budget, $message);
            $headers = "From: " . $config['smtp_user'] . "\r\n";
            $headers .= "Reply-To: " . $email . "\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            if (mail($to, $subject, $body, $headers)) {
                sendResponse('success', '¡Mensaje enviado con éxito! Te contactaremos pronto.');
            } else {
                sendResponse('error', 'Error al enviar el mensaje. Por favor, intenta más tarde o contáctanos directamente.');
            }
        }
        
    } catch (Exception $e) {
        // Log del error real (opcional, para debugging)
        error_log("Error en formulario de contacto: " . $e->getMessage());
        sendResponse('error', 'Error temporal en el sistema. Por favor, intenta nuevamente o contáctanos directamente a info@vineksec.online');
    }
} else {
    sendResponse('error', 'Método no permitido.');
}

function generateEmailHTML($name, $company, $email, $phone, $service, $budget, $message) {
    return "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(45deg, #00d4ff, #bf00ff); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f9f9f9; padding: 20px; }
            .field { margin-bottom: 15px; padding: 10px; background: white; border-left: 4px solid #00d4ff; }
            .field strong { color: #333; }
            .footer { background: #333; color: white; padding: 15px; text-align: center; font-size: 12px; border-radius: 0 0 10px 10px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>🚀 Nuevo Contacto desde VinekDev</h2>
            </div>
            <div class='content'>
                <div class='field'><strong>Nombre:</strong> $name</div>
                <div class='field'><strong>Empresa:</strong> $company</div>
                <div class='field'><strong>Email:</strong> $email</div>
                <div class='field'><strong>Teléfono:</strong> $phone</div>
                <div class='field'><strong>Servicio de Interés:</strong> $service</div>
                <div class='field'><strong>Presupuesto:</strong> $budget</div>
                <div class='field'><strong>Mensaje:</strong><br>$message</div>
            </div>
            <div class='footer'>
                <p>Enviado desde el formulario de contacto de VinekDev</p>
                <p>Fecha: " . date('Y-m-d H:i:s') . "</p>
            </div>
        </div>
    </body>
    </html>";
}

function generateEmailText($name, $company, $email, $phone, $service, $budget, $message) {
    return "
🚀 NUEVO CONTACTO DESDE VINEKDEV
===============================

👤 Nombre: $name
🏢 Empresa: $company
📧 Email: $email
📱 Teléfono: $phone
🛠️ Servicio: $service
💰 Presupuesto: $budget

💬 Mensaje:
$message

---
📅 Enviado: " . date('Y-m-d H:i:s') . "
🌐 Desde: VinekDev.com
    ";
}
?>