# 🔧 Solución: "fatal: not a git repository"

## Problema
Al intentar hacer `git pull origin main` en el servidor, obtienes el error:
```
fatal: not a git repository (or any of the parent directories): .git
```

## Causa
Estás en un directorio que no es un repositorio Git, o no estás en el directorio correcto del proyecto.

## Solución

### Paso 1: Verificar el directorio actual
```bash
pwd
```

### Paso 2: Navegar al directorio correcto del proyecto
Según tu dominio actual, el proyecto debería estar en:
```bash
cd /home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com
```

O si estás en `public_html`:
```bash
cd /home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com/public_html
```

**Nota**: Si el directorio tiene un nombre diferente, verifica con:
```bash
cd ~/domains
ls -la
# Busca el directorio que corresponda a tu dominio
```

### Paso 3: Verificar si es un repositorio Git
```bash
git status
```

Si funciona, ya puedes hacer `git pull origin main`.

### Paso 4: Si NO es un repositorio Git

Tienes dos opciones:

#### Opción A: Inicializar Git en el directorio actual (si ya tienes los archivos)

```bash
# 1. Inicializar git
git init

# 2. Agregar el remote
git remote add origin git@github.com:nachobonilla6/waleeB2B.git
# O si prefieres HTTPS:
# git remote add origin https://github.com/nachobonilla6/waleeB2B.git

# 3. Verificar el remote
git remote -v

# 4. Hacer pull
git pull origin main
```

#### Opción B: Clonar el repositorio (si no tienes los archivos o quieres empezar de cero)

⚠️ **ADVERTENCIA**: Esto sobrescribirá los archivos existentes si ya los tienes.

```bash
# 1. Ir al directorio padre
cd /home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com

# 2. Hacer backup de los archivos existentes (si los hay)
# Si tienes archivos importantes, haz backup primero:
# tar -czf backup-$(date +%Y%m%d).tar.gz public_html/

# 3. Clonar el repositorio
git clone git@github.com:nachobonilla6/waleeB2B.git temp_repo
# O si prefieres HTTPS:
# git clone https://github.com/nachobonilla6/waleeB2B.git temp_repo

# 4. Mover los archivos
mv temp_repo/* public_html/
mv temp_repo/.* public_html/ 2>/dev/null || true
rmdir temp_repo

# 5. Ir al directorio del proyecto
cd public_html

# 6. Verificar
git status
```

### Paso 5: Si ya es un repositorio pero falta el remote

```bash
# Verificar si tiene remote
git remote -v

# Si no tiene remote, agregarlo
git remote add origin git@github.com:nachobonilla6/waleeB2B.git
# O si prefieres HTTPS:
# git remote add origin https://github.com/nachobonilla6/waleeB2B.git

# Verificar
git remote -v

# Hacer pull
git pull origin main
```

## Comandos Rápidos (Todo en uno)

Si estás seguro de que el directorio correcto es `/home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com`:

```bash
# Navegar al directorio
cd /home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com

# Verificar si es git
if [ -d .git ]; then
    echo "✅ Es un repositorio Git"
    git pull origin main
else
    echo "❌ No es un repositorio Git"
    echo "Necesitas inicializar git o clonar el repositorio"
fi
```

## Verificar la URL del repositorio remoto

Si necesitas saber la URL de tu repositorio, desde tu máquina local ejecuta:

```bash
cd "/home/josh/Desktop/Github repositories/waleeB2B"
git remote -v
```

Esto te mostrará la URL que debes usar en el servidor.

## Notas Importantes

1. **Backup**: Antes de clonar o inicializar git, asegúrate de hacer backup de archivos importantes como `.env`, `storage/`, etc.

2. **Permisos**: Asegúrate de tener permisos de escritura en el directorio.

3. **Credenciales**: Si el repositorio es privado, necesitarás configurar credenciales de Git en el servidor.

4. **Directorio correcto**: Según tu dominio actual (`ghostwhite-parrot-934435.hostingersite.com`), el proyecto debería estar en `/home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com` o en su subdirectorio `public_html`. Si no encuentras este directorio, verifica con `ls -la ~/domains/` para ver todos los dominios disponibles.

