<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Test Botón WhatsApp</title>
  <style>
    body {
      margin: 0;
      padding: 20px;
      min-height: 100vh;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      font-family: Arial, sans-serif;
      color: white;
    }
    h1 {
      text-align: center;
      margin-top: 50px;
    }
    .info {
      max-width: 600px;
      margin: 0 auto;
      background: rgba(255,255,255,0.1);
      padding: 20px;
      border-radius: 10px;
      margin-top: 30px;
    }
  </style>
</head>
<body>
  <h1>🔍 Prueba del Botón Flotante de WhatsApp</h1>
  
  <div class="info">
    <h2>Instrucciones:</h2>
    <ul>
      <li>Deberías ver un botón verde flotante en la esquina inferior derecha</li>
      <li>El botón debe tener un efecto de pulso animado</li>
      <li>Al hacer hover, debe agrandarse</li>
      <li>Abre la consola del navegador (F12) y haz clic en el botón para ver el tracking</li>
    </ul>
  </div>

  <?php 
  // Incluir botón flotante de WhatsApp
  require_once __DIR__ . '/includes/components/whatsapp-button.php'; 
  ?>
</body>
</html>
