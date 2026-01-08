# 🔧 Solución: Error "src refspec main does not match any"

## Problema
En el servidor, al intentar hacer push, obtienes el error:
```
error: src refspec main does not match any
error: failed to push some refs to 'https://github.com/nachobonilla6/waleeB2B.git'
```

## Causa
Este error ocurre porque:
1. **NO debes hacer push desde el servidor** - El servidor solo debe hacer `pull` para recibir cambios
2. Puede que no tengas una rama `main` local o no hay commits locales
3. El repositorio en el servidor está recién clonado/inicializado

## Solución Correcta

### En el Servidor (solo hacer PULL, nunca PUSH)

> **Nota**: Se asume que ya estás en el directorio del proyecto

```bash
# 1. Verificar el estado actual
git status

# 3. Verificar qué rama estás usando
git branch

# 4. Si no estás en la rama main, cambiarte a ella:
git checkout -b main origin/main

# 5. O simplemente hacer pull (Git te dirá qué hacer si hay problemas):
git pull origin main
```

### Si el repositorio está recién inicializado

Si acabas de hacer `git init` y aún no has hecho el primer pull:

```bash
# 1. Agregar el remote (si no lo has hecho)
git remote add origin https://github.com/nachobonilla6/waleeB2B.git

# 2. Verificar el remote
git remote -v

# 3. Hacer fetch para traer las referencias
git fetch origin

# 4. Hacer checkout de la rama main
git checkout -b main origin/main

# O hacer pull directamente:
git pull origin main --allow-unrelated-histories
```

### Si hay archivos locales que causan conflicto

Si ya moviste `default.php` a `default.php.backup` y quieres hacer pull:

```bash
# 1. Verificar estado
git status

# 2. Si default.php.backup aparece como "untracked", puedes ignorarlo
# Agregarlo al .gitignore local (temporalmente) o simplemente hacer pull

# 3. Hacer pull
git pull origin main

# Si hay conflictos, Git te dirá qué hacer
```

## Comandos de Verificación

```bash
# Ver todas las ramas (locales y remotas)
git branch -a

# Ver los remotes configurados
git remote -v

# Ver el estado actual
git status

# Ver el historial de commits
git log --oneline -5
```

## Flujo Correcto de Trabajo

1. **En tu máquina local**: Haces cambios, commits y push
   ```bash
   git add .
   git commit -m "mensaje"
   git push origin main
   ```

2. **En el servidor**: Solo haces pull para recibir los cambios
   ```bash
   git pull origin main
   composer install --no-dev --optimize-autoloader
   php artisan config:clear
   php artisan cache:clear
   ```

## ⚠️ Importante

- **NUNCA hagas push desde el servidor** - El servidor es solo para recibir cambios
- **NUNCA hagas commits en el servidor** - Todos los commits se hacen localmente
- El servidor solo debe hacer `git pull` para actualizar el código

