<template>
    <Head title="Invoice List" />
    <AuthenticatedLayout>
        <!-- Header -->
        <div class="inline-flex w-full mb-4 overflow-hidden bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-center w-12 bg-pink-800">
                <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z"></path>
                </svg>
            </div>
            <div class="px-4 py-2 -mx-3">
                <div class="mx-3">
                    <span class="font-semibold text-blue-500">Invoice List</span>
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
                            {{ customer.name }} ({{ customer.mobile }})
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

                <!-- Financial Year Filter -->
                <div class="form-group">
                    <label for="financial_year" class="block text-sm font-medium text-gray-700 mb-1">Financial Year</label>
                    <select v-model="filters.financial_year" id="financial_year" class="form-control w-full">
                        <option value="">All Years</option>
                        <option v-for="year in (financialYears || [])" :key="year.value" :value="year.value">
                            {{ year.label }}
                        </option>
                    </select>
                </div>

                <!-- Payment Status Filter -->
                <div class="form-group">
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                    <select v-model="filters.payment_status" id="payment_status" class="form-control w-full">
                        <option value="">All Status</option>
                        <option v-for="status in (paymentStatuses || [])" :key="status" :value="status">
                            {{ status.charAt(0).toUpperCase() + status.slice(1).replace('-', ' ') }}
                        </option>
                    </select>
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
                <!-- <button @click="exportFiltered" class="btn btn-success">
                    <i class="fa fa-download mr-1"></i>
                    Export
                </button> -->
            </div>

            <!-- Active Filters Display -->
            <div v-if="hasActiveFilters" class="mt-3">
                <span class="text-sm font-medium text-gray-600">Active Filters:</span>
                <div class="flex flex-wrap gap-2 mt-1">
                    <span v-if="filters.customer_id" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Customer: {{ getCustomerName(filters.customer_id) }}
                        <button @click="filters.customer_id = ''" class="ml-1 text-blue-600 hover:text-blue-800">×</button>
                    </span>
                    <span v-if="filters.financial_year" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        FY: {{ filters.financial_year }}-{{ parseInt(filters.financial_year) + 1 }}
                        <button @click="filters.financial_year = ''" class="ml-1 text-green-600 hover:text-green-800">×</button>
                    </span>
                    <span v-if="filters.payment_status" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Status: {{ filters.payment_status }}
                        <button @click="filters.payment_status = ''" class="ml-1 text-purple-600 hover:text-purple-800">×</button>
                    </span>
                    <span v-if="filters.start_date" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        From: {{ filters.start_date }}
                        <button @click="filters.start_date = ''" class="ml-1 text-yellow-600 hover:text-yellow-800">×</button>
                    </span>
                    <span v-if="filters.end_date" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        To: {{ filters.end_date }}
                        <button @click="filters.end_date = ''" class="ml-1 text-yellow-600 hover:text-yellow-800">×</button>
                    </span>
                    <span v-if="filters.min_amount" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        Min: ₹{{ filters.min_amount }}
                        <button @click="filters.min_amount = ''" class="ml-1 text-indigo-600 hover:text-indigo-800">×</button>
                    </span>
                    <span v-if="filters.max_amount" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        Max: ₹{{ filters.max_amount }}
                        <button @click="filters.max_amount = ''" class="ml-1 text-indigo-600 hover:text-indigo-800">×</button>
                    </span>
                </div>
            </div>
        </div>

        <!-- Results Summary and Per Page Selector -->
        <div class="bg-white rounded-lg shadow mb-4 p-4">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Showing {{ paginationInfo.from }} to {{ paginationInfo.to }} of {{ paginationInfo.total }} results
                </div>
                <div class="flex items-center gap-2">
                    <label for="per_page" class="text-sm text-gray-600">Show:</label>
                    <select
                        v-model="filters.per_page"
                        id="per_page"
                        class="form-control w-auto text-sm"
                        @change="applyFilters"
                    >
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-600">per page</span>
                </div>
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
                            <th class="col-1">No. Packets</th>
                            <th class="col-1">Customer</th>
                            <th class="col-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!invoiceList || invoiceList.length === 0">
                            <td colspan="10" class="text-center py-4">
                                <div class="text-gray-500">
                                    <i class="fa fa-inbox fa-3x mb-3"></i>
                                    <p>No invoices found matching your criteria</p>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="(invoice, index) in invoiceList" :key="invoice.id">
                            <td>{{ getSerialNumber(index) }}</td>
                            <td>{{ invoice.invoice_number || "" }}</td>
                            <td>{{ formatDate(invoice.invoice_date) }}</td>
                            <td>₹{{ formatAmount(invoice.total_ammount) }}</td>
                            <td>₹{{ formatAmount(invoice.payment?.paid_amount) }}</td>
                            <td>
                                <span
                                  class="px-3 py-1 mr-2 text-xs font-medium rounded-full"
                                  :class="{
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': invoice.payment?.status === 'due',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': invoice.payment?.status === 'partial-paid',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': invoice.payment?.status === 'paid'
                                  }"
                                >
                                  {{ invoice.payment?.status || 'N/A' }}
                                </span>
                            </td>
                            <td>{{ invoice.vehicle_no || "N/A" }}</td>
                            <td>{{ invoice.no_packets || "NO PACK" }}</td>
                            <td>{{invoice.customer.company_name || "N/A" }}</td>

                            <td>
                                <div class="dropdown">
                                    <span class="p-2" style="font-size: 15px; cursor: pointer" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <Link class="dropdown-item" :href="route('customer.bill.edit')" method="get" :data="{ invoice_id: invoice.id }">
                                                <i class="fa-solid fa-pen-to-square" style="color: blueviolet"></i>
                                                Edit
                                            </Link>
                                        </li>
                                        <li>
                                            <Link :href="route('view.invoice')" method="get" :data="{ invoice_id: invoice.id }" class="dropdown-item">
                                                <i class="fa fa-print" aria-hidden="true" style="color: rgb(241, 12, 12);"></i>
                                                Print
                                            </Link>
                                        </li>
                                        <li>
                                            <Link class="dropdown-item" :href="route('bill.sendmail')" method="post" :data="{ invoice_id: invoice.id }">
                                                <i class="fa fa-envelope-square" aria-hidden="true" style="color: rgb(245, 180, 0);"></i>
                                                Mail
                                            </Link>
                                        </li>
                                        <li>
                                            <Link class="dropdown-item" :href="route('create.eway.bill')" method="get" :data="{ invoice_id: invoice.id, type: 'regular' }">
                                                <i class="fa fa-envelope-square" aria-hidden="true" style="color: rgb(245, 180, 0);"></i>
                                                Generate E-Way Bill
                                            </Link>
                                        </li>
                                        <li>
                                            <a @click.prevent="openModal(invoice.id)" class="dropdown-item" href="#">
                                              <i class="fa fa-car" aria-hidden="true" style="color: rgb(245, 180, 0);"></i>
                                              Package Update
                                            </a>
                                        </li>
                                        <li>
                                            <a @click.prevent="openPayBillModal(invoice.id)" class="dropdown-item" href="#">
                                              <i class="fa fa-inr" aria-hidden="true" style="color: rgb(245, 180, 0);"></i>
                                              Pay Bill
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination -->
            <div class="flex flex-col items-center px-4 py-3 bg-white border-t sm:flex-row sm:justify-between">
                <div class="text-sm text-gray-700 mb-2 sm:mb-0">
                    Showing <span class="font-medium">{{ paginationInfo.from }}</span> to
                    <span class="font-medium">{{ paginationInfo.to }}</span> of
                    <span class="font-medium">{{ paginationInfo.total }}</span> results
                </div>
                <div class="flex items-center gap-4">
                    <!-- Page size info -->
                    <div class="text-sm text-gray-500">
                        Page {{ paginationInfo.current_page }} of {{ paginationInfo.last_page }}
                    </div>
                    <!-- Pagination component -->
                    <pagination
                        v-if="paginationInfo.links && paginationInfo.links.length > 3"
                        :links="paginationInfo.links"
                    />
                </div>
            </div>
        </div>

        <!-- Package Update Modal -->
        <div v-if="showModal" class="modal fade show" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Package</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitForm">
                            <div class="form-group mb-3">
                                <label for="no_packets">No. of Package</label>
                                <input type="number" class="form-control" id="no_packets" v-model="no_packets" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="vehicle_no">Vehicle Number</label>
                                <input type="text" class="form-control" id="vehicle_no" v-model="vehicle_no" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="dispatched_through">Dispatched Through</label>
                                <input type="text" class="form-control" id="dispatched_through" v-model="dispatched_through">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showModal" class="modal-backdrop fade show"></div>

        <!-- Pay Bill Modal -->
        <div v-if="PayBillModal" class="modal fade show" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pay Bill - {{ invoiceNumber }}</h5>
                        <button type="button" class="close" @click="closePayBillModal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitPayBillForm">
                            <fieldset class="mb-3">
                                <legend class="col-form-label pt-0">Payment Method</legend>
                                <div v-for="(type, index) in paymentTypes" :key="index" class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" :id="type.name" :value="type.id" v-model="payment_method_id">
                                    <label class="form-check-label" :for="type.name">{{ type.name }}</label>
                                </div>
                            </fieldset>
                            <div class="form-group mb-3">
                                <label for="reference_no">Reference Number</label>
                                <input type="text" class="form-control" id="reference_no" v-model="reference_no" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="amount">Amount</label>
                                <input type="number" class="form-control" id="amount" v-model="amount" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="remark">Remark (if any)</label>
                                <textarea class="form-control" id="remark" v-model="remark"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="closePayBillModal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="PayBillModal" class="modal-backdrop fade show"></div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, computed, onMounted, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
    invoices: {
        type: [Object, Array],
        default: () => ({})
    },
    customers: {
        type: Array,
        default: () => []
    },
    paymentStatuses: {
        type: Array,
        default: () => []
    },
    financialYears: {
        type: Array,
        default: () => []
    },
});

// Filter reactive variables - Updated with per_page
const filters = ref({
    customer_id: '',
    customer_name: '',
    financial_year: '',
    payment_status: '',
    start_date: '',
    end_date: '',
    min_amount: '',
    max_amount: '',
    per_page: '20'
});

// Existing modal variables
const showModal = ref(false);
const PayBillModal = ref(false);
const no_packets = ref('');
const vehicle_no = ref('');
const dispatched_through = ref('');
const invoiceId = ref(null);
const invoiceNumber = ref('');
const reference_no = ref('');
const payment_method_id = ref('');
const amount = ref('');
const remark = ref('');
const paymentTypes = ref([]);

// Computed properties for safe data access
const invoiceList = computed(() => {
    // Handle both paginated (object with data property) and non-paginated (array) responses
    if (props.invoices && typeof props.invoices === 'object') {
        if (props.invoices.data && Array.isArray(props.invoices.data)) {
            return props.invoices.data;
        } else if (Array.isArray(props.invoices)) {
            return props.invoices;
        }
    }
    return [];
});

// Updated pagination info computation
const paginationInfo = computed(() => {
    if (props.invoices && typeof props.invoices === 'object' &&
        'data' in props.invoices && 'current_page' in props.invoices) {
        return {
            from: props.invoices.from || 0,
            to: props.invoices.to || 0,
            total: props.invoices.total || 0,
            current_page: props.invoices.current_page || 1,
            last_page: props.invoices.last_page || 1,
            per_page: props.invoices.per_page || 20,
            links: props.invoices.links || [],
            prev_page_url: props.invoices.prev_page_url,
            next_page_url: props.invoices.next_page_url
        };
    }
    return {
        from: 0,
        to: 0,
        total: invoiceList.value.length,
        current_page: 1,
        last_page: 1,
        per_page: 20,
        links: []
    };
});

const hasActiveFilters = computed(() => {
    const filtersToCheck = { ...filters.value };
    delete filtersToCheck.per_page; // Don't consider per_page as an active filter
    return Object.values(filtersToCheck).some(value => value !== '');
});

// Methods
const getSerialNumber = (index) => {
    return (paginationInfo.value.current_page - 1) * paginationInfo.value.per_page + index + 1;
};

// Updated applyFilters method
const applyFilters = () => {
    const queryParams = {};

    Object.keys(filters.value).forEach(key => {
        if (filters.value[key] !== '') {
            queryParams[key] = filters.value[key];
        }
    });

    router.get(route('invoice.list'), queryParams, {
        preserveState: true,
        preserveScroll: false, // Scroll to top on page change
        replace: false
    });
};

// Updated clearFilters method
const clearFilters = () => {
    Object.keys(filters.value).forEach(key => {
        if (key === 'per_page') {
            filters.value[key] = '20'; // Reset to default
        } else {
            filters.value[key] = '';
        }
    });

    router.get(route('invoice.list'), { per_page: '20' }, {
        preserveState: true,
        preserveScroll: true
    });
};

const exportFiltered = () => {
    const queryParams = { ...filters.value, export: 'excel' };

    // Remove empty values
    Object.keys(queryParams).forEach(key => {
        if (queryParams[key] === '' || key === 'per_page') {
            delete queryParams[key];
        }
    });

    const url = route('invoice.export') + '?' + new URLSearchParams(queryParams).toString();
    window.open(url, '_blank');
};

const getCustomerName = (customerId) => {
    if (!customerId || !props.customers) return '';
    const customer = props.customers.find(c => c.id == customerId);
    return customer ? customer.name : '';
};

const formatDate = (date) => {
    if (!date) return '';
    try {
        return new Date(date).toLocaleDateString('en-IN');
    } catch (e) {
        return date;
    }
};

const formatAmount = (amount) => {
    if (!amount) return '0.00';
    try {
        return parseFloat(amount).toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    } catch (e) {
        return amount;
    }
};

// Existing modal methods
const openModal = (id) => {
    showModal.value = true;
    invoiceId.value = id;
    const type = 'regular';

    axios.get(`/api/fetch-invoice/${id}/${type}`)
        .then(response => {
            const invoiceData = response.data;
            no_packets.value = invoiceData.no_packets || '';
            vehicle_no.value = invoiceData.vehicle_no || '';
            dispatched_through.value = invoiceData.dispatched_through || '';
        })
        .catch(error => {
            console.error('Error fetching invoice:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Failed to fetch invoice data.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
};

const closeModal = () => {
    showModal.value = false;
    no_packets.value = '';
    vehicle_no.value = '';
    dispatched_through.value = '';
    invoiceId.value = null;
};

const submitForm = () => {
    axios.post(`/api/update-invoice-package`, {
        no_packets: no_packets.value,
        vehicle_no: vehicle_no.value,
        dispatched_through: dispatched_through.value,
        invoice_id: invoiceId.value,
        type: 'regular'
    })
    .then(response => {
        Swal.fire({
            title: 'Success!',
            text: 'Package updated successfully.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            location.reload();
        });
        closeModal();
    })
    .catch(error => {
        console.error('Error updating package:', error);
        Swal.fire({
            title: 'Error!',
            text: 'There was an error updating the package.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
};

const openPayBillModal = (id) => {
    PayBillModal.value = true;
    invoiceId.value = id;
    const type = 'regular';

    axios.get(`/api/fetch-invoice/${id}/${type}`)
        .then(response => {
            invoiceNumber.value = response.data.invoice_number;
        });

    axios.get(`/api/fetch-payment-types`)
        .then(response => {
            paymentTypes.value = response.data;
        })
        .catch(error => {
            console.error('Error fetching payment types:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Failed to fetch payment types.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
};

const closePayBillModal = () => {
    PayBillModal.value = false;
    invoiceNumber.value = '';
    payment_method_id.value = '';
    reference_no.value = '';
    amount.value = '';
    remark.value = '';
    invoiceId.value = null;
};

const submitPayBillForm = () => {
    axios.post(`/api/pay-bill`, {
        payment_method_id: payment_method_id.value,
        reference_no: reference_no.value,
        amount: amount.value,
        remark: remark.value,
        invoice_id: invoiceId.value,
        type: 'regular'
    })
    .then(response => {
        if(response.data.status == 1) {
            Swal.fire({
                title: 'Success!',
                text: response.data.message,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                title: 'Info!',
                text: response.data.message,
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }
        closePayBillModal();
    })
    .catch(error => {
        console.error('Error paying bill:', error);
        Swal.fire({
            title: 'Error!',
            text: 'There was an error paying the bill.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
};

// Updated initialization to include per_page
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    Object.keys(filters.value).forEach(key => {
        if (urlParams.has(key)) {
            filters.value[key] = urlParams.get(key);
        }
    });

    // Set default per_page if not present
    if (!urlParams.has('per_page')) {
        filters.value.per_page = '20';
    }
});

// Updated watch to exclude per_page from auto-apply (since it has @change handler)
let filterTimeout = null;
watch(filters, (newFilters, oldFilters) => {
    // Don't auto-apply if only per_page changed (it has its own handler)
    const filtersWithoutPerPage = { ...newFilters };
    const oldFiltersWithoutPerPage = { ...oldFilters };
    delete filtersWithoutPerPage.per_page;
    delete oldFiltersWithoutPerPage.per_page;

    if (JSON.stringify(filtersWithoutPerPage) !== JSON.stringify(oldFiltersWithoutPerPage)) {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            if (hasActiveFilters.value) {
                applyFilters();
            }
        }, 1000);
    }
}, { deep: true });

</script>
