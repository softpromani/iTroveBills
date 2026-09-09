<template>
    <header class="flex items-center justify-between px-6 py-4 bg-white border-b-4 border-pink-900">
        <div class="flex items-center">
            <button @click="$page.props.showingMobileMenu = !$page.props.showingMobileMenu" class="text-gray-500 focus:outline-none lg:hidden">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        <div class="flex items-center">
            <dropdown>
                <template #trigger>
                    <button @click="dropdownOpen = ! dropdownOpen" class="relative block overflow-hidden">
                        {{ $page.props.auth.user.name }}
                    </button>
                </template>

                <template #content>
                    <dropdown-link :href="route('profile.edit')" class="w-full text-left">
                        Profile
                    </dropdown-link>

                    <button @click.prevent="showResetModal = true" class="block w-full px-4 py-2 text-sm leading-5 text-red-600 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out text-left font-semibold">
                        Reset DB Data
                    </button>

                    <dropdown-link class="w-full text-left" :href="route('logout')" method="post" as="button">
                        Log out
                    </dropdown-link>
                </template>
            </dropdown>
        </div>

        <ResetInvoiceDataModal
            :show="showResetModal"
            defaultType="gst"
            @close="showResetModal = false"
        />
    </header>
</template>

<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResetInvoiceDataModal from '@/Components/ResetInvoiceDataModal.vue';

const showResetModal = ref(false);
</script>
