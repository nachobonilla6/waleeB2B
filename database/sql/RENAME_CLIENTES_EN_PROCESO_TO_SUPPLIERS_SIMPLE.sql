-- Script SQL SIMPLIFICADO para renombrar la tabla clientes_en_proceso a suppliers
-- Solo ejecuta los comandos de las tablas que realmente existen en tu base de datos

-- PRIMERO: Verifica qué foreign keys existen ejecutando esto:
-- SELECT CONSTRAINT_NAME, TABLE_NAME 
-- FROM information_schema.KEY_COLUMN_USAGE 
-- WHERE REFERENCED_TABLE_NAME = 'clientes_en_proceso';

-- Paso 1: Eliminar foreign keys (ejecuta solo los de las tablas que existen)
-- Copia el CONSTRAINT_NAME de la consulta anterior y úsalo aquí:

-- Ejemplo para rproductos (si existe):
-- ALTER TABLE `rproductos` DROP FOREIGN KEY `[NOMBRE_REAL_DE_LA_FK]`;

-- Ejemplo para citas (si existe):
-- ALTER TABLE `citas` DROP FOREIGN KEY `[NOMBRE_REAL_DE_LA_FK]`;

-- Ejemplo para posts (si existe):
-- ALTER TABLE `posts` DROP FOREIGN KEY `[NOMBRE_REAL_DE_LA_FK]`;

-- Ejemplo para notes (si existe):
-- ALTER TABLE `notes` DROP FOREIGN KEY `[NOMBRE_REAL_DE_LA_FK]`;

-- Paso 2: Renombrar la tabla (ESTE ES EL COMANDO PRINCIPAL)
RENAME TABLE `clientes_en_proceso` TO `suppliers`;

-- Paso 3: Recrear foreign keys solo para las tablas que existen
-- Descomenta y ejecuta solo los de las tablas que tienes:

-- Si rproductos existe y tiene columna cliente_id:
-- ALTER TABLE `rproductos` 
-- ADD CONSTRAINT `rproductos_cliente_id_foreign` 
-- FOREIGN KEY (`cliente_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

-- Si citas existe y tiene columna client_id:
-- ALTER TABLE `citas` 
-- ADD CONSTRAINT `citas_client_id_foreign` 
-- FOREIGN KEY (`client_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

-- Si posts existe y tiene columna cliente_id:
-- ALTER TABLE `posts` 
-- ADD CONSTRAINT `posts_cliente_id_foreign` 
-- FOREIGN KEY (`cliente_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

-- Si notes existe y tiene columna client_id:
-- ALTER TABLE `notes` 
-- ADD CONSTRAINT `notes_client_id_foreign` 
-- FOREIGN KEY (`client_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

