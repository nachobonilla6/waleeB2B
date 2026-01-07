-- SQL para agregar campos a la tabla gastos
-- Ejecutar este script en tu base de datos MySQL/MariaDB

-- Agregar campo descripcion (TEXT, nullable) después de nombre
ALTER TABLE `gastos` 
ADD COLUMN `descripcion` TEXT NULL AFTER `nombre`;

-- Agregar campo tipo (ENUM: mensual/anual, default mensual) después de descripcion
ALTER TABLE `gastos` 
ADD COLUMN `tipo` ENUM('mensual', 'anual') NOT NULL DEFAULT 'mensual' AFTER `descripcion`;

-- Agregar campo proxima_fecha_pago (DATE, nullable) después de fecha
ALTER TABLE `gastos` 
ADD COLUMN `proxima_fecha_pago` DATE NULL AFTER `fecha`;

-- Verificar que los campos se agregaron correctamente
-- DESCRIBE `gastos`;


