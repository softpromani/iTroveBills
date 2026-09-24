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
                                        'bg-red-100 text-red-800': (invoice.payment?.status || 'due') === 'due',
                                        'bg-blue-100 text-blue-800': invoice.payment?.status === 'partial-paid',
                                        'bg-green-100 text-green-800': invoice.payment?.status === 'paid'
                                      }"
                                    >
                                      {{ invoice.payment?.status || invoice.paymentStatus?.status || 'unpaid' }}
                                    </span>
                                </td>
                                <td>{{ invoice.vehicle_no || "N/A" }}</td>
                                <td>{{ invoice.no_packets || "NO PACK" }}</td>
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
                                                <a @click.prevent="sendMail('book.bill.sendmail', invoice.id)" class="dropdown-item" href="#">
                                                    <i class="fa fa-envelope-square" aria-hidden="true" style="color: rgb(245, 180, 0);"></i>
                                                    Mail (Buyer)
                                                </a>
                                            </li>
                                            <li>
                                                <Link class="dropdown-item" :href="route('create.eway.bill')" method="get" :data="{ invoice_id: invoice.id, type: 'plain' }">
                                                    <i class="fa fa-shipping-fast" aria-hidden="true" style="color: rgb(245, 180, 0);"></i>
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
                                            <li>
                                                <a @click.prevent="openPaymentHistoryModal(invoice.id)" class="dropdown-item" href="#">
                                                    <i class="fa fa-history" aria-hidden="true" style="color: rgb(0, 123, 255);"></i>
                                                    Payment History
                                                </a>
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

        <!-- Package Update Modal Component -->
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

        <!-- Modal Backdrop -->
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
                                    <input
                                      class="form-check-input"
                                      type="radio"
                                      :id="type.name"
                                      :value="type.id"
                                      v-model="payment_method_id"
                                    >
                                    <label class="form-check-label" :for="type.name">{{ type.name }}</label>
                                </div>
                            </fieldset>

                            <div class="form-group mb-3">
                                <label for="reference_no">Reference Number</label>
                                <input type="text" class="form-control" id="reference_no" v-model="reference_no" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="amount">Amount (Remaining: ₹{{ formatAmount(remainingBalance) }})</label>
                                <input type="number" step="0.01" class="form-control" id="amount" v-model="amount" :max="remainingBalance" required>
                                <small v-if="amount > remainingBalance" class="text-danger">Amount exceeds remaining balance!</small>
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

        <!-- Payment History Modal -->
        <div v-if="PaymentHistoryModal" class="modal fade show" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payment History - {{ invoiceNumber }}</h5>
                        <button type="button" class="close" @click="closePaymentHistoryModal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Method</th>
                                        <th>Ref No</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="history in paymentHistories" :key="history.id">
                                        <td>{{ new Date(history.created_at).toLocaleDateString() }}</td>
                                        <td>{{ history.payment_type?.name }}</td>
                                        <td>{{ history.reference_no }}</td>
                                        <td>₹{{ formatAmount(history.amount) }}</td>
                                        <td>
                                            <button @click="editHistory(history)" class="btn btn-sm btn-info mr-1">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button @click="deleteHistory(history.id)" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="paymentHistories.length === 0">
                                        <td colspan="5" class="text-center">No payment records found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Edit Section -->
                        <div v-if="editingHistory" class="border-top mt-3 pt-3">
                            <h6>Edit Payment Record</h6>
                            <form @submit.prevent="submitUpdateHistoryForm">
                                <div class="row">
                                    <div class="col-md-6 form-group mb-2">
                                        <label>Method</label>
                                        <select class="form-control" v-model="editForm.payment_method_id" required>
                                            <option v-for="type in paymentTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group mb-2">
                                        <label>Reference Number</label>
                                        <input type="text" class="form-control" v-model="editForm.reference_no" required>
                                    </div>
                                    <div class="col-md-6 form-group mb-2">
                                        <label>Amount (Remaining: ₹{{ formatAmount(remainingBalance + (editingHistory ? editingHistory.amount : 0)) }})</label>
                                        <input type="number" step="0.01" class="form-control" v-model="editForm.amount" required>
                                    </div>
                                    <div class="col-md-6 form-group mb-2">
                                        <label>Remark</label>
                                        <input type="text" class="form-control" v-model="editForm.remark">
                                    </div>
                                </div>
                                <div class="mt-2 text-right">
                                    <button type="button" class="btn btn-secondary mr-2" @click="editingHistory = null">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closePaymentHistoryModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="PaymentHistoryModal" class="modal-backdrop fade show"></div>

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
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
    invoices: Array,
    customers: Array,
    needs_auth: Boolean,
});

const sendMail = (routeName, invoiceId) => {
  Swal.fire({
    title: 'Sending Mail...',
    text: 'Please wait while the invoice is being sent.',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });

  router.post(route(routeName), { invoice_id: invoiceId }, {
    onFinish: () => {
      Swal.close();
    },
    onError: () => {
      Swal.close();
    }
  });
};

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

// Reactive variables for package update modal
const showModal = ref(false);
const no_packets = ref('');
const vehicle_no = ref('');
const dispatched_through = ref('');
const invoiceId = ref(null);

// Reactive variables for Pay Bill modal
const PayBillModal = ref(false);
const PaymentHistoryModal = ref(false);
const invoiceNumber = ref('');
const reference_no = ref('');
const payment_method_id = ref('');
const amount = ref('');
const remark = ref('');
const remainingBalance = ref(0);
const paymentTypes = ref([]);
const paymentHistories = ref([]);
const editingHistory = ref(null);
const editForm = ref({
    id: null,
    payment_method_id: '',
    reference_no: '',
    amount: '',
    remark: ''
});

// Method to open the modal and fetch invoice data
const openModal = (id) => {
    showModal.value = true;
    invoiceId.value = id;
    const type = 'plain';

    axios.get(`/api/fetch-invoice/${id}/${type}`)
        .then(response => {
            const invoiceData = response.data;
            no_packets.value = invoiceData.no_packets || '';
            vehicle_no.value = invoiceData.vehicle_no || '';
            dispatched_through.value = invoiceData.dispatched_through || '';
            invoiceId.value = invoiceData.id || '';
        })
        .catch(error => {
            console.error('Error fetching invoice:', error);
        });
};

// Method to close the modal and reset fields
const closeModal = () => {
    showModal.value = false;
    no_packets.value = '';
    vehicle_no.value = '';
    dispatched_through.value = '';
    invoiceId.value = null;
};

// Method to submit the form and update the invoice package
const submitForm = () => {
    axios.post(`/api/update-invoice-package`, {
        no_packets: no_packets.value,
        vehicle_no: vehicle_no.value,
        dispatched_through: dispatched_through.value,
        invoice_id: invoiceId.value,
        type: 'plain'
    })
    .then(response => {
        console.log('Package updated successfully:', response.data);
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
    const type = 'plain';

    axios.get(`/api/fetch-invoice/${id}/${type}`)
        .then(response => {
            invoiceNumber.value = response.data.invoice_number;
            const payment = response.data.payment;
            if (payment) {
                remainingBalance.value = Math.max(0, payment.total_amount - payment.paid_amount);
                amount.value = remainingBalance.value;
            } else {
                const total = response.data.total_ammount;
                remainingBalance.value = total;
                amount.value = total;
            }
        });

    axios.get(`/api/fetch-payment-types`)
        .then(response => {
            paymentTypes.value = response.data;
        })
        .catch(error => {
            console.error('Error fetching payment types:', error);
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
    if (amount.value > remainingBalance.value) {
        Swal.fire({
            title: 'Overpayment Warning',
            text: `The amount ₹${formatAmount(amount.value)} exceeds the remaining balance ₹${formatAmount(remainingBalance.value)}. Please adjust.`,
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    axios.post(`/api/pay-bill`, {
        payment_method_id: payment_method_id.value,
        reference_no: reference_no.value,
        amount: amount.value,
        remark: remark.value,
        invoice_id: invoiceId.value,
        type: 'plain'
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

const openPaymentHistoryModal = (id) => {
    PaymentHistoryModal.value = true;
    invoiceId.value = id;
    const type = 'plain';

    axios.get(`/api/fetch-invoice/${id}/${type}`)
        .then(response => {
            invoiceNumber.value = response.data.invoice_number;
            const payment = response.data.payment;
            remainingBalance.value = payment ? Math.max(0, payment.total_amount - payment.paid_amount) : 0;
        });

    fetchHistories(id);
    
    if (paymentTypes.value.length === 0) {
        axios.get(`/api/fetch-payment-types`)
            .then(response => {
                paymentTypes.value = response.data;
            });
    }
};

const fetchHistories = (id) => {
    axios.get(`/api/fetch-payment-history/${id}/plain`)
        .then(response => {
            paymentHistories.value = response.data;
        });
};

const closePaymentHistoryModal = () => {
    PaymentHistoryModal.value = false;
    paymentHistories.value = [];
    editingHistory.value = null;
    invoiceId.value = null;
};

const editHistory = (history) => {
    editingHistory.value = history;
    editForm.value = {
        id: history.id,
        payment_method_id: history.payment_type_id,
        reference_no: history.reference_no,
        amount: history.amount,
        remark: history.remark
    };
};

const submitUpdateHistoryForm = () => {
    const currentRemainingPlusThis = remainingBalance.value + editingHistory.value.amount;
    if (editForm.value.amount > currentRemainingPlusThis) {
        Swal.fire({
            title: 'Overpayment Warning',
            text: `The amount ₹${formatAmount(editForm.value.amount)} exceeds the allowed balance ₹${formatAmount(currentRemainingPlusThis)}.`,
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    axios.post(`/api/update-payment-history`, editForm.value)
        .then(response => {
            if (response.data.status == 1) {
                Swal.fire({
                    title: 'Success!',
                    text: response.data.message,
                    icon: 'success'
                });
                fetchHistories(invoiceId.value);
                editingHistory.value = null;
                // Refresh remaining balance
                axios.get(`/api/fetch-invoice/${invoiceId.value}/plain`).then(res => {
                    const payment = res.data.payment;
                    remainingBalance.value = payment ? Math.max(0, payment.total_amount - payment.paid_amount) : 0;
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: response.data.message,
                    icon: 'error'
                });
            }
        });
};

const deleteHistory = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/api/delete-payment-history/${id}`)
                .then(response => {
                    Swal.fire(
                        'Deleted!',
                        response.data.message,
                        'success'
                    );
                    fetchHistories(invoiceId.value);
                    // Refresh remaining balance
                    axios.get(`/api/fetch-invoice/${invoiceId.value}/plain`).then(res => {
                        const payment = res.data.payment;
                        remainingBalance.value = payment ? Math.max(0, payment.total_amount - payment.paid_amount) : 0;
                    });
                });
        }
    });
};

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
