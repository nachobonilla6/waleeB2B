<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva tu Cita | Salón de Belleza</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f5f0;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #f5d6e6 0%, #e9c4d9 100%);
        }
        .treatment-card {
            transition: all 0.3s ease;
        }
        .treatment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .time-slot {
            transition: all 0.2s ease;
        }
        .time-slot:hover {
            background-color: #f3e8ff;
            border-color: #a78bfa;
        }
        .time-slot.selected {
            background-color: #8b5cf6;
            color: white;
            border-color: #8b5cf6;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="gradient-bg text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Salón de Belleza</h1>
            <p class="text-xl opacity-90">Reserva tu cita para lucir espectacular</p>
        </div>
    </header>

    <main class="container mx-auto px-4 py-12">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="md:flex">
                    <!-- Sección de Tratamientos -->
                    <div class="md:w-1/2 p-6 border-b md:border-b-0 md:border-r border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Selecciona tu Tratamiento</h2>
                        <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                            @foreach($treatments as $treatment)
                            <div class="treatment-card bg-white border border-gray-200 rounded-xl p-4 cursor-pointer hover:shadow-md transition-all duration-300 flex flex-col h-full" 
                                 onclick="selectTreatment({{ $treatment['id'] }})"
                                 id="treatment-{{ $treatment['id'] }}">
                                <div class="flex-1 flex flex-col">
                                    <div class="w-full h-40 mb-3 overflow-hidden rounded-lg">
                                        <img src="{{ $treatment['image'] }}" alt="{{ $treatment['name'] }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-semibold text-gray-800">{{ $treatment['name'] }}</h3>
                                        <div class="flex items-center text-sm text-gray-600 mt-1">
                                            <i class="far fa-clock mr-1"></i>
                                            <span>{{ $treatment['duration'] }}</span>
                                            <span class="mx-2">•</span>
                                            <span class="text-purple-600 font-medium">${{ number_format($treatment['price'], 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Formulario de Reserva -->
                    <div class="md:w-1/2 p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Detalles de la Cita</h2>
                        <form id="appointmentForm" class="space-y-6">
                            <!-- Fecha -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                                <input type="date" id="appointmentDate" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                       min="{{ date('Y-m-d') }}">
                            </div>

                            <!-- Hora -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Hora</label>
                                <div class="grid grid-cols-3 gap-2" id="timeSlots">
                                    @foreach($timeSlots as $time)
                                    <div class="time-slot text-center py-2 border rounded-lg cursor-pointer" 
                                         onclick="selectTimeSlot(this, '{{ $time }}')">
                                        {{ $time }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Información del Cliente -->
                            <div class="pt-4 border-t border-gray-200">
                                <h3 class="text-lg font-medium text-gray-800 mb-4">Tus Datos</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                                        <input type="text" required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                                        <input type="tel" required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                                        <input type="email" required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    </div>
                                </div>
                            </div>

                            <!-- Resumen -->
                            <div class="bg-gray-50 p-4 rounded-lg mt-6">
                                <h3 class="font-medium text-gray-800 mb-3">Resumen de la Cita</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tratamiento:</span>
                                        <span id="selectedTreatment" class="font-medium">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Duración:</span>
                                        <span id="selectedDuration" class="font-medium">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Fecha y Hora:</span>
                                        <span id="selectedDateTime" class="font-medium">-</span>
                                    </div>
                                    <div class="border-t border-gray-200 my-2"></div>
                                    <div class="flex justify-between font-bold text-lg">
                                        <span>Total:</span>
                                        <span id="totalPrice" class="text-purple-600">$0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de Reserva -->
                            <button type="submit" 
                                    class="w-full bg-purple-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-purple-700 transition-colors duration-300">
                                Confirmar Cita
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <p>© {{ date('Y') }} Salón de Belleza. Todos los derechos reservados.</p>
            <div class="flex justify-center space-x-6 mt-4">
                <a href="#" class="text-gray-400 hover:text-white">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-white">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-white">
                    <i class="fab fa-whatsapp"></i>
                </a>
            </div>
        </div>
    </footer>

    <script>
        let selectedTreatment = null;
        let selectedTime = null;
        let selectedDate = null;
        const treatments = @json($treatments);

        function selectTreatment(treatmentId) {
            // Reset previous selection
            document.querySelectorAll('.treatment-card').forEach(el => {
                el.classList.remove('ring-2', 'ring-purple-500');
            });
            
            // Set new selection
            const treatmentElement = document.getElementById(`treatment-${treatmentId}`);
            treatmentElement.classList.add('ring-2', 'ring-purple-500');
            
            // Update selected treatment
            selectedTreatment = treatments.find(t => t.id === treatmentId);
            
            // Update UI
            document.getElementById('selectedTreatment').textContent = selectedTreatment.name;
            document.getElementById('selectedDuration').textContent = selectedTreatment.duration;
            document.getElementById('totalPrice').textContent = `$${selectedTreatment.price}`;
            
            updateDateTime();
        }

        function selectTimeSlot(element, time) {
            // Reset previous selection
            document.querySelectorAll('.time-slot').forEach(el => {
                el.classList.remove('selected', 'bg-purple-600', 'text-white');
            });
            
            // Set new selection
            element.classList.add('selected', 'bg-purple-600', 'text-white');
            selectedTime = time;
            
            updateDateTime();
        }

        document.getElementById('appointmentDate').addEventListener('change', function(e) {
            selectedDate = e.target.value;
            updateDateTime();
        });

        function updateDateTime() {
            if (selectedDate && selectedTime) {
                const date = new Date(selectedDate);
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const formattedDate = date.toLocaleDateString('es-ES', options);
                document.getElementById('selectedDateTime').textContent = 
                    `${formattedDate} a las ${selectedTime}`;
            }
        }

        // Form submission
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!selectedTreatment) {
                alert('Por favor selecciona un tratamiento');
                return;
            }
            
            if (!selectedDate || !selectedTime) {
                alert('Por favor selecciona una fecha y hora');
                return;
            }
            
            // Aquí iría la lógica para enviar los datos al servidor
            alert('¡Cita agendada con éxito! Te esperamos el ' + 
                  document.getElementById('selectedDateTime').textContent);
        });
    </script>
</body>
</html>