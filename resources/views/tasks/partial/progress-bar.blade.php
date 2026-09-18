<!-- Indikator Progres Penyelesaian Tugas (SRS-FR-07) -->
<div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 mb-6 w-full">
    <div class="flex justify-between items-end mb-3">
        <div>
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Progres Daftar Tugas</h3>
            <p class="text-xs text-gray-500 mt-1">
                <span class="font-semibold text-gray-700">{{ $completedTasks }}</span> dari 
                <span class="font-semibold text-gray-700">{{ $totalTasks }}</span> tugas selesai
            </p>
        </div>
        <span class="text-2xl font-extrabold text-blue-600">{{ $progressPercentage }}%</span>
    </div>
    
    <!-- Trek Progress Bar -->
    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden shadow-inner">
        <!-- Bar Animasi Dinamis -->
        <div class="bg-blue-600 h-3 rounded-full transition-all duration-700 ease-out relative" 
             style="width: {{ $progressPercentage }}%;">
            <!-- Efek kilauan pada bar -->
            <div class="absolute top-0 right-0 bottom-0 left-0 bg-white opacity-20 transform -skew-x-12"></div>
        </div>
    </div>
</div>