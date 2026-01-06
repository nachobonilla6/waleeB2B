-- SQL para configurar idioma e industria en órdenes programadas de extracción de clientes
-- El campo 'configuracion' ya existe en la tabla ordenes_programadas como JSON

-- 1. Verificar que la tabla tiene el campo configuracion
-- DESCRIBE ordenes_programadas;

-- 2. Actualizar una orden existente de extracción de clientes con idioma e industria
-- Ejemplo: Configurar para extraer clientes en español de la industria de Turismo
UPDATE ordenes_programadas
SET configuracion = JSON_OBJECT(
    'idioma', 'es',
    'industria', 'Turismo'
)
WHERE tipo = 'extraccion_clientes'
AND user_id = 1; -- Reemplazar con el ID del usuario correspondiente

-- 3. Actualizar con idioma inglés y industria Tecnología
UPDATE ordenes_programadas
SET configuracion = JSON_OBJECT(
    'idioma', 'en',
    'industria', 'Tecnología'
)
WHERE tipo = 'extraccion_clientes'
AND user_id = 1;

-- 4. Actualizar con solo idioma (sin industria específica)
UPDATE ordenes_programadas
SET configuracion = JSON_OBJECT(
    'idioma', 'fr'
)
WHERE tipo = 'extraccion_clientes'
AND user_id = 1;

-- 5. Actualizar con solo industria (sin idioma específico)
UPDATE ordenes_programadas
SET configuracion = JSON_OBJECT(
    'industria', 'Salud'
)
WHERE tipo = 'extraccion_clientes'
AND user_id = 1;

-- 6. Limpiar la configuración (dejar NULL)
UPDATE ordenes_programadas
SET configuracion = NULL
WHERE tipo = 'extraccion_clientes'
AND user_id = 1;

-- 7. Consultar órdenes con configuración específica
-- Buscar órdenes de extracción configuradas para español
SELECT 
    id,
    tipo,
    activo,
    recurrencia_horas,
    JSON_EXTRACT(configuracion, '$.idioma') as idioma,
    JSON_EXTRACT(configuracion, '$.industria') as industria,
    configuracion,
    created_at,
    updated_at
FROM ordenes_programadas
WHERE tipo = 'extraccion_clientes'
AND JSON_EXTRACT(configuracion, '$.idioma') = 'es';

-- 8. Consultar órdenes con industria específica
SELECT 
    id,
    tipo,
    activo,
    recurrencia_horas,
    JSON_EXTRACT(configuracion, '$.idioma') as idioma,
    JSON_EXTRACT(configuracion, '$.industria') as industria,
    configuracion,
    created_at,
    updated_at
FROM ordenes_programadas
WHERE tipo = 'extraccion_clientes'
AND JSON_EXTRACT(configuracion, '$.industria') = 'Turismo';

-- 9. Consultar todas las órdenes de extracción con su configuración
SELECT 
    id,
    tipo,
    activo,
    recurrencia_horas,
    JSON_EXTRACT(configuracion, '$.idioma') as idioma,
    JSON_EXTRACT(configuracion, '$.industria') as industria,
    configuracion,
    user_id,
    created_at,
    updated_at
FROM ordenes_programadas
WHERE tipo = 'extraccion_clientes'
ORDER BY updated_at DESC;

-- 10. Crear una nueva orden de extracción con configuración desde el inicio
INSERT INTO ordenes_programadas 
(tipo, activo, recurrencia_horas, last_run, configuracion, user_id, created_at, updated_at)
VALUES 
(
    'extraccion_clientes', 
    1, 
    2.0, 
    NULL, 
    JSON_OBJECT('idioma', 'es', 'industria', 'Turismo'),
    1, -- Reemplazar con el ID del usuario
    NOW(), 
    NOW()
);

-- NOTA: Los valores posibles para idioma son:
-- 'es' (Español), 'en' (English), 'fr' (Français), 'de' (Deutsch), 'it' (Italiano), 'pt' (Português)
-- O NULL para todos los idiomas

-- NOTA: Los valores posibles para industria son:
-- 'Turismo', 'Gastronomía', 'Retail', 'Salud', 'Educación', 'Tecnología', 'Servicios',
-- 'Comercio', 'Manufactura', 'Inmobiliaria', 'Automotriz', 'Belleza y Estética',
-- 'Fitness y Deportes', 'Arte y Cultura', 'Legal', 'Finanzas', 'Marketing',
-- 'Construcción', 'Agricultura', 'Otro'
-- O NULL para todas las industrias

