<template>
    <Head title="Company" />
    <AuthenticatedLayout>
        <form
            @submit.prevent="getcustomerdetail"
            method="post"
            :class="company_list ? (props.customer_data ? 'hidden' : '') : ''"
        >
            <div class="container p-4 card">
                <div class="d-flex justify-content-evenly row">
                    <Dropdown
                        v-model="form.customer_id"
                        :options="customer_list"
                        filter
                        optionLabel="name"
                        placeholder="Select a Customer"
                        class="h-12 mb-1 col-md-4"
                    />
                    <Dropdown
                        v-model="form.company_id"
                        :options="company_list"
                        filter
                        optionLabel="name"
                        placeholder="Select a Company"
                        class="h-12 mb-1 col-md-4"
                    />
                    <PrimaryButton
                        :disabled="form.processing"
                        class="h-12 col-md-2"
                    >
                        Search</PrimaryButton
                    >
                </div>
            </div>
        </form>
        <CustomerDetailVue
            :billing_user_detail="data"
            :series="series"
            :company="company_id"
            :customer="customer_id"
            :company_name="CompanyName"
            :class="props.customer_data ? '' : 'hidden'"
        />
        <div :class="props.customer_data ? '' : 'hidden'">
            <Editable />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import Editable from "./Partials/Editable.vue";
import CustomerDetailVue from "./Partials/CustomerDetail.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Dropdown from "primevue/dropdown";
import { Link, useForm, usePage } from "@inertiajs/vue3";
const props = defineProps({
    customer_list: Array,
    company_list: Array,
    customer_data: Object,
    inv_no: String,
    company: Object,
    customer_id: Number,
    company_id: Number,
});
const series = props.inv_no ? props.inv_no : "";
const CompanyName = props.company ? props.company.company_name : "";
const data = props.customer_data ? props.customer_data.customer_detail : "";
const customer_id = props.customer_id ? props.customer_id: "";
const company_id = props.company_id ? props.company_id : "";
const form = useForm({
    company_id: "",
    customer_id: "",
});

function getcustomerdetail() {
    form.get(route("performa.customer.detail.bill"), {
        preserveScroll: true,
    });
}
</script>
