-- Agregar campo google_event_id a la tabla tareas
-- Este campo almacena el ID del evento en Google Calendar para sincronización bidireccional
-- Base de datos: support

USE `support`;

ALTER TABLE `tareas` 
ADD COLUMN `google_event_id` VARCHAR(255) NULL 
AFTER `estado`;

-- Verificar que el campo se agregó correctamente
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'support' 
AND TABLE_NAME = 'tareas' 
AND COLUMN_NAME = 'google_event_id';

