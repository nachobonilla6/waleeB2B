-- Agregar campo nota a la tabla clientes_en_proceso
-- Ejecutar este SQL directamente en la base de datos

ALTER TABLE `clientes_en_proceso` 
ADD COLUMN `nota` TEXT NULL 
AFTER `token`;

