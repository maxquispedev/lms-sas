import './bootstrap';
import Alpine from 'alpinejs';

// Make Alpine available globally (Livewire will start it)
window.Alpine = Alpine;

/**
 * Sincroniza la clase `dark` en <html> con localStorage / preferencia del sistema.
 * Tras `wire:navigate`, Livewire iguala atributos de <html> con la respuesta del servidor
 * (que no incluye `class="dark"`), borrando el tema aplicado solo en cliente.
 */
function applyStoredThemeToHtml() {
    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = saved || (prefersDark ? 'dark' : 'light');
    document.documentElement.classList.toggle('dark', theme === 'dark');
}

document.addEventListener('livewire:navigated', applyStoredThemeToHtml);
