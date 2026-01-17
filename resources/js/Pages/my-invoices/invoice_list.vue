<template>
    <Head title="Customer view" />
    <AuthenticatedLayout>
        <div
            class="inline-flex w-full mb-4 overflow-hidden bg-white rounded-lg shadow-md"
        >
            <div class="flex items-center justify-center w-12 bg-pink-800">
                <svg
                    class="w-6 h-6 text-white fill-current"
                    viewBox="0 0 40 40"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z"
                    ></path>
                </svg>
            </div>

            <div class="px-4 py-2 -mx-3">
                <div class="mx-3">
                    <span class="font-semibold text-blue-500"
                        >My Invoice List</span
                    >
                </div>
            </div>
        </div>
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
                            <th class="col-1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(invoice, index) in invoices"
                            :key="invoice.id"
                        >
                            <td>{{ index + 1 }}</td>
                            <td>{{ invoice.invoice_number ?? "" }}</td>
                            <td>{{ invoice.invoice_date ?? "" }}</td>
                            <td>{{ invoice.total_ammount ?? "" }}</td>
                            <td>{{ invoice.payment.paid_amount ?? "" }}</td>
                            <td>
                                <span
                                  class="px-3 py-1 mr-2 text-xs font-medium rounded-full"
                                  :class="{
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': invoice.payment.status === 'due',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': invoice.payment.status === 'partial-paid',
                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': invoice.payment.status === 'paid'
                                  }"
                                >
                                  {{ invoice.payment.status }}
                                </span>
                            </td>

                            <td>
                                <div class="dropdown">
                                    <span
                                        class="p-2"
                                        style="font-size: 15px; cursor: pointer"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                    >
                                        <i class="fa fa-ellipsis-v"></i>
                                    </span>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <Link
                                                :href="route('view.invoice')"
                                                method="get"
                                                :data="{ invoice_id: invoice.id }"
                                                class="dropdown-item"
                                                ><i
                                                    class="fa fa-print"
                                                    aria-hidden="true"
                                                    style="
                                                        color: rgb(241, 12, 12);
                                                    "
                                                ></i>
                                                Print</Link
                                            >
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
            <div
                class="flex flex-col items-center px-1 py-1 bg-white border-t xs:flex-row xs:justify-between"
            >
                <!-- <pagination :links="invoices.links" /> -->
            </div>
        </div>



     <!-- Modal Component -->
     <div v-if="PayBillModal" class="modal fade show" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Pay Bill</h5>
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
                    <input
                      type="text"
                      class="form-control"
                      id="reference_no"
                      v-model="reference_no"
                      required>
                  </div>
                  <div class="form-group mb-3">
                    <label for="reference_no">Amount</label>
                    <input
                      type="number"
                      class="form-control"
                      id="reference_no"
                      v-model="amount"
                      required>
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

    <!-- Modal Backdrop -->
    <div v-if="PayBillModal" class="modal-backdrop fade show"></div>


    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import PrimaryButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import axios from 'axios';
import Swal from 'sweetalert2';

const props = defineProps({
  invoices: Object,
});

// Reactive variables for pay bill modal and form fields
const PayBillModal = ref(false);
const reference_no = ref('');
const payment_method_id = ref('');
const amount = ref('');
const remark = ref('');
const paymentTypes = ref([]);

// Method to close the pay bill modal and reset fields
const closePayBillModal = () => {
  PayBillModal.value = false;
  payment_method_id.value = '';
  reference_no.value = '';
  amount.value = '';
  remark.value = '';
};

// Method to open the modal and fetch invoice data
const openPayBillModal = (id) => {
  PayBillModal.value = true;
  invoiceId.value = id;

  axios.get(`/api/fetch-payment-types`)
    .then(response => {
      paymentTypes.value = response.data;
    })
    .catch(error => {
      console.error('Error fetching types:', error);
    });

};

// Method to submit the form and update the invoice
const submitPayBillForm = () => {
  axios.post(`/api/pay-bill`, {
    payment_method_id: payment_method_id.value,
    reference_no: reference_no.value,
    amount: amount.value,
    remark: remark.value,
    invoice_id: invoiceId.value
  })
  .then(response => {
    console.log('Bill paid successfully:', response);
    if(response.data.status == 1)
    {
        Swal.fire({
        title: 'Success!',
        text: response.data.message,
        icon: 'success',
        confirmButtonText: 'OK'
        }).then(() => {
        setTimeout(() => {
            location.reload();
        }, 5000);
        });
    }
    else{
        Swal.fire({
        title: 'Info!',
        text: response.data.message,
        icon: 'warning',
        confirmButtonText: 'OK'
        }).then(() => {
        setTimeout(() => {
            location.reload();
        }, 5000);
        });
    }

    closePayBillModal();

  })
  .catch(error => {
    console.error('Error paying bill:', error);

    // Show error message using SweetAlert
    Swal.fire({
      title: 'Error!',
      text: 'There was an error paying the bill.',
      icon: 'error',
      confirmButtonText: 'OK'
    });
  });
};

</script>

