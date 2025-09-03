<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

// Mobile menu toggle
const mobileMenuOpen = ref(false);
</script>

<template>
    <Head title="ITrove Billing Software - Professional Invoice & E-Bill Solutions" />

    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="mx-auto max-w-screen-xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between">
                <div class="flex-1 md:flex md:items-center md:gap-12">
                    <a class="block text-teal-600" href="/">
                        <span class="sr-only">Home</span>
                        <img :src="'itimages/itlogo.png'" alt="ITrove Logo" class="mt-2 h-12 w-auto" />
                    </a>
                </div>

                <div class="md:flex md:items-center md:gap-12">
                    <!-- Desktop Navigation -->
                    <nav aria-label="Global" class="hidden md:block">
                        <ul class="flex items-center gap-8 text-sm font-medium">
                            <li><a href="#features" class="text-gray-700 hover:text-purple-600 transition-colors">Features</a></li>
                            <li><a href="#pricing" class="text-gray-700 hover:text-purple-600 transition-colors">Pricing</a></li>
                            <li><a href="#testimonials" class="text-gray-700 hover:text-purple-600 transition-colors">Reviews</a></li>
                            <li><a href="#contact" class="text-gray-700 hover:text-purple-600 transition-colors">Contact</a></li>
                        </ul>
                    </nav>

                    <div class="flex items-center gap-4">
                        <div class="sm:flex sm:gap-4">
                            <Link
                                v-if="$page.props.auth.user?.email"
                                :href="route('dashboard')"
                                class="rounded-lg bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:ring-4 focus:ring-purple-300 font-medium px-6 py-3 text-sm text-white transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                            >
                                Dashboard
                            </Link>
                            <template v-else>
                                <div class="flex gap-2">
                                <Link
                                    class="rounded-lg bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:ring-4 focus:ring-purple-300 font-medium px-6 py-3 text-sm text-white transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                                    :href="route('login')"
                                >
                                    Login
                                </Link>
                                    <Link
                                        class="rounded-lg border-2 border-purple-600 text-purple-600 hover:bg-purple-600 hover:text-white font-medium px-6 py-3 text-sm transition-all duration-200"
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
                                class="rounded bg-gray-100 p-2 text-gray-600 transition hover:text-gray-600/75"
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
            <div v-show="mobileMenuOpen" class="md:hidden border-t border-gray-200 bg-white">
                <nav class="px-4 py-4">
                    <ul class="space-y-4">
                        <li><a href="#features" class="block text-gray-700 hover:text-purple-600">Features</a></li>
                        <li><a href="#pricing" class="block text-gray-700 hover:text-purple-600">Pricing</a></li>
                        <li><a href="#testimonials" class="block text-gray-700 hover:text-purple-600">Reviews</a></li>
                        <li><a href="#contact" class="block text-gray-700 hover:text-purple-600">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-purple-900 via-purple-800 to-pink-700 min-h-screen flex items-center">
        <!-- Background decorative elements -->
        <div class="absolute -bottom-32 -left-40 w-80 h-80 border-4 rounded-full border-white border-opacity-20"></div>
        <div class="absolute -bottom-40 -left-20 w-80 h-80 border-4 rounded-full border-white border-opacity-10"></div>
        <div class="absolute -top-40 -right-0 w-80 h-80 border-4 rounded-full border-white border-opacity-20"></div>
        <div class="absolute -top-20 -right-20 w-80 h-80 border-4 rounded-full border-white border-opacity-10"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <h1 class="text-5xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                        ITrove<span class="text-pink-300">Bills</span>
                    </h1>
                    <p class="text-xl lg:text-2xl text-purple-100 mb-8 leading-relaxed">
                        Empowering businesses with innovative billing solutions featuring
                        <span class="font-semibold text-pink-200">E-Way Bills</span> and
                        <span class="font-semibold text-pink-200">E-Invoice</span> generation
                    </p>
                    <p class="text-lg text-purple-200 mb-8">
                        Seamless GST compliance, automated invoicing, and comprehensive financial management
                        ensuring precision and client satisfaction.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <button class="bg-white text-purple-800 font-bold py-4 px-8 rounded-xl shadow-2xl hover:bg-gray-100 transition-all duration-300 transform hover:-translate-y-1">
                            Start Free Trial
                        </button>
                        <button class="border-2 border-white text-white font-semibold py-4 px-8 rounded-xl hover:bg-white hover:text-purple-800 transition-all duration-300">
                            Watch Demo
                        </button>
                    </div>
                </div>
                <div class="hidden lg:block">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                <span class="text-white">E-Way Bill Generation</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-blue-400 rounded-full"></div>
                                <span class="text-white">E-Invoice Compliance</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                <span class="text-white">GST Ready Reports</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-pink-400 rounded-full"></div>
                                <span class="text-white">Multi-Currency Support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                    Powerful Billing Features
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Everything you need to manage your business finances with complete GST compliance and automation
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- E-Way Bill Feature -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-16 h-16 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">E-Way Bill Generation</h3>
                    <p class="text-gray-600 mb-6">Automated E-Way Bill generation with real-time GST portal integration. Bulk processing and validation included.</p>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li>✓ Real-time validation</li>
                        <li>✓ Bulk generation</li>
                        <li>✓ Auto-renewal alerts</li>
                    </ul>
                </div>

                <!-- E-Invoice Feature -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 w-16 h-16 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">E-Invoice Solutions</h3>
                    <p class="text-gray-600 mb-6">Government-compliant e-invoicing with digital signatures and automated tax calculations for seamless compliance.</p>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li>✓ Digital signatures</li>
                        <li>✓ Auto tax calculation</li>
                        <li>✓ Government compliance</li>
                    </ul>
                </div>

                <!-- GST Compliance -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 w-16 h-16 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">GST Compliance</h3>
                    <p class="text-gray-600 mb-6">Complete GST management with automated returns, GSTR reports, and real-time compliance monitoring.</p>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li>✓ GSTR-1, GSTR-3B reports</li>
                        <li>✓ Auto return filing</li>
                        <li>✓ Compliance alerts</li>
                    </ul>
                </div>

                <!-- Multi-Currency -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 w-16 h-16 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Multi-Currency Support</h3>
                    <p class="text-gray-600 mb-6">Handle international transactions with real-time currency conversion and multi-language invoice support.</p>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li>✓ 150+ currencies</li>
                        <li>✓ Live exchange rates</li>
                        <li>✓ Multi-language invoices</li>
                    </ul>
                </div>

                <!-- Analytics -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="bg-gradient-to-br from-pink-500 to-pink-600 w-16 h-16 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Advanced Analytics</h3>
                    <p class="text-gray-600 mb-6">Comprehensive business insights with detailed reports, cash flow analysis, and predictive analytics.</p>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li>✓ Real-time dashboards</li>
                        <li>✓ Cash flow forecasting</li>
                        <li>✓ Custom reports</li>
                    </ul>
                </div>

                <!-- Customer Portal -->
                <div class="bg-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                    <div class="bg-gradient-to-br from-teal-500 to-teal-600 w-16 h-16 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Customer Portal</h3>
                    <p class="text-gray-600 mb-6">Self-service customer portal for invoice viewing, payments, and communication with automated notifications.</p>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li>✓ Online payments</li>
                        <li>✓ Invoice history</li>
                        <li>✓ Auto notifications</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                    How It Works
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Get started with ITrove Bills in just a few simple steps
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-gradient-to-br from-blue-500 to-purple-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Setup Your Business</h3>
                    <p class="text-gray-600">Add your business details, GST information, and configure your billing preferences in minutes.</p>
                </div>
                <div class="text-center">
                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Create & Send Invoices</h3>
                    <p class="text-gray-600">Generate professional invoices with E-Way Bills and E-Invoices. Send them instantly to your customers.</p>
                </div>
                <div class="text-center">
                    <div class="bg-gradient-to-br from-pink-500 to-orange-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Track & Analyze</h3>
                    <p class="text-gray-600">Monitor payments, track business performance, and generate GST reports with comprehensive analytics.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                    What Our Customers Say
                </h2>
                <p class="text-xl text-gray-600">
                    Trusted by thousands of businesses across India
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">"ITrove Bills transformed our billing process. E-Way Bill generation is seamless and GST compliance is automatic. Highly recommended!"</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold mr-4">
                            RS
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Rajesh Sharma</h4>
                            <p class="text-sm text-gray-500">CEO, Tech Solutions Pvt Ltd</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">"The multi-currency feature and customer portal have made our international business so much easier. Customer satisfaction has increased significantly."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-teal-400 rounded-full flex items-center justify-center text-white font-bold mr-4">
                            PK
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Priya Kumari</h4>
                            <p class="text-sm text-gray-500">Founder, Export House</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-xl">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6">"Best investment for our business! The analytics and reporting features help us make data-driven decisions. Customer support is excellent too."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-400 to-blue-400 rounded-full flex items-center justify-center text-white font-bold mr-4">
                            AS
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Amit Singh</h4>
                            <p class="text-sm text-gray-500">Director, Manufacturing Co.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <!-- <section id="pricing" class="py-20 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                    Simple, Transparent Pricing
                </h2>
                <p class="text-xl text-gray-600">
                    Choose the plan that fits your business needs
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">

                <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 hover:border-purple-400 transition-all duration-300">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Starter</h3>
                        <div class="text-5xl font-bold text-purple-600 mb-2">₹999</div>
                        <p class="text-gray-500">per month</p>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            50 Invoices/month
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            E-Way Bill Generation
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Basic GST Reports
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Email Support
                        </li>
                    </ul>
                    <button class="w-full bg-purple-600 text-white font-semibold py-3 px-6 rounded-xl hover:bg-purple-700 transition-colors">
                        Get Started
                    </button>
                </div>

                <div class="bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl p-8 text-white relative transform scale-105 shadow-2xl">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="bg-yellow-400 text-purple-800 px-4 py-1 rounded-full text-sm font-semibold">Most Popular</span>
                    </div>
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold mb-4">Professional</h3>
                        <div class="text-5xl font-bold mb-2">₹2499</div>
                        <p class="text-purple-100">per month</p>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-300 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Unlimited Invoices
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-300 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            E-Invoice Generation
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-300 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Advanced Analytics
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-300 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Customer Portal
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-300 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Priority Support
                        </li>
                    </ul>
                    <button class="w-full bg-white text-purple-600 font-semibold py-3 px-6 rounded-xl hover:bg-gray-100 transition-colors">
                        Start Free Trial
                    </button>
                </div>

                <div class="bg-white rounded-2xl border-2 border-gray-200 p-8 hover:border-purple-400 transition-all duration-300">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Enterprise</h3>
                        <div class="text-5xl font-bold text-purple-600 mb-2">₹4999</div>
                        <p class="text-gray-500">per month</p>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Everything in Professional
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Multi-Currency Support
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            API Integration
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Custom Branding
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            24/7 Dedicated Support
                        </li>
                    </ul>
                    <button class="w-full bg-purple-600 text-white font-semibold py-3 px-6 rounded-xl hover:bg-purple-700 transition-colors">
                        Contact Sales
                    </button>
                </div>
            </div>
        </div>
    </section>-->

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-purple-900 to-pink-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6">
                Ready to Transform Your Billing?
            </h2>
            <p class="text-xl text-purple-100 mb-8 max-w-3xl mx-auto">
                Join thousands of businesses already using ITrove Bills for seamless invoicing and GST compliance
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button class="bg-white text-purple-800 font-bold py-4 px-8 rounded-xl shadow-lg hover:bg-gray-100 transition-all duration-300 transform hover:-translate-y-1">
                    Start 14-Day Free Trial
                </button>
                <button class="border-2 border-white text-white font-semibold py-4 px-8 rounded-xl hover:bg-white hover:text-purple-800 transition-all duration-300">
                    Schedule Demo
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="bg-gray-900 text-white py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="md:col-span-2">
                    <div class="flex items-center mb-6">
                        <img :src="'itimages/itlogo.png'" alt="ITrove Logo" class="h-12 w-auto mr-4" />
                        <span class="text-2xl font-bold">ITrove Bills</span>
                    </div>
                    <p class="text-gray-300 mb-6 max-w-md">
                        Empowering businesses with innovative billing solutions. Complete GST compliance,
                        E-Way Bills, and E-Invoice generation made simple.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0" viewBox="0 0 24 24">
                                <path stroke="none" d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path>
                                <circle cx="4" cy="4" r="2" stroke="none"></circle>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-300 hover:text-white transition-colors">Features</a></li>
                        <li><a href="#pricing" class="text-gray-300 hover:text-white transition-colors">Pricing</a></li>
                        <li><a href="#testimonials" class="text-gray-300 hover:text-white transition-colors">Testimonials</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Help Center</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-300">info@innovationtrove.in</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span class="text-gray-300">+91 9648061515 (India), +975 17126364 (Bhutan)</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-3 mt-1 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-gray-300">Daragaon, Jaigaon, West Bengal, India</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    © 2024 Innovation Trove LLP. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped>
/* Custom animations and hover effects */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
}

/* Custom gradient text */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>
