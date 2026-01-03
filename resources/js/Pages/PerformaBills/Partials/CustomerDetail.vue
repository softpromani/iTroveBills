<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
const currentDate = ref("");
const showCard = ref(true);
const updateCurrentDate = () => {
    const now = new Date();
    currentDate.value = now.toLocaleDateString();
};
updateCurrentDate();
const toggleCard = () => {
    showCard.value = !showCard.value;
};
const props = defineProps({
    billing_user_detail: Object,
    series: String,
    customer: Number,
    company: Number,
    company_name:String
});
const form = useForm({
    invoice_no: props.series ?? "",
    date: currentDate.value,
    gst: props.billing_user_detail?.gst ?? "",
    email: props.billing_user_detail?.email ?? "",
    address: props.billing_user_detail?.address ?? "",
    mobile: props.billing_user_detail?.mobile ?? "",
    customer_name: props.billing_user_detail?.name ?? "",
    customer: props.customer ?? "",
    company: props.company ?? "",
});

// Watch for prop changes to update form
watch(() => props.series, (val) => form.invoice_no = val);
watch(() => props.company, (val) => form.company = val);
watch(() => props.customer, (val) => form.customer = val);
watch(() => props.billing_user_detail, (val) => {
    if (val) {
        form.gst = val.gst ?? "";
        form.email = val.email ?? "";
        form.address = val.address ?? "";
        form.mobile = val.mobile ?? "";
        form.customer_name = val.name ?? "";
    } else {
        // Clear fields if no customer selected
        form.gst = "";
        form.email = "";
        form.address = "";
        form.mobile = "";
        form.customer_name = "";
    }
}, { deep: true });
</script>
<template>
    <section class="container-fluid">
        <div class="row">
            <div class="mt-2 bg-purple-700 rounded-md position-relative py-2 px-3">
                <h2
                    class="text-sm font-medium text-center text-white uppercase font-weight-bolder mb-0"
                >
               {{ props.company_name ?? "Itrove Bills"}}
                </h2>
                <span
                    class="text-white cursor-pointer top-2 right-3 position-absolute"
                    @click="toggleCard"
                >
                    <i class="fa-solid fa-bullseye fa-lg"></i>
                </span>
            </div>
            <transition name="slide-fade">
                <div class="card" v-if="showCard">
                    <form action="" method="post">
                        <div class="p-2 row">
                            <div class="mt-2 col-md-6">
                                <InputLabel
                                    for="invoice_no"
                                    value="Invoice No*"
                                />
                                <TextInput
                                    id="invoice_no"
                                    type="text"
                                    disabled
                                    class="block w-full mt-1"
                                    v-model="form.invoice_no"
                                    required
                                    autofocus
                                    autocomplete="invoice_no"
                                />
                                <TextInput
                                    id="company"
                                    type="text"
                                    hidden
                                    class="block w-full mt-1"
                                    v-model="form.company"
                                    required
                                    autofocus
                                />
                                <TextInput
                                    id="customer"
                                    type="text"
                                    hidden
                                    class="block w-full mt-1"
                                    v-model="form.customer"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.invoice_no"
                                />
                            </div>
                            <div class="mt-2 col-md-6">
                                <InputLabel for="date" value="Date*" />
                                <TextInput
                                    id="date"
                                    type="text"
                                    disabled
                                    class="block w-full mt-1"
                                    v-model="form.date"
                                    required
                                    autofocus
                                    autocomplete="date"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.date"
                                />
                            </div>
                            <div class="mt-2 col-md-6">
                                <InputLabel
                                    for="customer_name"
                                    value="Customer Name*"
                                />
                                <TextInput
                                    id="customer_name"
                                    type="text"
                                    class="block w-full mt-1"
                                    v-model="form.customer_name"
                                    required
                                    autofocus
                                    autocomplete="customer_name"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.customer_name"
                                />
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

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.mobile"
                                />
                            </div>
                            <div class="mt-2 col-md-6">
                                <InputLabel for="address" value="Address*" />
                                <TextInput
                                    id="address"
                                    type="text"
                                    class="block w-full mt-1"
                                    v-model="form.address"
                                    required
                                    autofocus
                                    autocomplete="address"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.address"
                                />
                            </div>
                            <div class="mt-2 col-md-6">
                                <InputLabel for="email" value="Email ID*" />
                                <TextInput
                                    id="email"
                                    type="text"
                                    class="block w-full mt-1"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="email"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.email"
                                />
                            </div>
                            <div class="mt-2 col-md-6">
                                <InputLabel
                                    for="gst"
                                    value="ID/ TPIN/ TIN NO*"
                                />
                                <TextInput
                                    id="gst"
                                    type="text"
                                    class="block w-full mt-1"
                                    v-model="form.gst"
                                    required
                                    autofocus
                                    autocomplete="gst"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.gst"
                                />
                            </div>
                            <div class="mt-2 col-md-6">
                                <InputLabel
                                    for="vehical_no"
                                    value="Vehicle No*"
                                />
                                <TextInput
                                    id="vehical_no"
                                    type="text"
                                    class="block w-full mt-1"
                                    v-model="form.vehical_no"
                                    required
                                    autofocus
                                    autocomplete="vehical_no"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.vehical_no"
                                />
                            </div>
                            <div class="mt-2 col-md-6">
                                <InputLabel
                                    for="delivery_in_days"
                                    value="Delivery In Days"
                                />
                                <TextInput
                                    id="delivery_in_days"
                                    type="text"
                                    class="block w-full mt-1"
                                    v-model="form.delivery_in_days"
                                    placeholder="e.g. 5-7 Days"
                                    autofocus
                                    autocomplete="delivery_in_days"
                                />
                            </div>
                        </div>
                    </form>
                </div>
            </transition>
        </div>
    </section>
</template>
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
