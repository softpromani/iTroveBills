<template>
    <Head title="Ledger Report" />
    <AuthenticatedLayout>
        <template #header> Ledger Report </template>

        <!-- Search Filters (Hidden on Print) -->
        <div class="mb-6 p-4 bg-white rounded-lg shadow-md no-print">
            <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <InputLabel for="customer" value="Select Customer" />
                    <select
                        id="customer"
                        v-model="form.seller_customer_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required
                    >
                        <option value="" disabled>Choose a customer</option>
                        <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                            {{ customer.customer_detail?.name || 'Unnamed' }}
                        </option>
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
                        @click="printReport" 
                        type="button"
                        v-if="report_data"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                    >
                        <i class="fa fa-print mr-2"></i> Print
                    </button>
                </div>
            </form>
        </div>

        <!-- Report Content -->
        <div v-if="report_data" class="report-container bg-white p-8 shadow-lg rounded-sm" id="report-content">
            <!-- Organization Header -->
            <div class="text-center mb-8 border-b pb-4">
                <h1 class="text-2xl font-bold uppercase">{{ report_data.company?.company_name || 'My Business' }}</h1>
                <p class="text-sm uppercase">{{ report_data.company?.address || '' }}</p>
                <p class="text-sm">E-Mail: {{ report_data.company?.email || '' }}</p>
            </div>

            <!-- Customer Details -->
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold uppercase">{{ report_data.customer?.name || 'Customer' }}</h2>
                <p class="text-sm">Ledger Account</p>
                <p class="text-sm uppercase">{{ report_data.customer?.address || '' }}</p>
                <p class="mt-4 font-semibold">
                    <span v-if="report_data.start_date">{{ formatDate(report_data.start_date) }}</span>
                    <span v-else>Up to</span>
                    to 
                    {{ formatDate(report_data.end_date) }}
                </p>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-t border-b border-black">
                    <thead>
                        <tr class="border-b border-black">
                            <th class="px-2 py-2 text-left w-24">Date</th>
                            <th class="px-2 py-2 text-left">Particulars</th>
                            <th class="px-2 py-2 text-left w-32">Vch Type</th>
                            <th class="px-2 py-2 text-left w-20">Vch No.</th>
                            <th class="px-2 py-2 text-right w-28">Debit</th>
                            <th class="px-2 py-2 text-right w-28">Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Opening Balance -->
                        <tr class="font-semibold border-b border-gray-100">
                            <td class="px-2 py-2">{{ formatDate(report_data.start_date) }}</td>
                            <td class="px-2 py-2">Opening Balance</td>
                            <td></td>
                            <td></td>
                            <td class="px-2 py-2 text-right">
                                {{ report_data.opening_balance >= 0 ? formatAmount(report_data.opening_balance) : '' }}
                            </td>
                            <td class="px-2 py-2 text-right">
                                {{ report_data.opening_balance < 0 ? formatAmount(Math.abs(report_data.opening_balance)) : '' }}
                            </td>
                        </tr>

                        <!-- Transactions -->
                        <tr v-for="(tx, idx) in report_data.transactions" :key="idx" class="border-b border-gray-50 align-top">
                            <td class="px-2 py-1">{{ formatDate(tx.date) }}</td>
                            <td class="px-2 py-1">{{ tx.particulars }}</td>
                            <td class="px-2 py-1 text-xs">{{ tx.vch_type }}</td>
                            <td class="px-2 py-1">{{ tx.vch_no }}</td>
                            <td class="px-2 py-1 text-right">{{ tx.debit > 0 ? formatAmount(tx.debit) : '' }}</td>
                            <td class="px-2 py-1 text-right">{{ tx.credit > 0 ? formatAmount(tx.credit) : '' }}</td>
                        </tr>

                        <!-- Closing Balance Row -->
                        <tr class="font-semibold border-t border-black bg-gray-50">
                            <td colspan="4" class="px-2 py-2 text-right italic">Closing Balance</td>
                            <td class="px-2 py-2 text-right">
                                {{ report_data.closing_balance < 0 ? formatAmount(Math.abs(report_data.closing_balance)) : '' }}
                            </td>
                            <td class="px-2 py-2 text-right">
                                {{ report_data.closing_balance >= 0 ? formatAmount(report_data.closing_balance) : '' }}
                            </td>
                        </tr>

                        <!-- Grand Total Row -->
                        <tr class="font-bold border-t-2 border-b-2 border-black">
                            <td colspan="4" class="px-2 py-2">Total</td>
                            <td class="px-2 py-2 text-right border-t border-black">
                                {{ formatAmount(report_data.total_debit + (report_data.opening_balance > 0 ? report_data.opening_balance : 0) + (report_data.closing_balance < 0 ? Math.abs(report_data.closing_balance) : 0)) }}
                            </td>
                            <td class="px-2 py-2 text-right border-t border-black">
                                {{ formatAmount(report_data.total_credit + (report_data.opening_balance < 0 ? Math.abs(report_data.opening_balance) : 0) + (report_data.closing_balance > 0 ? report_data.closing_balance : 0)) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer Message -->
            <div class="mt-8 text-xs text-center text-gray-400 no-print">
                Generated on {{ new Date().toLocaleString() }}
            </div>
        </div>
        
        <div v-else-if="!report_data && filters?.seller_customer_id" class="p-8 text-center text-gray-500 bg-white rounded-lg shadow mt-6">
            Calculating report...
        </div>
        <div v-else class="p-12 text-center text-gray-400 border-2 border-dashed rounded-lg mt-6">
            Select a customer and date range to generate the ledger report.
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { onMounted } from "vue";

const props = defineProps({
    customers: Array,
    report_data: Object,
    filters: Object,
});

const form = useForm({
    seller_customer_id: props.filters?.seller_customer_id || "",
    start_date: props.filters?.start_date || "",
    end_date: props.filters?.end_date || "",
});

const submit = () => {
    form.get(route("ledger.report.generate"), {
        preserveState: true,
        preserveScroll: true,
    });
};

const formatDate = (dateString) => {
    if (!dateString) return "";
    const options = { day: 'numeric', month: 'short', year: '2-digit' };
    return new Date(dateString).toLocaleDateString('en-GB', options).replace(/ /g, '-');
};

const formatAmount = (amount) => {
    return Number(amount).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

const printReport = () => {
    window.print();
};

onMounted(() => {
    // If we have filters but no report data (e.g. redirected with params), trigger submit
    if (form.seller_customer_id && form.start_date && form.end_date && !props.report_data) {
        // we can optionally auto-submit here, but Inertia usually handles the GET back with data
    }
});
</script>

<style>
@media print {
    .no-print, .no-print * {
        display: none !important;
    }
    body {
        background: white !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .report-container {
        box-shadow: none !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }
    .AuthenticatedLayout main {
        padding: 0 !important;
    }
    @page {
        margin: 1cm;
    }
}

/* Custom table spacing to closer match the image styling */
table th, table td {
    border-color: #000 !important;
}
</style>
