<template>
    <Head title="Customers" />

    <AuthenticatedLayout>
        <template #header> Seller Customers </template>

        <div class="container">
            <div class="row">
                <div class="p-4 mb-2 bg-white rounded-lg shadow-md form-group">
                    <label for="search">Search Customers</label>
                    <div class="mb-3 input-group">
                        <input
                            type="text"
                            class="form-control"
                            id="search"
                            placeholder="search customer"
                            aria-label="search customer"
                            aria-describedby="button-addon2"
                            v-model="enteredValue"
                        />
                        <button
                            class="btn btn-outline-secondary"
                            type="button"
                            id="button-addon2"
                            @click="handleInput"
                        >
                            Search
                        </button>
                    </div>
                </div>
                <div class="pb-3 bg-white rounded-lg shadow-md col-md-12">
                    <form @submit.prevent="submit" method="post" class="mt-6 space-y-6">
                        <div class="row">
                            <div class="mt-2 col-md-6">
                                <InputLabel for="name" value="Customer Name" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="block w-full mt-1"
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
                            <div class="mt-2 col-md-6">
                                <InputLabel
                                    for="email"
                                    value="Customer email"
                                />

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
                                    for="mobile"
                                    value="Customer mobile"
                                />

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
                            <div class="pt-4 mt-2 col-md-6">

                                <div v-for="item in inv_tax_type" :key="item.id"  class="form-check form-check-inline">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="inv_tax_type"
                                        :value="item.status"
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
                            <div class="mt-2 col-md-6">
                                <InputLabel
                                    for="gst"
                                    value="Customer GST/PAN/TAN"
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
                                <InputLabel for="pin" value="Customer pin" />

                                <TextInput
                                    id="pin"
                                    type="text"
                                    class="block w-full mt-1"
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
                            <div class="mt-2 col-12">
                                <InputLabel
                                    for="address"
                                    value="Customer address"
                                />

                                <TextareaInput
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
                        </div>
                        <div class="flex items-center gap-4">
                            <PrimaryButton
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
    users: Object,
    searchdata: Object,
    inv_tax_type : Object,
});
const form = useForm({
    name: props.searchdata ? props.searchdata.company_name : "",
    email: props.searchdata ? props.searchdata.email : "",
    mobile: props.searchdata ? props.searchdata.mobile : "",
    gst: props.searchdata ? props.searchdata.gstin : "",
    pin: props.searchdata ? props.searchdata.pin : "",
    address: props.searchdata ? props.searchdata.address : "",
    tax_type:props.searchdata?props.searchdata.tax_type : ""
});
const form2 = useForm({});
const enteredValue = ref('');

function submit() {
        form.post(route("company.customer.store"), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        });
}

const handleInput = () => {
    var search = enteredValue.value;
    if (search.length > 0) {
        form2.get(route("search.seller.customer", search));
    }
};
</script>
