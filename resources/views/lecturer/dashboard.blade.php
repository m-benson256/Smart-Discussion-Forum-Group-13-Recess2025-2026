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
<input class="block w-full pl-10 pr-3 py-2 border-none bg-surface-low rounded-lg text-sm focus:ring-brand focus:ring-2" placeholder="Search for quizzes, students, or reports..." type="text">
</div>
<div class="flex items-center gap-4">
<button class="p-2 text-brand hover:bg-surface-dim rounded-full"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></button>
<button class="p-2 text-brand hover:bg-surface-dim rounded-full"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></button>
</div>
</header>
<!-- END: Top Bar -->
<div class="p-8" id="view-container">
<!-- BEGIN: Dashboard View -->
<section class="space-y-8" id="view-dashboard">
<!-- Welcome Bar -->
<div class="flex gap-6 items-stretch">
<div class="flex-1 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
<h2 class="text-3xl font-bold mb-1">Welcome Lecturer</h2>
<p class="text-gray-500">Your dashboard overview for Semester 2, Academic Year 2024.</p>
</div>
<!-- Stats -->
<div class="w-32 lg:w-48 bg-[#f1f6f6] p-6 rounded-[2rem] flex flex-col justify-between">
<div class="text-[#4c7c7c]"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a7 7 0 00-7 7v1h12v-1a7 7 0 00-7-7z"></path></svg></div>
<div>
<p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Total Students</p>
<p class="text-2xl font-bold">1,284</p>
</div>
</div>
<div class="w-32 lg:w-48 bg-[#f5faf1] p-6 rounded-[2rem] flex flex-col justify-between">
<div class="text-[#72924c]"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path clip-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" fill-rule="evenodd"></path></svg></div>
<div>
<p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Active Discussions</p>
<p class="text-2xl font-bold">42</p>
</div>
</div>
<div class="w-32 lg:w-48 bg-[#fffcf0] p-6 rounded-[2rem] flex flex-col justify-between">
<div class="text-[#927d4c]"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg></div>
<div>
<p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Average Mark</p>
<p class="text-2xl font-bold">78.4%</p>
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
<button class="pb-4 border-b-2 border-brand font-bold">All Quizzes</button>
<button class="pb-4 border-b-2 border-transparent text-gray-400 hover:text-brand">Scheduled</button>
<button class="pb-4 border-b-2 border-transparent text-gray-400 hover:text-brand">Past Due</button>
</div>
<div class="space-y-4">
<div class="flex items-center gap-4 p-6 bg-surface-low rounded-[1.5rem] border border-transparent hover:border-brand/20 transition-all-200 group">
<div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-brand">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="flex-1">
<h4 class="font-bold">CS101: Introduction to Algorithms</h4>
<p class="text-sm text-gray-500">Scheduled for Oct 24, 2024 • 45 Questions • 120 Mins</p>
</div>
<div class="flex gap-2">
<button class="p-2 text-gray-400 hover:text-brand"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></button>
<button class="p-2 text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></button>
</div>
</div>
<div class="flex items-center gap-4 p-6 bg-surface-low rounded-[1.5rem] border border-transparent hover:border-brand/20 transition-all-200 group">
<div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-brand">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</div>
<div class="flex-1">
<h4 class="font-bold">DB202: Relational Schema Design</h4>
<p class="text-sm text-gray-500">Active until Friday • 15 Questions • 30 Mins</p>
</div>
<div class="flex gap-2">
<button class="p-2 text-gray-400 hover:text-brand"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></button>
<button class="p-2 text-gray-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></button>
</div>
</div>
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
<p class="text-gray-500">Global overview of all active and pending assessments.</p>
</div>
<a href="{{ route('quiz.create') }}" class="bg-brand text-white px-8 py-4 rounded-full font-bold flex items-center gap-3 hover:scale-105 transition-all-200">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
              Create New Assessment
            </a>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<!-- Example Cards -->
<div class="bg-white p-6 rounded-[1.5rem] border border-gray-100 shadow-sm">
<div class="flex justify-between mb-4">
<span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">ACTIVE</span>
<span class="text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></span>
</div>
<h4 class="font-bold text-lg mb-2">Final Exam: Networks</h4>
<p class="text-sm text-gray-500 mb-4">Due Dec 12 • 100 Marks</p>
<div class="flex justify-between items-center text-sm font-medium">
<span class="text-brand">84 Submissions</span>
<button class="text-brand hover:underline">Edit Quiz</button>
</div>
</div>
<!-- Duplicate for visual filler -->
<div class="bg-white p-6 rounded-[1.5rem] border border-gray-100 shadow-sm">
<div class="flex justify-between mb-4">
<span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full">DRAFT</span>
<span class="text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg></span>
</div>
<h4 class="font-bold text-lg mb-2">Midterm: Security</h4>
<p class="text-sm text-gray-500 mb-4">No due date set • 50 Marks</p>
<div class="flex justify-between items-center text-sm font-medium">
<span class="text-gray-400">0 Submissions</span>
<button class="text-brand hover:underline">Edit Quiz</button>
</div>
</div>
</div>
</section>
<!-- END: Quizzes View -->
<!-- BEGIN: Groups (Students) View -->
<section class="hidden h-full" id="view-groups">
<div class="grid grid-cols-12 gap-8 items-start">
<!-- Left: Form -->
<div class="col-span-12 lg:col-span-4 bg-white p-8 rounded-[2rem] shadow-sm border border-gray-100">
<h3 class="text-xl font-bold mb-6">Register New Student</h3>
<form action="#" class="space-y-4" method="POST">
<div>
<label class="block text-xs font-bold text-gray-500 uppercase mb-1">Full Name</label>
<input class="w-full rounded-lg border-gray-200 focus:ring-brand focus:border-brand" name="student_name" placeholder="e.g. John Doe" type="text">
</div>
<div>
<label class="block text-xs font-bold text-gray-500 uppercase mb-1">Student ID</label>
<input class="w-full rounded-lg border-gray-200 focus:ring-brand focus:border-brand" name="student_id" placeholder="STU-00123" type="text">
</div>
<div>
<label class="block text-xs font-bold text-gray-500 uppercase mb-1">Classroom</label>
<select class="w-full rounded-lg border-gray-200 focus:ring-brand focus:border-brand" name="classroom">
<option>CS101 - Algorithms</option>
<option>DB202 - Databases</option>
</select>
</div>
<button class="w-full bg-brand text-white py-3 rounded-lg font-bold hover:bg-brand-dark transition-all-200 mt-4" type="submit">Add Student</button>
</form>
</div>
<!-- Right: Data Table -->
<div class="col-span-12 lg:col-span-8 bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
<div class="p-8 border-b border-gray-100 flex justify-between items-center">
<h3 class="text-xl font-bold">Student Directory</h3>
<div class="text-sm text-gray-500">Showing 1-10 of 1,284</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead class="bg-surface-low text-[10px] uppercase font-bold text-gray-500">
<tr>
<th class="px-8 py-4">Student</th>
<th class="px-8 py-4">ID</th>
<th class="px-8 py-4">Status</th>
<th class="px-8 py-4">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-100">
<tr class="hover:bg-surface-low transition-colors">
<td class="px-8 py-4 font-medium">Alice Johnson</td>
<td class="px-8 py-4 text-gray-500">STU-12845</td>
<td class="px-8 py-4"><span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded">ENROLLED</span></td>
<td class="px-8 py-4"><button class="text-brand hover:underline font-bold text-xs">Profile</button></td>
</tr>
<tr class="hover:bg-surface-low transition-colors">
<td class="px-8 py-4 font-medium">Bob Smith</td>
<td class="px-8 py-4 text-gray-500">STU-12902</td>
<td class="px-8 py-4"><span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded">ENROLLED</span></td>
<td class="px-8 py-4"><button class="text-brand hover:underline font-bold text-xs">Profile</button></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</section>
<!-- END: Groups (Students) View -->
<!-- BEGIN: Reports View -->
<section class="hidden" id="view-reports">
<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
<div class="p-8 border-b border-gray-100 flex justify-between items-center">
<h3 class="text-xl font-bold">Performance Analytics</h3>
<button class="flex items-center gap-2 text-brand font-bold border border-brand px-4 py-2 rounded-lg hover:bg-brand hover:text-white transition-all-200">
<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                Export CSV
              </button>
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
<tbody class="divide-y divide-gray-100">
<tr>
<td class="px-8 py-4 font-medium">Charlie Brown</td>
<td class="px-8 py-4">Algorithms Basics</td>
<td class="px-8 py-4 text-brand font-bold">92/100</td>
<td class="px-8 py-4"><span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">PASSED</span></td>
</tr>
<tr>
<td class="px-8 py-4 font-medium">Dana White</td>
<td class="px-8 py-4">Algorithms Basics</td>
<td class="px-8 py-4 text-red-500 font-bold">45/100</td>
<td class="px-8 py-4"><span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">FAILED</span></td>
</tr>
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
      const views = ['dashboard', 'quizzes', 'groups', 'reports', 'announcements'];
      
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

    /**
     * Simulated Logout Redirect
     */
  </script>
<!-- END: State Control Script -->




</body></html>