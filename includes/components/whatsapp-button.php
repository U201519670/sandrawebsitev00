<?php
/**
 * Botón Flotante de WhatsApp
 * Se incluye en todas las páginas del sitio
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers.php';

$whatsappURL = getWhatsAppURL();
?>

<!-- Botón Flotante de WhatsApp -->
<a 
  href="<?= htmlspecialchars($whatsappURL, ENT_QUOTES, 'UTF-8') ?>"
  id="whatsapp-float-btn"
  class="whatsapp-float"
  target="_blank"
  rel="noopener noreferrer"
  onclick="trackWhatsAppClick(event)"
  aria-label="Contactar por WhatsApp"
>
  <!-- Icono de WhatsApp (SVG) - Logo oficial -->
  <svg viewBox="0 0 24 24" class="whatsapp-icon" fill="white">
    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
  </svg>
  
  <!-- Tooltip (opcional) -->
  <span class="whatsapp-tooltip">¿Necesitas ayuda?</span>
</a>

<!-- Estilos del Botón -->
<style>
.whatsapp-float {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 9999;
  width: 60px;
  height: 60px;
  background: #25D366;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15), 0 2px 6px rgba(37, 211, 102, 0.3);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  text-decoration: none;
  cursor: pointer;
}

/* Efecto de pulso animado en el borde */
.whatsapp-float::before {
  content: '';
  position: absolute;
  top: -5px;
  left: -5px;
  right: -5px;
  bottom: -5px;
  border-radius: 50%;
  border: 3px solid rgba(37, 211, 102, 0.6);
  animation: whatsappPulse 2s ease-out infinite;
}

@keyframes whatsappPulse {
  0% {
    transform: scale(0.95);
    opacity: 1;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.7;
  }
  100% {
    transform: scale(1.3);
    opacity: 0;
  }
}

.whatsapp-float:hover {
  transform: scale(1.1);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2), 0 4px 12px rgba(37, 211, 102, 0.4);
}

.whatsapp-float:hover::before {
  animation-play-state: paused;
  opacity: 0;
}

.whatsapp-float:active {
  transform: scale(1.05);
}

.whatsapp-icon {
  width: 36px;
  height: 36px;
  transition: transform 0.3s ease;
  position: relative;
  z-index: 1;
}

.whatsapp-float:hover .whatsapp-icon {
  transform: scale(1.1);
}

.whatsapp-tooltip {
  position: absolute;
  right: 72px;
  background: #1F2937;
  color: white;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  white-space: nowrap;
  opacity: 0;
  visibility: hidden;
  transform: translateX(10px);
  transition: all 0.3s ease;
  pointer-events: none;
}

.whatsapp-tooltip::after {
  content: '';
  position: absolute;
  right: -6px;
  top: 50%;
  transform: translateY(-50%);
  border: 6px solid transparent;
  border-left-color: #1F2937;
}

.whatsapp-float:hover .whatsapp-tooltip {
  opacity: 1;
  visibility: visible;
  transform: translateX(0);
}

/* Responsive */
@media (max-width: 768px) {
  .whatsapp-float {
    bottom: 20px;
    right: 20px;
    width: 56px;
    height: 56px;
  }
  
  .whatsapp-float::before {
    top: -4px;
    left: -4px;
    right: -4px;
    bottom: -4px;
    border-width: 2.5px;
  }
  
  .whatsapp-icon {
    width: 32px;
    height: 32px;
  }
  
  .whatsapp-tooltip {
    display: none; /* Ocultar en móviles */
  }
}

/* Animación de entrada */
@keyframes bounceIn {
  0% {
    opacity: 0;
    transform: scale(0.3);
  }
  50% {
    opacity: 1;
    transform: scale(1.05);
  }
  70% {
    transform: scale(0.9);
  }
  100% {
    transform: scale(1);
  }
}

.whatsapp-float {
  animation: bounceIn 0.6s ease-out 0.5s backwards;
}
</style>

<!-- Script de Tracking -->
<script>
/**
 * Tracking de conversiones de Google Ads para WhatsApp
 */
function trackWhatsAppClick(event) {
  // Datos de la página actual
  const pageData = {
    page: window.location.pathname,
    url: window.location.href,
    timestamp: new Date().toISOString()
  };
  
  <?php if (defined('APP_ENV') && APP_ENV === 'development'): ?>
  // Modo desarrollo: solo log en consola
  console.log('[DEBUG] WhatsApp Click Tracking:', {
    event: 'whatsapp_click',
    conversion_id: '<?= GOOGLE_ADS_ID ?>',
    conversion_label: '<?= GOOGLE_ADS_CONVERSION_LABEL ?>',
    ...pageData
  });
  <?php else: ?>
  // Modo producción: enviar a Google Ads
  if (typeof gtag === 'function') {
    gtag('event', 'conversion', {
      'send_to': '<?= GOOGLE_ADS_ID ?>/<?= GOOGLE_ADS_CONVERSION_LABEL ?>',
      'event_category': 'engagement',
      'event_label': 'whatsapp_float_button',
      'value': 1.0,
      'currency': 'PEN'
    });
    
    console.log('[INFO] Google Ads conversion tracked');
  } else {
    console.warn('[WARN] gtag function not found - Google Ads not loaded');
  }
  <?php endif; ?>
  
  // Tracking adicional (opcional para analytics internos)
  try {
    // Puedes agregar aquí llamadas a tu sistema de analytics interno
    // fetch('/api/track-whatsapp', { method: 'POST', body: JSON.stringify(pageData) });
  } catch (error) {
    console.error('[ERROR] Tracking failed:', error);
  }
  
  // Permitir que el enlace continúe (no prevenir el comportamiento por defecto)
  return true;
}
</script>
