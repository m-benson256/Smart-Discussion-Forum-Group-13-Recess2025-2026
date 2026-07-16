<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<title> Onboarding interests</title>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Google Fonts: Inter -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary-fixed": "#dbe1ff",
                        "on-error-container": "#93000a",
                        "secondary-container": "#d0e1fb",
                        "on-tertiary": "#ffffff",
                        "tertiary-fixed": "#ffdbcd",
                        "inverse-on-surface": "#eef0ff",
                        "background": "#faf8ff",
                        "on-tertiary-fixed-variant": "#7d2d00",
                        "on-background": "#131b2e",
                        "outline-variant": "#c3c6d7",
                        "on-primary-fixed-variant": "#003ea8",
                        "secondary-fixed": "#d3e4fe",
                        "error": "#ba1a1a",
                        "on-secondary": "#ffffff",
                        "tertiary-fixed-dim": "#ffb596",
                        "surface-dim": "#d2d9f4",
                        "secondary": "#505f76",
                        "surface-container": "#eaedff",
                        "on-secondary-fixed": "#0b1c30",
                        "surface-bright": "#faf8ff",
                        "outline": "#737686",
                        "on-tertiary-container": "#ffede6",
                        "surface-container-high": "#e2e7ff",
                        "surface-container-low": "#f2f3ff",
                        "on-secondary-fixed-variant": "#38485d",
                        "primary-container": "#2563eb",
                        "on-primary": "#ffffff",
                        "inverse-primary": "#b4c5ff",
                        "on-tertiary-fixed": "#360f00",
                        "surface-variant": "#dae2fd",
                        "surface": "#faf8ff",
                        "on-surface-variant": "#434655",
                        "surface-tint": "#0053db",
                        "on-surface": "#131b2e",
                        "primary": "#004ac6",
                        "tertiary": "#943700",
                        "error-container": "#ffdad6",
                        "on-primary-container": "#eeefff",
                        "surface-container-lowest": "#ffffff",
                        "secondary-fixed-dim": "#b7c8e1",
                        "on-error": "#ffffff",
                        "surface-container-highest": "#dae2fd",
                        "inverse-surface": "#283044",
                        "tertiary-container": "#bc4800",
                        "primary-fixed-dim": "#b4c5ff",
                        "on-primary-fixed": "#00174b",
                        "on-secondary-container": "#54647a"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "container-max": "1200px",
                        "margin-mobile": "16px",
                        "gutter": "24px",
                        "onboarding-card-max": "560px",
                        "unit": "8px",
                        "margin-desktop": "40px"
                    },
                    "fontFamily": {
                        "display": ["Inter"],
                        "label-sm": ["Inter"],
                        "body-lg": ["Inter"],
                        "label-md": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "headline-lg-mobile": ["Inter"]
                    },
                    "fontSize": {
                        "display": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "label-sm": ["12px", {"lineHeight": "1.2", "fontWeight": "600"}],
                        "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "label-md": ["14px", {"lineHeight": "1.4", "letterSpacing": "0.01em", "fontWeight": "500"}],
                        "body-md": ["16px", {"lineHeight": "1.5", "fontWeight": "400"}],
                        "headline-md": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}],
                        "headline-lg": ["32px", {"lineHeight": "1.2", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "1.2", "fontWeight": "600"}]
                    }
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .active-card {
            border-color: rgba(96, 165, 250, 0.75) !important;
            background-color: rgba(255, 255, 255, 0.18) !important;
            backdrop-filter: blur(24px) !important;
            border-width: 2px !important;
            box-shadow: 0 0 0 1px rgba(96, 165, 250, 0.18), 0 20px 60px -24px rgba(0, 0, 0, 0.85) !important;
        }
        .step-progress {
            height: 4px;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-zinc-900 text-on-surface font-body-md antialiased min-h-screen flex flex-col relative">
<div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.18),_transparent_45%),radial-gradient(circle_at_bottom_right,_rgba(14,165,233,0.12),_transparent_35%)]"></div>
<!-- Progress Indicator Shell -->
<div class="fixed top-0 left-0 w-full bg-white/10 backdrop-blur-md z-50">
<div class="step-progress bg-primary-container w-2/3"></div>
</div>


<main class="relative z-10 flex-grow flex items-center justify-center p-4 md:p-8">
<!-- Onboarding Container -->
<div class="max-w-4xl w-full bg-white/10 backdrop-blur-2xl rounded-xl border border-white/15 shadow-[0_24px_80px_-24px_rgba(0,0,0,0.65)] overflow-hidden fade-in-up">
<div class="p-8 md:p-12">
<!-- Heading Group -->
<div class="text-center mb-10">
<h1 class="font-headline-lg text-headline-lg text-white mb-2 tracking-tight">Welcome! Personalize Your Feed</h1>
<p class="font-body-lg text-white/70 max-w-md mx-auto">Choose topics of interest to help us curate your specialized technical stream.</p>
</div>
<!-- Interest Grid Form -->
<form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="onboardingForm" method="POST" action="{{ route('onboarding.complete') }}">
@csrf
<!-- ML -->
<label class="relative flex flex-col p-5 bg-white/10 backdrop-blur-xl border border-white/15 rounded-lg cursor-pointer transition-all hover:border-white/30 hover:bg-white/15 active:scale-[0.98] group shadow-[0_10px_30px_-18px_rgba(0,0,0,0.75)]">
<input class="hidden peer" name="interests[]" onchange="toggleCardSelection(this)" type="checkbox" value="ml"/>
<div class="flex justify-between items-start mb-2">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">psychology</span>
<span class="material-symbols-outlined text-primary hidden peer-checked:block transition-all" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<h3 class="font-headline-md text-base font-bold text-white mb-1">Machine Learning</h3>
<p class="font-label-sm text-white/70">Neural networks, deep learning models, and predictive analytics.</p>
</label>
<!-- Web Dev -->
<label class="relative flex flex-col p-5 bg-white/10 backdrop-blur-xl border border-white/15 rounded-lg cursor-pointer transition-all hover:border-white/30 hover:bg-white/15 active:scale-[0.98] group shadow-[0_10px_30px_-18px_rgba(0,0,0,0.75)]">
<input class="hidden peer" name="interests[]" onchange="toggleCardSelection(this)" type="checkbox" value="web"/>
<div class="flex justify-between items-start mb-2">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">code</span>
<span class="material-symbols-outlined text-primary hidden peer-checked:block transition-all" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<h3 class="font-headline-md text-base font-bold text-white mb-1">Web Development</h3>
<p class="font-label-sm text-white/70">Modern frontend frameworks, serverless architecture, and API design.</p>
</label>
<!-- Databases -->
<label class="relative flex flex-col p-5 bg-white/10 backdrop-blur-xl border border-white/15 rounded-lg cursor-pointer transition-all hover:border-white/30 hover:bg-white/15 active:scale-[0.98] group shadow-[0_10px_30px_-18px_rgba(0,0,0,0.75)]">
<input class="hidden peer" name="interests[]" onchange="toggleCardSelection(this)" type="checkbox" value="db"/>
<div class="flex justify-between items-start mb-2">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">database</span>
<span class="material-symbols-outlined text-primary hidden peer-checked:block transition-all" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<h3 class="font-headline-md text-base font-bold text-white mb-1">Database Systems</h3>
<p class="font-label-sm text-white/70">SQL vs NoSQL, distributed storage, and high availability clusters.</p>
</label>
<!-- Mobile -->
<label class="relative flex flex-col p-5 bg-white/10 backdrop-blur-xl border border-white/15 rounded-lg cursor-pointer transition-all hover:border-white/30 hover:bg-white/15 active:scale-[0.98] group shadow-[0_10px_30px_-18px_rgba(0,0,0,0.75)]">
<input class="hidden peer" name="interests[]" onchange="toggleCardSelection(this)" type="checkbox" value="mobile"/>
<div class="flex justify-between items-start mb-2">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">smartphone</span>
<span class="material-symbols-outlined text-primary hidden peer-checked:block transition-all" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<h3 class="font-headline-md text-base font-bold text-white mb-1">Mobile Engineering</h3>
<p class="font-label-sm text-white/70">Native iOS/Android dev, cross-platform performance, and UX.</p>
</label>
<!-- Security -->
<label class="relative flex flex-col p-5 bg-white/10 backdrop-blur-xl border border-white/15 rounded-lg cursor-pointer transition-all hover:border-white/30 hover:bg-white/15 active:scale-[0.98] group shadow-[0_10px_30px_-18px_rgba(0,0,0,0.75)]">
<input class="hidden peer" name="interests[]" onchange="toggleCardSelection(this)" type="checkbox" value="security"/>
<div class="flex justify-between items-start mb-2">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">lock</span>
<span class="material-symbols-outlined text-primary hidden peer-checked:block transition-all" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<h3 class="font-headline-md text-base font-bold text-white mb-1">Cybersecurity</h3>
<p class="font-label-sm text-white/70">Encryption, threat detection, ethical hacking, and network defense.</p>
</label>
<!-- UI/UX -->
<label class="relative flex flex-col p-5 bg-white/10 backdrop-blur-xl border border-white/15 rounded-lg cursor-pointer transition-all hover:border-white/30 hover:bg-white/15 active:scale-[0.98] group shadow-[0_10px_30px_-18px_rgba(0,0,0,0.75)]">
<input class="hidden peer" name="interests[]" onchange="toggleCardSelection(this)" type="checkbox" value="design"/>
<div class="flex justify-between items-start mb-2">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">palette</span>
<span class="material-symbols-outlined text-primary hidden peer-checked:block transition-all" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<h3 class="font-headline-md text-base font-bold text-white mb-1">UI/UX Design</h3>
<p class="font-label-sm text-white/70">Design systems, accessibility, typography, and user research.</p>
</label>
<!-- Cloud -->
<label class="relative flex flex-col p-5 bg-white/10 backdrop-blur-xl border border-white/15 rounded-lg cursor-pointer transition-all hover:border-white/30 hover:bg-white/15 active:scale-[0.98] group shadow-[0_10px_30px_-18px_rgba(0,0,0,0.75)]">
<input class="hidden peer" name="interests[]" onchange="toggleCardSelection(this)" type="checkbox" value="cloud"/>
<div class="flex justify-between items-start mb-2">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">cloud</span>
<span class="material-symbols-outlined text-primary hidden peer-checked:block transition-all" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<h3 class="font-headline-md text-base font-bold text-white mb-1">Cloud Computing</h3>
<p class="font-label-sm text-white/70">Scalable infrastructure, orchestration, and hybrid cloud strategy.</p>
</label>
<!-- Data Science -->
<label class="relative flex flex-col p-5 bg-white/10 backdrop-blur-xl border border-white/15 rounded-lg cursor-pointer transition-all hover:border-white/30 hover:bg-white/15 active:scale-[0.98] group shadow-[0_10px_30px_-18px_rgba(0,0,0,0.75)]">
<input class="hidden peer" name="interests[]" onchange="toggleCardSelection(this)" type="checkbox" value="data"/>
<div class="flex justify-between items-start mb-2">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">monitoring</span>
<span class="material-symbols-outlined text-primary hidden peer-checked:block transition-all" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<h3 class="font-headline-md text-base font-bold text-white mb-1">Data Science</h3>
<p class="font-label-sm text-white/70">Statistical analysis, visualization, and big data engineering.</p>
</label>
<!-- AI -->
<label class="relative flex flex-col p-5 bg-white/10 backdrop-blur-xl border border-white/15 rounded-lg cursor-pointer transition-all hover:border-white/30 hover:bg-white/15 active:scale-[0.98] group shadow-[0_10px_30px_-18px_rgba(0,0,0,0.75)]">
<input class="hidden peer" name="interests[]" onchange="toggleCardSelection(this)" type="checkbox" value="ai"/>
<div class="flex justify-between items-start mb-2">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">smart_toy</span>
<span class="material-symbols-outlined text-primary hidden peer-checked:block transition-all" style="font-variation-settings: 'FILL' 1;">check_circle</span>
</div>
<h3 class="font-headline-md text-base font-bold text-white mb-1">Artificial Intelligence</h3>
<p class="font-label-sm text-white/70">Generative AI, ethics, robotics, and natural language processing.</p>
</label>
</form>
</div>
<!-- Dynamic Action Footer -->
<div class="bg-white/10 backdrop-blur-xl px-8 py-6 border-t border-white/15 flex justify-between items-center min-h-[88px]" id="footerContainer">
<button class="text-white/70 font-label-md hover:text-white underline transition-colors bg-transparent p-0 border-0" id="skipLink" type="submit" form="onboardingForm" name="action" value="skip">Skip for now →</button>
<button class="hidden bg-primary-container text-on-primary font-bold py-3 px-8 rounded-lg shadow-lg hover:brightness-110 active:scale-95 transition-all" id="completeBtn" type="submit" form="onboardingForm" name="action" value="complete">
                    Complete Registration
                </button>
</div>
</div>
</main>

<script>
        function toggleCardSelection(checkbox) {
            const label = checkbox.closest('label');
            if (checkbox.checked) {
                label.classList.add('active-card');
            } else {
                label.classList.remove('active-card');
            }
            updateFooterState();
        }

        function updateFooterState() {
            const selectedCount = document.querySelectorAll('input[name="interests[]"]:checked').length;
            const skipLink = document.getElementById('skipLink');
            const completeBtn = document.getElementById('completeBtn');
            const progress = document.querySelector('.step-progress');

            if (selectedCount >= 1) {
                skipLink.classList.add('hidden');
                completeBtn.classList.remove('hidden');
                completeBtn.classList.add('fade-in-up');
                progress.style.width = '100%';
            } else {
                skipLink.classList.remove('hidden');
                completeBtn.classList.add('hidden');
                progress.style.width = '66.6%';
            }
        }

        // Initialize progress bar
        window.addEventListener('DOMContentLoaded', () => {
            updateFooterState();
        });
    </script>
</body></html>