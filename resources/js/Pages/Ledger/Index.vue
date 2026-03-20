<template>
    <Head title="Ledger Entries" />
    <AuthenticatedLayout>
        <template #header> Ledger Entries </template>

        <div class="mb-6 flex justify-between items-center">
            <Link
                :href="route('ledgers.create')"
                class="inline-flex items-center px-4 py-2 bg-pink-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-700 active:bg-pink-900 focus:outline-none focus:border-pink-900 focus:shadow-outline-pink transition ease-in-out duration-150 no-underline"
            >
                <i class="fa fa-plus mr-2"></i> Add New Entry
            </Link>
        </div>

        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg bg-white">
            <div class="table-responsive">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sr.no.</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(ledger, index) in ledgers.data" :key="ledger.id" class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ (ledgers.current_page - 1) * ledgers.per_page + index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ ledger.seller_customer?.customer_detail?.name || 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ledger.date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span :class="ledger.type === 'debit' ? 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800' : 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800'">
                                    {{ ledger.type.toUpperCase() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ledger.payment_type.toUpperCase() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold" :class="ledger.type === 'debit' ? 'text-red-600' : 'text-green-600'">
                                ₹{{ Number(ledger.amount).toLocaleString('en-IN') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" :title="ledger.description">
                                {{ ledger.description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="dropdown">
                                    <button
                                        class="text-gray-400 hover:text-gray-600 focus:outline-none"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                    >
                                        <i class="fa fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu shadow-lg border-0">
                                        <li>
                                            <Link class="dropdown-item flex items-center py-2" :href="route('ledgers.edit', ledger.id)">
                                                <i class="fa fa-edit mr-2 text-indigo-600"></i> Edit
                                            </Link>
                                        </li>
                                        <li>
                                            <button class="dropdown-item flex items-center py-2 text-red-600" @click="confirmEntryDeletion(ledger.id)">
                                                <i class="fa fa-trash mr-2"></i> Delete
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="ledgers.data.length === 0">
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                No ledger entries found. Click "Add New Entry" to get started.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-white border-t border-gray-200">
                <pagination :links="ledgers.links" />
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="confirmingEntryDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this ledger entry?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    This action cannot be undone.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>
                    <DangerButton
                        :class="{ 'opacity-25': deleteForm.processing }"
                        :disabled="deleteForm.processing"
                        @click="deleteEntry"
                    >
                        Delete Entry
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    ledgers: Object,
});

const confirmingEntryDeletion = ref(false);
const deleteForm = useForm({});
let entryIdToDelete = null;

const confirmEntryDeletion = (id) => {
    entryIdToDelete = id;
    confirmingEntryDeletion.value = true;
};

const closeModal = () => {
    confirmingEntryDeletion.value = false;
};

const deleteEntry = () => {
    deleteForm.delete(route("ledgers.destroy", entryIdToDelete), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};
</script>

<style scoped>
.dropdown-item:hover {
    background-color: #f3f4f6;
}
</style>
