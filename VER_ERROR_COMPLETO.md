# 🔍 Ver Error Completo en los Logs

El stack trace que viste muestra el pipeline de Laravel, pero necesitamos ver el mensaje de error inicial.

## Comando para ver el error completo

En el servidor, ejecuta:

```bash
tail -n 200 storage/logs/laravel.log | grep -B 20 "stacktrace" | head -n 50
```

O mejor aún, busca el último error:

```bash
tail -n 500 storage/logs/laravel.log | grep -A 5 "local.ERROR" | tail -n 30
```

## O ver el último error completo

```bash
tail -n 1000 storage/logs/laravel.log | grep -B 5 "local.ERROR" | tail -n 100
```

## Buscar específicamente errores de la ruta

```bash
tail -n 500 storage/logs/laravel.log | grep -i "walee-calendario-aplicaciones\|POST.*crear" -A 10 -B 5
```

## Ver el último error con contexto completo

```bash
tail -n 2000 storage/logs/laravel.log | grep -B 10 "local.ERROR" | tail -n 150
```

El mensaje de error debería estar justo antes del stack trace. Busca líneas que digan:
- `local.ERROR:`
- `local.WARNING:`
- `local.INFO:`

Y luego el mensaje de error específico.

