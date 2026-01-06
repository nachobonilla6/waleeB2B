# 🔧 Resolver Conflicto de Git en el Servidor

## Problema
Git no puede hacer `pull` porque hay cambios locales en `storage/app/google-credentials.json` que entrarían en conflicto.

## Solución

Ejecuta estos comandos en el servidor:

### Opción 1: Guardar cambios locales y luego hacer pull (Recomendado)

```bash
# Guardar los cambios locales temporalmente
git stash

# Hacer pull de los cambios remotos
git pull origin main

# Ver qué cambios locales tenías
git stash show -p

# Si necesitas restaurar tus cambios locales después del pull:
# git stash pop
```

### Opción 2: Descartar cambios locales y usar la versión del repositorio

```bash
# Descartar cambios locales en google-credentials.json
git checkout -- storage/app/google-credentials.json

# Hacer pull
git pull origin main
```

### Opción 3: Hacer commit de los cambios locales primero

```bash
# Agregar el archivo
git add storage/app/google-credentials.json

# Hacer commit
git commit -m "Actualizar credenciales en servidor"

# Hacer pull (puede requerir merge)
git pull origin main

# Si hay conflictos, resolverlos y luego:
git add storage/app/google-credentials.json
git commit -m "Resolver conflicto de credenciales"
```

## ⚠️ Importante

El archivo `google-credentials.json` contiene credenciales sensibles. Después de hacer pull:

1. **Verifica que el archivo tenga las credenciales correctas** del servidor
2. Si el archivo del repositorio sobrescribe tus credenciales del servidor, necesitarás actualizarlo manualmente con las credenciales correctas

## Recomendación

Usa la **Opción 1** (stash) para mantener tus cambios locales y luego decide si necesitas restaurarlos después del pull.

