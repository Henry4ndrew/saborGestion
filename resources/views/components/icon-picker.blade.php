{{-- resources/views/components/icon-picker.blade.php --}}
@props(['selectedIcon' => 'fa-tag', 'name' => 'icono'])

<div x-data="{
    showIconPicker: false,
    selectedIcon: '{{ $selectedIcon }}',
    searchTerm: '',
    currentCategory: 'all',
    
    // Base de datos de iconos
    iconsDatabase: {
        comida: [
            { name: 'fa-pizza-slice', label: 'Pizza', emoji: '🍕' },
            { name: 'fa-hamburger', label: 'Hamburguesa', emoji: '🍔' },
            { name: 'fa-fish', label: 'Pescado', emoji: '🐟' },
            { name: 'fa-egg', label: 'Huevo', emoji: '🥚' },
            { name: 'fa-cheese', label: 'Queso', emoji: '🧀' },
            { name: 'fa-anchor', label: 'Marino', emoji: '🦐' },
            { name: 'fa-bread-slice', label: 'Pan', emoji: '🍞' },
            { name: 'fa-apple-alt', label: 'Manzana', emoji: '🍎' },
            { name: 'fa-carrot', label: 'Zanahoria', emoji: '🥕' },
            { name: 'fa-leaf', label: 'Hoja', emoji: '🌿' },
            { name: 'fa-cookie', label: 'Postres', emoji: '🍩' },
            { name: 'fa-glass-water', label: 'Refrescos', emoji: '🥤' },
            { name: 'fa-bowl-food', label: 'Sopas', emoji: '🍲' },
            { name: 'fa-seedling', label: 'Planta', emoji: '🌱' },
            { name: 'fa-ice-cream', label: 'Helado', emoji: '🍦' },
            { name: 'fa-candy-cane', label: 'Caramelo', emoji: '🍬' },
            { name: 'fa-cookie', label: 'Galleta', emoji: '🍪' },
            { name: 'fa-cake-candles', label: 'Pastel', emoji: '🎂' },
            { name: 'fa-bacon', label: 'Tocino', emoji: '🥓' },
            { name: 'fa-hotdog', label: 'Hot dog', emoji: '🌭' },
            { name: 'fa-bowl-food', label: 'Bowl de comida', emoji: '🥣' },
            { name: 'fa-plate-wheat', label: 'Plato', emoji: '🍽️' },
            { name: 'fa-drumstick-bite', label: 'Pollo', emoji: '🍗' },
            { name: 'fa-pepper-hot', label: 'Chile picante', emoji: '🌶️' },
            { name: 'fa-ice-cream', label: 'Helado', emoji: '🍦' },
            { name: 'fa-cube', label: 'Hielo', emoji: '🧊' },
            { name: 'fa-bread-slice', label: 'Wrap/Burrito', emoji: '🌯' },
            { name: 'fa-bowl-food', label: 'Ramen', emoji: '🍜' },
            { name: 'fa-bowl-food', label: 'Papas fritas', emoji: '🍟' },
        ],
        bebidas: [
            { name: 'fa-mug-hot', label: 'Taza caliente', emoji: '☕' },
            { name: 'fa-wine-bottle', label: 'Vino', emoji: '🍷' },
            { name: 'fa-beer', label: 'Cerveza', emoji: '🍺' },
            { name: 'fa-coffee', label: 'Café', emoji: '☕' },
            { name: 'fa-mug-saucer', label: 'Té', emoji: '🍵' },
            { name: 'fa-cocktail', label: 'Cóctel', emoji: '🍹' },
            { name: 'fa-wine-glass-alt', label: 'Copa de vino', emoji: '🍷' },
            { name: 'fa-champagne-glasses', label: 'Champán', emoji: '🥂' },
            { name: 'fa-whiskey-glass', label: 'Whiskey', emoji: '🥃' },
        ],
        utensilios: [
            { name: 'fa-utensils', label: 'Cubiertos', emoji: '🍴' },
            { name: 'fa-kitchen-set', label: 'Set cocina', emoji: '🔪' },
            { name: 'fa-blender', label: 'Licuadora', emoji: '🥤' },

            { name: 'fa-temperature-high', label: 'Horno', emoji: '🔥' },
            { name: 'fa-utensils', label: 'Cubiertos', emoji: '🔪' },
            { name: 'fa-utensils', label: 'Tenedor', emoji: '🍴' },
            { name: 'fa-spoon', label: 'Cuchara', emoji: '🥄' },
            { name: 'fa-utensils', label: 'Palillos', emoji: '🥢' },
            { name: 'fa-fire', label: 'Parrilla', emoji: '🔥' },
            { name: 'fa-utensils', label: 'Wok', emoji: '🍳' },
            { name: 'fa-bowl-food', label: 'Olla', emoji: '🍲' },
        ],
        restaurante: [
            { name: 'fa-store', label: 'Tienda', emoji: '🏪' },
            { name: 'fa-shop', label: 'Tienda', emoji: '🛒' },
            { name: 'fa-truck-fast', label: 'Delivery', emoji: '🚚' },
            { name: 'fa-concierge-bell', label: 'Servicio', emoji: '🔔' },
            { name: 'fa-cash-register', label: 'Caja', emoji: '💰' },
            { name: 'fa-receipt', label: 'Factura', emoji: '🧾' },
            { name: 'fa-credit-card', label: 'Tarjeta', emoji: '💳' },
        ],
        general: [
            { name: 'fa-tag', label: 'Etiqueta', emoji: '🏷️' },
            { name: 'fa-star', label: 'Estrella', emoji: '⭐' },
            { name: 'fa-heart', label: 'Corazón', emoji: '❤️' },
            { name: 'fa-fire', label: 'Fuego', emoji: '🔥' },
             { name: 'fa-snowflake', label: 'Frío', emoji: '❄️' },
            { name: 'fa-crown', label: 'Corona', emoji: '👑' },
            { name: 'fa-gem', label: 'Gema', emoji: '💎' },
            { name: 'fa-rocket', label: 'Cohete', emoji: '🚀' },
            { name: 'fa-sun', label: 'Sol', emoji: '☀️' },
            { name: 'fa-moon', label: 'Luna', emoji: '🌙' },
            { name: 'fa-cloud-sun', label: 'Nube', emoji: '⛅' },
            { name: 'fa-snowflake', label: 'Nieve', emoji: '❄️' },
            { name: 'fa-tree', label: 'Árbol', emoji: '🌳' },
            { name: 'fa-spa', label: 'Flor', emoji: '🌸' },
        ]
    },
    
    get allIcons() {
        return [
            ...this.iconsDatabase.comida,
            ...this.iconsDatabase.bebidas,
            ...this.iconsDatabase.utensilios,
            ...this.iconsDatabase.restaurante,
            ...this.iconsDatabase.general
        ];
    },
    
    get filteredIcons() {
        let icons = this.currentCategory === 'all' ? this.allIcons : this.iconsDatabase[this.currentCategory];
        
        if (this.searchTerm) {
            const term = this.searchTerm.toLowerCase();
            icons = icons.filter(icon => 
                icon.label.toLowerCase().includes(term) ||
                icon.name.toLowerCase().includes(term)
            );
        }
        
        return icons;
    },
    
    openIconPicker() {
        this.showIconPicker = true;
        this.searchTerm = '';
        this.currentCategory = 'all';
    },
    
    closeIconPicker() {
        this.showIconPicker = false;
    },
    
    selectIcon(iconName) {
        this.selectedIcon = iconName;
        this.closeIconPicker();
        
        // Disparar evento para actualizar el input visible
        const input = document.querySelector('#icon_value');
        if (input) {
            input.value = iconName;
            input.dispatchEvent(new Event('input'));
        }
        
        // Feedback visual
        const btn = document.querySelector('.btn-secondary');
        if (btn) {
            btn.style.transform = 'scale(0.95)';
            setTimeout(() => {
                btn.style.transform = '';
            }, 200);
        }
    }
}" 
x-data>
    
    <!-- Input con botón lateral -->
    <div class="flex gap-2">
        <div class="relative flex-1">
            <input type="text" 
                   id="icon_value"
                   name="{{ $name }}"
                   x-model="selectedIcon"
                   placeholder="fa-utensils"
                   class="w-full px-4 py-2 pl-10 rounded-lg border border-border focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
            <div class="absolute left-3 top-1/2 -translate-y-1/2">
                <i :class="'fas ' + (selectedIcon || 'fa-tag')" class="text-primary"></i>
            </div>
        </div>
        <button type="button" @click="openIconPicker" class="btn-secondary px-4" title="Seleccionar icono">
            <i class="fas fa-icons"></i>
        </button>
    </div>
    
    <p class="text-xs text-muted mt-1">
        <i class="fas fa-info-circle"></i> 
        Haz clic en el botón <i class="fas fa-icons"></i> para seleccionar un icono visualmente
    </p>
    
    <!-- Panel flotante de iconos -->
    <div x-show="showIconPicker" 
         x-cloak
         @click.away="closeIconPicker"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         style="display: none;">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl mx-4 max-h-[90vh] flex flex-col">
            <!-- Cabecera -->
            <div class="flex justify-between items-center p-4 border-b border-border">
                <h3 class="text-xl font-bold text-primary">Seleccionar Icono</h3>
                <button @click="closeIconPicker" class="text-muted hover:text-text">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Contenido -->
            <div class="flex-1 overflow-y-auto p-4">
                <!-- Búsqueda -->
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" 
                               x-model="searchTerm"
                               placeholder="Buscar icono..." 
                               class="w-full px-4 py-2 pl-10 rounded-lg border border-border focus:outline-none focus:border-primary">
                        <i class="fas fa-search absolute left-3 top-3 text-muted"></i>
                    </div>
                </div>
                
                <!-- Pestañas de categorías -->
                <div class="flex gap-2 mb-4 border-b border-border flex-wrap">
                    <button type="button" 
                            @click="currentCategory = 'all'"
                            :class="{'border-primary text-primary': currentCategory === 'all', 'border-transparent text-muted': currentCategory !== 'all'}"
                            class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                        Todos
                    </button>
                    <button type="button" 
                            @click="currentCategory = 'comida'"
                            :class="{'border-primary text-primary': currentCategory === 'comida', 'border-transparent text-muted': currentCategory !== 'comida'}"
                            class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                        🍕 Comida
                    </button>
                    <button type="button" 
                            @click="currentCategory = 'bebidas'"
                            :class="{'border-primary text-primary': currentCategory === 'bebidas', 'border-transparent text-muted': currentCategory !== 'bebidas'}"
                            class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                        🍹 Bebidas
                    </button>
                    <button type="button" 
                            @click="currentCategory = 'utensilios'"
                            :class="{'border-primary text-primary': currentCategory === 'utensilios', 'border-transparent text-muted': currentCategory !== 'utensilios'}"
                            class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                        🍴 Utensilios
                    </button>
                    <button type="button" 
                            @click="currentCategory = 'restaurante'"
                            :class="{'border-primary text-primary': currentCategory === 'restaurante', 'border-transparent text-muted': currentCategory !== 'restaurante'}"
                            class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                        🏪 Restaurante
                    </button>
                    <button type="button" 
                            @click="currentCategory = 'general'"
                            :class="{'border-primary text-primary': currentCategory === 'general', 'border-transparent text-muted': currentCategory !== 'general'}"
                            class="px-4 py-2 text-sm font-medium border-b-2 transition-colors">
                        ✨ General
                    </button>
                </div>
                
                <!-- Grid de iconos -->
                <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-3">
                    <template x-for="icon in filteredIcons" :key="icon.name">
                        <div class="icon-card p-3 rounded-lg border border-border hover:border-primary hover:bg-primary/5 cursor-pointer transition-all text-center group"
                             @click="selectIcon(icon.name)">
                            <i :class="'fas ' + icon.name" class="text-2xl text-text group-hover:text-primary transition-colors"></i>
                            <p class="text-xs text-muted mt-1 truncate" x-text="icon.emoji + ' ' + icon.label"></p>
                        </div>
                    </template>
                    
                    <div x-show="filteredIcons.length === 0" class="col-span-full text-center py-8 text-muted">
                        <i class="fas fa-search text-4xl mb-2 block"></i>
                        <p>No se encontraron iconos</p>
                    </div>
                </div>
            </div>
            
            <!-- Pie -->
            <div class="p-4 border-t border-border flex justify-end">
                <button @click="closeIconPicker" class="btn-secondary px-4">Cerrar</button>
            </div>
        </div>
    </div>
    
    @push('styles')
    <style>
    [x-cloak] { display: none !important; }
    
    .icon-card {
        transition: all 0.2s ease;
    }
    
    .icon-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    </style>
    @endpush
</div>