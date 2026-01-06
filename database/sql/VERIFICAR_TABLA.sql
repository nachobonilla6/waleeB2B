-- ============================================
-- VERIFICAR SI LA TABLA EXISTE
-- ============================================

-- Verificar si la tabla existe
SHOW TABLES LIKE 'ordenes_programadas';

-- Si existe, mostrar su estructura
DESCRIBE ordenes_programadas;

-- Ver todas las órdenes programadas
SELECT * FROM ordenes_programadas;

-- Ver órdenes activas
SELECT * FROM ordenes_programadas WHERE activo = 1;

-- Ver órdenes pendientes (para n8n)
SELECT *
FROM ordenes_programadas
WHERE activo = 1
AND (
  last_run IS NULL
  OR last_run <= NOW() - INTERVAL 30 MINUTE
);

