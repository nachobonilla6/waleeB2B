# 🔧 Solución: Conflicto con default.php

## Problema
Al intentar hacer `git pull origin main` en el servidor, obtienes el error:
```
error: The following untracked working tree files would be overwritten by merge:
        public_html/default.php
Please move or remove them before you merge.
```

## Causa
El archivo `public_html/default.php` es la página por defecto de Hostinger. Existe una versión en el servidor que no está rastreada por Git, y Git quiere sobrescribirla con la versión del repositorio.

## Solución Rápida

Ejecuta estos comandos en el servidor (asumiendo que ya estás en el directorio del proyecto):

```bash
# 1. Mover o renombrar el archivo default.php
mv public_html/default.php public_html/default.php.backup

# 3. Ahora hacer pull
git pull origin main

# 4. (Opcional) Si quieres restaurar el archivo original después:
# mv public_html/default.php.backup public_html/default.php
```

## Solución Alternativa: Eliminar el archivo

Si no necesitas el archivo `default.php` (que es solo la página de bienvenida de Hostinger):

```bash
# 1. Eliminar el archivo
rm public_html/default.php

# 3. Hacer pull
git pull origin main
```

## Solución Recomendada: Usar stash

Esta opción guarda temporalmente tus cambios locales:

```bash
# 1. Agregar el archivo al staging (aunque no esté rastreado)
git add -f public_html/default.php

# 3. Hacer stash
git stash

# 4. Hacer pull
git pull origin main

# 5. (Opcional) Si necesitas restaurar el archivo:
# git stash pop
```

## Nota Importante

El archivo `default.php` ya está agregado al `.gitignore` en el repositorio, por lo que después de hacer pull, este archivo no debería causar más conflictos en el futuro.

## Verificación

Después de resolver el conflicto y hacer pull, verifica que todo esté bien:

```bash
# Verificar estado de Git
git status

# Verificar que el pull funcionó
git log --oneline -5
```

