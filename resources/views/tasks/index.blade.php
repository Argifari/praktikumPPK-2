<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management & Progress Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Project Task Board</h1>

        <!-- Visual Progress Bar -->
        <div class="mb-6">
            <div class="flex justify-between mb-1 text-sm font-medium text-gray-700">
                <span>Project Progress</span>
                <span id="progress-text">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div id="progress-bar" class="bg-blue-600 h-4 rounded-full" style="width: 0%"></div>
            </div>
        </div>

        <!-- Filter & Sorting Controls -->
        <div class="flex justify-between items-center mb-4">
            <div class="space-x-2">
                <button onclick="fetchTasks('due_date')" class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300">Sort by Due Date</button>
                <button onclick="fetchTasks('priority')" class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300">Sort by Priority</button>
            </div>
        </div>

        <!-- Task List / Card Container -->
        <div id="task-container" class="space-y-3">
            <!-- Dynamic Tasks Rendered Here -->
        </div>
    </div>

    <script>
        const projectId = 1; // Contoh ID Project aktif

        async function fetchTasks(sortBy = '') {
            let url = `/api/projects/${projectId}/tasks`;
            if (sortBy) url += `?sort_by=${sortBy}`;

            const response = await fetch(url);
            const result = await response.json();

            if (result.success) {
                // Update Progress Bar
                document.getElementById('progress-bar').style.width = `${result.progress_percentage}%`;
                document.getElementById('progress-text.innerText` = `${result.progress_percentage}%`;

                // Render Task Cards
                const container = document.getElementById('task-container');
                container.innerHTML = '';

                result.data.forEach(task => {
                    let badgeColor = task.priority === 'high' ? 'bg-red-100 text-red-700' : (task.priority === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700');
                    
                    container.innerHTML += `
                        <div class="p-4 border rounded-lg flex justify-between items-center bg-gray-50">
                            <div>
                                <h3 class="font-semibold text-lg">${task.title}</h3>
                                <p class="text-sm text-gray-600">${task.description ?? 'No description'}</p>
                                <span class="inline-block px-2 py-0.5 text-xs rounded font-semibold mt-2 ${badgeColor}">${task.priority.toUpperCase()}</span>
                                <span class="text-xs text-gray-500 ml-2">Due: ${task.due_date}</span>
                            </div>
                            <div>
                                <span class="px-3 py-1 text-xs rounded-full font-bold ${task.status === 'completed' ? 'bg-green-500 text-white' : 'bg-orange-400 text-white'}">${task.status}</span>
                            </div>
                        </div>
                    `;
                });
            }
        }

        // Panggil saat halaman dimuat
        fetchTasks();
    </script>
</body>
</html>