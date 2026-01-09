-- Script SQL para renombrar la tabla clientes_en_proceso a suppliers
-- y actualizar todas las foreign keys relacionadas

-- Paso 1: Eliminar todas las foreign keys que referencian clientes_en_proceso

-- Eliminar foreign key de rproductos
ALTER TABLE `rproductos` DROP FOREIGN KEY IF EXISTS `rproductos_cliente_id_foreign`;

-- Eliminar foreign key de citas
ALTER TABLE `citas` DROP FOREIGN KEY IF EXISTS `citas_client_id_foreign`;

-- Eliminar foreign key de posts
ALTER TABLE `posts` DROP FOREIGN KEY IF EXISTS `posts_cliente_id_foreign`;

-- Eliminar foreign key de propuestas_personalizadas
ALTER TABLE `propuestas_personalizadas` DROP FOREIGN KEY IF EXISTS `propuestas_personalizadas_cliente_id_foreign`;

-- Eliminar foreign key de notes
ALTER TABLE `notes` DROP FOREIGN KEY IF EXISTS `notes_client_id_foreign`;

-- Paso 2: Renombrar la tabla
RENAME TABLE `clientes_en_proceso` TO `suppliers`;

-- Paso 3: Recrear todas las foreign keys con el nuevo nombre de tabla

-- Recrear foreign key de rproductos
ALTER TABLE `rproductos` 
ADD CONSTRAINT `rproductos_cliente_id_foreign` 
FOREIGN KEY (`cliente_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

-- Recrear foreign key de citas
ALTER TABLE `citas` 
ADD CONSTRAINT `citas_client_id_foreign` 
FOREIGN KEY (`client_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

-- Recrear foreign key de posts
ALTER TABLE `posts` 
ADD CONSTRAINT `posts_cliente_id_foreign` 
FOREIGN KEY (`cliente_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

-- Recrear foreign key de propuestas_personalizadas
ALTER TABLE `propuestas_personalizadas` 
ADD CONSTRAINT `propuestas_personalizadas_cliente_id_foreign` 
FOREIGN KEY (`cliente_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

-- Recrear foreign key de notes
ALTER TABLE `notes` 
ADD CONSTRAINT `notes_client_id_foreign` 
FOREIGN KEY (`client_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

