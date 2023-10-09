<template>
    <button :type="type" class="rounded-md bg-gradient-to-r from-purple-400 via-pink-500 to-purple-600 hover:bg-gradient-to-br focus:ring-4 focus:ring-pink-300 dark:focus:ring-pink-800 font-medium px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
        <slot />
    </button>
</template>

<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>
