<?php

/**
 * Simulación: Envío de Emails vs Agregado de Clientes
 * 
 * Escenario:
 * - Emails enviados: 1 cada 30 minutos
 * - Clientes agregados: X cantidad cada 2 horas
 */

echo "=== ANÁLISIS: ENVÍO DE EMAILS VS AGREGADO DE CLIENTES ===\n\n";

// Configuración
$emailsPorHora = 2; // 1 cada 30 minutos = 2 por hora
$horasPorDia = 24;
$emailsPorDia = $emailsPorHora * $horasPorDia;

echo "📧 EMAILS:\n";
echo "   - Frecuencia: 1 cada 30 minutos\n";
echo "   - Por hora: {$emailsPorHora} emails\n";
echo "   - Por día: {$emailsPorDia} emails\n";
echo "   - Por mes (30 días): " . ($emailsPorDia * 30) . " emails\n\n";

echo "👥 CLIENTES:\n";
echo "   - Frecuencia: Cada 2 horas\n";
echo "   - Períodos por día: " . ($horasPorDia / 2) . " períodos\n\n";

echo "═══════════════════════════════════════════════════════════\n\n";

// Simular diferentes escenarios
$escenarios = [
    ['clientes_cada_2h' => 1, 'descripcion' => 'Escenario Conservador'],
    ['clientes_cada_2h' => 2, 'descripcion' => 'Escenario Balanceado'],
    ['clientes_cada_2h' => 3, 'descripcion' => 'Escenario Agresivo'],
    ['clientes_cada_2h' => 4, 'descripcion' => 'Escenario Crítico'],
    ['clientes_cada_2h' => 5, 'descripcion' => 'Escenario Insostenible'],
];

foreach ($escenarios as $escenario) {
    $clientesCada2h = $escenario['clientes_cada_2h'];
    $clientesPorDia = $clientesCada2h * 12; // 12 períodos de 2 horas en un día
    $ratio = $emailsPorDia / $clientesPorDia;
    
    echo "📊 {$escenario['descripcion']}:\n";
    echo "   Clientes cada 2h: {$clientesCada2h}\n";
    echo "   Clientes por día: {$clientesPorDia}\n";
    echo "   Emails por día: {$emailsPorDia}\n";
    echo "   Ratio: " . number_format($ratio, 2) . " emails por cliente\n";
    
    // Análisis
    if ($ratio >= 2.0) {
        echo "   Estado: ✅ SOBRAN EMAILS (muy seguro)\n";
    } elseif ($ratio >= 1.5) {
        echo "   Estado: ✅ BALANCEADO (saludable)\n";
    } elseif ($ratio >= 1.0) {
        echo "   Estado: ⚠️  JUSTO (poco margen)\n";
    } elseif ($ratio >= 0.8) {
        echo "   Estado: ⚠️  CRÍTICO (riesgo alto)\n";
    } else {
        echo "   Estado: ❌ INSUFICIENTE (se quedarán sin emails)\n";
    }
    
    // Simulación de acumulación
    $backlog = 0;
    $diasSimulados = 7;
    $clientesSinEmail = [];
    
    for ($dia = 1; $dia <= $diasSimulados; $dia++) {
        $clientesNuevos = $clientesPorDia;
        $clientesPendientes = $backlog;
        $totalClientes = $clientesNuevos + $clientesPendientes;
        $emailsDisponibles = $emailsPorDia;
        
        if ($totalClientes <= $emailsDisponibles) {
            $backlog = 0;
        } else {
            $backlog = $totalClientes - $emailsDisponibles;
        }
        
        $clientesSinEmail[] = $backlog;
    }
    
    $backlogPromedio = array_sum($clientesSinEmail) / count($clientesSinEmail);
    $backlogMaximo = max($clientesSinEmail);
    
    echo "   Backlog promedio (7 días): " . number_format($backlogPromedio, 1) . " clientes\n";
    echo "   Backlog máximo: {$backlogMaximo} clientes\n";
    
    if ($backlogMaximo > 0) {
        $diasParaQuedarseSin = $emailsPorDia > 0 ? ceil($backlogMaximo / $emailsPorDia) : '∞';
        echo "   ⚠️  Se acumulará backlog constante\n";
    } else {
        echo "   ✅ No se acumula backlog\n";
    }
    
    echo "\n";
}

echo "═══════════════════════════════════════════════════════════\n\n";

// Recomendaciones
echo "💡 RECOMENDACIONES:\n\n";
echo "1. Para mantener balance saludable:\n";
echo "   - Máximo 2-3 clientes cada 2 horas (24-36 clientes/día)\n";
echo "   - Ratio recomendado: 1.5 - 2.5 emails por cliente\n";
echo "   - Margen de seguridad: Mantener al menos 1.5 emails por cliente\n\n";

echo "2. Si se agregan más de 3 clientes cada 2 horas:\n";
echo "   - Aumentar frecuencia de envío (cada 20-25 minutos)\n";
echo "   - O reducir la cantidad de clientes agregados\n";
echo "   - Implementar sistema de priorización\n\n";

echo "3. Fórmula para calcular:\n";
echo "   Ratio = (48 emails/día) / (Clientes cada 2h × 12)\n";
echo "   Si Ratio < 1.5 → ⚠️  Riesgo\n";
echo "   Si Ratio < 1.0 → ❌ Insuficiente\n\n";

// Simulación de diferentes cantidades de clientes
echo "═══════════════════════════════════════════════════════════\n\n";
echo "📈 TABLA DE RESULTADOS:\n\n";
echo str_pad("Clientes/2h", 15) . str_pad("Clientes/día", 15) . str_pad("Ratio", 12) . "Estado\n";
echo str_repeat("-", 70) . "\n";

for ($clientes = 1; $clientes <= 6; $clientes++) {
    $clientesPorDia = $clientes * 12;
    $ratio = $emailsPorDia / $clientesPorDia;
    
    $estado = '';
    if ($ratio >= 2.0) {
        $estado = '✅ Sobran emails';
    } elseif ($ratio >= 1.5) {
        $estado = '✅ Balanceado';
    } elseif ($ratio >= 1.0) {
        $estado = '⚠️  Justo';
    } elseif ($ratio >= 0.8) {
        $estado = '⚠️  Crítico';
    } else {
        $estado = '❌ Insuficiente';
    }
    
    echo str_pad($clientes, 15) . 
         str_pad($clientesPorDia, 15) . 
         str_pad(number_format($ratio, 2), 12) . 
         $estado . "\n";
}

echo "\n";

