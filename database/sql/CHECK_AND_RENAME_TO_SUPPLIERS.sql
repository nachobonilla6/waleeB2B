-- Script SQL para verificar y renombrar clientes_en_proceso a suppliers
-- Ejecuta paso por paso

-- PASO 1: Verificar qué foreign keys existen
-- Ejecuta esto primero para ver qué foreign keys tienes:
SELECT 
    CONSTRAINT_NAME, 
    TABLE_NAME,
    COLUMN_NAME
FROM 
    information_schema.KEY_COLUMN_USAGE 
WHERE 
    REFERENCED_TABLE_NAME = 'clientes_en_proceso'
    AND TABLE_SCHEMA = DATABASE();

-- PASO 2: Eliminar las foreign keys que encontraste en el paso 1
-- Copia los CONSTRAINT_NAME y TABLE_NAME de arriba y ejecuta:
-- ALTER TABLE `[TABLE_NAME]` DROP FOREIGN KEY `[CONSTRAINT_NAME]`;

-- Ejemplo (reemplaza con los nombres reales):
-- ALTER TABLE `rproductos` DROP FOREIGN KEY `rproductos_cliente_id_foreign`;
-- ALTER TABLE `citas` DROP FOREIGN KEY `citas_client_id_foreign`;
-- ALTER TABLE `posts` DROP FOREIGN KEY `posts_cliente_id_foreign`;
-- ALTER TABLE `notes` DROP FOREIGN KEY `notes_client_id_foreign`;

-- PASO 3: Renombrar la tabla (EJECUTA ESTO DESPUÉS DE ELIMINAR LAS FOREIGN KEYS)
RENAME TABLE `clientes_en_proceso` TO `suppliers`;

-- PASO 4: Recrear las foreign keys solo para las tablas que existen
-- Verifica qué tablas tienen las columnas antes de ejecutar:

-- Para rproductos (si existe la tabla y la columna cliente_id):
-- ALTER TABLE `rproductos` 
-- ADD CONSTRAINT `rproductos_cliente_id_foreign` 
-- FOREIGN KEY (`cliente_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

-- Para citas (si existe la tabla y la columna client_id):
-- ALTER TABLE `citas` 
-- ADD CONSTRAINT `citas_client_id_foreign` 
-- FOREIGN KEY (`client_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

-- Para posts (si existe la tabla y la columna cliente_id):
-- ALTER TABLE `posts` 
-- ADD CONSTRAINT `posts_cliente_id_foreign` 
-- FOREIGN KEY (`cliente_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

-- Para notes (si existe la tabla y la columna client_id):
-- ALTER TABLE `notes` 
-- ADD CONSTRAINT `notes_client_id_foreign` 
-- FOREIGN KEY (`client_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

