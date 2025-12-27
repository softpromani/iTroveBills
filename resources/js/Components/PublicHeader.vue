<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
        default: true
    },
    canRegister: {
        type: Boolean,
        default: true
    },
});

const mobileMenuOpen = ref(false);
</script>

<template>
    <header class="bg-white/90 backdrop-blur-md shadow-lg sticky top-0 z-50 transition-all duration-300">
        <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between">
                <div class="flex-1 md:flex md:items-center md:gap-12">
                    <a class="block text-teal-600 hover:opacity-80 transition-opacity" href="/">
                        <span class="sr-only">Home</span>
                        <!-- Use absolute path for images to work from other pages -->
                        <img src="/itimages/itlogo.png" alt="ITrove Logo" class="mt-2 h-12 w-auto" />
                    </a>
                </div>

                <div class="md:flex md:items-center md:gap-12">
                    <!-- Desktop Navigation -->
                    <nav aria-label="Global" class="hidden md:block">
                        <ul class="flex items-center gap-8 text-sm font-semibold tracking-wide">
                            <!-- Update links to be absolute from root so they work from policy pages -->
                            <li>
                                <a href="/" class="text-gray-700 hover:text-purple-600 transition-colors duration-200">
                                    Home
                                </a>
                            </li>
                            <li>
                                <a href="/#features" class="text-gray-700 hover:text-purple-600 transition-colors duration-200">
                                    Features
                                </a>
                            </li>
                            <li>
                                <a href="/#pricing" class="text-gray-700 hover:text-purple-600 transition-colors duration-200">
                                    Pricing
                                </a>
                            </li>
                            <li>
                                <a href="/#testimonials" class="text-gray-700 hover:text-purple-600 transition-colors duration-200">
                                    Reviews
                                </a>
                            </li>
                            <li>
                                <a href="/#contact" class="text-gray-700 hover:text-purple-600 transition-colors duration-200">
                                    Contact
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="flex items-center gap-4">
                        <div class="sm:flex sm:gap-4">
                            <Link
                                v-if="$page.props.auth.user?.email"
                                :href="route('dashboard')"
                                class="rounded-lg bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:ring-4 focus:ring-purple-300 font-bold px-6 py-3 text-sm text-white transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                            >
                                Dashboard
                            </Link>
                            <template v-else>
                                <div class="flex gap-2">
                                <Link
                                    v-if="canLogin"
                                    class="rounded-lg bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:ring-4 focus:ring-purple-300 font-bold px-6 py-3 text-sm text-white transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                    :href="route('login')"
                                >
                                    Login
                                </Link>
                                    <Link
                                        v-if="canRegister"
                                        class="rounded-lg border-2 border-purple-600 text-purple-600 hover:bg-purple-600 hover:text-white font-bold px-6 py-3 text-sm transition-all duration-200"
                                        :href="route('register')"
                                    >
                                        Register
                                    </Link>
                                </div>
                            </template>
                        </div>

                        <!-- Mobile menu button -->
                        <div class="block md:hidden">
                            <button
                                @click="mobileMenuOpen = !mobileMenuOpen"
                                class="rounded bg-gray-100 p-2 text-gray-600 transition hover:text-purple-600 hover:bg-gray-200"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div v-show="mobileMenuOpen" class="md:hidden border-t border-gray-200 bg-white/95 backdrop-blur-sm">
                <nav class="px-4 py-4">
                    <ul class="space-y-4">
                        <li><a href="/" class="block text-gray-700 font-semibold hover:text-purple-600 hover:bg-purple-50 px-3 py-2 rounded-lg transition-colors">Home</a></li>
                        <li><a href="/#features" class="block text-gray-700 font-semibold hover:text-purple-600 hover:bg-purple-50 px-3 py-2 rounded-lg transition-colors">Features</a></li>
                        <li><a href="/#pricing" class="block text-gray-700 font-semibold hover:text-purple-600 hover:bg-purple-50 px-3 py-2 rounded-lg transition-colors">Pricing</a></li>
                        <li><a href="/#testimonials" class="block text-gray-700 font-semibold hover:text-purple-600 hover:bg-purple-50 px-3 py-2 rounded-lg transition-colors">Reviews</a></li>
                        <li><a href="/#contact" class="block text-gray-700 font-semibold hover:text-purple-600 hover:bg-purple-50 px-3 py-2 rounded-lg transition-colors">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
</template>
