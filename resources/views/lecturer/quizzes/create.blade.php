<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Academic Forum - Quiz Management</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,600;0,8..60,700;1,8..60,400&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-primary-fixed-variant": "#2d476f",
                    "inverse-on-surface": "#eef1f3",
                    "surface-container-high": "#e5e9eb",
                    "tertiary-container": "#2c3647",
                    "surface-dim": "#d7dadc",
                    "on-surface": "#181c1e",
                    "on-error": "#ffffff",
                    "on-surface-variant": "#43474e",
                    "on-primary": "#ffffff",
                    "on-error-container": "#93000a",
                    "secondary-fixed": "#d8e3fa",
                    "background": "#f7fafc",
                    "secondary-fixed-dim": "#bcc7dd",
                    "on-secondary": "#ffffff",
                    "surface-container-lowest": "#ffffff",
                    "surface-tint": "#455f88",
                    "outline": "#74777f",
                    "on-secondary-fixed": "#111c2c",
                    "primary": "#002045",
                    "on-secondary-container": "#586377",
                    "error-container": "#ffdad6",
                    "primary-fixed": "#d6e3ff",
                    "surface-container-highest": "#e0e3e5",
                    "inverse-surface": "#2d3133",
                    "on-primary-container": "#86a0cd",
                    "secondary-container": "#d5e0f7",
                    "surface-container": "#ebeef0",
                    "on-tertiary": "#ffffff",
                    "outline-variant": "#c4c6cf",
                    "surface": "#f7fafc",
                    "primary-fixed-dim": "#adc7f7",
                    "tertiary-fixed-dim": "#bdc7dc",
                    "surface-container-low": "#f1f4f6",
                    "tertiary": "#172131",
                    "error": "#ba1a1a",
                    "surface-variant": "#e0e3e5",
                    "primary-container": "#1a365d",
                    "surface-bright": "#f7fafc",
                    "on-secondary-fixed-variant": "#3c475a",
                    "on-background": "#181c1e",
                    "secondary": "#545f72",
                    "on-tertiary-fixed": "#121c2c",
                    "on-tertiary-container": "#959fb3",
                    "on-primary-fixed": "#001b3c",
                    "tertiary-fixed": "#d9e3f9",
                    "inverse-primary": "#adc7f7",
                    "on-tertiary-fixed-variant": "#3d4759"
            },
            "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
            },
            "spacing": {
                    "xs": "4px",
                    "md": "24px",
                    "sidebar-width": "280px",
                    "xl": "80px",
                    "gutter": "24px",
                    "sm": "12px",
                    "lg": "48px",
                    "base": "8px",
                    "container-max": "1200px"
            },
            "fontFamily": {
                    "headline-md-mobile": ["Source Serif 4"],
                    "display-lg": ["Source Serif 4"],
                    "body-md": ["Inter"],
                    "label-md": ["Inter"],
                    "headline-sm": ["Source Serif 4"],
                    "caption": ["Inter"],
                    "display-lg-mobile": ["Source Serif 4"],
                    "body-lg": ["Inter"],
                    "headline-md": ["Source Serif 4"]
            },
            "fontSize": {
                    "headline-md-mobile": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}],
                    "display-lg": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                    "label-md": ["14px", {"lineHeight": "1", "letterSpacing": "0.01em", "fontWeight": "600"}],
                    "headline-sm": ["24px", {"lineHeight": "1.4", "fontWeight": "600"}],
                    "caption": ["12px", {"lineHeight": "1.4", "fontWeight": "400"}],
                    "display-lg-mobile": ["32px", {"lineHeight": "1.2", "fontWeight": "700"}],
                    "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                    "headline-md": ["32px", {"lineHeight": "1.3", "fontWeight": "600"}]
            }
          },
        },
      }
    </script>
<style>
        .active-tab-content { display: block !important; }
        .hidden-tab-content { display: none !important; }
        .transition-all-custom { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        /* Custom scrollbar for academic feel */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f4f6; }
        ::-webkit-scrollbar-thumb { background: #c4c6cf; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #74777f; }

        .form-focus:focus {
            border-width: 2px;
            border-color: #002045;
            outline: none;
        }
    </style>
</head>
<body class="bg-background text-on-surface font-body-md text-body-md overflow-hidden">
<div class="flex h-screen w-full">
<!-- Sidebar Navigation (SideNavBar) -->
<aside class="w-sidebar-width h-screen sticky top-0 left-0 bg-surface-container-low dark:bg-surface-container-lowest border-r border-outline-variant dark:border-outline flex flex-col py-lg px-base shrink-0">
<!-- Header Brand Section -->
<div class="mb-lg px-4 flex items-center gap-4">
<div class="w-10 h-10 bg-primary-container rounded-lg flex items-center justify-center text-on-primary-container">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">school</span>
</div>
<div>
<h1 class="font-headline-sm text-headline-sm font-bold text-primary dark:text-primary-fixed leading-tight">Discussion Forum</h1>
<p class="font-label-md text-label-md text-on-surface-variant">Quiz Management</p>
</div>
</div>
<!-- Navigation Tabs -->
<nav class="flex-1 space-y-2">
<button class="w-full text-left flex items-center gap-4 py-3 px-4 text-primary dark:text-primary-fixed border-l-4 border-primary dark:border-primary-fixed font-bold bg-surface-container-high transition-colors opacity-90" id="btn-configure-quiz" onclick="switchTab('configure-quiz', this)">
<span class="material-symbols-outlined">settings</span>
<span class="font-label-md text-label-md">Configure Quiz</span>
</button>
<button class="w-full text-left flex items-center gap-4 py-3 px-4 text-on-surface-variant dark:text-on-tertiary-container hover:bg-surface-container-high dark:hover:bg-tertiary-container transition-colors" id="btn-add-questions" onclick="switchTab('add-questions', this)">
<span class="material-symbols-outlined">quiz</span>
<span class="font-label-md text-label-md">Add Questions</span>
</button>
<button class="w-full text-left flex items-center gap-4 py-3 px-4 text-on-surface-variant dark:text-on-tertiary-container hover:bg-surface-container-high dark:hover:bg-tertiary-container transition-colors" id="btn-review-summary" onclick="switchTab('review-summary', this)">
<span class="material-symbols-outlined">summarize</span>
<span class="font-label-md text-label-md">Review &amp; Summary</span>
</button>
</nav>
<!-- Bottom Profile and Action -->
<div class="mt-auto border-t border-outline-variant pt-md px-2">
<div class="flex items-center gap-3 mb-md">
<div class="overflow-hidden">
<p class="font-label-md text-label-md font-bold truncate">Dr. Julian Sterling</p>
<p class="font-caption text-caption text-on-surface-variant">Senior Research Fellow</p>
</div>
</div>
<button class="w-full py-3 bg-primary text-on-primary font-bold rounded-lg hover:opacity-90 transition-opacity flex items-center justify-center gap-2" type="button" onclick="finishAndSave()">
<span class="material-symbols-outlined text-sm">check_circle</span>
<span>Finish &amp; Save</span>
</button>
</div>
</aside>
<!-- Main Workspace -->
<main class="flex-1 overflow-y-auto px-lg py-lg">
<div class="max-w-container-max mx-auto">
<!-- Tab 1: Configure Quiz -->
<section class="active-tab-content space-y-md" id="tab-configure-quiz">
<header class="mb-lg">
<h2 class="font-headline-md text-headline-md text-primary mb-2">Quiz Settings</h2>
<p class="text-on-surface-variant font-body-md">Define the foundational parameters for your assessment.</p>
</header>
<div class="grid grid-cols-1 md:grid-cols-12 gap-md">
<div class="md:col-span-8 bg-surface-container-lowest p-md border border-outline-variant rounded-xl shadow-sm">
<div class="space-y-6">
<div class="flex flex-col gap-2">
<label class="font-label-md text-label-md text-primary" for="quiz-title">Quiz Title</label>
<input class="p-3 border border-outline-variant rounded-lg form-focus text-body-md" id="quiz-title" oninput="updateSummary()" placeholder="e.g., Advanced Quantum Mechanics Midterm" type="text" value="Advanced Quantum Mechanics Midterm"/>
</div>
<div class="flex flex-col gap-2">
<label class="font-label-md text-label-md text-primary" for="quiz-desc">Description</label>
<textarea class="p-3 border border-outline-variant rounded-lg form-focus text-body-md" id="quiz-desc" oninput="updateSummary()" placeholder="Briefly describe the scope and objectives of this quiz..." rows="4">Comprehensive midterm exam covering fundamentals of quantum mechanics and wave functions.</textarea>
</div>
<div class="flex flex-col gap-2">
<label class="font-label-md text-label-md text-primary" for="quiz-category">Category (Student Group)</label>
<select class="p-3 border border-outline-variant rounded-lg form-focus text-body-md bg-transparent" id="quiz-category" onchange="updateSummary()">
<option value="Physics Dept - Year 2">Physics Dept - Year 2</option>
<option value="Advanced Research Group">Advanced Research Group</option>
<option value="General Engineering">General Engineering</option>
<option value="Postgraduate Scholars">Postgraduate Scholars</option>
</select>
</div>
</div>
</div>
<div class="md:col-span-4 space-y-md">
<div class="bg-surface-container-lowest p-md border border-outline-variant rounded-xl shadow-sm">
<h3 class="font-label-md text-label-md text-primary mb-4 flex items-center gap-2">
<span class="material-symbols-outlined text-sm">timer</span> Parameters
                                </h3>
<div class="space-y-4">
<div class="flex flex-col gap-1">
<label class="font-caption text-caption text-on-surface-variant" for="start-time">Start Time</label>
<input class="p-2 border border-outline-variant rounded-lg form-focus text-body-md" id="start-time" oninput="updateSummary()" type="datetime-local"/>
</div>
<div class="flex flex-col gap-1">
<label class="font-caption text-caption text-on-surface-variant" for="time-limit">Time Limit (Minutes)</label>
<input class="p-2 border border-outline-variant rounded-lg form-focus text-body-md" id="time-limit" oninput="updateSummary()" type="number" value="60"/>
</div>
<div class="flex flex-col gap-1">
<label class="font-caption text-caption text-on-surface-variant" for="total-marks">Total Marks</label>
<input class="p-2 border border-outline-variant rounded-lg form-focus text-body-md" id="total-marks" type="number" value="100"/>
</div>
<div class="flex items-center justify-between pt-2">
<span class="font-label-md text-label-md text-on-surface">Shuffle Questions</span>
<label class="relative inline-flex items-center cursor-pointer">
<input class="sr-only peer" type="checkbox" value=""/>
<div class="w-11 h-6 bg-surface-container-high rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-outline-variant after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
</label>
</div>
</div>
</div>
<div class="p-md bg-secondary-container rounded-xl">
<p class="text-on-secondary-container font-caption text-caption leading-relaxed">
<span class="font-bold">Note:</span> Configuring a strict time limit ensures integrity in a remote academic environment.
                                </p>
</div>
</div>
</div>
</section>
<!-- Tab 2: Add Questions -->
<section class="hidden-tab-content space-y-md" id="tab-add-questions">
<header class="mb-lg">
<h2 class="font-headline-md text-headline-md text-primary mb-2">Question Bank</h2>
<p class="text-on-surface-variant font-body-md">Construct your assessment items using various pedagogic formats.</p>
</header>
<div class="flex gap-gutter h-[600px]">
<!-- Left: List View -->
<div class="w-1/3 flex flex-col gap-4">
<div class="flex-1 overflow-y-auto space-y-3 bg-surface-container-low p-sm rounded-xl border border-outline-variant">
<div class="space-y-2" id="question-list-container">
<!-- Questions dynamically rendered here -->
</div>
<button class="w-full py-4 border-2 border-dashed border-outline-variant rounded-xl text-on-surface-variant hover:text-primary hover:border-primary transition-all flex flex-col items-center gap-1" onclick="resetEditor()">
<span class="material-symbols-outlined">add_circle</span>
<span class="font-label-md text-label-md">Add New Question</span>
</button>
</div>
</div>
<!-- Right: Editor -->
<div class="flex-1 bg-white border border-outline-variant rounded-xl shadow-sm flex flex-col overflow-hidden">
<div class="p-md border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
<h3 class="font-label-md text-label-md text-primary" id="editor-title">Adding New Question</h3>
<span class="bg-primary text-on-primary text-[10px] px-2 py-1 rounded font-bold uppercase tracking-wider">Draft</span>
</div>
<div class="p-md flex-1 overflow-y-auto space-y-6">
<div class="flex flex-col gap-2">
<label class="font-label-md text-label-md text-primary">Question Type</label>
<select class="p-3 border border-outline-variant rounded-lg form-focus text-body-md bg-transparent" id="question-type" onchange="toggleAnswerFields()">
<option value="mcq">Multiple Choice</option>
<option value="tf">True / False</option>
<option value="sa">Short Answer</option>
</select>
</div>
<div class="flex flex-col gap-2">
<label class="font-label-md text-label-md text-primary">Question Prompt</label>
<textarea class="p-3 border border-outline-variant rounded-lg form-focus text-body-md" id="question-prompt" placeholder="Enter the academic question here..." rows="4"></textarea>
</div>
<div class="space-y-4" id="answer-key-area">
<h4 class="font-label-md text-label-md text-primary pt-2 border-t border-outline-variant">Answer Key</h4>
<!-- MCQ Area -->
<div class="space-y-3" id="mcq-options">
<div class="flex items-center gap-3">
<input class="w-5 h-5 text-primary focus:ring-primary border-outline-variant" name="mcq-correct" onchange="updateGradingKey('A')" type="radio" value="A"/>
<input class="flex-1 p-2 border border-outline-variant rounded-lg text-body-md form-focus" id="opt-A" placeholder="Option A" type="text"/>
</div>
<div class="flex items-center gap-3">
<input class="w-5 h-5 text-primary focus:ring-primary border-outline-variant" name="mcq-correct" onchange="updateGradingKey('B')" type="radio" value="B"/>
<input class="flex-1 p-2 border border-outline-variant rounded-lg text-body-md form-focus" id="opt-B" placeholder="Option B" type="text"/>
</div>
<div class="flex items-center gap-3">
<input class="w-5 h-5 text-primary focus:ring-primary border-outline-variant" name="mcq-correct" onchange="updateGradingKey('C')" type="radio" value="C"/>
<input class="flex-1 p-2 border border-outline-variant rounded-lg text-body-md form-focus" id="opt-C" placeholder="Option C" type="text"/>
</div>
<div class="flex items-center gap-3">
<input class="w-5 h-5 text-primary focus:ring-primary border-outline-variant" name="mcq-correct" onchange="updateGradingKey('D')" type="radio" value="D"/>
<input class="flex-1 p-2 border border-outline-variant rounded-lg text-body-md form-focus" id="opt-D" placeholder="Option D" type="text"/>
</div>
<p class="font-label-md text-primary mt-4 py-2 px-3 bg-primary-fixed rounded-lg hidden" id="grading-key-indicator">System Grading Key: <span class="font-bold" id="selected-key-val">-</span></p>
</div>
<!-- T/F Area -->
<div class="hidden flex gap-md" id="tf-options">
<label class="flex-1 flex items-center justify-center gap-2 p-4 border border-outline-variant rounded-lg cursor-pointer hover:bg-surface-container-low transition-colors">
<input class="text-primary border-outline-variant" name="tf-correct" type="radio" value="True"/>
<span class="font-label-md text-label-md">True</span>
</label>
<label class="flex-1 flex items-center justify-center gap-2 p-4 border border-outline-variant rounded-lg cursor-pointer hover:bg-surface-container-low transition-colors">
<input class="text-primary border-outline-variant" name="tf-correct" type="radio" value="False"/>
<span class="font-label-md text-label-md">False</span>
</label>
</div>
<!-- SA Area -->
<div class="hidden flex flex-col gap-2" id="sa-options">
<p class="font-caption text-caption text-on-surface-variant mb-1">Students must match this exact keyword (case-insensitive).</p>
<input class="p-3 border border-outline-variant rounded-lg form-focus text-body-md" id="sa-correct" placeholder="Enter correct keyword..." type="text"/>
</div>
</div>
</div>
<div class="p-md bg-surface-container-low border-t border-outline-variant text-right">
<button class="px-6 py-2 bg-primary text-on-primary font-label-md text-label-md rounded-lg hover:opacity-90 transition-opacity" onclick="saveQuestion()">
                                    Save Question to List
                                </button>
</div>
</div>
</div>
</section>
<!-- Tab 3: Review & Summary -->
<section class="hidden-tab-content space-y-md" id="tab-review-summary">
<header class="mb-lg">
<h2 class="font-headline-md text-headline-md text-primary mb-2">Final Review</h2>
<p class="text-on-surface-variant font-body-md">Comprehensive overview of your academic assessment before publication.</p>
</header>
<div class="grid grid-cols-1 md:grid-cols-3 gap-md">
<!-- Main Summary Card -->
<div class="md:col-span-2 space-y-md">
<div class="bg-surface-container-lowest border border-outline-variant rounded-2xl shadow-sm overflow-hidden">
<div class="p-lg space-y-8">
<div>
<h3 class="font-caption text-caption text-on-surface-variant uppercase tracking-widest mb-2">Quiz Title</h3>
<p class="font-headline-sm text-headline-sm text-primary font-bold" id="summary-title">Advanced Quantum Mechanics Midterm</p>
</div>
<div>
<h3 class="font-caption text-caption text-on-surface-variant uppercase tracking-widest mb-2">Description</h3>
<p class="font-body-md text-on-surface-variant italic leading-relaxed" id="summary-desc">Comprehensive midterm exam covering fundamentals of quantum mechanics.</p>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 gap-md border-t border-outline-variant pt-md">
<div>
<p class="font-caption text-caption text-on-surface-variant">Category</p>
<p class="font-label-md text-label-md text-primary" id="summary-category">Physics Dept</p>
</div>
<div>
<p class="font-caption text-caption text-on-surface-variant">Start Time</p>
<p class="font-label-md text-label-md text-primary" id="summary-start-time">Not Set</p>
</div>
<div>
<p class="font-caption text-caption text-on-surface-variant">Time Limit</p>
<p class="font-label-md text-label-md text-primary" id="summary-time">60 Minutes</p>
</div>
<div>
<p class="font-caption text-caption text-on-surface-variant">Total Questions</p>
<p class="font-label-md text-label-md text-primary" id="summary-count">0 Questions</p>
</div>
</div>
<div class="pt-md border-t border-outline-variant">
<div class="flex flex-col gap-2 max-w-xs">
<label class="font-caption text-caption text-on-surface-variant" for="passing-score">Passing Score (%)</label>
<input class="p-2 border border-outline-variant rounded-lg form-focus text-body-md" id="passing-score" type="number" value="70"/>
</div>
</div>
</div>
<div class="bg-primary text-on-primary p-md flex items-center justify-between">
<span class="font-label-md text-label-md opacity-80 italic">Ready for publishing</span>
</div>
</div>
<!-- Dynamic Question List in Review -->
<div class="space-y-4" id="review-questions-list">
<h3 class="font-label-md text-label-md text-primary">Question Preview</h3>
<!-- Question items will be rendered here -->
<p class="text-on-surface-variant italic" id="empty-questions-msg">No questions added yet.</p>
</div>
</div>

</div>
</div>
</div>
</section>
</div>
</main>
</div>
<script>
        // Data Store
      // NEW:
let quizId = null; // set once the quiz is actually created in the database

let quizData = {
    title: "",
    description: "",
    category: "Physics Dept - Year 2",
    startTime: "",
    timeLimit: "60",
    questions: []
};
            
        let currentEditingIndex = -1;

        // Tab switching logic
        async function switchTab(tabId, btn) {
    // If leaving Configure tab for the first time, create the real quiz record now
    if (tabId === 'add-questions' && !quizId) {
        updateSummary(); // make sure quizData has the latest form values first
        const created = await ensureQuizCreated();
        if (!created) return; // stop here if creation failed, stay on current tab
    }

    const sections = ['configure-quiz', 'add-questions', 'review-summary'];
    sections.forEach(s => {
        document.getElementById('tab-' + s).classList.add('hidden-tab-content');
        document.getElementById('tab-' + s).classList.remove('active-tab-content');
    });

    document.getElementById('tab-' + tabId).classList.add('active-tab-content');
    document.getElementById('tab-' + tabId).classList.remove('hidden-tab-content');

    const navButtons = document.querySelectorAll('nav button');
    navButtons.forEach(b => {
        b.className = "w-full text-left flex items-center gap-4 py-3 px-4 text-on-surface-variant dark:text-on-tertiary-container hover:bg-surface-container-high dark:hover:bg-tertiary-container transition-colors";
    });

    btn.className = "w-full text-left flex items-center gap-4 py-3 px-4 text-primary dark:text-primary-fixed border-l-4 border-primary dark:border-primary-fixed font-bold bg-surface-container-high transition-colors opacity-90";
    
    if(tabId === 'review-summary') renderReviewList();
}



        function toggleAnswerFields() {
            const type = document.getElementById('question-type').value;
            const mcq = document.getElementById('mcq-options');
            const tf = document.getElementById('tf-options');
            const sa = document.getElementById('sa-options');

            mcq.classList.add('hidden');
            tf.classList.add('hidden');
            sa.classList.add('hidden');

            if (type === 'mcq') mcq.classList.remove('hidden');
            else if (type === 'tf') tf.classList.remove('hidden');
            else if (type === 'sa') sa.classList.remove('hidden');
        }

        function updateGradingKey(option) {
            const indicator = document.getElementById('grading-key-indicator');
            const valSpan = document.getElementById('selected-key-val');
            indicator.classList.remove('hidden');
            valSpan.innerText = option;
        }

        function updateSummary() {
            quizData.title = document.getElementById('quiz-title').value;
            quizData.description = document.getElementById('quiz-desc').value;
            quizData.category = document.getElementById('quiz-category').value;
            quizData.startTime = document.getElementById('start-time').value;
            quizData.timeLimit = document.getElementById('time-limit').value;

            document.getElementById('summary-title').innerText = quizData.title || "Untitled Quiz";
            document.getElementById('summary-desc').innerText = quizData.description || "No description provided.";
            document.getElementById('summary-category').innerText = quizData.category;
            document.getElementById('summary-start-time').innerText = quizData.startTime ? new Date(quizData.startTime).toLocaleString() : "Not Set";
            document.getElementById('summary-time').innerText = quizData.timeLimit + " Minutes";
            document.getElementById('summary-count').innerText = quizData.questions.length + " Questions";
        }

        function resetEditor() {
            currentEditingIndex = -1;
            document.getElementById('editor-title').innerText = "Adding New Question";
            document.getElementById('question-prompt').value = "";
            document.getElementById('question-type').value = "mcq";
            document.getElementById('sa-correct').value = "";
            document.querySelectorAll('input[name="mcq-correct"]').forEach(r => r.checked = false);
            document.querySelectorAll('input[name="tf-correct"]').forEach(r => r.checked = false);
            document.getElementById('grading-key-indicator').classList.add('hidden');
            toggleAnswerFields();
        }

        
        // NEW:
async function saveQuestion() {
    const prompt = document.getElementById('question-prompt').value;
    if(!prompt) return alert("Please enter a question prompt.");

    const type = document.getElementById('question-type').value;
    let correctKey = "";
    let options = null;

    if(type === 'mcq') {
        const checked = document.querySelector('input[name="mcq-correct"]:checked');
        if(!checked) return alert("Please select a grading key.");
        correctKey = checked.value;

        options = ['A', 'B', 'C', 'D'].map(key => ({
            option_key: key,
            option_text: document.getElementById('opt-' + key).value
        }));

        const missingText = options.some(o => !o.option_text.trim());
        if (missingText) return alert("Please fill in text for all four options.");

    } else if(type === 'tf') {
        const checked = document.querySelector('input[name="tf-correct"]:checked');
        if(!checked) return alert("Please select True or False.");
        correctKey = checked.value;
    } else {
        correctKey = document.getElementById('sa-correct').value;
        if(!correctKey) return alert("Please enter the correct keyword.");
    }

    if (!quizId) {
        alert("Quiz isn't saved yet — please go back to Configure Quiz first.");
        return;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const questionObj = { prompt, type, correctKey, options };

    try {
        let response;

        if (currentEditingIndex > -1 && quizData.questions[currentEditingIndex].id) {
            // Editing an existing saved question
            const existingId = quizData.questions[currentEditingIndex].id;
            response = await fetch(`/questions/${existingId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    type,
                    prompt,
                    correct_answer: correctKey,
                    options
                })
            });
        } else {
            // Creating a brand new question
            response = await fetch(`/quizzes/${quizId}/questions`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({
                    type,
                    prompt,
                    correct_answer: correctKey,
                    options
                })
            });
        }

        if (!response.ok) throw new Error('Failed to save question');

        const saved = await response.json();
        questionObj.id = saved.id; // remember the real database ID for future edits

        if(currentEditingIndex > -1) {
            quizData.questions[currentEditingIndex] = questionObj;
        } else {
            quizData.questions.push(questionObj);
        }

        renderQuestionList();
        updateSummary();
        resetEditor();
    } catch (err) {
        console.error(err);
        alert('Could not save question. Please try again.');
    }
}

        function renderQuestionList() {
            const container = document.getElementById('question-list-container');
            container.innerHTML = "";
            quizData.questions.forEach((q, idx) => {
                const div = document.createElement('div');
                div.className = "p-4 bg-white border border-outline-variant rounded-lg hover:border-primary transition-all cursor-pointer flex justify-between items-center group";
                div.onclick = () => editQuestion(idx);
                div.innerHTML = `
                    <div>
                        <p class="font-label-md text-label-md ${currentEditingIndex === idx ? 'text-primary font-bold' : 'text-on-surface'}">Question ${idx + 1}</p>
                        <p class="text-on-surface-variant font-caption text-caption truncate w-32">${q.prompt}</p>
                    </div>
                    <span class="material-symbols-outlined text-on-surface-variant text-sm">edit</span>
                `;
                container.appendChild(div);
            });
        }

        function editQuestion(idx) {
            currentEditingIndex = idx;
            const q = quizData.questions[idx];
            document.getElementById('editor-title').innerText = "Editing Question " + (idx + 1);
            document.getElementById('question-prompt').value = q.prompt;
            document.getElementById('question-type').value = q.type;
            toggleAnswerFields();

            if(q.type === 'mcq') {
                document.querySelector(`input[name="mcq-correct"][value="${q.correctKey}"]`).checked = true;
                updateGradingKey(q.correctKey);
            } else if(q.type === 'tf') {
                document.querySelector(`input[name="tf-correct"][value="${q.correctKey}"]`).checked = true;
            } else {
                document.getElementById('sa-correct').value = q.correctKey;
            }
        }

        function deleteQuestion(idx) {
            quizData.questions.splice(idx, 1);
            renderQuestionList();
            renderReviewList();
            updateSummary();
        }

        function renderReviewList() {
            const container = document.getElementById('review-questions-list');
            const emptyMsg = document.getElementById('empty-questions-msg');
            
            // Clear existing except header and empty message
            const items = container.querySelectorAll('.review-item');
            items.forEach(i => i.remove());

            if(quizData.questions.length === 0) {
                emptyMsg.classList.remove('hidden');
                return;
            }
            emptyMsg.classList.add('hidden');

            quizData.questions.forEach((q, idx) => {
                const card = document.createElement('div');
                card.className = "review-item p-4 bg-surface-container-low border border-outline-variant rounded-xl flex justify-between items-start";
                card.innerHTML = `
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-primary-container text-on-primary-container text-[10px] px-2 py-0.5 rounded font-bold uppercase">${q.type}</span>
                            <p class="font-label-md text-label-md text-primary">Question ${idx + 1}</p>
                        </div>
                        <p class="font-body-md text-on-surface mb-2">${q.prompt}</p>
                        <p class="font-caption text-caption text-on-surface-variant"><span class="font-bold">Grading Key:</span> ${q.correctKey}</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="switchTab('add-questions', document.getElementById('btn-add-questions')); editQuestion(${idx})" class="p-1 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </button>
                        <button onclick="deleteQuestion(${idx})" class="p-1 hover:text-error transition-colors">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        window.onload = function() {
            updateSummary();
        };

          async function ensureQuizCreated() {
    if (quizId) return true; // already created, nothing to do

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const response = await fetch('/quizzes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                title: quizData.title || 'Untitled Quiz',
                description: quizData.description,
                start_time: quizData.startTime || null,
                duration_minutes: parseInt(quizData.timeLimit) || 60,
                total_marks: parseInt(document.getElementById('total-marks').value) || 100,
                passing_score: parseInt(document.getElementById('passing-score')?.value) || 70,
                shuffle_questions: document.querySelector('input[type="checkbox"]')?.checked || false
            })
        });

        if (!response.ok) throw new Error('Failed to create quiz');

        const created = await response.json();
        quizId = created.id;
        return true;
    } catch (err) {
        console.error(err);
        alert('Could not save quiz configuration. Please try again.');
        return false;
    }
}

async function publishQuiz() {
    if (!quizId) return false;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const response = await fetch(`/quizzes/${quizId}/publish`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            const payload = await response.json().catch(() => ({}));
            alert(payload.message || 'Quiz was saved, but it could not be published yet.');
            return false;
        }

        return true;
    } catch (err) {
        console.error(err);
        alert('Quiz was saved, but publishing failed.');
        return false;
    }
}

async function finishAndSave() {
    updateSummary();

    const created = await ensureQuizCreated();
    if (!created) return;

    if (quizData.questions.length === 0) {
        alert('Quiz saved as a draft. Add questions to publish it later.');
        return;
    }

    const published = await publishQuiz();
    if (published) {
        alert('Quiz saved and published successfully.');
        window.location.href = '/lecturer/dashboard';
    }
}

    </script>
</body></html>