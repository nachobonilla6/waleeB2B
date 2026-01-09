-- Agregar campo stock a la tabla rproductos
ALTER TABLE `rproductos`
ADD COLUMN `stock` INT NOT NULL DEFAULT 0
AFTER `tipo`;

