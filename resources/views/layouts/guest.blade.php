<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <!-- Scripts -->

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
                    "primary": "#002045",
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
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans min-h-screen bg-cover bg-center bg-no-repeat items-center text-[#ffffff]"
          style="background-image: url('{{ asset('Gemini_Image_2.png') }}');">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0  ">
            <div>
                <a href="/">
                    
                </a>
            </div>

            <div class="w-full sm:max-w-md text-white mt-6 px-6 py-4 bg-white/10 backdrop-blur-md border border-white/20 overflow-hidden shadow-2xl rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
