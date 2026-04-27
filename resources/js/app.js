// resources/js/app.js
import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

// Registrar el plugin collapse
Alpine.plugin(collapse);

// Hacer Alpine disponible globalmente
window.Alpine = Alpine;

// Iniciar Alpine
Alpine.start();

console.log('Alpine.js inicializado correctamente');
