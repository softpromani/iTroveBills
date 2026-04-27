<template>
    <Head title="Plain Ledger Report" />

    <AuthenticatedLayout>
        <template #header> Plain Ledger Report </template>
        <template v-if="!needs_auth">

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print:p-0">
                    <!-- Filters (Hidden on Print) -->
                    <div class="bg-white p-6 rounded-lg shadow mb-6 print:hidden">
                        <form @submit.prevent="generateReport" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div class="md:col-span-2">
                                <InputLabel for="account" value="Select Customer / Party" />
                                <select
                                    id="account"
                                    v-model="selectedAccount"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                >
                                    <option value="" disabled>Choose an account</option>
                                    <optgroup label="Customers">
                                        <option v-for="customer in customers" :key="'c'+customer.id" :value="'customer_'+customer.id">
                                            {{ customer.customer_detail?.name }}
                                        </option>
                                    </optgroup>
                                    <optgroup label="Parties">
                                        <option v-for="party in parties" :key="'p'+party.id" :value="'party_'+party.id">
                                            {{ party.name }}
                                        </option>
                                    </optgroup>
                                </select>
                            </div>
                            <div>
                                <InputLabel for="start_date" value="Start Date" />
                                <TextInput id="start_date" type="date" v-model="form.start_date" class="mt-1 block w-full" />
                            </div>
                            <div>
                                <InputLabel for="end_date" value="End Date" />
                                <TextInput id="end_date" type="date" v-model="form.end_date" class="mt-1 block w-full" />
                            </div>
                            <div class="flex gap-2">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Generate
                                </PrimaryButton>
                                <button 
                                    v-if="report_data"
                                    type="button" 
                                    @click="printReport"
                                    class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition"
                                >
                                    Print
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Report Content -->
                    <div v-if="report_data" class="bg-white p-8 rounded-lg shadow print:shadow-none print:p-0" id="report-content">
                        <!-- Report Header -->
                        <div class="flex justify-between border-b-2 border-gray-900 pb-4 mb-6">
                            <div class="w-2/3">
                                <h1 class="text-2xl font-bold uppercase">{{ report_data.company?.company_name || 'Organization Name' }}</h1>
                                <p class="text-sm mt-1 whitespace-pre-line">{{ report_data.company?.address || 'Organization Address' }}</p>
                                <p class="text-sm">GSTIN: {{ report_data.company?.gstin || 'N/A' }}</p>
                            </div>
                            <div class="w-1/3 text-right">
                                <h2 class="text-xl font-bold uppercase">{{ report_data.customer?.name || 'Customer' }}</h2>
                                <p class="text-sm">Plain Ledger Account</p>
                                <p class="text-sm uppercase flex-wrap">{{ report_data.customer?.address || '' }}</p>
                                <p class="mt-4 font-semibold">
                                    <span v-if="report_data.start_date">{{ formatDate(report_data.start_date) }}</span>
                                    <span v-else>Up to</span>
                                    to 
                                    {{ formatDate(report_data.end_date) }}
                                </p>
                            </div>
                        </div>

                        <!-- Table -->
                        <table class="w-full border-collapse border border-gray-400 text-sm">
                            <thead>
                                <tr class="bg-gray-100 uppercase">
                                    <th class="border border-gray-400 p-2 text-left w-24">Date</th>
                                    <th class="border border-gray-400 p-2 text-left">Particulars</th>
                                    <th class="border border-gray-400 p-2 text-left w-24">Vch Type</th>
                                    <th class="border border-gray-400 p-2 text-left w-20">Vch No.</th>
                                    <th class="border border-gray-400 p-2 text-right w-32">Debit</th>
                                    <th class="border border-gray-400 p-2 text-right w-32">Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Opening Balance -->
                                <tr v-if="report_data.opening_balance !== 0 || report_data.start_date">
                                    <td class="border border-gray-400 p-2">{{ report_data.start_date ? formatDate(report_data.start_date) : '-' }}</td>
                                    <td class="border border-gray-400 p-2 font-bold italic">Opening Balance</td>
                                    <td class="border border-gray-400 p-2"></td>
                                    <td class="border border-gray-400 p-2"></td>
                                    <td class="border border-gray-400 p-2 text-right font-semibold">
                                        {{ report_data.opening_balance > 0 ? formatAmount(report_data.opening_balance) : '' }}
                                    </td>
                                    <td class="border border-gray-400 p-2 text-right font-semibold">
                                        {{ report_data.opening_balance < 0 ? formatAmount(Math.abs(report_data.opening_balance)) : '' }}
                                    </td>
                                </tr>

                                <!-- Primary Transactions -->
                                <tr v-for="(tx, index) in report_data.transactions" :key="index">
                                    <td class="border border-gray-400 p-2">{{ formatDate(tx.date) }}</td>
                                    <td class="border border-gray-400 p-2">{{ tx.particulars }}</td>
                                    <td class="border border-gray-400 p-2">{{ tx.vch_type }}</td>
                                    <td class="border border-gray-400 p-2 font-semibold">{{ tx.vch_no }}</td>
                                    <td class="border border-gray-400 p-2 text-right">
                                        {{ tx.debit != 0 ? formatAmount(tx.debit) : '' }}
                                    </td>
                                    <td class="border border-gray-400 p-2 text-right">
                                        {{ tx.credit != 0 ? formatAmount(tx.credit) : '' }}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="font-bold bg-gray-50 uppercase">
                                <!-- Footer Totals -->
                                <tr>
                                    <td class="border border-gray-400 p-2" colspan="4">Total Current Period</td>
                                    <td class="border border-gray-400 p-2 text-right">{{ formatAmount(report_data.total_debit) }}</td>
                                    <td class="border border-gray-400 p-2 text-right">{{ formatAmount(report_data.total_credit) }}</td>
                                </tr>
                                <!-- Closing Balance row to balance the total columns -->
                                <tr>
                                    <td class="border border-gray-400 p-2" colspan="4">Closing Balance</td>
                                    <td class="border border-gray-400 p-2 text-right">
                                        {{ report_data.closing_balance < 0 ? formatAmount(Math.abs(report_data.closing_balance)) : '' }}
                                    </td>
                                    <td class="border border-gray-400 p-2 text-right">
                                        {{ report_data.closing_balance >= 0 ? formatAmount(report_data.closing_balance) : '' }}
                                    </td>
                                </tr>
                                <tr class="bg-gray-200 uppercase">
                                    <td class="border border-gray-400 p-2" colspan="4">Grand Total</td>
                                    <td class="border border-gray-400 p-2 text-right">
                                        {{ formatAmount(report_data.total_debit + (report_data.opening_balance > 0 ? report_data.opening_balance : 0) + (report_data.closing_balance < 0 ? Math.abs(report_data.closing_balance) : 0)) }}
                                    </td>
                                    <td class="border border-gray-400 p-2 text-right">
                                        {{ formatAmount(report_data.total_credit + (report_data.opening_balance < 0 ? Math.abs(report_data.opening_balance) : 0) + (report_data.closing_balance >= 0 ? report_data.closing_balance : 0)) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Net Balance Display -->
                        <div class="mt-4 text-right">
                            <p class="text-lg font-bold">Net Balance: {{ formatAmount(Math.abs(report_data.closing_balance)) }} {{ report_data.closing_balance >= 0 ? 'Dr' : 'Cr' }}</p>
                        </div>

                        <!-- Signature Area (Hidden on screen, visible on Print) -->
                        <div class="mt-20 hidden print:flex justify-between">
                            <div class="border-t border-gray-900 px-8 pt-2 uppercase">
                                Customer Signature
                            </div>
                            <div class="border-t border-gray-900 px-8 pt-2 uppercase">
                                For {{ report_data.company?.company_name }}
                            </div>
                        </div>
                    </div>
                    
                    <div v-else-if="!report_data && filters?.seller_customer_id" class="p-8 text-center text-gray-500 bg-white rounded-lg shadow mt-6">
                        Calculating report...
                    </div>
                    <div v-else class="p-12 text-center text-gray-400 border-2 border-dashed rounded-lg mt-6">
                        Select a customer or party and optional date range to generate the plain ledger report.
                    </div>
                </div>
            </div>
        </template>

        <!-- Password Modal -->
        <Dialog 
            v-model:visible="showAuthModal" 
            modal 
            header="Plain Ledger Authentication" 
            :closable="false" 
            :draggable="false"
            :style="{ width: '400px' }"
        >
            <form @submit.prevent="submitAuth">
                <div class="p-field mb-4">
                    <label for="password" class="block mb-2 font-bold">Enter Module Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        v-model="authForm.password" 
                        class="form-control w-full" 
                        required 
                        placeholder="••••••••"
                        autoFocus
                    />
                    <InputError :message="authForm.errors.password" class="mt-2" />
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <PrimaryButton :disabled="authForm.processing">
                        Access Module
                    </PrimaryButton>
                </div>
            </form>
        </Dialog>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, useForm } from "@inertiajs/vue3";
import Dialog from "primevue/dialog";
import InputError from "@/Components/InputError.vue";
import { ref, watch, onMounted } from "vue";

const props = defineProps({
    customers: { type: Array, default: () => [] },
    parties: { type: Array, default: () => [] },
    report_data: { type: Object, default: null },
    filters: { type: Object, default: () => ({}) },
    needs_auth: Boolean,
});

const showAuthModal = ref(props.needs_auth);
const authForm = useForm({
    password: '',
});

const submitAuth = () => {
    authForm.post(route("ink.auth"), {
        onSuccess: () => {
            showAuthModal.value = false;
        },
    });
};

const selectedAccount = ref("");

const form = useForm({
    seller_customer_id: props.filters?.seller_customer_id || "",
    party_id: props.filters?.party_id || "",
    start_date: props.filters?.start_date || "",
    end_date: props.filters?.end_date || "",
});

onMounted(() => {
    if (props.filters?.seller_customer_id) {
        selectedAccount.value = `customer_${props.filters.seller_customer_id}`;
    } else if (props.filters?.party_id) {
        selectedAccount.value = `party_${props.filters.party_id}`;
    }
});

watch(selectedAccount, (val) => {
    if (val.startsWith("customer_")) {
        form.seller_customer_id = val.replace("customer_", "");
        form.party_id = "";
    } else if (val.startsWith("party_")) {
        form.party_id = val.replace("party_", "");
        form.seller_customer_id = "";
    }
});

const generateReport = () => {
    form.get(route("ink.report.generate"), {
        preserveState: true,
        replace: true,
    });
};

const formatDate = (dateString) => {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("en-IN", {
        day: "2-digit",
        month: "short",
        year: "numeric",
    });
};

const formatAmount = (amount) => {
    if (isNaN(amount)) return "0.00";
    return parseFloat(amount).toLocaleString("en-IN", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

const printReport = () => {
    window.print();
};
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #report-content, #report-content * {
        visibility: visible;
    }
    #report-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .print\:hidden {
        display: none !important;
    }
    .print\:p-0 {
        padding: 0 !important;
    }
    .print\:shadow-none {
        box-shadow: none !important;
    }
}
</style>
