# 🧪 Cómo Probar los Cambios Localmente

## ✅ Verificaciones Previas

### 1. Verificar que los archivos existen
```bash
cd websolutions-laravel

# Verificar archivos
ls -la app/Filament/Widgets/IngresosStatsWidget.php
ls -la app/Filament/Pages/Dashboard.php
```

### 2. Verificar sintaxis PHP
```bash
php -l app/Filament/Widgets/IngresosStatsWidget.php
php -l app/Filament/Pages/Dashboard.php
```

### 3. Limpiar caché (IMPORTANTE)
```bash
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan filament:cache-components
composer dump-autoload --optimize
```

## 🚀 Probar en el Navegador

### Opción 1: Si tienes servidor local corriendo

1. **Inicia el servidor** (si no está corriendo):
   ```bash
   php artisan serve
   ```
   O si usas Laravel Sail:
   ```bash
   ./vendor/bin/sail up
   ```

2. **Abre el navegador**:
   - Ve a: `http://localhost:8000/admin` o `http://127.0.0.1:8000/admin`
   - O la URL que uses normalmente para tu proyecto local

3. **Inicia sesión** en el panel de Filament

4. **Ve al Dashboard**:
   - Deberías ver 4 widgets de estadísticas
   - El widget de **Ingresos** debería mostrar:
     - Total Ingresos
     - Ingresos este mes
     - Ingresos este año
     - Ingresos hoy

### Opción 2: Verificar datos en la base de datos

```bash
# Verificar que hay facturas pagadas
php artisan tinker
```

Dentro de tinker:
```php
// Contar facturas pagadas
App\Models\Factura::where('estado', 'pagada')->count();

// Ver total de ingresos
App\Models\Factura::where('estado', 'pagada')->sum('total');

// Ver facturas pagadas este mes
App\Models\Factura::where('estado', 'pagada')
    ->where('fecha_emision', '>=', now()->startOfMonth())
    ->sum('total');
```

## 🔍 Verificar que el Widget se Carga

### 1. Revisar la consola del navegador
- Abre las herramientas de desarrollador (F12)
- Ve a la pestaña "Console"
- No debería haber errores de JavaScript

### 2. Revisar el código fuente
- Clic derecho → "Ver código fuente de la página"
- Busca "IngresosStatsWidget" o "Total Ingresos"
- Deberías ver referencias al widget

### 3. Verificar logs de Laravel
```bash
tail -f storage/logs/laravel.log
```
Luego recarga la página del dashboard y busca errores.

## 🐛 Si No Ves el Widget

### Problema 1: Widget no aparece
**Solución:**
```bash
# Verificar que está registrado en Dashboard.php
grep -n "IngresosStatsWidget" app/Filament/Pages/Dashboard.php

# Debería mostrar:
# use App\Filament\Widgets\IngresosStatsWidget;
# IngresosStatsWidget::class,
```

### Problema 2: Error de clase no encontrada
**Solución:**
```bash
composer dump-autoload --optimize
php artisan config:clear
```

### Problema 3: Datos muestran $0.00
**Solución:**
- Verifica que hay facturas con `estado = 'pagada'` en la base de datos
- Verifica que el campo `total` tiene valores
- Verifica que el campo `fecha_emision` está correctamente formateado

### Problema 4: Caché del navegador
**Solución:**
- Limpia la caché del navegador (Ctrl+Shift+Delete)
- O recarga forzada (Ctrl+F5)
- O abre en modo incógnito

## 📊 Verificar Datos de Prueba

Si no tienes datos, puedes crear facturas de prueba:

```bash
php artisan tinker
```

```php
use App\Models\Factura;
use Carbon\Carbon;

// Crear factura pagada de hoy
Factura::create([
    'estado' => 'pagada',
    'total' => 1000.00,
    'fecha_emision' => Carbon::today(),
    // ... otros campos requeridos
]);

// Crear factura pagada de este mes
Factura::create([
    'estado' => 'pagada',
    'total' => 2000.00,
    'fecha_emision' => Carbon::now()->startOfMonth(),
    // ... otros campos requeridos
]);
```

## ✅ Checklist de Prueba

- [ ] Archivos existen y tienen sintaxis correcta
- [ ] Caché limpiado
- [ ] Autoload regenerado
- [ ] Servidor local corriendo
- [ ] Puedo acceder al panel de Filament
- [ ] Veo el Dashboard con widgets
- [ ] Veo el widget de Ingresos
- [ ] Los datos se muestran correctamente
- [ ] No hay errores en la consola del navegador
- [ ] No hay errores en los logs de Laravel

## 🚀 Después de Verificar Localmente

Si todo funciona localmente:

1. **Hacer commit:**
   ```bash
   git add app/Filament/Widgets/IngresosStatsWidget.php
   git commit -m "feat: Agregar widget de estadísticas de ingresos"
   git push origin main
   ```

2. **En el servidor de Hostinger:**
   ```bash
   git pull origin main
   composer dump-autoload --optimize
   php artisan config:clear
   php artisan view:clear
   php artisan filament:cache-components
   ```

