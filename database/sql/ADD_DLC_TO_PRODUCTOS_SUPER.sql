-- Agregar campo DLC (Date Last Changed) a la tabla producto_supers
ALTER TABLE `producto_supers` 
ADD COLUMN `dlc` DATE NULL AFTER `fecha_salida`;

