@extends('layouts.pptk')

@section('title', 'Jadwal Kunjungan Dinas - Kalender Interaktif')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-green-800 dark:text-green-100 mb-2">Jadwal Kunjungan</h1>
                <p class="text-gray-600 dark:text-gray-300">Kalender interaktif kegiatan kunjungan dan penelitian</p>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded-lg flex items-center shadow-sm">
                <span class="material-icons mr-2">check_circle</span>{{ session('success') }}
            </div>
        @endif

        <!-- Calendar Container -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden">
            <!-- Calendar Header -->
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center space-x-4 mb-4 md:mb-0">
                    <button id="prevMonth" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
                        <span class="material-icons">chevron_left</span>
                    </button>
                    <h2 id="currentMonthYear" class="text-xl font-bold text-gray-800 dark:text-gray-100 min-w-[200px] text-center capitalize">
                        <!-- Month Year will be injected here -->
                    </h2>
                    <button id="nextMonth" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
                        <span class="material-icons">chevron_right</span>
                    </button>
                </div>
                
                <!-- Legend -->
                <div class="flex flex-wrap gap-3 justify-center">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-yellow-400 mr-2"></span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Menunggu/Terjadwal</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Disetujui/Selesai</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Dibatalkan</span>
                    </div>
                </div>
            </div>

            <!-- Calendar Grid Header (Days) -->
            <div class="grid grid-cols-7 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
                @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                    <div class="py-3 text-center text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Grid Body -->
            <div id="calendarGrid" class="grid grid-cols-7 auto-rows-fr bg-gray-200 dark:bg-gray-700 gap-px">
                <!-- Days will be injected here -->
                <div class="col-span-7 py-12 text-center bg-white dark:bg-gray-800">
                    <span class="material-icons animate-spin text-green-600 text-3xl">refresh</span>
                    <p class="mt-2 text-gray-500">Memuat jadwal...</p>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <div id="eventModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" id="modalOverlay"></div>

                <!-- Modal panel -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200 dark:border-gray-700">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div id="modalIconContainer" class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 sm:mx-0 sm:h-10 sm:w-10">
                                <span class="material-icons text-green-600 dark:text-green-400">event</span>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modalTitle">
                                    Detail Kunjungan
                                </h3>
                                <div class="mt-4 space-y-3">
                                    <div class="flex items-start">
                                        <span class="material-icons text-gray-400 text-sm mt-1 mr-2">event</span>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal & Waktu</p>
                                            <p id="modalDate" class="text-sm font-medium text-gray-800 dark:text-gray-200"></p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-start">
                                        <span class="material-icons text-gray-400 text-sm mt-1 mr-2">location_on</span>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Lokasi</p>
                                            <p id="modalLocation" class="text-sm font-medium text-gray-800 dark:text-gray-200"></p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <span class="material-icons text-gray-400 text-sm mt-1 mr-2">group</span>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Peneliti / Peserta</p>
                                            <p id="modalResearchers" class="text-sm font-medium text-gray-800 dark:text-gray-200"></p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <span class="material-icons text-gray-400 text-sm mt-1 mr-2">description</span>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Nama Kegiatan</p>
                                            <p id="modalDescription" class="text-sm font-medium text-gray-800 dark:text-gray-200"></p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <span class="material-icons text-gray-400 text-sm mt-1 mr-2">info</span>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                                            <span id="modalStatus" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" id="closeModalBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentDate = new Date();
        const calendarGrid = document.getElementById('calendarGrid');
        const currentMonthYear = document.getElementById('currentMonthYear');
        const eventModal = document.getElementById('eventModal');
        const modalOverlay = document.getElementById('modalOverlay');
        const closeModalBtn = document.getElementById('closeModalBtn');
        let events = [];

        // Navigation Buttons
        document.getElementById('prevMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            fetchEventsAndRender();
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            fetchEventsAndRender();
        });

        // Modal Controls
        function closeModal() {
            eventModal.classList.add('hidden');
        }

        closeModalBtn.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', closeModal);

        // Fetch Data
        function fetchEventsAndRender() {
            const month = currentDate.getMonth() + 1;
            const year = currentDate.getFullYear();
            
            // Show loading
            calendarGrid.innerHTML = `
                <div class="col-span-7 py-12 text-center bg-white dark:bg-gray-800">
                    <span class="material-icons animate-spin text-green-600 text-3xl">refresh</span>
                    <p class="mt-2 text-gray-500">Memuat jadwal...</p>
                </div>
            `;
            currentMonthYear.textContent = currentDate.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });

            fetch(`{{ route('visits.index') }}?month=${month}&year=${year}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                events = data;
                renderCalendar();
            })
            .catch(error => {
                console.error('Error fetching events:', error);
                calendarGrid.innerHTML = `<div class="col-span-7 py-4 text-center text-red-500">Gagal memuat data.</div>`;
            });
        }

        function renderCalendar() {
            calendarGrid.innerHTML = '';
            
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();
            
            const firstDayOfMonth = new Date(year, month, 1).getDay(); // 0 = Sunday
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            
            // Adjust for Monday start (default Date.getDay() is Sunday=0)
            // We want Monday=0, Sunday=6
            // If Sunday (0), becomes 6. If Mon (1), becomes 0.
            // Formula: (day + 6) % 7
            // Wait, standard calendar usually starts Sunday or Monday. The header says Min, Sen, ...
            // Ah, header in Blade: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] -> Sunday start.
            // So default getDay() is fine (0=Sunday).
            
            const paddingDays = firstDayOfMonth; 

            // Padding days (previous month)
            for (let i = 0; i < paddingDays; i++) {
                const dayDiv = document.createElement('div');
                dayDiv.className = 'bg-gray-100 dark:bg-gray-800/50 min-h-[100px] p-2 opacity-50';
                calendarGrid.appendChild(dayDiv);
            }

            // Days of month
            for (let i = 1; i <= daysInMonth; i++) {
                const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                const dayEvents = events.filter(e => e.date === dateStr);
                
                const dayDiv = document.createElement('div');
                dayDiv.className = `bg-white dark:bg-gray-800 min-h-[100px] p-2 border-t border-l border-gray-100 dark:border-gray-700 relative hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer group`;
                
                // Date Number
                const dateNum = document.createElement('div');
                dateNum.className = `text-sm font-semibold mb-1 ${
                    isToday(i, month, year) 
                    ? 'bg-green-600 text-white w-6 h-6 rounded-full flex items-center justify-center' 
                    : 'text-gray-700 dark:text-gray-300'
                }`;
                dateNum.textContent = i;
                dayDiv.appendChild(dateNum);

                // Events Container
                const eventsContainer = document.createElement('div');
                eventsContainer.className = 'space-y-1';
                
                dayEvents.forEach(event => {
                    const eventEl = document.createElement('div');
                    // Color coding based on status
                    let colorClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200 border-blue-200';
                    if (event.status === 'completed' || event.status === 'disetujui') { // Assuming mapped status or existing
                         colorClass = 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200 border-green-200';
                    } else if (event.status === 'cancelled') {
                        colorClass = 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200 border-red-200';
                    } else if (event.status === 'scheduled') {
                        colorClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-200 border-yellow-200';
                    }

                    eventEl.className = `text-xs px-1.5 py-1 rounded border ${colorClass} flex items-center gap-1.5 min-w-0`;

                    if (event.photo_url) {
                        const thumbnail = document.createElement('img');
                        thumbnail.src = event.photo_url;
                        thumbnail.alt = event.title || 'Foto kunjungan';
                        thumbnail.className = 'h-6 w-6 flex-none rounded object-cover border border-white/70 dark:border-gray-700';
                        eventEl.appendChild(thumbnail);
                    }

                    const eventTitle = document.createElement('span');
                    eventTitle.className = 'truncate';
                    eventTitle.textContent = event.title || 'Kunjungan';
                    eventEl.appendChild(eventTitle);

                    eventsContainer.appendChild(eventEl);
                });

                if (dayEvents.length > 0) {
                    dayDiv.addEventListener('click', () => showDayDetails(dateStr, dayEvents));
                    dayDiv.appendChild(eventsContainer);
                    
                    // Visual indicator (dot) for mobile if needed, or just the list
                }
                
                calendarGrid.appendChild(dayDiv);
            }
        }

        function isToday(day, month, year) {
            const today = new Date();
            return day === today.getDate() && month === today.getMonth() && year === today.getFullYear();
        }

        function showDayDetails(dateStr, dayEvents) {
            if (dayEvents.length === 0) return;

            // If multiple events, we might need a list modal. 
            // For now, let's show the first one or a list.
            // The requirement says "modal ... berisi daftar nama-nama kegiatan ...".
            // So if there are multiple, I should list them or pick one?
            // "Saat pengguna mengklik salah satu tanggal yang memiliki kegiatan, sistem harus menampilkan modal... berisi (1) daftar nama-nama kegiatan..."
            // So it implies a summary of the day or details of all events.
            
            // Let's make the modal show a list of events for that day.
            // But the modal design I made above has single fields.
            // I should update the modal to handle multiple events or just update the content dynamically.
            
            // Let's just update the modal to show the first event for now, or redesign modal content to be a list.
            // Given the complexity, I'll show the details of the *first* event if there's only one, 
            // or a list if there are multiple.
            // Or better, render a list of cards inside the modal body.
            
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.querySelector('#eventModal .mt-4'); // Container
            
            modalTitle.textContent = `Jadwal Tanggal ${formatDate(dateStr)}`;
            modalBody.innerHTML = ''; // Clear existing

            dayEvents.forEach(event => {
                const card = document.createElement('div');
                card.className = 'bg-gray-50 dark:bg-gray-700/50 rounded p-3 mb-3 border border-gray-100 dark:border-gray-600';
                
                let statusColor = 'bg-yellow-100 text-yellow-800';
                let statusLabel = 'Menunggu';
                if (event.status === 'completed') { statusColor = 'bg-green-100 text-green-800'; statusLabel = 'Selesai'; }
                else if (event.status === 'scheduled') { statusColor = 'bg-blue-100 text-blue-800'; statusLabel = 'Terjadwal'; }
                else if (event.status === 'cancelled') { statusColor = 'bg-red-100 text-red-800'; statusLabel = 'Batal'; }

                const photos = Array.isArray(event.photos) ? event.photos : [];
                const photoMarkup = photos.length > 0
                    ? `
                        <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-2">
                            ${photos.slice(0, 3).map(photo => `
                                <figure class="overflow-hidden rounded border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800">
                                    <img src="${escapeHtml(photo.url)}" alt="${escapeHtml(photo.caption || event.title || 'Foto kunjungan')}" class="h-24 w-full object-cover">
                                    ${photo.caption ? `<figcaption class="px-2 py-1 text-[11px] text-gray-500 dark:text-gray-400 truncate">${escapeHtml(photo.caption)}</figcaption>` : ''}
                                </figure>
                            `).join('')}
                        </div>
                    `
                    : '';

                card.innerHTML = `
                    <h4 class="font-bold text-gray-900 dark:text-white mb-2">${escapeHtml(event.title || 'Kunjungan')}</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex">
                            <span class="w-24 text-gray-500 dark:text-gray-400">Waktu:</span>
                            <span class="text-gray-800 dark:text-gray-200">${escapeHtml(event.time || '-')}</span>
                        </div>
                        <div class="flex">
                            <span class="w-24 text-gray-500 dark:text-gray-400">Lokasi:</span>
                            <span class="text-gray-800 dark:text-gray-200">${escapeHtml(event.location || '-')}</span>
                        </div>
                        <div class="flex">
                            <span class="w-24 text-gray-500 dark:text-gray-400">Peneliti:</span>
                            <span class="text-gray-800 dark:text-gray-200">${escapeHtml(event.visitor_name || '-')} ${event.participants_list ? '(' + escapeHtml(event.participants_list) + ')' : ''}</span>
                        </div>
                         <div class="flex">
                            <span class="w-24 text-gray-500 dark:text-gray-400">Status:</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium ${statusColor}">${statusLabel}</span>
                        </div>
                    </div>
                    ${photoMarkup}
                `;
                modalBody.appendChild(card);
            });

            eventModal.classList.remove('hidden');
        }

        function formatDate(dateStr) {
            const parts = dateStr.split('-');
            const date = new Date(parts[0], parts[1] - 1, parts[2]);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, function(character) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                }[character];
            });
        }

        // Initial fetch
        fetchEventsAndRender();
    });
</script>
@endpush
