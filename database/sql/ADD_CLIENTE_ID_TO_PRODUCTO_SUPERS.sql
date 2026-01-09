-- Agregar campo cliente_id a la tabla producto_supers
-- Este campo relaciona los productos con los suppliers (antes clientes_en_proceso)

ALTER TABLE `producto_supers`
ADD COLUMN `cliente_id` BIGINT UNSIGNED NULL
AFTER `id`;

-- Agregar foreign key constraint
ALTER TABLE `producto_supers`
ADD CONSTRAINT `producto_supers_cliente_id_foreign`
FOREIGN KEY (`cliente_id`) REFERENCES `suppliers` (`id`)
ON DELETE CASCADE;

