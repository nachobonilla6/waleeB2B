#!/bin/bash

# Script para verificar y configurar Git en el servidor de Hostinger
# Uso: Copia este script al servidor y ejecútalo

echo "🔍 Verificando configuración de Git en el servidor..."
echo ""

# Directorio esperado del proyecto (actualiza según tu dominio)
PROJECT_DIR="/home/u655097049/domains/ghostwhite-parrot-934435.hostingersite.com"
REPO_URL="git@github.com:nachobonilla6/waleeB2B.git"
REPO_URL_HTTPS="https://github.com/nachobonilla6/waleeB2B.git"

# Verificar directorio actual
echo "📂 Directorio actual:"
pwd
echo ""

# Verificar si el directorio del proyecto existe
if [ -d "$PROJECT_DIR" ]; then
    echo "✅ El directorio del proyecto existe: $PROJECT_DIR"
    cd "$PROJECT_DIR" || exit 1
    
    # Verificar si es un repositorio Git
    if [ -d .git ]; then
        echo "✅ Es un repositorio Git"
        echo ""
        echo "📋 Información del repositorio:"
        git remote -v
        echo ""
        echo "🌿 Rama actual:"
        git branch
        echo ""
        echo "📊 Estado:"
        git status --short
        echo ""
        echo "✅ Puedes hacer 'git pull origin main' ahora"
    else
        echo "❌ No es un repositorio Git"
        echo ""
        echo "¿Quieres inicializar Git aquí? (s/n)"
        read -r respuesta
        if [ "$respuesta" = "s" ] || [ "$respuesta" = "S" ]; then
            echo "🔧 Inicializando Git..."
            git init
            git remote add origin "$REPO_URL_HTTPS"
            echo "✅ Git inicializado. Ahora puedes hacer 'git pull origin main'"
        else
            echo "❌ No se inicializó Git. Debes hacerlo manualmente."
        fi
    fi
else
    echo "❌ El directorio del proyecto no existe: $PROJECT_DIR"
    echo ""
    echo "¿Quieres crear el directorio y clonar el repositorio? (s/n)"
    read -r respuesta
    if [ "$respuesta" = "s" ] || [ "$respuesta" = "S" ]; then
        echo "⚠️  ADVERTENCIA: Esto creará un nuevo directorio y clonará el repositorio."
        echo "Si ya tienes archivos en otro lugar, haz backup primero."
        echo ""
        echo "¿Continuar? (s/n)"
        read -r confirmar
        if [ "$confirmar" = "s" ] || [ "$confirmar" = "S" ]; then
            mkdir -p "$PROJECT_DIR"
            cd "$PROJECT_DIR" || exit 1
            echo "📥 Clonando repositorio..."
            git clone "$REPO_URL_HTTPS" .
            echo "✅ Repositorio clonado en $PROJECT_DIR"
        fi
    fi
fi

echo ""
echo "📝 Comandos útiles:"
echo "  - Ver estado: git status"
echo "  - Hacer pull: git pull origin main"
echo "  - Ver remotes: git remote -v"
echo "  - Ver ramas: git branch -a"

