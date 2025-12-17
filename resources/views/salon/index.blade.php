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
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            color: #e2e8f0;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 50%, #be185d 100%);
            backdrop-filter: blur(10px);
        }
        .header-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(236, 72, 153, 0.3);
        }
        .main-content {
            margin-top: 180px;
        }
        .dark-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(236, 72, 153, 0.2);
        }
        .treatment-card {
            transition: all 0.3s ease;
            position: relative;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 2px solid rgba(236, 72, 153, 0.2);
        }
        .treatment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(236, 72, 153, 0.4);
            border-color: rgba(236, 72, 153, 0.5);
        }
        .treatment-card.selected {
            border: 3px solid #ec4899;
            box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.2), 0 10px 30px rgba(236, 72, 153, 0.3);
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
        .time-slot {
            transition: all 0.2s ease;
            position: relative;
            background: #1e293b;
            border: 2px solid rgba(236, 72, 153, 0.3);
            color: #e2e8f0;
        }
        .time-slot:hover:not(.disabled) {
            background-color: rgba(236, 72, 153, 0.2);
            border-color: #ec4899;
            transform: scale(1.05);
            color: #fff;
        }
        .time-slot.selected {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
            color: white;
            border-color: #ec4899;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);
        }
        .time-slot.disabled {
            opacity: 0.3;
            cursor: not-allowed;
            background-color: #0f172a;
            border-color: rgba(236, 72, 153, 0.1);
        }
        .appointment-item {
            animation: slideIn 0.3s ease;
            background: linear-gradient(135deg, rgba(236, 72, 153, 0.1) 0%, rgba(219, 39, 119, 0.1) 100%);
            border: 2px solid rgba(236, 72, 153, 0.3);
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .cart-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
        .smooth-scroll {
            scroll-behavior: smooth;
        }
        .pink-accent {
            color: #ec4899;
        }
        .pink-gradient {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
        }
        .dark-input {
            background: #1e293b;
            border: 2px solid rgba(236, 72, 153, 0.3);
            color: #e2e8f0;
        }
        .dark-input:focus {
            border-color: #ec4899;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.2);
        }
        .dark-input::placeholder {
            color: #64748b;
        }
        .dark-bg-section {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(236, 72, 153, 0.2);
        }
        .pink-text {
            color: #ec4899;
        }
        .pink-border {
            border-color: rgba(236, 72, 153, 0.3);
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header Fijo -->
    <header class="gradient-bg text-white py-6 shadow-lg header-fixed">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold mb-2">✨ Salón de Belleza</h1>
                    <p class="text-lg opacity-90">Reserva tus citas de forma fácil y rápida</p>
                </div>
                <div class="relative">
                    <button onclick="toggleCart()" class="relative bg-white/20 hover:bg-white/30 rounded-full p-4 transition-all duration-300">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                        <span id="cartBadge" class="cart-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center" style="display: none;">0</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8 main-content">
        <div class="max-w-7xl mx-auto">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="text-sm text-gray-400">
                    <ol class="flex items-center space-x-2">
                        <li><a href="/" class="hover:pink-accent transition-colors">Inicio</a></li>
                        <li><i class="fas fa-chevron-right text-xs"></i></li>
                        <li class="text-gray-300 font-medium">Reservar Citas</li>
                    </ol>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Sección de Tratamientos (2/3) -->
                <div class="lg:col-span-2">
                    <div class="dark-card rounded-2xl shadow-xl p-6 mb-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-white">
                                <i class="fas fa-spa pink-accent mr-2"></i>
                                Selecciona tus Tratamientos
                            </h2>
                            <span class="text-sm text-gray-400">Puedes elegir varios</span>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4 smooth-scroll" id="treatmentsContainer">
                            @foreach($treatments as $treatment)
                            <div class="treatment-card rounded-xl p-4 cursor-pointer transition-all duration-300" 
                                 onclick="toggleTreatment({{ $treatment['id'] }})"
                                 id="treatment-{{ $treatment['id'] }}"
                                 data-treatment='@json($treatment)'>
                                <div class="flex items-start space-x-4">
                                    <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-lg border-2 border-pink-500/30">
                                        <img src="{{ $treatment['image'] }}" alt="{{ $treatment['name'] }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-white text-lg mb-2">{{ $treatment['name'] }}</h3>
                                        <div class="flex items-center text-sm text-gray-400 mb-2">
                                            <i class="far fa-clock mr-2 pink-accent"></i>
                                            <span>{{ $treatment['duration'] }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-2xl font-bold pink-accent">${{ number_format($treatment['price'], 0) }}</span>
                                            <div class="check-icon hidden">
                                                <i class="fas fa-check-circle text-2xl pink-accent"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Formulario para Agregar Cita -->
                    <div class="dark-card rounded-2xl shadow-xl p-6" id="appointmentFormSection">
                        <h2 class="text-2xl font-bold text-white mb-6">
                            <i class="fas fa-calendar-plus pink-accent mr-2"></i>
                            Agregar Nueva Cita
                        </h2>
                        <form id="appointmentForm" class="space-y-6">
                            <!-- Fecha -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    <i class="far fa-calendar mr-2 pink-accent"></i>
                                    Fecha
                                </label>
                                <input type="date" id="appointmentDate" 
                                       class="dark-input w-full px-4 py-3 rounded-lg transition-all"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                            </div>

                            <!-- Hora -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    <i class="far fa-clock mr-2 pink-accent"></i>
                                    Hora
                                </label>
                                <div class="grid grid-cols-4 md:grid-cols-6 gap-2" id="timeSlots">
                                    @foreach($timeSlots as $time)
                                    <button type="button" 
                                            class="time-slot text-center py-2 px-3 border-2 border-gray-300 rounded-lg cursor-pointer text-sm font-medium"
                                            onclick="selectTimeSlot(this, '{{ $time }}')"
                                            data-time="{{ $time }}">
                                        {{ $time }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Botón para agregar cita -->
                            <button type="button" 
                                    onclick="addAppointment()"
                                    class="w-full pink-gradient text-white py-3 px-6 rounded-lg font-semibold hover:opacity-90 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-plus mr-2"></i>
                                Agregar Cita al Carrito
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Sidebar - Carrito de Citas (1/3) -->
                <div class="lg:col-span-1">
                    <div class="dark-card rounded-2xl shadow-xl p-6 sticky top-6" id="cartSidebar">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-white">
                                <i class="fas fa-shopping-cart pink-accent mr-2"></i>
                                Mis Citas
                            </h2>
                            <button onclick="clearCart()" class="text-sm text-red-600 hover:text-red-700">
                                <i class="fas fa-trash mr-1"></i>
                                Limpiar
                            </button>
                        </div>

                        <!-- Lista de Citas -->
                        <div id="appointmentsList" class="space-y-3 max-h-[500px] overflow-y-auto smooth-scroll mb-6">
                            <div class="text-center text-gray-400 py-8" id="emptyCart">
                                <i class="fas fa-calendar-times text-4xl mb-3 opacity-50"></i>
                                <p>No hay citas agregadas</p>
                                <p class="text-sm mt-2">Selecciona tratamientos y agrega fechas</p>
                            </div>
                        </div>

                        <!-- Resumen -->
                        <div class="border-t pink-border pt-4">
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm text-gray-400">
                                    <span>Citas:</span>
                                    <span id="totalAppointments" class="font-medium text-white">0</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-400">
                                    <span>Tratamientos:</span>
                                    <span id="totalTreatments" class="font-medium text-white">0</span>
                                </div>
                                <div class="border-t pink-border pt-2"></div>
                                <div class="flex justify-between text-lg font-bold">
                                    <span class="text-white">Total:</span>
                                    <span id="totalPrice" class="pink-accent">$0</span>
                                </div>
                            </div>

                            <!-- Información del Cliente -->
                            <div class="dark-bg-section p-4 rounded-lg mb-4 border pink-border">
                                <h3 class="font-medium text-white mb-3">
                                    <i class="fas fa-user mr-2 pink-accent"></i>
                                    Tus Datos
                                </h3>
                                <div class="space-y-3">
                                    <input type="text" id="customerName" placeholder="Nombre Completo" required
                                           class="dark-input w-full px-3 py-2 rounded-lg text-sm">
                                    <input type="tel" id="customerPhone" placeholder="Teléfono" required
                                           class="dark-input w-full px-3 py-2 rounded-lg text-sm">
                                    <input type="email" id="customerEmail" placeholder="Correo Electrónico" required
                                           class="dark-input w-full px-3 py-2 rounded-lg text-sm">
                                </div>
                            </div>

                            <!-- Botón de Confirmación -->
                            <button onclick="confirmAppointments()" 
                                    id="confirmButton"
                                    disabled
                                    class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-green-600 hover:to-emerald-700 transition-all duration-300 transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                <i class="fas fa-check-circle mr-2"></i>
                                Confirmar Todas las Citas
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="mb-4">© {{ date('Y') }} Salón de Belleza. Todos los derechos reservados.</p>
            <div class="flex justify-center space-x-6">
                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fab fa-facebook-f text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fab fa-instagram text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors">
                    <i class="fab fa-whatsapp text-xl"></i>
                </a>
            </div>
        </div>
    </footer>

    <script>
        let selectedTreatments = new Set();
        let appointments = [];
        const treatments = @json($treatments);
        let selectedTime = null;
        let selectedDate = null;

        // Toggle tratamiento seleccionado
        function toggleTreatment(treatmentId) {
            const treatmentElement = document.getElementById(`treatment-${treatmentId}`);
            const checkIcon = treatmentElement.querySelector('.check-icon');
            
            if (selectedTreatments.has(treatmentId)) {
                selectedTreatments.delete(treatmentId);
                treatmentElement.classList.remove('selected');
                checkIcon.classList.add('hidden');
            } else {
                selectedTreatments.add(treatmentId);
                treatmentElement.classList.add('selected');
                checkIcon.classList.remove('hidden');
            }
            
            updateCartBadge();
            updateSummary();
        }

        // Seleccionar hora
        function selectTimeSlot(element, time) {
            document.querySelectorAll('.time-slot').forEach(el => {
                el.classList.remove('selected');
            });
            element.classList.add('selected');
            selectedTime = time;
        }

        // Agregar cita al carrito
        function addAppointment() {
            const date = document.getElementById('appointmentDate').value;
            
            if (!date) {
                alert('Por favor selecciona una fecha');
                return;
            }
            
            if (!selectedTime) {
                alert('Por favor selecciona una hora');
                return;
            }
            
            if (selectedTreatments.size === 0) {
                alert('Por favor selecciona al menos un tratamiento');
                return;
            }

            // Crear cita con todos los tratamientos seleccionados
            const appointmentTreatments = Array.from(selectedTreatments).map(id => {
                return treatments.find(t => t.id === id);
            });

            const appointment = {
                id: Date.now(),
                date: date,
                time: selectedTime,
                treatments: appointmentTreatments,
                total: appointmentTreatments.reduce((sum, t) => sum + t.price, 0)
            };

            appointments.push(appointment);
            renderAppointments();
            updateSummary();
            updateCartBadge();
            
            // Reset form
            document.getElementById('appointmentDate').value = '';
            selectedTime = null;
            document.querySelectorAll('.time-slot').forEach(el => {
                el.classList.remove('selected');
            });
        }

        // Renderizar citas
        function renderAppointments() {
            const container = document.getElementById('appointmentsList');
            const emptyCart = document.getElementById('emptyCart');
            
            if (appointments.length === 0) {
                emptyCart.style.display = 'block';
                container.innerHTML = '';
                container.appendChild(emptyCart);
                return;
            }
            
            emptyCart.style.display = 'none';
            
            container.innerHTML = appointments.map(appointment => {
                const date = new Date(appointment.date);
                const formattedDate = date.toLocaleDateString('es-ES', { 
                    weekday: 'short', 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                });
                
                const treatmentsList = appointment.treatments.map(t => 
                    `<span class="inline-block bg-pink-500/20 text-pink-300 text-xs px-2 py-1 rounded mr-1 mb-1 border border-pink-500/30">${t.name}</span>`
                ).join('');
                
                return `
                    <div class="appointment-item rounded-lg p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <div class="font-semibold text-white mb-1">
                                    <i class="far fa-calendar pink-accent mr-1"></i>
                                    ${formattedDate}
                                </div>
                                <div class="text-sm text-gray-400 mb-2">
                                    <i class="far fa-clock pink-accent mr-1"></i>
                                    ${appointment.time}
                                </div>
                                <div class="text-xs text-gray-500 mb-2">
                                    ${treatmentsList}
                                </div>
                                <div class="font-bold pink-accent">
                                    $${appointment.total.toLocaleString()}
                                </div>
                            </div>
                            <button onclick="removeAppointment(${appointment.id})" 
                                    class="text-red-400 hover:text-red-300 ml-2 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Eliminar cita
        function removeAppointment(id) {
            appointments = appointments.filter(a => a.id !== id);
            renderAppointments();
            updateSummary();
            updateCartBadge();
        }

        // Limpiar carrito
        function clearCart() {
            if (confirm('¿Estás seguro de que deseas eliminar todas las citas?')) {
                appointments = [];
                selectedTreatments.clear();
                document.querySelectorAll('.treatment-card').forEach(el => {
                    el.classList.remove('selected');
                    el.querySelector('.check-icon').classList.add('hidden');
                });
                renderAppointments();
                updateSummary();
                updateCartBadge();
            }
        }

        // Actualizar resumen
        function updateSummary() {
            const totalAppointments = appointments.length;
            const totalTreatments = appointments.reduce((sum, a) => sum + a.treatments.length, 0);
            const totalPrice = appointments.reduce((sum, a) => sum + a.total, 0);
            
            document.getElementById('totalAppointments').textContent = totalAppointments;
            document.getElementById('totalTreatments').textContent = totalTreatments;
            document.getElementById('totalPrice').textContent = `$${totalPrice.toLocaleString()}`;
            
            const confirmButton = document.getElementById('confirmButton');
            confirmButton.disabled = appointments.length === 0;
        }

        // Actualizar badge del carrito
        function updateCartBadge() {
            const badge = document.getElementById('cartBadge');
            const totalItems = appointments.length;
            
            if (totalItems > 0) {
                badge.style.display = 'flex';
                badge.textContent = totalItems;
            } else {
                badge.style.display = 'none';
            }
        }

        // Toggle carrito (móvil)
        function toggleCart() {
            const sidebar = document.getElementById('cartSidebar');
            sidebar.classList.toggle('hidden');
        }

        // Confirmar citas
        function confirmAppointments() {
            const name = document.getElementById('customerName').value.trim();
            const phone = document.getElementById('customerPhone').value.trim();
            const email = document.getElementById('customerEmail').value.trim();
            
            if (!name || !phone || !email) {
                alert('Por favor completa todos tus datos');
                return;
            }
            
            if (appointments.length === 0) {
                alert('Por favor agrega al menos una cita');
                return;
            }
            
            // Aquí iría la lógica para enviar los datos al servidor
            const summary = appointments.map(a => {
                const date = new Date(a.date);
                return `${date.toLocaleDateString('es-ES')} a las ${a.time} - ${a.treatments.map(t => t.name).join(', ')}`;
            }).join('\n');
            
            alert(`¡Citas confirmadas exitosamente!\n\n${summary}\n\nTotal: $${appointments.reduce((sum, a) => sum + a.total, 0).toLocaleString()}\n\nTe esperamos!`);
            
            // Limpiar después de confirmar
            clearCart();
            document.getElementById('customerName').value = '';
            document.getElementById('customerPhone').value = '';
            document.getElementById('customerEmail').value = '';
        }

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            updateSummary();
            updateCartBadge();
        });
    </script>
</body>
</html>

