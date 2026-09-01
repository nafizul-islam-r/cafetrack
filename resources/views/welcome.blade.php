<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CafeTrack - Cafeteria Inventory Management System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900" x-data="{
    mobileMenuOpen: false,
    activeSection: 'hero',

    // This function runs on scroll to check which section is active
    updateActiveSection() {
        let currentSection = 'hero'; // Default to hero
        const sections = ['hero', 'about', 'services', 'faq', 'contact'];
        const offset = 150; // How close to the top of the screen a section needs to be

        for (const sectionId of sections) {
            const section = document.getElementById(sectionId);
            if (section) {
                // Check if the section's top has passed the offset line
                if (section.getBoundingClientRect().top <= offset) {
                    currentSection = sectionId;
                }
            }
        }
        this.activeSection = currentSection;
    }
}" x-init="updateActiveSection()"
    @scroll.window.throttle.100ms="updateActiveSection()">

    <div class="min-h-screen text-gray-800 dark:text-gray-200">
        <header class="bg-white dark:bg-gray-800 shadow-md fixed w-full z-20 top-0">
            <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('welcome') }}">
                            <img class="w-32" src="{{ asset('app-logo.png') }}" alt="CafeTrack Logo">
                        </a>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-8">
                        <a href="#hero" class="text-sm font-medium"
                            :class="activeSection === 'hero' ? 'text-brand-red' :
                                'text-gray-500 dark:text-gray-400 hover:text-brand-red dark:hover:text-brand-red'">Home</a>

                        <a href="#services" class="text-sm font-medium"
                            :class="activeSection === 'services' ? 'text-brand-red' :
                                'text-gray-500 dark:text-gray-400 hover:text-brand-red dark:hover:text-brand-red'">Services</a>
                        <a href="#faq" class="text-sm font-medium"
                            :class="activeSection === 'faq' ? 'text-brand-red' :
                                'text-gray-500 dark:text-gray-400 hover:text-brand-red dark:hover:text-brand-red'">FAQ</a>
                        <a href="#contact" class="text-sm font-medium"
                            :class="activeSection === 'contact' ? 'text-brand-red' :
                                'text-gray-500 dark:text-gray-400 hover:text-brand-red dark:hover:text-brand-red'">Contact</a>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}"
                                    class="px-4 py-2 text-sm font-medium text-white bg-brand-red hover:bg-opacity-90 rounded-md shadow-sm">Dashboard</a>
                            @else
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 text-sm font-medium text-white bg-brand-red hover:bg-opacity-90 rounded-md shadow-sm">Get
                                    Started</a>
                            @endauth
                        @endif
                    </div>

                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ 'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }"
                                    class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ 'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }"
                                    class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </nav>

            <div :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }"
                class="hidden sm:hidden bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="#hero" @click="mobileMenuOpen = false"
                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                        :class="activeSection === 'hero' ? 'border-brand-red text-brand-red bg-red-50 dark:bg-gray-700' :
                            'border-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'">Home</a>

                    <a href="#services" @click="mobileMenuOpen = false"
                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                        :class="activeSection === 'services' ? 'border-brand-red text-brand-red bg-red-50 dark:bg-gray-700' :
                            'border-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'">Services</a>
                    <a href="#faq" @click="mobileMenuOpen = false"
                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                        :class="activeSection === 'faq' ? 'border-brand-red text-brand-red bg-red-50 dark:bg-gray-700' :
                            'border-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'">FAQ</a>
                    <a href="#contact" @click="mobileMenuOpen = false"
                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                        :class="activeSection === 'contact' ? 'border-brand-red text-brand-red bg-red-50 dark:bg-gray-700' :
                            'border-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'">Contact</a>
                </div>
                <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-600">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="block mx-4 px-4 py-2 text-center text-base font-medium text-white bg-brand-red hover:bg-opacity-90 rounded-md shadow-sm">Dashboard</a>
                        @else
                            <a href="{{ route('register') }}"
                                class="block mx-4 px-4 py-2 text-center text-base font-medium text-white bg-brand-red hover:bg-opacity-90 rounded-md shadow-sm">Get
                                Started</a>
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <main>
            <section id="hero" class="bg-white dark:bg-gray-800"
                style="padding-top: 5rem; padding-bottom: 5rem; padding: 300px;">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 text-center">
                    <h1 class="text-4xl md:text-6xl font-extrabold text-brand-brown dark:text-white">
                        Welcome to <span class="text-brand-red">CaféTrack</span>
                    </h1>
                    <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-500 dark:text-gray-300">
                        Your university cafeteria, simplified. Check food availability, see board game stock, and leave
                        reviews, all in one place.
                    </p>
                    <div class="mt-8 flex justify-center space-x-4">
                        <a href="{{ route('register') }}"
                            class="px-8 py-3 text-lg font-medium text-white bg-brand-red hover:bg-opacity-90 rounded-md shadow-lg transition-transform transform hover:scale-105">
                            Get Started
                        </a>
                        <a href="#services"
                            class="px-8 py-3 text-lg font-medium text-brand-brown dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-md shadow-lg transition-transform transform hover:scale-105">
                            Learn More
                        </a>
                    </div>
                </div>
            </section>



            <section id="services" class="py-60 bg-gray-50 dark:bg-gray-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-extrabold text-brand-brown dark:text-white">What We Offer</h2>
                        <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">Check out our services before you even
                            get to the cafeteria.</p>
                    </div>
                    <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-lg p-8 text-center">
                            <svg class="w-16 h-16 mx-auto text-brand-red" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <h3 class="mt-6 text-2xl font-bold text-brand-brown dark:text-white">Food Menu</h3>
                            <p class="mt-4 text-gray-500 dark:text-gray-300">Browse the full cafeteria menu, check
                                prices, and see what's in stock now.</p>
                            <a href="{{ route('food-items.public') }}"
                                class="mt-6 inline-block px-6 py-2 text-base font-medium text-white bg-brand-red hover:bg-opacity-90 rounded-md shadow-sm">View
                                Menu</a>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-lg p-8 text-center">
                            <svg class="w-16 h-16 mx-auto text-brand-red" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.121 15.879l-4.242 4.242a1 1 0 01-1.414 0l-4.242-4.242a1 1 0 010-1.414l4.242-4.242a1 1 0 011.414 0l4.242 4.242a1 1 0 010 1.414z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.121 15.879l-4.242 4.242a1 1 0 01-1.414 0l-4.242-4.242a1 1 0 010-1.414l4.242-4.242a1 1 0 011.414 0l4.242 4.242a1 1 0 010 1.414zM4.929 4.929l4.242-4.242a1 1 0 011.414 0l4.242 4.242a1 1 0 010 1.414l-4.242 4.242a1 1 0 01-1.414 0l-4.242-4.242a1 1 0 010-1.414z">
                                </path>
                            </svg>
                            <h3 class="mt-6 text-2xl font-bold text-brand-brown dark:text-white">Board Games</h3>
                            <p class="mt-4 text-gray-500 dark:text-gray-300">See which board games are available to
                                check out for your break.</p>
                            <a href="{{ route('board-games.public') }}"
                                class="mt-6 inline-block px-6 py-2 text-base font-medium text-white bg-brand-red hover:bg-opacity-90 rounded-md shadow-sm">View
                                Games</a>
                        </div>
                    </div>
                </div>
            </section>

            <section id="faq" class="py-40 bg-white dark:bg-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-extrabold text-brand-brown dark:text-white">Frequently Asked Questions
                        </h2>
                    </div>
                    <div class="mt-12 max-w-3xl mx-auto space-y-4">
                        <div x-data="{ open: false }" class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
                            <button @click="open = !open"
                                class="flex justify-between items-center w-full px-6 py-4 text-left">
                                <span class="text-lg font-medium text-brand-brown dark:text-gray-100">Is this app free
                                    to use?</span>
                                <svg :class="{ 'transform rotate-180': open }"
                                    class="w-6 h-6 text-brand-red transition-transform"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="px-6 pb-4 text-gray-500 dark:text-gray-400">
                                <p>Yes! CaféTrack is completely free for all students and faculty of this university.
                                </p>
                            </div>
                        </div>
                        <div x-data="{ open: false }" class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
                            <button @click="open = !open"
                                class="flex justify-between items-center w-full px-6 py-4 text-left">
                                <span class="text-lg font-medium text-brand-brown dark:text-gray-100">How do I check
                                    out a board game?</span>
                                <svg :class="{ 'transform rotate-180': open }"
                                    class="w-6 h-6 text-brand-red transition-transform"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="px-6 pb-4 text-gray-500 dark:text-gray-400">
                                <p>Simply go to the cafeteria counter and show the staff your student ID. They will use
                                    the CaféTrack admin panel to assign the game to you.</p>
                            </div>
                        </div>
                        <div x-data="{ open: false }" class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
                            <button @click="open = !open"
                                class="flex justify-between items-center w-full px-6 py-4 text-left">
                                <span class="text-lg font-medium text-brand-brown dark:text-gray-100">Can I see who
                                    wrote a review?</span>
                                <svg :class="{ 'transform rotate-180': open }"
                                    class="w-6 h-6 text-brand-red transition-transform"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="px-6 pb-4 text-gray-500 dark:text-gray-400">
                                <p>Yes, reviews are public and show the name of the student who wrote them to ensure
                                    accountability and build a trusted community.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="contact" class="py-40 bg-gray-50 dark:bg-gray-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center">
                        <h2 class="text-3xl font-extrabold text-brand-brown dark:text-white">Get in Touch</h2>
                        <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">Have a question or a technical issue?
                            Let us know.</p>
                    </div>
                    <div class="mt-12 max-w-lg mx-auto bg-gray-50 dark:bg-gray-700 shadow-lg rounded-lg p-8">
                        <form action="#" method="POST">
                            <div class="grid grid-cols-1 gap-y-6">
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-200">Full
                                        Name</label>
                                    <input type="text" name="name" id="name" autocomplete="name"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:ring-brand-red focus:border-brand-red">
                                </div>
                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                                    <input type="email" name="email" id="email" autocomplete="email"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:ring-brand-red focus:border-brand-red">
                                </div>
                                <div>
                                    <label for="message"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-200">Message</label>
                                    <textarea id="message" name="message" rows="4"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 rounded-md shadow-sm focus:ring-brand-red focus:border-brand-red"></textarea>
                                </div>
                                <div>
                                    <button type="submit"
                                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-brand-red hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-red">
                                        Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </main>

        <footer class="bg-brand-brown">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="flex-shrink-0">
                        <img class="h-24 w-auto" src="{{ asset('logo-footer.png') }}" alt="CafeTrack Logo">
                    </div>
                    <div class="mt-8 md:mt-0 md:ml-10 flex flex-wrap justify-center md:justify-start space-x-6">

                        <a href="#services" class="text-base font-medium text-gray-300 hover:text-white">Services</a>
                        <a href="#faq" class="text-base font-medium text-gray-300 hover:text-white">FAQ</a>
                        <a href="#contact" class="text-base font-medium text-gray-300 hover:text-white">Contact</a>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-600 pt-8">
                    <p class="text-base text-gray-400 text-center">&copy; {{ date('Y') }} CaféTrack. All rights
                        reserved.</p>
                </div>
            </div>
        </footer>

    </div>
</body>

</html>
