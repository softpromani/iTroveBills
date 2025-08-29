<template>
    <Head title="Dashboard - ITroveBilling" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-sm text-gray-600">Welcome back! Here's what's happening with your business.</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ currentDate }}</p>
                        <p class="text-xs text-gray-500">{{ currentTime }}</p>
                    </div>
                </div>
            </div>
        </template>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Revenue</p>
                        <p class="text-3xl font-bold">₹{{ formatCurrency(stats.totalRevenue) }}</p>
                        <p class="text-blue-100 text-xs mt-1">From all invoices</p>
                    </div>
                    <div class="bg-blue-400 rounded-full p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8s.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582s-.07.34-.433.582c-.155.103-.346.196-.567.267z"></path>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8s.602 1.766 1.324 2.246.8.396 1.676.662V13a2 2 0 001.706.932 1 1 0 100-2A2 2 0 0110 10V9.908c.877-.266 1.524-.8 1.676-.662C12.398 8.766 13 7.991 13 7s-.602-1.766-1.324-2.246A4.535 4.535 0 0011 4.092V4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Active Invoices</p>
                        <p class="text-3xl font-bold">{{ stats.activeInvoices }}</p>
                        <p class="text-green-100 text-xs mt-1">Paid invoices</p>
                    </div>
                    <div class="bg-green-400 rounded-full p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Customers</p>
                        <p class="text-3xl font-bold">{{ stats.totalCustomers }}</p>
                        <p class="text-purple-100 text-xs mt-1">Unique customers</p>
                    </div>
                    <div class="bg-purple-400 rounded-full p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Pending Payments</p>
                        <p class="text-3xl font-bold">₹{{ formatCurrency(stats.pendingPayments) }}</p>
                        <p class="text-orange-100 text-xs mt-1">Unpaid invoices</p>
                    </div>
                    <div class="bg-orange-400 rounded-full p-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Quick Actions / Modules -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Quick Actions</h2>
                        <span class="text-sm text-gray-500">Access your modules</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Dashboard -->
                        <Link :href="route('dashboard')"
                              class="group flex flex-col items-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 hover:shadow-md">
                            <div class="bg-blue-500 rounded-full p-3 mb-3 group-hover:bg-blue-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">Dashboard</h3>
                            <p class="text-xs text-gray-600 text-center">Overview & Analytics</p>
                        </Link>

                        <!-- Subscription -->
                        <Link :href="route('subscription.index')"
                              class="group flex flex-col items-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200 hover:from-green-100 hover:to-green-200 transition-all duration-200 hover:shadow-md">
                            <div class="bg-green-500 rounded-full p-3 mb-3 group-hover:bg-green-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">Subscription</h3>
                            <p class="text-xs text-gray-600 text-center">Plan & Billing</p>
                        </Link>

                        <!-- My Bills -->
                        <Link :href="route('customer.bill')"
                              class="group flex flex-col items-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border border-purple-200 hover:from-purple-100 hover:to-purple-200 transition-all duration-200 hover:shadow-md">
                            <div class="bg-purple-500 rounded-full p-3 mb-3 group-hover:bg-purple-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">My Bills</h3>
                            <p class="text-xs text-gray-600 text-center">View All Bills</p>
                        </Link>

                        <!-- Firm -->
                        <Link :href="route('company.index')"
                              class="group flex flex-col items-center p-6 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg border border-indigo-200 hover:from-indigo-100 hover:to-indigo-200 transition-all duration-200 hover:shadow-md">
                            <div class="bg-indigo-500 rounded-full p-3 mb-3 group-hover:bg-indigo-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-6a1 1 0 00-1-1H7a1 1 0 00-1 1v6a1 1 0 01-1 1H2a1 1 0 110-2V4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">Firm</h3>
                            <p class="text-xs text-gray-600 text-center">Company Details</p>
                        </Link>

                        <!-- Customers -->
                        <Link :href="route('company.customer')"
                              class="group flex flex-col items-center p-6 bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg border border-pink-200 hover:from-pink-100 hover:to-pink-200 transition-all duration-200 hover:shadow-md">
                            <div class="bg-pink-500 rounded-full p-3 mb-3 group-hover:bg-pink-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">Customers</h3>
                            <p class="text-xs text-gray-600 text-center">Manage Clients</p>
                        </Link>

                        <!-- Invoices -->
                        <Link :href="route('invoice.list')"
                              class="group flex flex-col items-center p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200 hover:from-yellow-100 hover:to-yellow-200 transition-all duration-200 hover:shadow-md">
                            <div class="bg-yellow-500 rounded-full p-3 mb-3 group-hover:bg-yellow-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">Invoices</h3>
                            <p class="text-xs text-gray-600 text-center">Create & Manage</p>
                        </Link>

                        <!-- Proforma Invoices -->
                        <Link :href="route('performa.invoice.list')"
                              class="group flex flex-col items-center p-6 bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg border border-teal-200 hover:from-teal-100 hover:to-teal-200 transition-all duration-200 hover:shadow-md">
                            <div class="bg-teal-500 rounded-full p-3 mb-3 group-hover:bg-teal-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4zm9 1a1 1 0 010-2h4a1 1 0 011 1v4a1 1 0 01-2 0V6.414l-2.293 2.293a1 1 0 11-1.414-1.414L13.586 5H12zm-9 7a1 1 0 012 0v1.586l2.293-2.293a1 1 0 111.414 1.414L6.414 15H8a1 1 0 010 2H4a1 1 0 01-1-1v-4zm13-1a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 010-2h1.586l-2.293-2.293a1 1 0 111.414-1.414L15 13.586V12a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">Proforma</h3>
                            <p class="text-xs text-gray-600 text-center">Draft Invoices</p>
                        </Link>

                        <!-- Payment Requests -->
                        <Link :href="route('invoice.list')"
                              class="group flex flex-col items-center p-6 bg-gradient-to-br from-red-50 to-red-100 rounded-lg border border-red-200 hover:from-red-100 hover:to-red-200 transition-all duration-200 hover:shadow-md">
                            <div class="bg-red-500 rounded-full p-3 mb-3 group-hover:bg-red-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1">Payments</h3>
                            <p class="text-xs text-gray-600 text-center">Payment Requests</p>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Notifications -->
            <div class="space-y-6">
                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                        <Link :href="route('invoice.list')" class="text-sm text-blue-600 hover:text-blue-700">View All</Link>
                    </div>
                    <div class="space-y-4">
                        <div v-if="recentActivity.length === 0" class="text-center py-8">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500">No recent activity</p>
                        </div>
                        <div v-else v-for="activity in recentActivity" :key="activity.id" class="flex items-start space-x-3">
                            <div class="bg-blue-100 rounded-full p-1">
                                <div class="bg-blue-500 rounded-full w-2 h-2"></div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900">
                                    Invoice #{{ activity.invoice_number || `INV-${activity.id}` }}
                                    <span v-if="activity.payment_status === 1" class="text-green-600">- Paid</span>
                                    <span v-else class="text-orange-600">- Pending</span>
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ activity.company?.name || 'Amount' }} -
                                    ₹{{ formatCurrency(activity.total_ammount) }}
                                </p>
                                <p class="text-xs text-gray-500">{{ formatDate(activity.invoice_date) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Summary</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Revenue</span>
                            <span class="text-sm font-semibold text-green-600">₹{{ formatCurrency(stats.totalRevenue) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Active Invoices</span>
                            <span class="text-sm font-semibold text-green-600">{{ stats.activeInvoices }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Pending Amount</span>
                            <span class="text-sm font-semibold text-orange-600">₹{{ formatCurrency(stats.pendingPayments) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Customers</span>
                            <span class="text-sm font-semibold text-blue-600">{{ stats.totalCustomers }}</span>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Subscription Status -->
                <div v-if="subscription" class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Subscription Status</h3>
                        <div :class="getSubscriptionStatusClass(subscription.status)">
                            <span class="text-xs font-medium">{{ getSubscriptionStatusText(subscription.status) }}</span>
                        </div>
                    </div>

                    <!-- Plan Name -->
                    <p class="text-sm opacity-90 mb-2">
                        {{ subscription.seller_subscription?.title || 'Current Plan' }}
                    </p>

                    <!-- Expiry -->
                    <p class="text-xs opacity-75 mb-2" v-if="subscription.end_date">
                        {{ getSubscriptionExpiryText(subscription) }}
                    </p>

                    <!-- Amount -->
                    <p class="text-xs opacity-75 mb-3" v-if="subscription.order_ammount">
                        ₹{{ formatCurrency(subscription.order_ammount) }} / {{ subscription.sellerSubscription?.month || 1 }} month(s)
                    </p>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <Link :href="route('subscription.index')" class="inline-block text-xs bg-white bg-opacity-20 hover:bg-opacity-30 rounded px-3 py-1 transition-all">
                            Manage Plan
                        </Link>
                        <Link v-if="isSubscriptionExpiring(subscription)" :href="route('subscription.index')" class="inline-block text-xs bg-yellow-500 bg-opacity-80 hover:bg-opacity-100 rounded px-3 py-1 transition-all">
                            Renew Now
                        </Link>
                    </div>
                </div>

                <!-- No Subscription State -->
                <div v-else class="bg-gradient-to-r from-gray-500 to-gray-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">Subscription Status</h3>
                        <div class="bg-red-400 rounded-full px-2 py-1">
                            <span class="text-xs font-medium">No Active Plan</span>
                        </div>
                    </div>
                    <p class="text-sm opacity-90 mb-2">You don't have an active subscription</p>
                    <p class="text-xs opacity-75 mb-3">Subscribe to access all features</p>
                    <Link :href="route('subscription.index')" class="inline-block text-xs hover:bg-blue-600 rounded px-3 py-1 transition-all">
                        Choose a Plan
                    </Link>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';

// Props from the controller
const props = defineProps({
    stats: {
        type: Object,
        required: true,
        default: () => ({
            totalRevenue: 0,
            activeInvoices: 0,
            totalCustomers: 0,
            pendingPayments: 0
        })
    },
    recentActivity: {
        type: Array,
        default: () => []
    },
    subscription: {
        type: Object,
        default: null
    }
});

// Current date and time
const currentDate = ref('');
const currentTime = ref('');

const updateDateTime = () => {
    const now = new Date();
    currentDate.value = now.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    currentTime.value = now.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Helper functions
const formatCurrency = (amount) => {
    if (!amount) return '0';
    return new Intl.NumberFormat('en-IN').format(amount);
};

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Subscription helper functions
// Map DB status codes to text & classes
const getSubscriptionStatusClass = (status) => {
    const statusClasses = {
        10: 'bg-green-400 rounded-full px-2 py-1', // Active
        0:  'bg-red-400 rounded-full px-2 py-1',   // Expired
        20: 'bg-gray-400 rounded-full px-2 py-1',  // Cancelled
        30: 'bg-yellow-400 rounded-full px-2 py-1',// Pending
        40: 'bg-orange-400 rounded-full px-2 py-1' // Suspended
    };
    return statusClasses[status] || 'bg-gray-400 rounded-full px-2 py-1';
};

const getSubscriptionStatusText = (status) => {
    const statusTexts = {
        10: 'Active',
        0: 'Expired',
        20: 'Cancelled',
        30: 'Pending',
        40: 'Suspended'
    };
    return statusTexts[status] || 'Unknown';
};

const getSubscriptionExpiryText = (subscription) => {
    const expiryDate = subscription.end_date;
    if (!expiryDate) return '';

    const expiry = new Date(expiryDate);
    const now = new Date();
    const diffTime = expiry - now;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays < 0) {
        return `Expired ${Math.abs(diffDays)} days ago`;
    } else if (diffDays === 0) {
        return 'Expires today';
    } else if (diffDays === 1) {
        return 'Expires tomorrow';
    } else if (diffDays <= 7) {
        return `Expires in ${diffDays} days`;
    } else {
        return `Expires: ${formatDate(expiryDate)}`;
    }
};

const isSubscriptionExpiring = (subscription) => {
    if (!subscription || subscription.status !== 10) return false; // Only active

    const expiryDate = subscription.end_date;
    if (!expiryDate) return false;

    const expiry = new Date(expiryDate);
    const now = new Date();
    const diffTime = expiry - now;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    return diffDays <= 7 && diffDays >= 0;
};


let timeInterval;

onMounted(() => {
    updateDateTime();
    timeInterval = setInterval(updateDateTime, 60000); // Update every minute
});

onUnmounted(() => {
    if (timeInterval) {
        clearInterval(timeInterval);
    }
});
</script>

<style scoped>
/* Custom animations */
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

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
}

/* Custom hover effects */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Smooth transitions */
* {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}
</style>
