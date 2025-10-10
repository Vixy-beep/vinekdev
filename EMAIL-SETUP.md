# 📧 Configuración del Formulario de Contacto - VinekDev

## 🚀 Configuración con SMTP de Hostinger

### 📋 Pasos de Configuración:

1. **Copia el archivo de configuración:**
   ```bash
   cp config.example.php config.php
   ```

2. **Edita `config.php` con tus datos de Hostinger:**
   - Abre el archivo `config.php`
   - Reemplaza los valores con tus datos reales

3. **Encuentra tus datos SMTP en Hostinger:**
   - Entra a [hpanel.hostinger.com](https://hpanel.hostinger.com)
   - Ve a **"Emails"** → **"Configuración"**
   - Busca **"Configuración SMTP"**

4. **Instala las dependencias PHP (si usas servidor propio):**
   ```bash
   composer install
   ```

### ⚙️ Datos Típicos de Hostinger:

```php
'smtp_host' => 'smtp.hostinger.com',
'smtp_port' => 587,
'smtp_secure' => 'tls',
'smtp_user' => 'info@vineksec.online',  // Tu email completo
'smtp_pass' => 'tu-contraseña-real',    // Contraseña del email
```

### 🔒 Seguridad:

- ✅ El archivo `config.php` está en `.gitignore`
- ✅ No se subirá al repositorio
- ✅ Mantén tus credenciales seguras

### 🌐 Hosting en Hostinger:

Si subes el sitio a Hostinger:

1. **Sube todos los archivos excepto `config.php`**
2. **Crea `config.php` directamente en el servidor**
3. **Hostinger incluye PHPMailer automáticamente**

### 🧪 Prueba del Formulario:

1. Configura `config.php` con tus datos
2. Sube el sitio a tu servidor Hostinger
3. Prueba el formulario desde el sitio web
4. Verifica que lleguen los emails a tu bandeja

### 🆘 Solución de Problemas:

**Error "Could not authenticate":**
- Verifica usuario y contraseña
- Usa el email completo como usuario

**Error de conexión:**
- Verifica el servidor SMTP
- Prueba puerto 465 con SSL si 587 no funciona

**No llegan emails:**
- Revisa la carpeta de spam
- Verifica que el email destino sea correcto

---

### 🎯 ¿Necesitas ayuda?

Si tienes problemas con la configuración, proporciona:
1. Los datos SMTP de tu panel Hostinger
2. Cualquier mensaje de error que veas

¡El formulario estará funcionando con tu email profesional de Hostinger! 📬✨