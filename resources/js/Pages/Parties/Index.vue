<template>
    <Head title="Parties" />

    <AuthenticatedLayout>
        <template #header> Managed Parties </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-800">Parties List</h2>
                            <Link
                                :href="route('parties.create')"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
                            >
                                Add New Party
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="party in parties.data" :key="party.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ party.name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ party.address || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <Link :href="route('parties.edit', party.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</Link>
                                            <button @click="deleteParty(party.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                    <tr v-if="parties.data.length === 0">
                                        <td colspan="3" class="px-6 py-10 text-center text-gray-500 text-sm">
                                            No parties found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            <Pagination :links="parties.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="confirmingDeletion" @close="confirmingDeletion = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Are you sure you want to delete this party?</h2>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="confirmingDeletion = false">Cancel</SecondaryButton>
                    <DangerButton class="ml-3" @click="proceedWithDeletion" :class="{ 'opacity-25': processingDeletion }" :disabled="processingDeletion">
                        Delete Party
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { ref } from "vue";

const props = defineProps({
    parties: Object,
});

const confirmingDeletion = ref(false);
const partyToDelete = ref(null);
const processingDeletion = ref(false);

const deleteForm = useForm({});

const deleteParty = (id) => {
    partyToDelete.value = id;
    confirmingDeletion.value = true;
};

const proceedWithDeletion = () => {
    processingDeletion.value = true;
    deleteForm.delete(route("parties.destroy", partyToDelete.value), {
        onSuccess: () => {
            confirmingDeletion.value = false;
            processingDeletion.value = false;
        },
        onError: () => {
            processingDeletion.value = false;
        }
    });
};
</script>
