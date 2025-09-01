<template>
    <Head title="Company" />
    <AuthenticatedLayout>
        <section class="container-fluid">
            <div class="mt-2 -mb-2 bg-purple-700 rounded-md position-relative">
                <h2 class="font-medium text-center text-white uppercase font-weight-bolder">
                    ITrove Bills
                </h2>
                <span class="text-white cursor-pointer top-2 right-2 position-absolute" @click="toggleCard">
                    <i class="fa-solid fa-bullseye"></i>
                </span>
            </div>
            <transition name="slide-fade">
                <div class="card" v-if="showCard">
                    <form @submit.prevent="updatebill">
                        <div class="p-2 row">
                            <div class="mt-2 col-md-6">
                                <InputLabel for="invoice_no" value="Invoice No*" />
                                <TextInput
                                    id="invoice_no"
                                    type="text"
                                    v-model="form.invoice_no"
                                    class="block w-full mt-1"
                                    required
                                    autofocus
                                    autocomplete="invoice_no"
                                />
                                <InputError class="mt-2" :message="form.errors.invoice_no" />
                            </div>
                            <div class="mt-2 col-md-6">
                                <InputLabel for="customer_name" value="Customer Name*" />
                                <TextInput
                                    id="customer_name"
                                    type="text"
                                    v-model="form.customer_name"
                                    class="block w-full mt-1"
                                    required
                                    autofocus
                                    autocomplete="customer_name"
                                />
                                <InputError class="mt-2" :message="form.errors.customer_name" />
                            </div>
                            <div class="mt-2 col-md-6">
                                <InputLabel for="mobile" value="Mobile No" />
                                <TextInput
                                    id="mobile"
                                    type="text"
                                    class="block w-full mt-1"
                                    v-model="form.mobile"
                                    required
                                    autofocus
                                    autocomplete="mobile"
                                />
                                <InputError class="mt-2" :message="form.errors.mobile" />
                            </div>
                            <div class="mt-2 col-md-6">
                                <InputLabel for="address" value="Address*" />
                                <TextInput
                                    id="address"
                                    type="text"
                                    class="block w-full mt-1"
                                    required
                                    v-model="form.address"
                                    autofocus
                                    autocomplete="address"
                                />
                                <InputError class="mt-2" :message="form.errors.address" />
                            </div>
                            <div class="mt-2 col-md-6">
                                <InputLabel for="email" value="Email ID*" />
                                <TextInput
                                    id="email"
                                    type="text"
                                    v-model="form.email"
                                    class="block w-full mt-1"
                                    required
                                    autofocus
                                    autocomplete="email"
                                />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                            <div class="mt-2 col-md-6">
                                <InputLabel for="gst" value="ID/ TPIN/ TIN NO*" />
                                <TextInput
                                    id="gst"
                                    type="text"
                                    v-model="form.gst"
                                    class="block w-full mt-1"
                                    required
                                    autofocus
                                    autocomplete="gst"
                                />
                                <InputError class="mt-2" :message="form.errors.gst" />
                            </div>
                            <div class="mt-2 col-md-6">
                                <InputLabel for="vehical_no" value="Vehicle No*" />
                                <TextInput
                                    id="vehical_no"
                                    type="text"
                                    class="block w-full mt-1"
                                    required
                                    v-model="form.vehical_no"
                                    autofocus
                                    autocomplete="vehical_no"
                                />
                                <InputError class="mt-2" :message="form.errors.vehical_no" />
                            </div>
                        </div>
                    </form>
                </div>
            </transition>
        </section>
        <div>
            <Editbilldata :invoicedetails="form" :invoiceitem="edit_data.invoiceitems" :invoice-id="edit_data.id" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import Editbilldata from "./Partials/Editbilldata.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

const showCard = ref(true);
const props = defineProps({
    edit_data: Object,
});

const toggleCard = () => {
    showCard.value = !showCard.value;
};

const form = useForm({
    invoice_no: props.edit_data.invoice_number || '',
    gst: props.edit_data.customer?.gstin || '',
    email: props.edit_data.customer?.email || '',
    address: props.edit_data.customer?.address || '',
    mobile: props.edit_data.customer?.mobile || '',
    vehical_no: props.edit_data.vehicle_no || '',
    customer_name: props.edit_data.customer?.company_name || '',
    customer: props.edit_data.customer_company_id || '',
    company: props.edit_data.company_id || '',
});

const updatebill = () => {
    // This will be handled by the child component
    console.log('Update bill called from parent');
};
</script>

<style>
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}
.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>
