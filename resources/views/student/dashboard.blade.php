<!DOCTYPE html>

<html class="h-full bg-slate-50" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport" />
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<title>Smart Discussion Forum - Unified Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>

<style data-purpose="custom-colors">
        :root {
            --sidebar-navy: #1a2e4c;
            --sidebar-hover: #2d4368;
            --sidebar-active: #3d5a80;
        }
        .bg-sidebar { background-color: var(--sidebar-navy); }
        .bg-sidebar-active { background-color: var(--sidebar-active); }
        .hover-sidebar:hover { background-color: var(--sidebar-hover); }
        
        /* Scrollbar styling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .context-menu-item:hover {
            background-color: #f1f5f9;
        }
        .reaction-badge {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 9999px;
            padding: 2px 8px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            margin-top: 4px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            cursor: pointer;
            user-select: none;
            transition: all 0.1s ease;
        }
        .reaction-badge:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        .reaction-badge.active {
            border-color: #3b82f6;
            background: #dbeafe;
            font-weight: 600;
        }
        .flag-icon {
            color: #f59e0b;
            font-size: 14px;
            margin-top: 4px;
            display: inline-block;
        }
        
        /* Revised Heart (Like) Style */
        .like-button {
            display: inline-flex;
            align-items: center;
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 4px;
            transition: all 0.2s;
            padding: 2px 4px;
            border-radius: 4px;
            border: 1px solid transparent;
        }
        .like-button i {
            font-weight: 400; /* Outline heart */
        }
        .like-button.active {
            color: #475569;
            font-weight: 700;
            border-color: #e2e8f0;
            background: #f8fafc;
        }
        .like-button.active i {
            font-weight: 900; /* Heavier outline for "active" but no red fill */
        }

        .notification-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #1e293b;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            transform: translateY(100px);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 200;
        }
        .notification-toast.show {
            transform: translateY(0);
        }
    </style>
</head>
<body class="h-full font-sans text-slate-900">
<div class="flex h-full">
<aside class="w-64 bg-sidebar text-white flex flex-col fixed h-full z-30" data-purpose="main-sidebar">
<div class="p-6">
<h1 class="text-xl font-bold leading-tight">Smart Discussion Forum</h1>
<p class="text-slate-400 text-sm">Student Dashboard</p>
</div>
<nav class="flex-1 px-3 space-y-1 mt-4" data-purpose="navigation-menu" id="sidebar-nav-container">
<button class="nav-item flex items-center w-full px-4 py-3 rounded-lg bg-sidebar-active transition-colors" data-view="groups">
<i class="fa-solid fa-users mr-3 w-5"></i> Groups
</button>
<button class="nav-item flex items-center w-full px-4 py-3 rounded-lg hover-sidebar transition-colors" data-view="discussions">
<i class="fa-regular fa-comments mr-3 w-5"></i> Discussions
</button>
<button class="nav-item flex items-center w-full px-4 py-3 rounded-lg hover-sidebar transition-colors" data-view="my-topics">
<i class="fa-regular fa-newspaper mr-3 w-5"></i> My Topics
</button>
<button class="nav-item flex items-center w-full px-4 py-3 rounded-lg hover-sidebar transition-colors" data-view="quizzes">
<i class="fa-solid fa-clipboard-question mr-3 w-5"></i> Quizzes
</button>
<button class="nav-item flex items-center w-full px-4 py-3 rounded-lg hover-sidebar transition-colors" data-view="performance">
<i class="fa-solid fa-chart-line mr-3 w-5"></i> Performance
</button>
</nav>
<div class="p-3 border-t border-slate-700/50 space-y-1" data-purpose="sidebar-footer">
<button class="nav-item flex items-center w-full px-4 py-3 rounded-lg hover-sidebar transition-colors" data-view="settings">
<i class="fa-solid fa-gear mr-3 w-5"></i> Settings
</button>
<form method="POST" action="{{ route('logout') }}" class="m-0 p-0 w-full">
    @csrf
    <button type="submit" class="flex items-center w-full px-4 py-3 rounded-lg hover-sidebar transition-colors text-left text-white">
        <i class="fa-solid fa-right-from-bracket mr-3 w-5"></i> Logout
    </button>
</form>
</div>
</aside>
<div class="flex-1 ml-64 flex flex-col min-h-screen">
<header class="h-16 bg-white border-b flex items-center justify-between px-8 sticky top-0 z-20" data-purpose="top-header">
<div class="relative w-96">
<span class="absolute inset-y-0 left-0 flex items-center pl-3">
<i class="fa-solid fa-magnifying-glass text-slate-400"></i>
</span>
<input class="w-full pl-10 pr-4 py-2 bg-slate-100 border-none rounded-md focus:ring-2 focus:ring-blue-500 transition-all text-sm" id="global-search" placeholder="Search groups, topics, quizzes..." type="text"/>
</div>
<div class="flex items-center space-x-4">
<span class="text-slate-600 text-sm">Welcome back, <span class="font-bold text-slate-800">Benson</span></span>
<div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold cursor-pointer hover:bg-blue-700 transition-colors">B</div>
</div>
</header>
<main class="p-8 flex-1 flex flex-col" data-purpose="content-display" id="main-content">
</main>
</div>
</div>
<div class="notification-toast" id="notification-toast">Action successful.</div>
<div class="hidden fixed inset-0 bg-slate-900/60 flex items-center justify-center z-50 px-4" id="group-modal">
<div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
<div class="p-6 border-b">
<h3 class="text-xl font-bold">Create New Group</h3>
</div>
<div class="p-6 space-y-4">
<div>
<label class="block text-sm font-medium text-slate-700 mb-1">Group Name</label>
<input class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500" id="group-input-name" placeholder="e.g., Data Science Group" type="text"/>
</div>
<div>
<label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
<textarea class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500" id="group-input-desc" placeholder="What is this group about?" rows="3"></textarea>
</div>
</div>
<div class="p-6 bg-slate-50 flex justify-end space-x-3">
<button class="px-4 py-2 text-slate-600 hover:text-slate-800 font-medium" onclick="toggleGroupModal(false)">Cancel</button>
<button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors" id="save-group">Create Group</button>
</div>
</div>
</div>
<div class="hidden fixed inset-0 bg-slate-900/60 flex items-center justify-center z-50 px-4" id="topic-modal">
<div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
<div class="p-6 border-b">
<h3 class="text-xl font-bold">Create New Topic</h3>
</div>
<div class="p-6 space-y-4">
<div>
<label class="block text-sm font-medium text-slate-700 mb-1">Topic Title</label>
<input class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500" id="topic-input-name" placeholder="e.g., Introduction to CSS Grid" type="text"/>
</div>
<div>
<label class="block text-sm font-medium text-slate-700 mb-1">Post content</label>
<textarea class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500" id="topic-input-desc" placeholder="Start the discussion..." rows="4"></textarea>
</div>
</div>
<div class="p-6 bg-slate-50 flex justify-end space-x-3">
<button class="px-4 py-2 text-slate-600 hover:text-slate-800 font-medium" onclick="toggleTopicModal(false)">Cancel</button>
<button class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors" id="save-topic">Post Topic</button>
</div>
</div>
</div>
<div class="hidden fixed bg-white border border-slate-200 rounded-lg shadow-xl py-2 w-56 z-[100]" id="custom-context-menu">
<button class="w-full text-left px-4 py-2 text-sm text-slate-700 context-menu-item flex items-center" onclick="toggleEmojiPicker(event, true)">
<i class="fa-regular fa-face-smile mr-3 text-slate-400"></i> React with Emoji
</button>
<div class="my-1 border-t border-slate-100"></div>
<button class="w-full text-left px-4 py-2 text-sm text-red-600 context-menu-item flex items-center" onclick="handleContextAction('flag')">
<i class="fa-solid fa-triangle-exclamation mr-3 text-red-400"></i> Flag as Irrelevant
</button>
</div>
<div class="hidden fixed bg-white border border-slate-200 rounded-xl shadow-2xl z-[110] w-64 overflow-hidden flex flex-col" id="emoji-picker">
<div class="p-3 border-b bg-slate-50 text-[10px] font-bold text-slate-500 flex justify-between items-center tracking-wider">
<span>QUICK EMOJI</span>
<button class="hover:text-slate-800" onclick="document.getElementById('emoji-picker').classList.add('hidden')"><i class="fa-solid fa-xmark"></i></button>
</div>
<div class="h-48 overflow-y-auto p-2 custom-scrollbar grid grid-cols-6 gap-1" id="emoji-grid">
</div>
</div>
<script data-purpose="state-management">
    // Mock Data State
    const state = {
        user: "{{ Auth::user()->name }}",
        activeView: 'groups', 
        selectedGroupId: null,
        selectedTopicId: null,
        
        // Formats your real database groups into what your layout JS loops through
        groups: @json($groups ?? []).map(group => ({
            id: group.id,
            name: group.name,
            description: group.description,
            memberCount: group.memberCount ?? 0,
            likes: 0
        })),
        
        // Formats your real database topics into what your layout JS loops through
        topics: [],


       


        messages: {
            101: [
                { id: 1, author: "Sarah Miller", text: "Hey everyone! I'm starting a series on basic neural networks. Any interest?", time: "10:30 AM", isMe: false, likes: 2, myLike: false, reactions: [] },
                { id: 2, author: "Alex Chen", text: "That sounds great! Will you cover backpropagation?", time: "10:35 AM", isMe: false, likes: 0, myLike: false, reactions: [] },
                { id: 3, author: "Benson", text: "Count me in. I've been wanting to learn more about the math behind it.", time: "10:45 AM", isMe: true, likes: 1, myLike: false, reactions: [] }
            ],
            103: [
                { id: 1, author: "Benson", text: "Is flexbox still the king or are we moving fully to Grid for centering everything?", time: "09:00 AM", isMe: true, likes: 4, myLike: false, reactions: [] },
                { id: 2, author: "Mike Ross", text: "Flexbox for 1D, Grid for 2D. But for simple centering, place-content: center on a grid is cleaner IMO.", time: "09:15 AM", isMe: false, likes: 1, myLike: false, reactions: [] }
            ]
        },

        quizzes: [
            { id: 201, title: "Machine Learning Basics", category: "Data Science", dueDate: "Oct 25, 2024", duration: "45 mins", status: "incoming" },
            { id: 202, title: "CSS Layout Fundamentals", category: "Web Dev", dueDate: "Oct 28, 2024", duration: "30 mins", status: "incoming" },
            { id: 203, title: "Python Data Structures", category: "Data Science", dueDate: "Oct 15, 2024", score: "92/100", status: "submitted" },
            { id: 204, title: "JavaScript ES6 Essentials", category: "Web Dev", dueDate: "Oct 10, 2024", score: "88/100", status: "submitted" }
        ]
    };

    const emojis = [
        '😀', '😃', '😄', '😁', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', 
        '😌', '😍', '🥰', '😘', '😋', '😛', '😜', '🤪', '🤨', '🧐', '🤓', '😎',
        '👍', '👎', '👊', '✊', '🤛', '🤜', '👏', '🙌', '👐', '🤲', '🤝', '🙏',
        '❤️', '🧡', '💛', '💚', '💙', '💜', '🖤', '🔥', '✨', '💯', '🚀', '⭐'
    ];

    let currentContextElement = null;
    let pickerMode = 'reaction'; 
    let mainContent, navItems, groupModal, topicModal, contextMenu, emojiPicker, emojiGrid;

    // Wait until DOM is completely parsed before bootstrapping views
    document.addEventListener('DOMContentLoaded', () => {
        mainContent = document.getElementById('main-content');
        navItems = document.querySelectorAll('.nav-item');
        groupModal = document.getElementById('group-modal');
        topicModal = document.getElementById('topic-modal');
        contextMenu = document.getElementById('custom-context-menu');
        emojiPicker = document.getElementById('emoji-picker');
        emojiGrid = document.getElementById('emoji-grid');

        // Initialize Emoji Grid
        emojis.forEach(emoji => {
            const btn = document.createElement('button');
            btn.className = 'hover:bg-slate-100 p-1.5 text-lg rounded transition-colors';
            btn.innerText = emoji;
            btn.onclick = (e) => {
                e.stopPropagation();
                if (pickerMode === 'reaction') {
                    insertReaction(emoji);
                } else {
                    insertToInput(emoji);
                }
            };
            emojiGrid?.appendChild(btn);
        });

        // Setup click listener for dynamic view switching
        document.addEventListener('click', (e) => {
            const navBtn = e.target.closest('[data-view]');
            if (navBtn) {
                updateView(navBtn.getAttribute('data-view'));
            }
        });

        // Connect Modal Handlers securely
        document.getElementById('save-group')?.addEventListener('click', () => {
            const name = document.getElementById('group-input-name').value;
            const desc = document.getElementById('group-input-desc').value;
            if(name.trim() && desc.trim()) {
                state.groups.unshift({ id: Date.now(), name, description: desc, memberCount: 1, likes: 0 });
                toggleGroupModal(false);
                renderView();
                setupContextListeners();
            }
        });

        

// NEW:
document.getElementById('save-topic')?.addEventListener('click', async () => {
    const title = document.getElementById('topic-input-name').value;
    const content = document.getElementById('topic-input-desc').value;
    if (!title.trim() || !content.trim()) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const response = await fetch('/topics', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                title: title,
                content: content,
                group_id: state.selectedGroupId || null
            })
        });

        if (!response.ok) throw new Error('Failed to create topic');

        toggleTopicModal(false);
        await fetchTopics();
    } catch (err) {
        console.error(err);
        alert('Could not post topic. Please try again.');
    }
});


        // Global click out to hide floating panels
        document.addEventListener('click', (e) => {
            if (contextMenu && !contextMenu.contains(e.target)) contextMenu.classList.add('hidden');
            if (emojiPicker && !emojiPicker.contains(e.target)) emojiPicker.classList.add('hidden');
        });

        // Initial bootstrap render
        updateView('groups');
        fetchTopics();
    });

    function showNotification() {
        const toast = document.getElementById('notification-toast');
        toast?.classList.add('show');
        setTimeout(() => toast?.classList.remove('show'), 3000);
    }

    function updateView(viewName) {
        state.activeView = viewName;
        
        navItems?.forEach(item => {
            if(item.getAttribute('data-view') === viewName) {
                item.classList.add('bg-sidebar-active');
                item.classList.remove('hover-sidebar');
            } else {
                item.classList.remove('bg-sidebar-active');
                item.classList.add('hover-sidebar');
            }
        });

        renderView();
        setupContextListeners();
    }

    function openGroup(groupId) {
        state.selectedGroupId = groupId;
        updateView('group_details');
    }

    function openTopic(topicId) {
        state.selectedTopicId = topicId;
        updateView('chat');
    }

    function renderView() {
        let html = '';
        if (!mainContent) return;
        
        switch(state.activeView) {
            case 'groups':
                html = `
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Available Groups</h2>
                        <button onclick="toggleGroupModal(true)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition-colors">
                            <i class="fa-solid fa-plus mr-2"></i> Create Group
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        ${state.groups.map(group => `
                            <div onclick="openGroup(${group.id})" class="post-card bg-white p-6 rounded-xl border border-slate-200 shadow-sm hover:border-blue-400 cursor-pointer transition-all hover:shadow-md group flex flex-col h-full">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-users text-xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 mb-2">${group.name}</h3>
                                <p class="text-slate-500 text-sm mb-4 line-clamp-2">${group.description}</p>
                                <div class="mt-auto flex items-center justify-between text-xs font-medium text-slate-400">
                                    <span><i class="fa-regular fa-user mr-1"></i> ${group.memberCount} members</span>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
                break;

            case 'group_details':
                const group = state.groups.find(g => g.id === state.selectedGroupId);
                const gTopics = state.topics.filter(t => t.groupId === state.selectedGroupId);
                html = `
                    <div class="mb-6 flex items-center space-x-2 text-sm">
                        <button onclick="updateView('groups')" class="text-blue-600 hover:underline">Groups</button>
                        <span class="text-slate-400">/</span>
                        <span class="text-slate-600 font-medium">${group.name}</span>
                    </div>
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">${group.name} Topics</h2>
                        <button onclick="toggleTopicModal(true)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition-colors">
                            <i class="fa-solid fa-plus mr-2"></i> Create Topic
                        </button>
                    </div>
                    <div class="space-y-3">
                        ${gTopics.map(topic => renderTopicItem(topic)).join('')}
                    </div>
                `;
                break;

            case 'discussions':
                html = `
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">General Discussions</h2>
                        <button onclick="toggleTopicModal(true)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center transition-colors">
                            <i class="fa-solid fa-plus mr-2"></i> Create Topic
                        </button>
                    </div>
                    <div class="space-y-3">
                        ${state.topics.map(topic => renderTopicItem(topic)).join('')}
                    </div>
                `;
                break;

            case 'my-topics':
                const myTopics = state.topics.filter(t => t.author === state.user);
                html = `
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">My Topics</h2>
                    </div>
                    <div class="space-y-3">
                        ${myTopics.length ? myTopics.map(topic => renderTopicItem(topic)).join('') : '<div class="text-slate-400 py-10 text-center">You haven\'t started any topics yet.</div>'}
                    </div>
                `;
                break;

            case 'quizzes':
                const incoming = state.quizzes.filter(q => q.status === 'incoming');
                const submitted = state.quizzes.filter(q => q.status === 'submitted');
                html = `
                    <h2 class="text-2xl font-bold mb-6">Quizzes</h2>
                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                            <i class="fa-solid fa-hourglass-half mr-2 text-blue-500"></i> Incoming Quizzes
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${incoming.map(q => `
                                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">${q.category}</span>
                                            <span class="text-xs text-slate-400">Due: ${q.dueDate}</span>
                                        </div>
                                        <h4 class="font-bold text-slate-800 text-lg mb-1">${q.title}</h4>
                                        <p class="text-sm text-slate-500 mb-4"><i class="fa-regular fa-clock mr-1"></i> ${q.duration}</p>
                                    </div>
                                    <button class="w-full py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors">Start Quiz</button>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-700 mb-4 flex items-center">
                            <i class="fa-solid fa-circle-check mr-2 text-green-500"></i> Submitted Quizzes
                        </h3>
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-50 text-slate-600">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold">Quiz Title</th>
                                        <th class="px-6 py-4 font-semibold">Category</th>
                                        <th class="px-6 py-4 font-semibold">Submitted Date</th>
                                        <th class="px-6 py-4 font-semibold text-right">Score</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    ${submitted.map(q => `
                                        <tr>
                                            <td class="px-6 py-4 font-bold text-slate-800">${q.title}</td>
                                            <td class="px-6 py-4">${q.category}</td>
                                            <td class="px-6 py-4 text-slate-500">${q.dueDate}</td>
                                            <td class="px-6 py-4 text-right font-bold text-green-600">${q.score}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
                break;

            case 'performance':
                const scores = state.quizzes.filter(q => q.status === 'submitted');
                html = `
                    <h2 class="text-2xl font-bold mb-6">Performance Overview</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                            <p class="text-slate-500 text-sm mb-1">Average Score</p>
                            <h4 class="text-3xl font-bold text-blue-600">90%</h4>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                            <p class="text-slate-500 text-sm mb-1">Quizzes Completed</p>
                            <h4 class="text-3xl font-bold text-slate-800">${scores.length}</h4>
                        </div>
                        <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                            <p class="text-slate-500 text-sm mb-1">Global Rank</p>
                            <h4 class="text-3xl font-bold text-slate-800">#12</h4>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold mb-4">Quiz Score History</h3>
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                        ${scores.map(q => `
                            <div class="flex items-center justify-between p-6 border-b last:border-0 border-slate-100">
                                <div>
                                    <h4 class="font-bold text-slate-800">${q.title}</h4>
                                    <p class="text-xs text-slate-400 mt-1">${q.category} • Completed on ${q.dueDate}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xl font-black text-slate-900">${q.score}</span>
                                    <div class="w-24 bg-slate-100 h-2 rounded-full mt-2 overflow-hidden">
                                        <div class="bg-green-500 h-full" style="width: ${q.score.split('/')[0]}%"></div>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
                break;

            case 'chat':
                const topic = state.topics.find(t => t.id === state.selectedTopicId);
                const topicMessages = state.messages[state.selectedTopicId] || [];
                html = `
                    <div class="flex flex-col h-full -m-8">
                        <div class="h-16 bg-white border-b px-8 flex items-center justify-between">
                            <div class="flex items-center">
                                <button onclick="updateView('${state.selectedGroupId ? 'group_details' : 'discussions'}')" class="mr-4 text-slate-400 hover:text-slate-600">
                                    <i class="fa-solid fa-arrow-left"></i>
                                </button>
                                <h2 class="text-lg font-bold text-slate-800">${topic.title}</h2>
                            </div>
                        </div>
                        <div class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-slate-50" id="chat-messages">
                            <div class="max-w-4xl mx-auto space-y-6">
                                ${topicMessages.map(msg => `
                                    <div class="flex ${msg.isMe ? 'flex-row-reverse' : 'flex-row'} items-start space-x-2 ${msg.isMe ? 'space-x-reverse' : ''}">
                                        <div class="w-8 h-8 rounded-full mt-1 flex-shrink-0 flex items-center justify-center font-bold text-xs text-white ${msg.isMe ? 'bg-blue-600' : 'bg-slate-400'}">
                                            ${msg.author.charAt(0)}
                                        </div>
                                        <div class="max-w-[70%] group">
                                            <div class="flex items-center mb-1 ${msg.isMe ? 'justify-end' : ''}">
                                                <span class="text-xs font-bold text-slate-700 mr-2">${msg.author}</span>
                                                <span class="text-[10px] text-slate-400">${msg.time}</span>
                                            </div>
                                            <div class="message-bubble relative ${msg.isMe ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-white text-slate-700 border border-slate-200 rounded-tl-none'} p-4 rounded-2xl shadow-sm text-sm">
                                                ${msg.text}
                                            </div>
                                            <div class="flex items-center space-x-2 mt-1 ${msg.isMe ? 'flex-row-reverse space-x-reverse' : ''}">
                                                <button onclick="handleLike(event, this)" class="like-button ${msg.myLike ? 'active' : ''}">
                                                    <i class="fa-regular fa-heart mr-1.5"></i> <span>${msg.likes || 0}</span>
                                                </button>
                                                <div class="reaction-container flex flex-wrap gap-1 ${msg.isMe ? 'flex-row-reverse' : ''}">
                                                    ${(msg.reactions || []).map(r => `<div class="reaction-badge ${r.me ? 'active' : ''}" onclick="toggleReaction(event, this, '${r.emoji}')">${r.emoji} ${r.count}</div>`).join('')}
                                                </div>
                                                ${msg.flagged ? '<div class="flag-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>' : ''}
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        <div class="h-24 bg-white border-t px-8 py-4 sticky bottom-0">
                            <div class="max-w-4xl mx-auto flex space-x-4 items-center h-full">
                                <div class="flex-1 relative">
                                    <input id="chat-input" type="text" class="w-full border-slate-200 rounded-full pl-6 pr-12 py-3 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Type your message here...">
                                    <button onclick="toggleEmojiPicker(event, false)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                        <i class="fa-regular fa-face-smile text-lg"></i>
                                    </button>
                                </div>
                                <button onclick="sendMessage()" class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors shadow-lg">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                break;
            
            case 'settings':
                html = `
                    <h2 class="text-2xl font-bold mb-6">Settings</h2>
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 max-w-2xl">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Display Name</label>
                                <input class="w-full border-slate-200 rounded-lg p-2" value="${state.user}" type="text" id="settings-username-input" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Email Notifications</label>
                                <div class="flex items-center">
                                    <input type="checkbox" class="rounded text-blue-600" checked />
                                    <span class="ml-2 text-sm text-slate-600">Receive weekly performance reports</span>
                                </div>
                            </div>
                            <button class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors" onclick="state.user = document.getElementById('settings-username-input').value; showNotification();">Save Changes</button>
                        </div>
                    </div>
                `;
                break;
        }
        
        mainContent.innerHTML = html;
        if(state.activeView === 'chat') {
            const chatBox = document.getElementById('chat-messages');
            if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
            
            const chatInput = document.getElementById('chat-input');
            if (chatInput) {
                chatInput.onkeypress = (e) => { if(e.key === 'Enter') sendMessage(); };
            }
        }
    }

    function renderTopicItem(topic) {
        return `
            <div onclick="openTopic(${topic.id})" class="post-card bg-white p-4 rounded-lg border border-slate-200 shadow-sm hover:border-blue-300 cursor-pointer transition-colors flex justify-between items-center group">
                <div class="flex flex-col">
                    <div class="flex items-center space-x-4 mb-2">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                            <i class="fa-regular fa-comment-dots"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900">${topic.title}</h4>
                            <div class="text-xs text-slate-500 mt-1">
                                Posted by <span class="font-semibold text-slate-700">${topic.author}</span> • ${topic.date}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-slate-400 text-sm flex items-center">
                    <i class="fa-regular fa-message mr-2"></i> ${topic.replies} replies
                </div>
            </div>
        `;
    }

    function handleLike(event, btn) {
        event.stopPropagation();
        const isActive = btn.classList.contains('active');
        const countSpan = btn.querySelector('span:last-child');
        let currentCount = parseInt(countSpan.innerText);
        
        if (isActive) {
            btn.classList.remove('active');
            countSpan.innerText = Math.max(0, currentCount - 1);
        } else {
            btn.classList.add('active');
            countSpan.innerText = currentCount + 1;
            showNotification();
        }
    }

    async function fetchTopics() {
    try {
        const response = await fetch('/topics');
        const data = await response.json();

        state.topics = data.map(topic => ({
            id: topic.id,
            groupId: topic.group_id,
            title: topic.title,
            content: topic.content,
            author: topic.user ? topic.user.name : 'Unknown',
            date: topic.created_at ? new Date(topic.created_at).toLocaleDateString() : 'Just now',
            replies: 0,
            likes: 0
        }));

        renderView();
        setupContextListeners();
    } catch (err) {
        console.error('Failed to load topics:', err);
    }
}
     
    function toggleReaction(event, badge, emoji) {
        event.stopPropagation();
        const isActive = badge.classList.contains('active');
        const text = badge.innerText;
        const countStr = text.replace(emoji, '').trim();
        let currentCount = parseInt(countStr);

        if (isActive) {
            badge.classList.remove('active');
            currentCount--;
            if (currentCount <= 0) {
                badge.remove();
            } else {
                badge.innerText = `${emoji} ${currentCount}`;
            }
        } else {
            badge.classList.add('active');
            badge.innerText = `${emoji} ${currentCount + 1}`;
        }
    }

    function toggleEmojiPicker(event, isContextMenuMode) {
        event.stopPropagation();
        const rect = event.currentTarget.getBoundingClientRect();
        
        if (isContextMenuMode) {
            pickerMode = 'reaction';
            if (emojiPicker) {
                emojiPicker.style.left = `${rect.right + 10}px`;
                emojiPicker.style.top = `${rect.top}px`;
            }
            contextMenu?.classList.add('hidden');
        } else {
            pickerMode = 'input';
            if (emojiPicker) {
                emojiPicker.style.left = `${rect.left - 200}px`;
                emojiPicker.style.top = `${rect.top - 230}px`;
            }
        }
        
        emojiPicker?.classList.remove('hidden');
    }

    function handleContextAction(action) {
        if (!currentContextElement) return;

        const postMeta = currentContextElement.closest('.group').querySelector('.flex.items-center.space-x-2, .flex.items-center.space-x-3');

        switch(action) {
            case 'flag':
                let flagIcon = postMeta?.querySelector('.flag-icon');
                if (!flagIcon && postMeta) {
                    flagIcon = document.createElement('div');
                    flagIcon.className = 'flag-icon';
                    flagIcon.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i>';
                    postMeta.appendChild(flagIcon);
                }
                break;
        }
        contextMenu?.classList.add('hidden');
    }

    function insertReaction(emoji) {
        if (!currentContextElement) return;
        const container = currentContextElement.closest('.group').querySelector('.reaction-container');
        if (!container) return;
        
        let existingBadge = Array.from(container.querySelectorAll('.reaction-badge')).find(b => b.innerText.includes(emoji));
        
        if (existingBadge) {
            if (!existingBadge.classList.contains('active')) {
                toggleReaction({ stopPropagation: () => {} }, existingBadge, emoji);
            }
        } else {
            const badge = document.createElement('div');
            badge.className = 'reaction-badge active';
            badge.innerHTML = `${emoji} 1`;
            badge.onclick = (e) => toggleReaction(e, badge, emoji);
            container.appendChild(badge);
        }
        
        emojiPicker?.classList.add('hidden');
    }

    function insertToInput(emoji) {
        const input = document.getElementById('chat-input');
        if (!input) return;
        
        const start = input.selectionStart;
        const end = input.selectionEnd;
        const text = input.value;
        const before = text.substring(0, start);
        const after = text.substring(end, text.length);
        
        input.value = before + emoji + after;
        input.selectionStart = input.selectionEnd = start + emoji.length;
        input.focus();
        
        emojiPicker?.classList.add('hidden');
    }

    function setupContextListeners() {
        const elements = document.querySelectorAll('.message-bubble');
        elements.forEach(el => {
            el.addEventListener('contextmenu', (e) => {
                e.preventDefault();
                e.stopPropagation();
                currentContextElement = el;
                
                if (contextMenu) {
                    contextMenu.style.left = `${e.clientX}px`;
                    contextMenu.style.top = `${e.clientY}px`;
                    contextMenu.classList.remove('hidden');
                }
                emojiPicker?.classList.add('hidden');
            });
        });
    }

    function toggleGroupModal(show) {
        groupModal?.classList.toggle('hidden', !show);
    }

    function toggleTopicModal(show) {
        topicModal?.classList.toggle('hidden', !show);
    }

    function sendMessage() {
        const input = document.getElementById('chat-input');
        if (!input) return;
        const text = input.value.trim();
        if(!text) return;
        if(!state.messages[state.selectedTopicId]) state.messages[state.selectedTopicId] = [];
        state.messages[state.selectedTopicId].push({
            id: Date.now(),
            author: state.user,
            text: text,
            time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            isMe: true,
            likes: 0,
            myLike: false,
            reactions: []
        });
        input.value = '';
        renderView();
        setupContextListeners();
    }
</script>
</body></html>