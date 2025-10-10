<?php
// Diagnóstico del sistema de email
header('Content-Type: text/html; charset=UTF-8');

echo "<h2>🔍 Diagnóstico del Sistema de Email - VinekDev</h2>";

// 1. Verificar archivo de configuración
echo "<h3>1. Verificando configuración...</h3>";
if (file_exists('config.php')) {
    echo "✅ config.php existe<br>";
    $config = include 'config.php';
    if ($config && is_array($config)) {
        echo "✅ Configuración cargada correctamente<br>";
        echo "📧 SMTP Host: " . $config['smtp_host'] . "<br>";
        echo "🔌 Puerto: " . $config['smtp_port'] . "<br>";
        echo "🔒 Seguridad: " . $config['smtp_secure'] . "<br>";
        echo "👤 Usuario: " . $config['smtp_user'] . "<br>";
        echo "📬 Destino: " . $config['to_email'] . "<br>";
    } else {
        echo "❌ Error al cargar configuración<br>";
    }
} else {
    echo "❌ config.php no existe<br>";
}

// 2. Verificar PHPMailer
echo "<h3>2. Verificando PHPMailer...</h3>";
if (file_exists('vendor/autoload.php')) {
    echo "✅ vendor/autoload.php existe<br>";
    try {
        require_once 'vendor/autoload.php';
        if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            echo "✅ PHPMailer disponible<br>";
        } else {
            echo "❌ PHPMailer no se puede cargar<br>";
        }
    } catch (Exception $e) {
        echo "❌ Error al cargar PHPMailer: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ vendor/autoload.php no existe<br>";
    echo "💡 Ejecuta: composer install<br>";
}

// 3. Verificar función mail() nativa
echo "<h3>3. Verificando mail() nativa de PHP...</h3>";
if (function_exists('mail')) {
    echo "✅ Función mail() disponible<br>";
} else {
    echo "❌ Función mail() no disponible<br>";
}

// 4. Test de conectividad SMTP
echo "<h3>4. Test de conectividad SMTP...</h3>";
if (isset($config)) {
    $connection = @fsockopen($config['smtp_host'], $config['smtp_port'], $errno, $errstr, 5);
    if ($connection) {
        echo "✅ Conexión SMTP exitosa<br>";
        fclose($connection);
    } else {
        echo "❌ No se puede conectar al servidor SMTP<br>";
        echo "Error: $errstr ($errno)<br>";
    }
}

// 5. Información del servidor
echo "<h3>5. Información del servidor...</h3>";
echo "🐘 PHP Version: " . phpversion() . "<br>";
echo "🖥️ Servidor: " . $_SERVER['SERVER_SOFTWARE'] . "<br>";
echo "📂 Directorio actual: " . getcwd() . "<br>";

echo "<hr>";
echo "<h3>💡 Recomendaciones:</h3>";
echo "<ul>";
echo "<li>Si PHPMailer no está disponible, usa la <strong>Solución Simple</strong></li>";
echo "<li>Si la conexión SMTP falla, verifica las credenciales en Hostinger</li>";
echo "<li>Prueba cambiar el puerto de 465 a 587 (o viceversa)</li>";
echo "<li>Verifica que tu email y contraseña sean correctos</li>";
echo "</ul>";

?>