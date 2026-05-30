<template>
    <!-- component -->
    <div class="min-h-screen md:flex overflow-hidden">
        <FlashMessages/>
        <div
            class="relative overflow-hidden md:flex w-1/2 bg-gradient-to-tr from-purple-800 to-pink-700 justify-around items-center hidden px-12"
        >
            <div class="z-10 w-full max-w-lg">
                <h1 class="text-white font-bold text-5xl font-sans mb-6 tracking-tight flex items-start">
                    ITroveBills<sup class="inline-flex items-center justify-center border border-pink-300 rounded-full w-5 h-5 text-[10px] font-extrabold ml-2 text-pink-300 align-super mt-1.5 leading-none">TM</sup>
                </h1>
                <div class="space-y-4">
                    <p class="text-white text-lg leading-relaxed opacity-90">
                        Empowering businesses with innovative billing solutions.
                    </p>
                    <p class="text-white text-lg leading-relaxed opacity-90">
                        Innovation Trove delivers seamless and efficient invoicing experiences,
                    </p>
                    <p class="text-white text-lg leading-relaxed font-semibold">
                        ensuring financial precision and client satisfaction.
                    </p>
                </div>
                <button
                    type="button"
                    class="block w-32 bg-white shadow-xl text-indigo-800 mt-8 py-3 rounded-2xl font-bold hover:scale-105 transition-transform duration-200"
                >
                    Read More
                </button>
            </div>
            <!-- Decorative Circles -->
            <div class="absolute -bottom-32 -left-40 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -bottom-40 -left-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -top-40 -right-0 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
            <div class="absolute -top-20 -right-20 w-80 h-80 border-4 rounded-full border-opacity-30 border-t-8"></div>
        </div>
        <div class="flex md:w-1/2 justify-center py-12 items-center bg-white px-6">
            <div class="w-full max-w-md">
                <slot />
            </div>
        </div>
    </div>
</template>
<script setup>
import FlashMessages from "@/Components/FlashMessages.vue";
</script>
