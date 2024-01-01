<template>
    <div
        class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <h5 class="mb-4 text-xl font-medium text-gray-500 dark:text-gray-400">
            {{ planName }}
        </h5>
        <div class="flex items-baseline text-gray-900 dark:text-white">
            <span class="text-3xl font-semibold">₹</span>
            <span class="text-5xl font-extrabold tracking-tight">{{
                price
            }}</span>
            <span class="ml-1 text-xl font-normal text-gray-500 dark:text-gray-400">/month</span>
        </div>
        <div v-for="(feature, index) in features" :key="index" class="mt-2 mb-2 d-flex">
            <img src="itimages\tic.png" style="height: 20px" alt="Example Image" />
            &nbsp;&nbsp;&nbsp;
            <label class="form-check-label">{{ feature }}</label>
        </div>
        <Button v-if="subscription_id" type="button"
            class="no-underline bg-gradient-to-r from-purple-400 via-pink-500 to-purple-600 hover:bg-gradient-to-br  text-white hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-200 dark:focus:ring-blue-900 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center w-full text-center">
            {{ buttonText }}
        </Button>
        <Link v-else href="buy/subscription" method="get" :data="{ subscriptin_id: buttonId }" type="button"
            class="no-underline bg-gradient-to-r from-purple-400 via-pink-500 to-purple-600 hover:bg-gradient-to-br  text-white hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-200 dark:focus:ring-blue-900 font-medium rounded-lg text-sm px-5 py-2.5 inline-flex justify-center w-full text-center">
        {{ buttonText }}
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
