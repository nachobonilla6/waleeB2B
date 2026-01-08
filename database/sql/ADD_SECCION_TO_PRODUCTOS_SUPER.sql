-- Agregar campo seccion a la tabla producto_supers
ALTER TABLE `producto_supers` 
ADD COLUMN `seccion` VARCHAR(255) NULL AFTER `categoria`;

