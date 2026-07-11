<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Welcome') }} - {{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:ital,opsz,wght@0,8..60,400..900;1,8..60,400..900&amp;family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&amp;display=swap" rel="stylesheet"/>
        <!-- Material Symbols -->
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
         <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
         <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
          
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fonts

       
        
         <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-tertiary-fixed-variant": "#444749",
                    "on-primary": "#ffffff",
                    "tertiary-fixed": "#e0e3e5",
                    "tertiary-fixed-dim": "#c4c7c9",
                    "on-primary-container": "#86a0cd",
                    "surface-variant": "#dae2fd",
                    "on-secondary-container": "#54647a",
                    "on-surface": "#131b2e",
                    "error-container": "#ffdad6",
                    "inverse-surface": "#283044",
                    "primary-fixed-dim": "#adc7f7",
                    "inverse-primary": "#adc7f7",
                    "on-error-container": "#93000a",
                    "on-surface-variant": "#43474e",
                    "tertiary-container": "#333638",
                    "surface-container-highest": "#dae2fd",
                    "on-tertiary-fixed": "#191c1e",
                    "surface-container-lowest": "#ffffff",
                    "on-secondary": "#f5d4d4",
                    "tertiary": "#1d2123",
                    "on-error": "#ffffff",
                    "surface-tint": "#617faf",
                    "primary": "#6da9ee",
                    "surface-bright": "#faf8ff",
                    "outline": "#74777f",
                    "on-tertiary": "#ffffff",
                    "surface": "#faf8ff",
                    "on-secondary-fixed-variant": "#38485d",
                    "on-background": "#131b2e",
                    "surface-dim": "#d2d9f4",
                    "surface-container-low": "#f2f3ff",
                    "error": "#ba1a1a",
                    "primary-fixed": "#d6e3ff",
                    "secondary-container": "#d0e1fb",
                    "on-primary-fixed": "#001b3c",
                    "surface-container": "#eaedff",
                    "on-tertiary-container": "#9c9fa1",
                    "secondary-fixed-dim": "#b7c8e1",
                    "background": "#faf8ff",
                    "inverse-on-surface": "#eef0ff",
                    "outline-variant": "#c4c6cf",
                    "on-secondary-fixed": "#0b1c30",
                    "secondary": "#505f76",
                    "surface-container-high": "#e2e7ff",
                    "on-primary-fixed-variant": "#2d476f",
                    "secondary-fixed": "#d3e4fe",
                    "primary-container": "#1a365d"
            },
            "borderRadius": {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
            },
            "spacing": {
                    "margin-desktop": "48px",
                    "base": "8px",
                    "gutter": "24px",
                    "container-max-width": "1200px",
                    "margin-mobile": "16px"
            },
            "fontFamily": {
                    "headline-md": ["\"Source Serif 4\""],
                    "label-md": ["Hanken Grotesk"],
                    "display-lg-mobile": ["\"Source Serif 4\""],
                    "headline-sm": ["\"Source Serif 4\""],
                    "display-lg": ["\"Source Serif 4\""],
                    "body-md": ["Hanken Grotesk"],
                    "body-lg": ["Hanken Grotesk"],
                    "label-sm": ["Hanken Grotesk"]
            },
            "fontSize": {
                    "headline-md": ["32px", {"lineHeight": "40px", "fontWeight": "600"}],
                    "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                    "display-lg-mobile": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "700"}],
                    "headline-sm": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                    "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                    "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                    "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}]
            }
          },
        },
      }
    </script>

    </head>
    <body class="bg-gradient-to-br from-slate-950 via-slate-900 to-zinc-900 bg-white/10 backdrop-blur-md border border-white/20 color:white shadow-2xl rounded-2xl  to-pink-500 color-gray-500 blur-3 opacity-1 text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ route('dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-white border-[#19140035] hover:border-[#1915014a] border text-white dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] rounded-full text-[#f5d4d4] hover:bg-blue-400 border hover:border-[#19140035] dark:hover:border-[#3E3E3A] bg-[#617faf] text-sm leading-normal"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] rounded-full text-[#f5d4d4] hover:bg-blue-400 border hover:border-[#19140035] dark:hover:border-[#3E3E3A] bg-[#617faf] text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Hero Section -->
        <section class="relative min-h-[819px] flex items-center gap-3  bg-white/10 backdrop-blur-md border border-white/20  shadow-2xl rounded-2xl opacity-100 overflow-hidden bg-surface py-20">
<!-- Background Decoration -->

            <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
          <div class="absolute top-0 right-0 w-1/2 h-full bg-primary-container rounded-bl-full filter blur-3xl transform translate-x-1/2"></div>
         <div class="absolute bottom-0 left-0 w-1/3 h-1/2 bg-secondary-container rounded-tr-full filter blur-3xl transform -translate-x-1/4"></div>
         </div>
          <div class="relative z-10 px-margin-desktop max-w-container-max-width mx-auto w-full">
          <div class="grid md:grid-cols-2 gap-gutter items-center">
          <div class="space-y-base">
           <span class=" mb-6 font-display-lg text-white font-label-sm px-3 py-1 text-display-lg-mobile  md:text-display-lg rounded-full mb-4">
               <h1>SMART DISCUSSION FORUM</h1>
          </span>
          <span class="inline-block px-3 py-1 bg-white/10  backdrop-blur-md text-white font-label-sm text-label-sm rounded-full mb-4">
            ACADEMIC EXCELLENCE</span>
          <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-primary mb-6">Where Academic Minds Meet.</h1>
          <p class="font-body-lg text-body-lg text-white max-w-lg mb-8">
                            Join the premier discussion forum for scholars, researchers, and students to collaborate on the future of knowledge. A secure environment for rigorous peer review and interaction.
                        </p>
           <div class="flex flex-wrap gap-4">

          </div>
          </div>
          <div class="hidden md:block relative">
          <div class="rounded-xl overflow-hidden shadow-2xl border border-outline-variant">
         <img class="w-full h-[500px] object-cover" data-alt=" " src="{{ asset('OIP.webp') }}"/>
         </div>


<!-- Floating Badge -->
         <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-full shadow-xl border border-outline-variant max-w-xs animate-bounce-slow">
         <div class="flex items-center gap-4">

          <div class="rounded-full ">
          <p class="font-label-md text-label-md text-primary">Peer Reviewed</p>
          <p class="font-label-sm text-label-sm text-on-surface-variant">Validated by experts worldwide</p>
           </div>
          </div>
          </div>
          </div>
          </div>
          </div>
          </section>
            <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow "></div>

           @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
           @endif
    </body>
</html>
