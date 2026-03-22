<template>
    <Head title="Ledger Entries" />

    <AuthenticatedLayout>
        <template #header> Ledger Entries </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-800">All Transactions</h2>
                            <Link
                                :href="route('ledgers.create')"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
                            >
                                Add New Entry
                            </Link>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voucher/Bill</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vch No.</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="ledger in ledgers.data" :key="ledger.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ledger.date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span v-if="ledger.seller_customer">
                                                <span class="text-xs font-bold text-blue-600 uppercase">C</span> 
                                                {{ ledger.seller_customer.customer_detail?.name }}
                                            </span>
                                            <span v-else-if="ledger.party">
                                                <span class="text-xs font-bold text-green-600 uppercase">P</span> 
                                                {{ ledger.party.name }}
                                            </span>
                                            <span v-else class="text-gray-400">N/A</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span 
                                                :class="ledger.type === 'debit' ? 'text-blue-600 font-semibold' : 'text-red-600 font-semibold'"
                                            >
                                                {{ ledger.type === 'debit' ? 'Debit (+)' : 'Credit (-)' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 uppercase">{{ ledger.payment_type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ledger.particular_type || '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">{{ ledger.voucher_no || ledger.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ₹{{ parseFloat(ledger.amount).toLocaleString('en-IN', { minimumFractionDigits: 2 }) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <Link :href="route('ledgers.edit', ledger.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</Link>
                                            <button @click="deleteLedger(ledger.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                    <tr v-if="ledgers.data.length === 0">
                                        <td colspan="8" class="px-6 py-10 text-center text-gray-500 text-sm">
                                            No ledger entries found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            <Pagination :links="ledgers.links" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="confirmingDeletion" @close="confirmingDeletion = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Are you sure you want to delete this entry?</h2>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="confirmingDeletion = false">Cancel</SecondaryButton>
                    <DangerButton class="ml-3" @click="proceedWithDeletion" :class="{ 'opacity-25': processingDeletion }" :disabled="processingDeletion">
                        Delete Entry
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
    ledgers: Object,
});

const confirmingDeletion = ref(false);
const ledgerToDelete = ref(null);
const processingDeletion = ref(false);

const deleteForm = useForm({});

const deleteLedger = (id) => {
    ledgerToDelete.value = id;
    confirmingDeletion.value = true;
};

const proceedWithDeletion = () => {
    processingDeletion.value = true;
    deleteForm.delete(route("ledgers.destroy", ledgerToDelete.value), {
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
