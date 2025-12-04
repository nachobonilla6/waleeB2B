import './bootstrap';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize any Livewire components
// import './components/example';

// Start Livewire (esto inicializa Alpine automáticamente)
Livewire.start();