<template>
    <Head title="About us" />

    <AuthenticatedLayout>
        <div class="mb-8 p-6 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-purple-100 rounded-xl">
                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Subscription Plans</h1>
                <p class="text-gray-500 font-medium">Choose the best plan that fits your business needs.</p>
            </div>
        </div>

        <div class="container-fluid">
            <div class="flex flex-wrap -mx-4 justify-center">
                <div v-for="(sub, index) in subscriptions" :key="index" class="w-full md:w-1/3 p-4">
                    <SubscriptinCard
                        :planName="sub.title"
                        :price="sub.mrp"
                        :buttonId="sub.id"
                        :features="sub.features"
                        :buttonText="'Subscribe Now'"
                        :subscription_id="user_subscription_id"
                    />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import SubscriptinCard from "@/Components/SubscriptinCard.vue";

const props = defineProps({
    subscriptions: Object,
    user_subscription_id:Number
})
</script>
