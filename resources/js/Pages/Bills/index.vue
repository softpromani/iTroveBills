<template>
    <Head title="Company" />
    <AuthenticatedLayout>
        <form
            @submit.prevent="getcustomerdetail"
            method="get"
        >
            <div class="container p-4 card">
                <div class="d-flex justify-content-evenly row">
                <div class="row">
                    <div class="col-md-5">
                        <Dropdown
                            v-model="form.company_id"
                            :options="company_list"
                            filter
                            optionLabel="name"
                            placeholder="Select a Company"
                            class="mb-1 h-12 w-100"
                            showClear
                        />
                        <InputError :message="form.errors.company_id" class="mt-1" />
                    </div>
                    <div class="col-md-5">
                        <Dropdown
                            v-model="form.customer_id"
                            :options="customer_list"
                            filter
                            optionLabel="name"
                            placeholder="Select a Customer"
                            class="mb-1 h-12 w-100"
                            showClear
                        />
                        <InputError :message="form.errors.customer_id" class="mt-1" />
                    </div>
                    <div class="col-md-2">
                        <PrimaryButton
                            :disabled="form.processing"
                            class="h-12 w-100"
                        >
                            Search
                        </PrimaryButton>
                    </div>
                </div>
                </div>
            </div>
        </form>
        
        <CustomerDetailVue
            :billing_user_detail="currentCustomerDetail"
            :series="series"
            :company="company_id"
            :customer="customer_id"
            :company_name="CompanyName"
        />
        <div>
            <Editable />
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import Editable from "./Partials/Editable.vue";
import CustomerDetailVue from "./Partials/CustomerDetail.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Dropdown from "primevue/dropdown";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    customer_list: Array,
    company_list: Array,
    customer_data: Object,
    inv_no: String,
    company: Object,
    customer_id: Number,
    company_id: Number,
});

const series = computed(() => props.inv_no ? props.inv_no : "");
const CompanyName = computed(() => props.company ? props.company.company_name : "");
const currentCustomerDetail = computed(() => props.customer_data ? props.customer_data.customer_detail : null);

const form = useForm({
    company_id: props.company_id ? props.company_list.find(c => c.id === props.company_id) : null,
    customer_id: props.customer_id ? props.customer_list.find(c => c.id === props.customer_id) : null,
});

function getcustomerdetail() {
    form.clearErrors();
    if (!form.company_id) {
        form.setError('company_id', 'Please select a company to proceed.');
        return;
    }

    router.get(route("customer.bill"), {
        customer_id: form.customer_id ? form.customer_id.id : null,
        company_id: form.company_id ? form.company_id.id : null,
    }, {
        preserveState: true,
        preserveScroll: true,
        only: ['customer_data', 'inv_no', 'company', 'series', 'customer_id', 'company_id'] 
    });
}

watch(() => [form.customer_id, form.company_id], ([newCust, newComp]) => {
    // Only fetch if we have a company selection (often required for series generation)
    // But user might want to select customer first. 'index' controller handles partials?
    // Controller logic requires BOTH currently: if ($request->filled('customer_id') && $request->filled('company_id'))
    // So we wait for both.
    if (newComp) {
        getcustomerdetail();
    }
});

// Update form local state if props change from outside (e.g. valid reload)
watch(() => props.customer_id, (val) => {
    if (val && (!form.customer_id || form.customer_id.id !== val)) {
         form.customer_id = props.customer_list.find(c => c.id === val);
    }
});
watch(() => props.company_id, (val) => {
    if (val && (!form.company_id || form.company_id.id !== val)) {
         form.company_id = props.company_list.find(c => c.id === val);
    }
});
</script>
