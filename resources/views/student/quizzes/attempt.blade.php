<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>const quizId = {{ $quizId }};</script>
<title>Quiz Portal </title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "surface-container-low": "#f2f4f6",
                    "on-secondary-container": "#5c647a",
                    "surface-bright": "#f7f9fb",
                    "surface-dim": "#d8dadc",
                    "surface": "#f7f9fb",
                    "primary": "#006194",
                    "on-error": "#ffffff",
                    "on-tertiary-fixed-variant": "#005048",
                    "error": "#ba1a1a",
                    "error-container": "#ffdad6",
                    "background": "#f7f9fb",
                    "on-primary-fixed-variant": "#004b73",
                    "inverse-surface": "#2d3133",
                    "tertiary": "#00685d",
                    "surface-tint": "#006398",
                    "on-tertiary-fixed": "#00201c",
                    "on-tertiary": "#ffffff",
                    "outline-variant": "#bfc7d2",
                    "secondary-container": "#dae2fd",
                    "tertiary-container": "#008376",
                    "on-error-container": "#93000a",
                    "tertiary-fixed-dim": "#4fdbc8",
                    "on-primary-container": "#fdfcff",
                    "primary-container": "#007bb9",
                    "surface-container": "#eceef0",
                    "secondary-fixed": "#dae2fd",
                    "on-surface-variant": "#3f4850",
                    "on-secondary-fixed": "#131b2e",
                    "primary-fixed": "#cce5ff",
                    "on-secondary": "#ffffff",
                    "on-primary": "#ffffff",
                    "surface-container-lowest": "#ffffff",
                    "surface-container-high": "#e6e8ea",
                    "secondary-fixed-dim": "#bec6e0",
                    "on-surface": "#191c1e",
                    "on-primary-fixed": "#001d31",
                    "outline": "#707881",
                    "on-tertiary-container": "#f4fffb",
                    "surface-variant": "#e0e3e5",
                    "on-secondary-fixed-variant": "#3f465c",
                    "inverse-on-surface": "#eff1f3",
                    "on-background": "#191c1e",
                    "surface-container-highest": "#e0e3e5",
                    "secondary": "#565e74",
                    "primary-fixed-dim": "#93ccff",
                    "tertiary-fixed": "#71f8e4",
                    "inverse-primary": "#93ccff"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "margin-mobile": "16px",
                    "gutter": "24px",
                    "stack-lg": "48px",
                    "container-max": "1280px",
                    "unit": "8px",
                    "stack-sm": "12px",
                    "stack-md": "24px",
                    "margin-desktop": "40px"
            },
            "fontFamily": {
                    "label-md": ["Inter"],
                    "headline-md": ["Inter"],
                    "body-sm": ["Inter"],
                    "headline-lg-mobile": ["Inter"],
                    "headline-lg": ["Inter"],
                    "body-md": ["Inter"],
                    "label-sm": ["Inter"],
                    "body-lg": ["Inter"]
            },
            "fontSize": {
                    "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "500"}],
                    "headline-md": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                    "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                    "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                    "headline-lg": ["30px", {"lineHeight": "38px", "letterSpacing": "-0.02em", "fontWeight": "600"}],
                    "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                    "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "600"}],
                    "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}]
            }
          },
        },
      }
    </script>
<style>
        body {
            background-color: #F8FAFC; /* Custom slate background as per guidance */
            font-family: 'Inter', sans-serif;
        }
        .question-card {
            background-color: #FFFFFF;
            border: 1px solid #E2E8F0;
            box-shadow: 0px 4px 12px rgba(15, 23, 42, 0.05);
            transition: box-shadow 0.2s ease-in-out;
        }
        .question-card:hover {
            box-shadow: 0px 8px 20px rgba(15, 23, 42, 0.08);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .progress-bar-fill {
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="min-h-screen text-on-surface">
<!-- TopAppBar (Based on JSON Anchors) -->
<header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-margin-desktop h-16 bg-surface-container-lowest border-b border-outline-variant shadow-sm">
<div class="flex items-center gap-4">
<h1 class="font-headline-md text-headline-md text-primary">Quiz Portal</h1>
<div class="h-6 w-px bg-outline-variant mx-2 hidden md:block"></div>
<span class="font-body-md text-on-surface-variant hidden md:block">Calculus II: Final Exam</span>
</div>
<div class="flex items-center gap-6">
<div class="flex items-center gap-2 px-3 py-1 bg-error-container text-on-error-container rounded-lg">
<span class="material-symbols-outlined text-[20px]" data-icon="timer">timer</span>
<span class="font-label-md text-label-md" id="timer">Time Remaining: --:--</span>
</div>
<div class="hidden lg:flex items-center gap-4 min-w-[200px]">
<div class="flex-1 h-2 bg-surface-container-high rounded-full overflow-hidden">
<div class="progress-bar-fill h-full bg-primary" style="width: 40%"></div>
</div>
<span class="font-label-sm text-label-sm text-primary">40% Complete</span>
</div>
</div>
</header>
<!-- Main Content Canvas -->
<main class="pt-24 pb-32 px-margin-mobile md:px-margin-desktop max-w-[1280px] mx-auto">
<div class="max-w-[800px] mx-auto space-y-gutter" id="questions-container">
    <!-- Questions rendered here by JS -->
</div>
</main>
<!-- BottomNavBar (Based on JSON Anchors) -->
<nav class="fixed bottom-0 left-0 w-full z-50 flex justify-between items-center h-20 px-margin-desktop bg-surface-container-lowest border-t border-outline-variant shadow-md">

<!-- Custom High-Contrast Submit Button -->
<button class="flex flex-col items-center justify-center bg-primary text-on-primary rounded-xl px-8 py-2 shadow-lg hover:bg-primary-container transition-all active:scale-95" type="button" onclick="submitQuiz()">
<span class="material-symbols-outlined" data-icon="check_circle" style="font-variation-settings: 'FILL' 1;">check_circle</span>
<span class="font-label-md text-label-md font-bold">Submit Quiz</span>
</button>
</nav>
<script>
    let timerInterval = null;
    let remainingSeconds = 0;
    let quizSubmitted = false; // guards against double-submit
    let autoSubmit = false; // tracks whether submission was time-triggered

    let attemptId = null;
    let quizData = null;
    let answers = {}; // { questionId: selectedAnswer }

    async function loadQuiz() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch(`/quizzes/${quizId}/start`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            });

            if (!response.ok) {
                const err = await response.json();
                alert(err.message || 'Could not load quiz.');
                window.location.href = '/student/dashboard';
                return;
            }

            const data = await response.json();
            attemptId = data.attempt_id;
            quizData = data.quiz;

            // Anchor to the server-computed deadline (quiz start_time + duration)
            if (data.deadline) {
                const deadline = new Date(data.deadline);
                remainingSeconds = Math.max(Math.floor((deadline.getTime() - Date.now()) / 1000), 0);
            } else {
                // No scheduled start_time on this quiz — fall back to a fresh full-duration timer
                remainingSeconds = quizData.duration_minutes * 60;
            }

            if (remainingSeconds <= 0) {
                // Time already expired (e.g. they reloaded after time was up)
                autoSubmitQuiz();
                return;
            }

            document.querySelector('.font-headline-md.text-headline-md.text-primary').textContent = quizData.title;
            startTimer();
            renderQuestions();
        } catch (err) {
            console.error(err);
            alert('Could not load quiz.');
        }
    }

    function startTimer() {
        updateTimerDisplay();
        timerInterval = setInterval(() => {
            remainingSeconds--;

            if (remainingSeconds <= 0) {
                remainingSeconds = 0;
                updateTimerDisplay();
                clearInterval(timerInterval);
                autoSubmit = true;
                autoSubmitQuiz();
                return;
            }

            updateTimerDisplay();
        }, 1000);
    }

    function updateTimerDisplay() {
        const minutes = Math.floor(remainingSeconds / 60);
        const seconds = remainingSeconds % 60;
        const formatted = `${minutes}:${String(seconds).padStart(2, '0')}`;

        const timerEl = document.getElementById('timer');
        if (timerEl) timerEl.textContent = `Time Remaining: ${formatted}`;

        const timerBox = timerEl?.closest('.bg-error-container');
        if (remainingSeconds <= 60) {
            timerBox?.classList.add('animate-pulse');
        }
    }

    function renderQuestions() {
        const container = document.getElementById('questions-container');
        container.innerHTML = quizData.questions.map((q, idx) => {
            let answerArea = '';

            if (q.type === 'mcq') {
                answerArea = `
                    <div class="space-y-4">
                        ${q.options.map(opt => `
                            <label class="flex items-center p-4 rounded-lg border border-outline-variant hover:bg-surface-container-low cursor-pointer transition-colors group">
                                <input class="w-5 h-5 text-primary border-outline-variant focus:ring-primary" name="q${q.id}" type="radio" onclick="setAnswer(${q.id}, '${opt.option_key}')"/>
                                <span class="ml-4 font-body-md text-body-md text-on-surface">${opt.option_text}</span>
                            </label>
                        `).join('')}
                    </div>
                `;
            } else if (q.type === 'tf') {
                answerArea = `
                    <div class="flex gap-4" id="tf-group-${q.id}">
                        <button type="button" class="flex-1 py-4 px-6 border-2 border-outline-variant rounded-xl font-body-lg text-body-lg hover:bg-surface-container-low transition-all active:scale-95" onclick="toggleActive(this, ${q.id}, 'True')">True</button>
                        <button type="button" class="flex-1 py-4 px-6 border-2 border-outline-variant rounded-xl font-body-lg text-body-lg hover:bg-surface-container-low transition-all active:scale-95" onclick="toggleActive(this, ${q.id}, 'False')">False</button>
                    </div>
                `;
            } else {
                answerArea = `
                    <textarea class="w-full p-4 bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary font-body-md text-body-md resize-none transition-all" placeholder="Type your answer here..." rows="6" oninput="setAnswer(${q.id}, this.value)"></textarea>
                `;
            }

            return `
                <section class="question-card rounded-xl p-stack-md">
                    <div class="flex justify-between items-start mb-6">
                        <span class="font-label-sm text-label-sm px-3 py-1 bg-surface-container-low text-on-surface-variant rounded-full uppercase tracking-wider">Question ${String(idx + 1).padStart(2, '0')}</span>
                    </div>
                    <h2 class="font-headline-md text-headline-md text-on-surface mb-8">${q.prompt}</h2>
                    ${answerArea}
                </section>
            `;
        }).join('');
    }

    function setAnswer(questionId, value) {
        answers[questionId] = value;
        updateProgress();
        autosaveAnswer(questionId, value);
    }

    async function autosaveAnswer(questionId, value) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        try {
            await fetch(`/attempts/${attemptId}/answer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ question_id: questionId, selected_answer: value })
            });
        } catch (err) {
            console.error('Autosave failed:', err);
        }
    }

    function toggleActive(element, questionId, value) {
        const buttons = element.parentElement.querySelectorAll('button');
        buttons.forEach(btn => {
            btn.classList.remove('bg-primary', 'text-white', 'border-primary');
            btn.classList.add('border-outline-variant', 'text-on-surface');
        });
        element.classList.remove('border-outline-variant', 'text-on-surface');
        element.classList.add('bg-primary', 'text-white', 'border-primary');

        setAnswer(questionId, value);
    }

    function updateProgress() {
        const total = quizData.questions.length;
        const answered = Object.keys(answers).length;
        const percent = total > 0 ? Math.round((answered / total) * 100) : 0;

        document.querySelector('.progress-bar-fill').style.width = percent + '%';
        document.querySelector('.font-label-sm.text-label-sm.text-primary').textContent = percent + '% Complete';
    }

    async function submitQuiz() {
        if (quizSubmitted) return;
        quizSubmitted = true;
        clearInterval(timerInterval);

        const submitBtn = document.querySelector('[onclick="submitQuiz()"]');
        if (submitBtn) submitBtn.disabled = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const payload = {
            answers: Object.entries(answers).map(([questionId, selectedAnswer]) => ({
                question_id: parseInt(questionId),
                selected_answer: selectedAnswer
            }))
        };

        try {
            const response = await fetch(`/attempts/${attemptId}/submit`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (!response.ok) {
                quizSubmitted = false;
                alert(result.message || 'Could not submit quiz.');
                if (submitBtn) submitBtn.disabled = false;
                return;
            }

            const prefix = autoSubmit ? 'Time is up. ' : '';
            alert(`${prefix}Quiz submitted! Score: ${result.score}/${result.total_marks} (${result.correct_count}/${result.total_questions} correct)`);
            window.location.href = '/student/dashboard?view=quizzes';
        } catch (err) {
            quizSubmitted = false;
            console.error(err);
            alert('Could not submit quiz. Please try again.');
            if (submitBtn) submitBtn.disabled = false;
        }
    }

    async function autoSubmitQuiz() {
        if (quizSubmitted) return;
        alert("Time's up! Your quiz is being submitted automatically.");
        await submitQuiz();
    }

    document.addEventListener('DOMContentLoaded', loadQuiz);
</script>
</body></html>