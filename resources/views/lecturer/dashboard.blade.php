<!DOCTYPE html><html class="h-full bg-[#faf9fc]" lang="en"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Smart Discussion Forum - Lecturer Dashboard</title>
<!-- Google Font: Manrope -->
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<!-- Tailwind CSS v3 -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              DEFAULT: '#152f53',
              dark: '#0f223d',
              light: '#2a4a7a',
              accent: '#3e638d',
            },
            surface: {
              DEFAULT: '#faf9fc',
              container: '#ffffff',
              low: '#f4f3f7',
              dim: '#dbd9dd',
            }
          },
          fontFamily: {
            sans: ['Manrope', 'sans-serif'],
          },
          borderRadius: {
            'custom': '0.75rem',
          }
        }
      }
}
  </script>
<style data-purpose="custom-layout">
    body { font-family: 'Manrope', sans-serif; }
    .sidebar-active { background-color: #3e638d; color: white; }
    .transition-all-200 { transition: all 0.2s ease-in-out; }
  </style>
</head>
<body class="h-full overflow-hidden text-[#152f53]">
<!-- BEGIN: Main Layout -->
<div class="flex h-screen overflow-hidden">
<!-- BEGIN: Sidebar -->
<aside class="w-64 bg-brand flex-shrink-0 flex flex-col transition-all-200">
<div class="p-6">
<h1 class="text-white text-xl font-bold leading-tight">Smart Discussion Forum</h1>
<p class="text-brand-accent text-sm">Lecturer Dashboard</p>
</div>
<nav class="flex-1 px-4 space-y-2 mt-4" data-purpose="navigation-menu">
<button class="sidebar-active w-full flex items-center gap-3 px-4 py-3 rounded-custom text-sm font-medium transition-all-200 hover:bg-brand-accent" data-nav="dashboard" onclick="switchView('dashboard')">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          Dashboard
        </button>
<button class="text-gray-300 w-full flex items-center gap-3 px-4 py-3 rounded-custom text-sm font-medium transition-all-200 hover:bg-brand-accent hover:text-white" data-nav="quizzes" onclick="switchView('quizzes')">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          Quizzes
        </button>
<button class="text-gray-300 w-full flex items-center gap-3 px-4 py-3 rounded-custom text-sm font-medium transition-all-200 hover:bg-brand-accent hover:text-white" data-nav="groups" onclick="switchView('groups')"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          Groups</button>

<button class="text-gray-300 w-full flex items-center gap-3 px-4 py-3 rounded-custom text-sm font-medium transition-all-200 hover:bg-brand-accent hover:text-white" data-nav="discussions" onclick="switchView('discussions')">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          Discussions
        </button>          
<button class="text-gray-300 w-full flex items-center gap-3 px-4 py-3 rounded-custom text-sm font-medium transition-all-200 hover:bg-brand-accent hover:text-white" data-nav="reports" onclick="switchView('reports')">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          Reports
        </button>
<button class="text-gray-300 w-full flex items-center gap-3 px-4 py-3 rounded-custom text-sm font-medium transition-all-200 hover:bg-brand-accent hover:text-white" data-nav="announcements" onclick="switchView('announcements')">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          Announcements
        </button>
</nav>
<div class="px-4 py-6 border-t border-brand-accent/30 space-y-2">

<button class="text-gray-300 w-full flex items-center gap-3 px-4 py-3 rounded-custom text-sm font-medium transition-all-200 hover:bg-red-500 hover:text-white" onclick="toggleModal('logoutModal')">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
          Logout
        </button>
</div>
</aside>
<!-- END: Sidebar -->
<!-- BEGIN: Main Content Area -->
<main class="flex-1 flex flex-col min-w-0 bg-surface overflow-y-auto">
<!-- BEGIN: Top Bar -->
<header class="h-16 flex items-center justify-between px-8 py-10">
<div class="relative w-1/3">
<span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
<svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</span>
<input id="searchInput" class="block w-full pl-10 pr-3 py-2 border-none bg-surface-low rounded-lg text-sm focus:ring-brand focus:ring-2" placeholder="Search for quizzes, students, or reports... (press Enter)" type="text" onkeydown="if(event.key === 'Enter') runSearch()">
<div id="searchResults" class="hidden absolute top-full mt-2 w-[28rem] max-h-96 overflow-y-auto bg-white rounded-xl shadow-2xl border border-gray-100 z-50 p-4"></div>
</div>

</header>
<!-- END: Top Bar -->
<div class="p-8" id="view-container">
<!-- BEGIN: Dashboard View -->
<section class="space-y-8" id="view-dashboard">
<!-- Welcome Bar -->
<div class="flex gap-6 items-stretch">
<div class="flex-1 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
<h2 class="text-3xl font-bold mb-1">Welcome Lecturer, {{ $lecturerName }}</h2>
</div>
<!-- Stats -->
<div class="w-32 lg:w-48 bg-[#f1f6f6] p-6 rounded-[2rem] flex flex-col justify-between">
<div class="text-[#4c7c7c]"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a7 7 0 00-7 7v1h12v-1a7 7 0 00-7-7z"></path></svg></div>
<div>
<p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Total Students</p>
<p class="text-2xl font-bold">{{ number_format($totalStudents) }}</p>
</div>
</div>
<div class="w-32 lg:w-48 bg-[#f5faf1] p-6 rounded-[2rem] flex flex-col justify-between">
<div class="text-[#72924c]"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" fill-rule="evenodd"></path></svg></div>
<div>
<p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Topics</p>
<p class="text-2xl font-bold">{{ number_format($activeDiscussions) }}</p>
</div>
</div>

</div>
<div class="grid grid-cols-12 gap-8">
<!-- Left Col: Quiz List -->
<div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 col-span-12">
<div class="flex justify-between items-start mb-6">
<div>
<h3 class="text-2xl font-bold">Quiz Configuration</h3>
<p class="text-gray-500">Manage, create, and schedule student assessments.</p>
</div>
<a href="{{ route('quiz.create') }}" class="bg-brand text-white px-6 py-3 rounded-full font-bold flex items-center gap-2 hover:bg-brand-dark transition-all-200">
<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                  Create New Quiz
                </a>
</div>
<div class="flex border-b border-gray-100 mb-8 gap-6">
<button id="quiz-tab-all" class="pb-4 border-b-2 border-brand font-bold" onclick="setQuizTab('all')">All Quizzes</button>
<button id="quiz-tab-scheduled" class="pb-4 border-b-2 border-transparent text-gray-400 hover:text-brand" onclick="setQuizTab('scheduled')">Scheduled</button>
<button id="quiz-tab-pastdue" class="pb-4 border-b-2 border-transparent text-gray-400 hover:text-brand" onclick="setQuizTab('pastdue')">Past Due</button>
</div>
<div class="space-y-4" id="quiz-list-container">
<div class="text-center text-gray-400 py-8">Loading quizzes...</div>
</div>
</div>
<!-- Right Col: Tasks & Activity -->

</div>
</section>
<!-- END: Dashboard View -->
<!-- BEGIN: Quizzes View -->
<section class="hidden space-y-8" id="view-quizzes">
<div class="flex justify-between items-center bg-white p-8 rounded-[2rem] border border-gray-100">
<div>
<h2 class="text-3xl font-bold">Quiz Management</h2>
</div>
<a href="{{ route('quiz.create') }}" class="bg-brand text-white px-8 py-4 rounded-full font-bold flex items-center gap-3 hover:scale-105 transition-all-200">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
              Create New Assessment
            </a>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="quiz-management-list">
<div class="text-center text-gray-400 py-8 col-span-full">Loading quizzes...</div>
</div>
</section>
<!-- END: Quizzes View -->
<!-- BEGIN: Groups (Students) View -->
<section class="hidden h-full" id="view-groups">
<div class="grid grid-cols-12 gap-8 items-start">
<!-- Right: Data Table -->
<div class="col-span-12 lg:col-span-8 bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
<div class="p-8 border-b border-gray-100 flex justify-between items-center">
<h3 class="text-xl font-bold">All Groups</h3>
<div class="text-sm text-gray-500" id="groups-count-label"></div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead class="bg-surface-low text-[10px] uppercase font-bold text-gray-500">
<tr>
<th class="px-8 py-4">Group Name</th>
<th class="px-8 py-4">Created By</th>
<th class="px-8 py-4">Members</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-100" id="groups-table-body">
<tr><td class="px-8 py-4 text-gray-400" colspan="4">Loading groups...</td></tr>
</tbody>
</table>
</div>
</div>
</div>
</section>
<!-- END: Groups (Students) View -->
 <!-- BEGIN: Discussions View -->
<section class="hidden space-y-6" id="view-discussions">
<div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
<h3 class="text-xl font-bold mb-1">All Discussions</h3>
<p class="text-gray-500 text-sm">Topics currently active across your groups.</p>
</div>
<div class="space-y-4" id="discussions-list">
<div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 text-center text-gray-400">
    Loading discussions...
</div>
</div>
</section>
<!-- END: Discussions View -->
<!-- BEGIN: Reports View -->
<section class="hidden" id="view-reports">
<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
<div class="p-8 border-b border-gray-100 flex justify-between items-center">
<h3 class="text-xl font-bold">Performance Analytics</h3>
</div>
<table class="w-full text-left">
<thead class="bg-surface-low text-[10px] uppercase font-bold text-gray-500">
<tr>
<th class="px-8 py-4">Student Name</th>
<th class="px-8 py-4">Quiz Title</th>
<th class="px-8 py-4">Score</th>
<th class="px-8 py-4">Status</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-100" id="reports-table-body">
<tr><td class="px-8 py-4 text-gray-400" colspan="4">Loading reports...</td></tr>
</tbody>
</table>
</div>
</section>
<!-- END: Reports View -->
<!-- BEGIN: Announcements View -->
<section class="hidden" id="view-announcements">
<div class="max-w-3xl mx-auto space-y-8">
<!-- Feed (read-only) -->
<div class="space-y-6">
    @forelse($announcements as $announcement)
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 rounded-full bg-brand-accent flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($announcement->user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <h4 class="font-bold">{{ $announcement->user->name ?? 'Unknown Lecturer' }}</h4>
                    <p class="text-xs text-gray-500">{{ $announcement->created_at->format('M j, Y \a\t g:i A') }}</p>
                </div>
            </div>
            <p class="text-gray-700 leading-relaxed">{{ $announcement->content }}</p>
            @if($announcement->quiz)
                <p class="text-xs text-brand mt-3 font-bold">Related quiz: {{ $announcement->quiz->title }}</p>
            @endif
        </div>
    @empty
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 text-center text-gray-400">
            No announcements yet.
        </div>
    @endforelse
</div>
</div>
</section>
<!-- END: Announcements View -->
</div>
</main>
<!-- END: Main Content Area -->
</div>
<!-- END: Main Layout -->
<!-- BEGIN: Modal Overlays -->
<!-- Logout Modal -->
<div aria-labelledby="modal-title" aria-modal="true" class="hidden fixed inset-0 z-50 overflow-y-auto" id="logoutModal" role="dialog">
<div class="flex items-center justify-center min-h-screen px-4">
<div class="fixed inset-0 bg-brand/40 backdrop-blur-sm transition-opacity" onclick="toggleModal('logoutModal')"></div>
<div class="relative bg-white rounded-[2rem] p-8 max-w-sm w-full shadow-2xl">
<div class="text-center">
<div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 text-red-600 mb-6">
<svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<h3 class="text-xl font-bold mb-2">Confirm Logout</h3>
<p class="text-gray-500 mb-8">Are you sure you want to end your current session?</p>
<div class="flex gap-4">
<button class="flex-1 py-3 border border-gray-200 rounded-xl font-bold text-gray-500 hover:bg-gray-50" onclick="toggleModal('logoutModal')">Cancel</button>
<form method="POST" action="{{ route('logout') }}" class="flex-1">
    @csrf
    <button type="submit" class="w-full py-3 bg-red-500 text-white rounded-xl font-bold hover:bg-red-600">
        Logout
    </button>
</form>
</div>
</div>
</div>
</div>
</div>
<!-- BEGIN: State Control Script -->
<script data-purpose="navigation-logic">
    /**
     * Toggles visibility between different views
     * @param {string} viewId - The ID of the view to show
     */
    function switchView(viewId) {
      const views = ['dashboard', 'quizzes', 'groups','discussions', 'reports', 'announcements'];
      
      views.forEach(v => {
        const el = document.getElementById('view-' + v);
        const navBtn = document.querySelector('[data-nav="' + v + '"]');
        
        if (v === viewId) {
          el.classList.remove('hidden');
          navBtn.classList.add('sidebar-active');
          navBtn.classList.remove('text-gray-300');
        } else {
          el.classList.add('hidden');
          navBtn.classList.remove('sidebar-active');
          navBtn.classList.add('text-gray-300');
        }
      });

      if (viewId === 'dashboard') {
    loadQuizzes();
     }
     if (viewId === 'quizzes') {
    loadQuizManagement();
    }

      if (viewId === 'groups') {
    loadGroups(); // NEW — fetch fresh group data every time this tab opens
    }
  if (viewId === 'discussions') {
    loadDiscussions(); // NEW — fetch fresh discussion data every time this tab opens
  }
  if (viewId === 'reports') {
    loadReports(); // NEW — fetch fresh report data every time this tab opens
  } 
      
      // Update browser history/state if needed
      console.log('Switched to view:', viewId);
    }

    /**
     * Generic Modal Toggler
     * @param {string} modalId - The ID of the modal to toggle
     */
    function toggleModal(modalId) {
      const modal = document.getElementById(modalId);
      modal.classList.toggle('hidden');
    }

    async function loadGroups() {
    const tbody = document.getElementById('groups-table-body');
    const countLabel = document.getElementById('groups-count-label');

    try {
        const response = await fetch('/groups', {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error('Failed to load groups');

        const groups = await response.json();

        countLabel.innerText = `${groups.length} group${groups.length === 1 ? '' : 's'}`;

        if (groups.length === 0) {
            tbody.innerHTML = `<tr><td class="px-8 py-4 text-gray-400" colspan="4">No groups yet.</td></tr>`;
            return;
        }

        tbody.innerHTML = groups.map(group => `
    <tr class="hover:bg-surface-low transition-colors">
        <td class="px-8 py-4 font-medium">${group.name}</td>
        <td class="px-8 py-4 text-gray-500">${group.creator?.name ?? 'Unknown'}</td>
        <td class="px-8 py-4">
            <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded">
                ${group.members_count} member${group.members_count === 1 ? '' : 's'}
            </span>
        </td>
    </tr>
`).join('');
    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td class="px-8 py-4 text-red-500" colspan="4">Could not load groups.</td></tr>`;
    }
}
async function loadDiscussions() {
    const container = document.getElementById('discussions-list');

    try {
        const response = await fetch('/topics', {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error('Failed to load discussions');

        const topics = await response.json();

        if (topics.length === 0) {
            container.innerHTML = `<div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 text-center text-gray-400">No discussions yet.</div>`;
            return;
        }

        container.innerHTML = topics.map(topic => `
            <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100">
                <div class="flex justify-between items-start mb-2 cursor-pointer" onclick="toggleTopic(${topic.id})">
                    <h4 class="font-bold text-lg">${topic.title}</h4>
                    <span class="text-xs text-gray-400">${topic.messages_count} message${topic.messages_count === 1 ? '' : 's'}</span>
                </div>
                <p class="text-sm text-gray-600 mb-3 cursor-pointer" onclick="toggleTopic(${topic.id})">${topic.content}</p>
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                    <span>Started by ${topic.user?.name ?? 'Unknown'}</span>
                    ${topic.group ? `<span>&middot; Group: ${topic.group.name}</span>` : ''}
                </div>
                <div id="topic-messages-${topic.id}" class="hidden border-t border-gray-100 pt-4 mt-2"></div>
            </div>
        `).join('');
    } catch (err) {
        console.error(err);
        container.innerHTML = `<div class="bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100 text-center text-red-500">Could not load discussions.</div>`;
    }
}
async function loadReports() {
    const tbody = document.getElementById('reports-table-body');

    try {
        const response = await fetch('/lecturer/reports', {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error('Failed to load reports');

        const reports = await response.json();

        if (reports.length === 0) {
            tbody.innerHTML = `<tr><td class="px-8 py-4 text-gray-400" colspan="4">No submitted quizzes yet.</td></tr>`;
            return;
        }

        tbody.innerHTML = reports.map(r => {
            const passed = r.total_marks ? (r.score / r.total_marks) >= 0.5 : true;
            const badgeClass = passed ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
            const badgeText = passed ? 'PASSED' : 'FAILED';
            const scoreDisplay = r.total_marks ? `${r.score}/${r.total_marks}` : r.score;

            return `
                <tr>
                    <td class="px-8 py-4 font-medium">${r.student_name}</td>
                    <td class="px-8 py-4">${r.quiz_title}</td>
                    <td class="px-8 py-4 ${passed ? 'text-brand' : 'text-red-500'} font-bold">${scoreDisplay}</td>
                    <td class="px-8 py-4"><span class="px-3 py-1 ${badgeClass} rounded-full text-xs font-bold">${badgeText}</span></td>
                </tr>
            `;
        }).join('');
    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td class="px-8 py-4 text-red-500" colspan="4">Could not load reports.</td></tr>`;
    }
}
async function runSearch() {
    const input = document.getElementById('searchInput');
    const panel = document.getElementById('searchResults');
    const query = input.value.trim();

    if (query === '') {
        panel.classList.add('hidden');
        return;
    }

    panel.classList.remove('hidden');
    panel.innerHTML = `<p class="text-gray-400 text-sm">Searching...</p>`;

    try {
        const response = await fetch(`/lecturer/search?q=${encodeURIComponent(query)}`, {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error('Search failed');

        const data = await response.json();
        panel.innerHTML = renderSearchResults(data);
    } catch (err) {
        console.error(err);
        panel.innerHTML = `<p class="text-red-500 text-sm">Search failed. Please try again.</p>`;
    }
}

function renderSearchResults(data) {
    const hasResults = data.quizzes.length || data.students.length || data.reports.length;

    if (!hasResults) {
        return `<p class="text-gray-400 text-sm">No results found.</p>`;
    }

    let html = '';

    if (data.quizzes.length) {
        html += `<h4 class="text-xs font-bold uppercase text-gray-400 mb-2">Quizzes</h4>`;
        html += data.quizzes.map(q => `
            <div class="py-2 border-b border-gray-50 cursor-pointer hover:bg-surface-low rounded px-2" onclick="goToQuiz(${q.id})">
                <p class="font-medium text-sm">${q.title}</p>
                <p class="text-xs text-gray-500">${q.status} &middot; ${q.duration_minutes} mins</p>
            </div>
        `).join('');
    }

    if (data.students.length) {
        html += `<h4 class="text-xs font-bold uppercase text-gray-400 mt-4 mb-2">Students</h4>`;
        html += data.students.map(s => `
            <div class="py-2 border-b border-gray-50 cursor-pointer hover:bg-surface-low rounded px-2" onclick="goToStudent('${s.name.replace(/'/g, "\\'")}')">
                <p class="font-medium text-sm">${s.name}</p>
                <p class="text-xs text-gray-500">${s.email}</p>
            </div>
        `).join('');
    }

    if (data.reports.length) {
        html += `<h4 class="text-xs font-bold uppercase text-gray-400 mt-4 mb-2">Reports</h4>`;
        html += data.reports.map(r => `
            <div class="py-2 border-b border-gray-50 cursor-pointer hover:bg-surface-low rounded px-2" onclick="goToReports()">
                <p class="font-medium text-sm">${r.student_name} &middot; ${r.quiz_title}</p>
                <p class="text-xs text-gray-500">Score: ${r.score}${r.total_marks ? '/' + r.total_marks : ''}</p>
            </div>
        `).join('');
    }

    return html;
}
function goToReports() {
    document.getElementById('searchResults').classList.add('hidden');
    switchView('reports', document.querySelector('[data-nav="reports"]'));
}

function goToStudent(name) {
    document.getElementById('searchResults').classList.add('hidden');
    switchView('groups', document.querySelector('[data-nav="groups"]'));
}
function goToQuiz(quizId) {
    document.getElementById('searchResults').classList.add('hidden');
    window.location.href = `/lecturer/quizzes/${quizId}/edit`;
}

// Hide the results panel when clicking anywhere outside it
document.addEventListener('click', function (e) {
    const panel = document.getElementById('searchResults');
    const input = document.getElementById('searchInput');
    if (panel && !panel.contains(e.target) && e.target !== input) {
        panel.classList.add('hidden');
    }
});

let openTopicId = null; // tracks which topic is currently expanded

async function toggleTopic(topicId) {
    const panel = document.getElementById(`topic-messages-${topicId}`);

    if (openTopicId === topicId) {
        panel.classList.add('hidden');
        openTopicId = null;
        return;
    }

    if (openTopicId !== null) {
        const previousPanel = document.getElementById(`topic-messages-${openTopicId}`);
        if (previousPanel) previousPanel.classList.add('hidden');
    }

    openTopicId = topicId;
    panel.classList.remove('hidden');
    panel.innerHTML = `<p class="text-gray-400 text-sm">Loading messages...</p>`;

    await loadTopicMessages(topicId);
}

async function loadTopicMessages(topicId) {
    const panel = document.getElementById(`topic-messages-${topicId}`);

    try {
        const response = await fetch(`/topics/${topicId}/messages`, {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error('Failed to load messages');

        const messages = await response.json();

        const messagesHtml = messages.length === 0
            ? `<p class="text-gray-400 text-sm mb-3">No replies yet. Be the first to respond.</p>`
            : messages.map(m => `
                <div class="bg-surface-low rounded-lg p-3 mb-2">
                    <p class="text-xs font-bold text-brand">${m.user?.name ?? 'Unknown'}</p>
                    <p class="text-sm text-gray-700">${m.body}</p>
                </div>
            `).join('');

        panel.innerHTML = `
            <div class="mb-3 max-h-64 overflow-y-auto">${messagesHtml}</div>
            <div class="flex gap-2">
                <input type="text" id="reply-input-${topicId}" placeholder="Write a reply..." class="flex-1 rounded-lg border-gray-200 focus:ring-brand focus:border-brand text-sm p-2" onkeydown="if(event.key === 'Enter') postReply(${topicId})">
                <button onclick="postReply(${topicId})" class="bg-brand text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-brand-dark">Reply</button>
            </div>
        `;
    } catch (err) {
        console.error(err);
        panel.innerHTML = `<p class="text-red-500 text-sm">Could not load messages.</p>`;
    }
}

async function postReply(topicId) {
    const input = document.getElementById(`reply-input-${topicId}`);
    const body = input.value.trim();

    if (body === '') return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const response = await fetch(`/topics/${topicId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ body })
        });

        if (!response.ok) throw new Error('Failed to post reply');

        input.value = '';
        await loadTopicMessages(topicId);
    } catch (err) {
        console.error(err);
        alert('Could not post your reply. Please try again.');
    }
}
let allQuizzes = [];
let currentQuizTab = 'all';

async function loadQuizzes() {
    const container = document.getElementById('quiz-list-container');

    try {
        const response = await fetch('/lecturer/quizzes', {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error('Failed to load quizzes');

        allQuizzes = await response.json();
        renderQuizList();
    } catch (err) {
        console.error(err);
        container.innerHTML = `<div class="text-center text-red-500 py-8">Could not load quizzes.</div>`;
    }
}

function setQuizTab(tab) {
    currentQuizTab = tab;

    const tabs = ['all', 'scheduled', 'pastdue'];
    tabs.forEach(t => {
        const btn = document.getElementById(`quiz-tab-${t}`);
        if (t === tab) {
            btn.className = 'pb-4 border-b-2 border-brand font-bold';
        } else {
            btn.className = 'pb-4 border-b-2 border-transparent text-gray-400 hover:text-brand';
        }
    });

    renderQuizList();
}

function renderQuizList() {
    const container = document.getElementById('quiz-list-container');
    const now = new Date();

    let filtered = allQuizzes;

    if (currentQuizTab === 'scheduled') {
        filtered = allQuizzes.filter(q =>
            q.status === 'published' && q.start_time && new Date(q.start_time) > now
        );
    } else if (currentQuizTab === 'pastdue') {
        filtered = allQuizzes.filter(q =>
            q.status === 'published' && q.start_time && new Date(q.start_time) < now
        );
    }

    if (filtered.length === 0) {
        container.innerHTML = `<div class="text-center text-gray-400 py-8">No quizzes found.</div>`;
        return;
    }

    container.innerHTML = filtered.map(q => `
    <div class="flex items-center gap-4 p-6 bg-surface-low rounded-[1.5rem] border border-transparent hover:border-brand/20 transition-all-200 group cursor-pointer" onclick="window.location.href='/lecturer/quizzes/${q.id}/edit'">
        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-brand">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </div>
        <div class="flex-1">
            <h4 class="font-bold">${q.title}</h4>
            <p class="text-sm text-gray-500">
                ${q.status === 'draft' ? 'Draft' : (q.start_time ? new Date(q.start_time).toLocaleDateString() : 'No start time')}
                &bull; ${q.questions_count} Question${q.questions_count === 1 ? '' : 's'}
                &bull; ${q.duration_minutes} Mins
            </p>
        </div>
    </div>
`).join('');
}
document.addEventListener('DOMContentLoaded', function () {
    loadQuizzes();
});
async function loadQuizManagement() {
    const container = document.getElementById('quiz-management-list');

    try {
        const response = await fetch('/lecturer/quizzes', {
            headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error('Failed to load quizzes');

        const quizzes = await response.json();

        if (quizzes.length === 0) {
            container.innerHTML = `<div class="text-center text-gray-400 py-8 col-span-full">No quizzes created yet.</div>`;
            return;
        }

        container.innerHTML = quizzes.map(q => {
            const isActive = q.status === 'published';
            const badgeClass = isActive ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
            const badgeText = isActive ? 'ACTIVE' : 'DRAFT';
            const dueDate = q.start_time
                ? new Date(q.start_time).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
                : 'No due date set';

            return `
                <div class="bg-white p-6 rounded-[1.5rem] border border-gray-100 shadow-sm">
                    <div class="flex justify-between mb-4">
                        <span class="px-3 py-1 ${badgeClass} text-xs font-bold rounded-full">${badgeText}</span>
                    </div>
                    <h4 class="font-bold text-lg mb-2">${q.title}</h4>
                    <p class="text-sm text-gray-500 mb-4">Due ${dueDate} &bull; ${q.total_marks} Marks</p>
                    <div class="flex justify-between items-center text-sm font-medium">
                        <span class="${q.submissions_count > 0 ? 'text-brand' : 'text-gray-400'}">${q.submissions_count} Submission${q.submissions_count === 1 ? '' : 's'}</span>
                        <a href="/lecturer/quizzes/${q.id}/edit" class="text-brand hover:underline">Edit Quiz</a>
                    </div>
                </div>
            `;
        }).join('');
    } catch (err) {
        console.error(err);
        container.innerHTML = `<div class="text-center text-red-500 py-8 col-span-full">Could not load quizzes.</div>`;
    }
}


  </script>
<!-- END: State Control Script -->




</body></html>