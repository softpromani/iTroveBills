<template>
    <Head title="Customers" />

    <AuthenticatedLayout>
        <template #header> Seller Customers </template>

        <div class="container">
            <div class="row">
                <div class="col-md-12 rounded-lg bg-white shadow-md pb-3">
                    <form class="mt-6 space-y-6">
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <InputLabel for="name" value="Customer Name" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>
                            <div class="col-md-6 mt-2">
                                <InputLabel
                                    for="email"
                                    value="Customer email"
                                />

                                <TextInput
                                    id="email"
                                    type="text"
                                    class="mt-1 block w-full"
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
                            <div class="col-md-6 mt-2">
                                <InputLabel
                                    for="mobile"
                                    value="Customer mobile"
                                />

                                <TextInput
                                    id="mobile"
                                    type="text"
                                    class="mt-1 block w-full"
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
                            <div class="col-md-6 mt-2 pt-4">

                                <div v-for="item in inv_tax_type" :key="item.id"  class="form-check form-check-inline">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="inv_tax_type"
                                        :value="item.id"
                                        v-model="form.tax_type"
                                        :id="`inlineRadio`+item.id"
                                    />
                                    <label
                                        class="form-check-label"
                                        :for="`inlineRadio`+item.id"
                                        >{{ item.status }}</label
                                    >
                                </div>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.tax_type"
                                />
                            </div>
                            <div class="col-md-6 mt-2">
                                <InputLabel
                                    for="gst"
                                    value="Customer GST/PAN/TAN"
                                />

                                <TextInput
                                    id="gst"
                                    type="text"
                                    class="mt-1 block w-full"
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
                            <div class="col-md-6 mt-2 hidden">
                                <InputLabel for="city" value="Customer City" />

                                <TextInput
                                    id="city"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.city"
                                    required
                                    autofocus
                                    autocomplete="city"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.city"
                                />
                            </div>
                            <div class="col-md-6 mt-2">
                                <InputLabel for="pin" value="Customer pin" />

                                <TextInput
                                    id="pin"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.pin"
                                    required
                                    autofocus
                                    autocomplete="pin"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.pin"
                                />
                            </div>
                            <div class="col-12 mt-2">
                                <InputLabel
                                    for="address"
                                    value="Customer address"
                                />

                                <TextareaInput
                                    id="address"
                                    type="text"
                                    class="mt-1 block w-full"
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
                        </div>
                        <div class="flex items-center gap-4">
                            <PrimaryButton @click="update" :="form.processing"
                                >Save</PrimaryButton
                            >

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p
                                    v-if="form.recentlySuccessful"
                                    class="text-sm text-gray-600"
                                >
                                    Saved.
                                </p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
const props = defineProps({
    edit_customers: Object,
    inv_tax_type : Object,
});
let edata = props.edit_customers.customer_detail;
const form = useForm({
    name:edata.name,
    email:edata.email,
    mobile:edata.mobile,
    gst:edata.gst,
    iec:edata.iec,
    city:edata.city,
    pin:edata.pin,
    address:edata.address,
    tax_type:edata.tax_type
});

function update() {
        form.post(route("company.customer.update",props.edit_customers.id));
}
</script>
