<template>
    <Head title="Customer view" />
    <AuthenticatedLayout>
        <div
            class="mb-4 inline-flex w-full overflow-hidden rounded-lg bg-white shadow-md"
        >
            <div class="flex w-12 items-center justify-center bg-pink-800">
                <svg
                    class="h-6 w-6 fill-current text-white"
                    viewBox="0 0 40 40"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM21.6667 28.3333H18.3334V25H21.6667V28.3333ZM21.6667 21.6666H18.3334V11.6666H21.6667V21.6666Z"
                    ></path>
                </svg>
            </div>

            <div class="-mx-3 px-4 py-2">
                <div class="mx-3">
                    <span class="font-semibold text-blue-500"
                        >Sample customer View Info</span
                    >
                </div>
            </div>
        </div>
        <div class="shadow rounded-lg bg-white">
            <div class="table-responsive rounded-lg">
                <table class="table" style="min-height: 200px">
                    <thead class="table-dark">
                        <tr>
                            <th>Sr.no.</th>
                            <th>invoice_number</th>
                            <th>invoice_date</th>
                            <th>total_ammount</th>
                            <th>paid_ammount</th>
                            <th>payment_status</th>
                            <th>total_weight</th>
                            <th>vehicle_no</th>
                            <th>no_packets</th>
                            <th>customer_id</th>
                            <th>company_id</th>
                            <th>Action</th>
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
                            <td>{{ invoice.paid_ammount ?? "" }}</td>
                            <td>
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-3 py-1 rounded-full dark:bg-blue-900 dark:text-blue-300"
                                    >{{ invoice.payment_status.status }}</span
                                >
                            </td>
                            <td>{{ invoice.total_weight ?? "" }}</td>
                            <td>{{ invoice.vehicle_no ?? "" }}</td>
                            <td>{{ invoice.no_packets ?? "NO PACK" }}</td>
                            <td>{{ invoice.customer.name ?? "" }}</td>
                            <td>{{ invoice.company.company_name ?? "" }}</td>
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
                                            <Link class="dropdown-item"
                                            :href="route('customer.bill.edit')"
                                                method="get"
                                                :data="{ invoice_id: invoice.id }"
                                                ><i
                                                    class="fa-solid fa-pen-to-square"
                                                    style="color: blueviolet"
                                                ></i>
                                                Edit</Link
                                            >
                                        </li>
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
                                            <Link class="dropdown-item"
                                                ><i
                                                    class="fa fa-envelope-square"
                                                    aria-hidden="true"
                                                    style="
                                                        color: rgb(245, 180, 0);
                                                    "
                                                ></i>
                                                Mail</Link
                                            >
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div
                class="flex flex-col items-center border-t bg-white px-1 py-1 xs:flex-row xs:justify-between"
            >
                <!-- <pagination :links="invoices.links" /> -->
            </div>
        </div>

        <Modal :show="confirmCustomerDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete your Customer?
                </h2>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{
                            'opacity-25': form.processing,
                        }"
                        :disabled="form.processing"
                        @click="deleteCustomer"
                    >
                        Delete Customer
                    </DangerButton>
                </div>
            </div>
        </Modal>
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
const props = defineProps({
    invoices: Object,
});
const confirmCustomerDeletion = ref(false);
const form = useForm({});
let delete_id = null;
const confirmUserDeletion = (id) => {
    delete_id = id;
    confirmCustomerDeletion.value = true;
};
const closeModal = () => {
    confirmCustomerDeletion.value = false;
};

const deleteCustomer = () => {
    form.post(route("company.customer.delete", delete_id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};
</script>
