<?php
// Archivo de ejemplo de configuración SMTP
// ========================================
// 
// Copia este archivo como 'config.php' y configura con tus datos reales
// NUNCA subas el archivo config.php real a Git (ya está en .gitignore)

return [
    'smtp_host' => 'smtp.hostinger.com',  // Servidor SMTP de Hostinger
    'smtp_port' => 587,                   // Puerto: 587 (TLS) o 465 (SSL)
    'smtp_secure' => 'tls',               // 'tls' para puerto 587, 'ssl' para 465
    'smtp_user' => 'tu-email@tudominio.com',  // Tu email completo de Hostinger
    'smtp_pass' => 'tu-contraseña-aqui',      // Tu contraseña del email
    'from_name' => 'VinekDev - Contacto',
    'to_email' => 'info@vineksec.online',     // Email donde recibirás los mensajes
];

// INFORMACIÓN PARA ENCONTRAR TUS DATOS SMTP EN HOSTINGER:
// =======================================================
// 
// 1. Entra a tu panel de Hostinger (hpanel.hostinger.com)
// 2. Ve a la sección "Emails"
// 3. Haz clic en "Configurar" o "Configuración" 
// 4. Busca la sección "Configuración SMTP" o "Servidor de correo saliente"
// 
// Los datos típicos de Hostinger son:
// - Servidor SMTP: smtp.hostinger.com
// - Puerto: 587 (recomendado) o 465
// - Seguridad: STARTTLS para 587, SSL para 465
// - Usuario: tu email completo (ej: info@vineksec.online)
// - Contraseña: la contraseña de tu cuenta de email
?>