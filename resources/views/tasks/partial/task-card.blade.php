<!-- Komponen Kartu Tugas Individual -->
<div class="flex items-start justify-between p-4 mb-3 border rounded-lg transition-colors duration-200 
    {{ $task->status === 'completed' ? 'bg-slate-50 border-slate-200' : 'bg-white border-slate-300 shadow-sm hover:shadow-md' }}">
    
    <div class="flex items-start gap-4 w-full">
        <!-- Tombol Toggle Status -->
        <!-- Atribut onclick disiapkan untuk memicu fungsi Fetch API ke endpoint PATCH -->
        <button class="mt-0.5 w-6 h-6 shrink-0 rounded-full border-2 flex items-center justify-center transition-colors focus:outline-none focus:ring-2 focus:ring-blue-200 
            {{ $task->status === 'completed' ? 'bg-green-500 border-green-500' : 'border-gray-400 hover:border-blue-500' }}"
            onclick="updateTaskStatus({{ $task->id }}, '{{ $task->status === 'completed' ? 'pending' : 'completed' }}')">
            
            @if($task->status === 'completed')
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            @endif
        </button>

        <!-- Informasi Detail Tugas -->
        <div class="flex-grow">
            <h4 class="text-base font-semibold {{ $task->status === 'completed' ? 'line-through text-gray-400' : 'text-gray-800' }}">
                {{ $task->title }}
            </h4>
            
            @if($task->description)
                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $task->description }}</p>
            @endif
            
            <div class="flex flex-wrap items-center gap-3 mt-3 text-xs font-medium">
                <!-- Indikator Tenggat Waktu (Merah jika melebihi batas) -->
                @php
                    $isOverdue = \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'completed';
                @endphp
                
                <span class="flex items-center gap-1 px-2 py-1 rounded-md border 
                    {{ $isOverdue ? 'text-red-700 bg-red-50 border-red-200' : 'text-gray-600 bg-gray-50 border-gray-200' }}">
                    🗓️ {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}
                </span>
                
                <!-- Lencana Prioritas -->
                <span class="px-2 py-1 rounded-md text-white capitalize shadow-sm
                    {{ $task->priority === 'high' ? 'bg-red-500' : ($task->priority === 'medium' ? 'bg-amber-500' : 'bg-emerald-500') }}">
                    {{ $task->priority }}
                </span>
            </div>
        </div>
    </div>

    <!-- Opsi Aksi -->
    <div class="flex items-center gap-3 ml-4">
        <button onclick="openEditModal({{ $task->id }})" class="text-gray-400 hover:text-blue-600 transition-colors" title="Edit Tugas">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
        </button>
        <button onclick="deleteTask({{ $task->id }})" class="text-gray-400 hover:text-red-600 transition-colors" title="Hapus Tugas">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
    </div>
</div>