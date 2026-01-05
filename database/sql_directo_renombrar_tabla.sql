-- Comandos SQL directos para renombrar la tabla y agregar el campo tipo

-- 1. Agregar el campo 'tipo' a la tabla propuestas_personalizadas
ALTER TABLE propuestas_personalizadas 
ADD COLUMN tipo VARCHAR(50) DEFAULT 'propuesta_personalizada' AFTER id;

-- 2. Actualizar todos los registros existentes (por si acaso)
UPDATE propuestas_personalizadas 
SET tipo = 'propuesta_personalizada' 
WHERE tipo IS NULL OR tipo = '';

-- 3. Renombrar la tabla de propuestas_personalizadas a emails
RENAME TABLE propuestas_personalizadas TO emails;

-- Verificar que todo está correcto
DESCRIBE emails;

