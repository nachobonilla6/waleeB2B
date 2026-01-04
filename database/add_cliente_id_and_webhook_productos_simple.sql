-- Script SQL simple para agregar las columnas
-- Ejecutar este script directamente en la base de datos

-- 1. Agregar cliente_id a rproductos
ALTER TABLE `rproductos` 
ADD COLUMN `cliente_id` BIGINT UNSIGNED NULL AFTER `id`,
ADD CONSTRAINT `rproductos_cliente_id_foreign` 
FOREIGN KEY (`cliente_id`) REFERENCES `clientes_en_proceso` (`id`) ON DELETE CASCADE;

-- 2. Agregar webhook_productos a clientes_en_proceso
ALTER TABLE `clientes_en_proceso` 
ADD COLUMN `webhook_productos` VARCHAR(255) NULL AFTER `webhook_url`;

