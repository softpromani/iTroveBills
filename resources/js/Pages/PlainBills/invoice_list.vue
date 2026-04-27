<template>
    <Head title="Plain Bill List" />
    <AuthenticatedLayout>
        <template v-if="!needs_auth">
            <!-- Header -->
            <div class="inline-flex w-full mb-4 overflow-hidden bg-white rounded-lg shadow-md">
                <div class="flex items-center justify-center w-12 bg-pink-800">
                    <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z"></path>
                    </svg>
                </div>
                <div class="px-4 py-2 -mx-3">
                    <div class="mx-3">
                        <span class="font-semibold text-blue-500">Plain Bill List</span>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow mb-4 p-4">
                <h3 class="text-lg font-semibold mb-4">Filters</h3>

                <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Customer Filter -->
                    <div class="form-group">
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                        <select v-model="filters.customer_id" id="customer_id" class="form-control w-full">
                            <option value="">All Customers</option>
                            <option v-for="customer in (customers || [])" :key="customer.id" :value="customer.id">
                                {{ customer.name }}
                            </option>
                        </select>
                    </div>

                    <!-- Customer Name Search -->
                    <div class="form-group">
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Search Customer</label>
                        <input
                            v-model="filters.customer_name"
                            type="text"
                            id="customer_name"
                            class="form-control w-full"
                            placeholder="Enter customer name"
                        >
                    </div>
                </form>

                <!-- Filter Buttons -->
                <div class="flex gap-2 mt-4">
                    <button @click="applyFilters" class="btn btn-primary">
                        <i class="fa fa-filter mr-1"></i>
                        Apply Filters
                    </button>
                    <button @click="clearFilters" class="btn btn-secondary">
                        <i class="fa fa-times mr-1"></i>
                        Clear Filters
                    </button>
                </div>
            </div>

            <!-- Invoice Table -->
            <div class="bg-white rounded-lg shadow">
                <div class="rounded-lg table-responsive">
                    <table class="table" style="min-height: 200px">
                        <thead class="table-dark">
                            <tr>
                                <th class="col-1">Sr.no.</th>
                                <th class="col-1">Invoice Number</th>
                                <th class="col-1">Invoice Date</th>
                                <th class="col-1">Total Amount</th>
                                <th class="col-1">Paid Amount</th>
                                <th class="col-2">Payment Status</th>
                                <th class="col-1">Vehicle No</th>
                                <th class="col-1">Customer</th>
                                <th class="col-1">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="!invoiceList || invoiceList.length === 0">
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-gray-500">
                                        <i class="fa fa-inbox fa-3x mb-3"></i>
                                        <p>No plain bills found matching your criteria</p>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="(invoice, index) in invoiceList" :key="invoice.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ invoice.invoice_number || "" }}</td>
                                <td>{{ formatDate(invoice.invoice_date) }}</td>
                                <td>₹{{ formatAmount(invoice.total_ammount) }}</td>
                                <td>₹{{ formatAmount(invoice.payment?.paid_amount) }}</td>
                                <td>
                                    <span
                                      class="px-3 py-1 mr-2 text-xs font-medium rounded-full"
                                      :class="{
                                        'bg-red-100 text-red-800': invoice.payment?.status === 'due',
                                        'bg-blue-100 text-blue-800': invoice.payment?.status === 'partial-paid',
                                        'bg-green-100 text-green-800': invoice.payment?.status === 'paid'
                                      }"
                                    >
                                      {{ invoice.paymentStatus?.status || 'unpaid' }}
                                    </span>
                                </td>
                                <td>{{ invoice.vehicle_no || "N/A" }}</td>
                                <td>{{ invoice.customer ? invoice.customer.company_name : "N/A" }}</td>

                                <td>
                                    <div class="dropdown">
                                        <span class="p-2" style="font-size: 15px; cursor: pointer" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </span>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <Link class="dropdown-item" :href="route('book.edit', invoice.id)">
                                                    <i class="fa-solid fa-pen-to-square" style="color: blueviolet"></i>
                                                    Edit
                                                </Link>
                                            </li>
                                            <li>
                                                <Link :href="route('book.view.invoice')" method="get" :data="{ invoice_id: invoice.id }" class="dropdown-item">
                                                    <i class="fa fa-print" aria-hidden="true" style="color: rgb(241, 12, 12);"></i>
                                                    Print
                                                </Link>
                                            </li>
                                            <li>
                                                <Link class="dropdown-item" :href="route('bill.sendmail')" method="post" :data="{ invoice_id: invoice.id }">
                                                    <i class="fa fa-envelope-square" aria-hidden="true" style="color: rgb(245, 180, 0);"></i>
                                                    Mail (Buyer)
                                                </Link>
                                            </li>
                                            <li>
                                                <Link class="dropdown-item" :href="route('create.eway.bill')" method="get" :data="{ invoice_id: invoice.id, type: 'plain' }">
                                                    <i class="fa fa-shipping-fast" aria-hidden="true" style="color: rgb(245, 180, 0);"></i>
                                                    Generate E-Way Bill
                                                </Link>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>

        <!-- Password Modal -->
        <Dialog 
            v-model:visible="showAuthModal" 
            modal 
            header="Plain Bill Authentication" 
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
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Dialog from "primevue/dialog";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    invoices: Array,
    customers: Array,
    needs_auth: Boolean,
});

const showAuthModal = ref(props.needs_auth);
const authForm = useForm({
    password: '',
});

const submitAuth = () => {
    authForm.post(route("book.auth"), {
        onSuccess: () => {
            showAuthModal.value = false;
        },
    });
};

const filters = ref({
    customer_id: '',
    customer_name: '',
});

const invoiceList = computed(() => props.invoices || []);

const applyFilters = () => {
    router.get(route('book.list'), filters.value, {
        preserveState: true,
        replace: true
    });
};

const clearFilters = () => {
    filters.value = { customer_id: '', customer_name: '' };
    applyFilters();
};

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-IN');
};

const formatAmount = (amount) => {
    if (!amount) return '0.00';
    return parseFloat(amount).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};
</script>
