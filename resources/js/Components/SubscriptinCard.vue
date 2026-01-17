<template>
    <div
        class="relative w-full max-w-sm p-6 bg-white border border-gray-200 rounded-2xl shadow-xl transition-all duration-300 hover:shadow-2xl dark:bg-gray-800 dark:border-gray-700"
        :class="{'ring-2 ring-purple-500 border-purple-500 scale-105 z-10': subscription_id === buttonId}"
    >
        <div v-if="subscription_id === buttonId" class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest shadow-lg">
            Current Plan
        </div>

        <h5 class="mb-4 text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center justify-between">
            {{ planName }}
            <span v-if="subscription_id === buttonId" class="text-green-500">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            </span>
        </h5>

        <div class="flex items-baseline text-gray-900 dark:text-white mb-6">
            <span class="text-3xl font-semibold text-gray-500">₹</span>
            <span class="text-5xl font-extrabold tracking-tight">{{ price }}</span>
            <span class="ml-1 text-xl font-medium text-gray-400">/month</span>
        </div>

        <ul class="space-y-3 mb-8">
            <li v-for="(feature, index) in features" :key="index" class="flex items-center space-x-3 text-gray-600 dark:text-gray-300">
                <svg class="flex-shrink-0 w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                <span class="font-medium capitalize">{{ index.replace(/_/g, ' ') }}: {{ feature }}</span>
            </li>
        </ul>

        <button v-if="subscription_id === buttonId" type="button" disabled
            class="w-full py-3.5 px-5 text-sm font-bold text-white bg-green-500 rounded-xl cursor-not-allowed shadow-md transition-all duration-200 flex items-center justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            Currently Subscribed
        </button>

        <Link v-else href="buy/subscription" method="get" :data="{ subscriptin_id: buttonId }" type="button"
            class="w-full no-underline bg-gradient-to-r from-purple-500 via-pink-500 to-purple-600 hover:bg-gradient-to-br text-white font-bold rounded-xl text-sm px-5 py-3.5 inline-flex justify-center text-center transition-all duration-200 transform hover:-translate-y-1 shadow-lg hover:shadow-purple-500/50">
            {{ subscription_id ? 'Upgrade / Switch Plan' : 'Subscribe Now' }}
        </Link>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    planName: String,
    planMonth: String,
    price: Number,
    features: Array,
    buttonText: String,
    buttonId: Number,
    subscription_id: Number,
});
</script>
