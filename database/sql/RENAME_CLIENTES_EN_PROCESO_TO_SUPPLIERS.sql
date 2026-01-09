-- Script SQL para renombrar la tabla clientes_en_proceso a suppliers
-- y actualizar todas las foreign keys relacionadas
-- 
-- IMPORTANTE: Ejecuta cada bloque solo si la tabla existe en tu base de datos

-- Paso 1: Verificar y eliminar foreign keys que referencian clientes_en_proceso
-- Solo ejecuta los ALTER TABLE de las tablas que existen en tu base de datos

-- Eliminar foreign key de rproductos (si la tabla existe)
-- ALTER TABLE `rproductos` DROP FOREIGN KEY IF EXISTS `rproductos_cliente_id_foreign`;

-- Eliminar foreign key de citas (si la tabla existe)
-- ALTER TABLE `citas` DROP FOREIGN KEY IF EXISTS `citas_client_id_foreign`;

-- Eliminar foreign key de posts (si la tabla existe)
-- ALTER TABLE `posts` DROP FOREIGN KEY IF EXISTS `posts_cliente_id_foreign`;

-- Eliminar foreign key de propuestas_personalizadas (si la tabla existe)
-- ALTER TABLE `propuestas_personalizadas` DROP FOREIGN KEY IF EXISTS `propuestas_personalizadas_cliente_id_foreign`;

-- Eliminar foreign key de notes (si la tabla existe)
-- ALTER TABLE `notes` DROP FOREIGN KEY IF EXISTS `notes_client_id_foreign`;

-- Paso 2: Renombrar la tabla
RENAME TABLE `clientes_en_proceso` TO `suppliers`;

-- Paso 3: Recrear las foreign keys solo para las tablas que existen
-- Descomenta solo las tablas que tienes en tu base de datos

-- Recrear foreign key de rproductos (si la tabla existe y tiene la columna cliente_id)
-- ALTER TABLE `rproductos` 
-- ADD CONSTRAINT `rproductos_cliente_id_foreign` 
-- FOREIGN KEY (`cliente_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

-- Recrear foreign key de citas (si la tabla existe y tiene la columna client_id)
-- ALTER TABLE `citas` 
-- ADD CONSTRAINT `citas_client_id_foreign` 
-- FOREIGN KEY (`client_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

-- Recrear foreign key de posts (si la tabla existe y tiene la columna cliente_id)
-- ALTER TABLE `posts` 
-- ADD CONSTRAINT `posts_cliente_id_foreign` 
-- FOREIGN KEY (`cliente_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

-- Recrear foreign key de propuestas_personalizadas (si la tabla existe y tiene la columna cliente_id)
-- ALTER TABLE `propuestas_personalizadas` 
-- ADD CONSTRAINT `propuestas_personalizadas_cliente_id_foreign` 
-- FOREIGN KEY (`cliente_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

-- Recrear foreign key de notes (si la tabla existe y tiene la columna client_id)
-- ALTER TABLE `notes` 
-- ADD CONSTRAINT `notes_client_id_foreign` 
-- FOREIGN KEY (`client_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

