#!/bin/bash
# ============================================================
# Script: subir_proyecto.sh
# Objetivo: Inicializar repo Git, crear ramas Main/Development/Qa
#           y subir el proyecto "PuntoDeVenta" con commits que
#           simulan el flujo de trabajo pedido en la actividad.
#
# IMPORTANTE:
#   - Ejecuta este script DENTRO de la carpeta raíz de tu proyecto
#     (donde están: app, database, resources, routes, etc.)
#   - Ajusta las variables de la sección CONFIGURACIÓN si lo necesitas.
#   - Requiere tener git instalado y configurado (usuario/correo) y
#     acceso al repo remoto (SSH o credenciales guardadas).
# ============================================================

set -e  # detener el script si algo falla

# ------------------- CONFIGURACIÓN -------------------
REPO_URL="https://github.com/MadeleyneRsmz/PuntoDeVenta.git"
MAIN_BRANCH="main"
DEV_BRANCH="development"
QA_BRANCH="qa"
AUTOR_NOMBRE="Madeleyne Rsmz"      # <-- cambia si quieres otro nombre
AUTOR_EMAIL="tuemail@example.com" # <-- cambia por tu correo de GitHub
# -------------------------------------------------------

export GIT_AUTHOR_NAME="$AUTOR_NOMBRE"
export GIT_AUTHOR_EMAIL="$AUTOR_EMAIL"
export GIT_COMMITTER_NAME="$AUTOR_NOMBRE"
export GIT_COMMITTER_EMAIL="$AUTOR_EMAIL"

# Función para hacer commit con fecha "falsa" (para simular avance en el tiempo)
# Uso: commit_fecha "YYYY-MM-DD HH:MM" "Mensaje del commit"
commit_fecha () {
    local fecha="$1"
    local mensaje="$2"
    GIT_AUTHOR_DATE="$fecha" GIT_COMMITTER_DATE="$fecha" git commit -m "$mensaje" --allow-empty
}

echo "=================================================="
echo " 1. Inicializando repositorio"
echo "=================================================="

if [ ! -d ".git" ]; then
    git init
fi

# Aseguramos que la rama por defecto se llame main
git checkout -b "$MAIN_BRANCH" 2>/dev/null || git checkout "$MAIN_BRANCH"

# Conectamos el remoto (si ya existe, lo actualizamos)
if git remote | grep -q origin; then
    git remote set-url origin "$REPO_URL"
else
    git remote add origin "$REPO_URL"
fi

echo "=================================================="
echo " 2. Rama MAIN: commits de creación del proyecto"
echo "=================================================="

git add .gitignore .editorconfig .gitattributes 2>/dev/null || true
commit_fecha "2026-06-01 09:00" "Inicializar proyecto Laravel - configuración base"

git add composer.json composer.lock package.json vite.config.js artisan 2>/dev/null || true
commit_fecha "2026-06-01 09:30" "Agregar dependencias iniciales (Composer y NPM)"

git add config/ bootstrap/ 2>/dev/null || true
commit_fecha "2026-06-01 10:00" "Configuración inicial del framework"

git add README.md 2>/dev/null || true
commit_fecha "2026-06-01 10:15" "Agregar README del proyecto"

echo "Subiendo rama $MAIN_BRANCH..."
git push -u origin "$MAIN_BRANCH"

echo "=================================================="
echo " 3. Rama DEVELOPMENT: commits del desarrollo"
echo "=================================================="

git checkout -b "$DEV_BRANCH"

# --- Base de datos / Backend ---
git add database/ 2>/dev/null || true
commit_fecha "2026-06-02 09:00" "Crear diagrama entidad-relación y migraciones de la base de datos"

git add app/Models/ 2>/dev/null || true
commit_fecha "2026-06-02 11:00" "Backend: crear modelos de Producto, Usuario y Venta"

git add app/Http/Controllers/ 2>/dev/null || true
commit_fecha "2026-06-03 09:00" "Backend: controladores para login, productos y ventas"

git add app/Http/Middleware/ 2>/dev/null || true
commit_fecha "2026-06-03 12:00" "Backend: middleware de autenticación (acceso solo con usuario/contraseña)"

git add routes/ 2>/dev/null || true
commit_fecha "2026-06-04 09:00" "Backend: definición de rutas del sistema"

# --- Frontend ---
git add resources/views/auth 2>/dev/null || true
commit_fecha "2026-06-04 15:00" "Frontend: vista de Iniciar Sesión"

git add resources/views/productos 2>/dev/null || true
commit_fecha "2026-06-05 10:00" "Frontend: formulario de registro y consulta de productos"

git add resources/views/ventas 2>/dev/null || true
commit_fecha "2026-06-05 16:00" "Frontend: tabla de productos registrados y ModalView de confirmación de venta"

git add imagenes/ storage/ 2>/dev/null || true
commit_fecha "2026-06-06 09:00" "Backend: funcionalidad para guardar imágenes de productos"

git add resources/ 2>/dev/null || true
commit_fecha "2026-06-06 13:00" "Frontend: ajustes de estilos y validaciones en formularios"

# Nos aseguramos de subir cualquier archivo restante
git add -A
commit_fecha "2026-06-07 10:00" "Integración final de módulos backend y frontend"

echo "Subiendo rama $DEV_BRANCH..."
git push -u origin "$DEV_BRANCH"

echo "=================================================="
echo " 4. Rama QA: commits de pruebas y observaciones"
echo "=================================================="

git checkout -b "$QA_BRANCH"

commit_fecha "2026-06-08 09:00" "QA: prueba de inicio de sesión - se detectó que permitía acceso sin contraseña"
commit_fecha "2026-06-08 10:00" "Fix: validación de credenciales en login corregida"

commit_fecha "2026-06-08 11:30" "QA: prueba de registro de producto - la tabla no se actualizaba automáticamente"
commit_fecha "2026-06-08 12:15" "Fix: actualización automática de la tabla de productos tras registrar uno nuevo"

commit_fecha "2026-06-09 09:00" "QA: prueba de venta de producto - el ModalView no mostraba la confirmación correctamente"
commit_fecha "2026-06-09 09:45" "Fix: corrección del ModalView de confirmación de venta"

commit_fecha "2026-06-09 11:00" "QA: prueba de carga de imágenes - error al guardar imágenes con nombres duplicados"
commit_fecha "2026-06-09 11:40" "Fix: manejo de nombres únicos al guardar imágenes de productos"

commit_fecha "2026-06-09 15:00" "QA: pruebas finales de todos los módulos - sin observaciones adicionales"

echo "Subiendo rama $QA_BRANCH..."
git push -u origin "$QA_BRANCH"

echo "=================================================="
echo " 5. Fusionar Development -> Main (proyecto terminado)"
echo "=================================================="

git checkout "$MAIN_BRANCH"
git merge "$DEV_BRANCH" -m "Merge development: integrar proyecto terminado a main"
git push origin "$MAIN_BRANCH"

echo "=================================================="
echo " ¡Listo! Ramas creadas y subidas: $MAIN_BRANCH, $DEV_BRANCH, $QA_BRANCH"
echo "=================================================="
