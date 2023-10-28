<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

const user = usePage().props.auth.user;
const props = defineProps({
    editdata: Object,
});
const form = useForm({
    company_name: props.editdata.company_name ?? "",
    email: props.editdata.email ?? "",
    mobile: props.editdata.mobile ?? "",
    logo: props.editdata.logo ?? "",
    sign: props.editdata.sign ?? "",
    address: props.editdata.address ?? "",
    city: props.editdata.city ?? "",
    pin: props.editdata.pin ?? "",
    gstin: props.editdata.gstin ?? "",
    iec: props.editdata.iec ?? "",
    invoice_series: props.editdata.invoice_series ?? "",
    bank_name: props.editdata.bank_name ?? "",
    bank_branch: props.editdata.bank_branch ?? "",
    bank_ifsc: props.editdata.bank_ifsc ?? "",
    bank_account_no: props.editdata.bank_account_no ?? "",
    adcode: props.editdata.ad_code ?? "",
});
function submit() {
    if (props.editdata) {
        form.post(route("company.update", props.editdata.id), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        });
    } else {
        form.post(route("company.store"), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        });
    }
}
</script>

<template>
    <section class="container">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Firm Information</h2>
        </header>

        <form @submit.prevent="submit" class="mt-6 space-y-6">
            <div class="row">
                <div class="mt-2 col-md-6">
                    <InputLabel for="company_name" value="Firm Name" />

                    <TextInput
                        id="company_name"
                        type="text"
                        class="block w-full mt-1"
                        v-model="form.company_name"
                        required
                        autofocus
                        autocomplete="company_name"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.company_name"
                    />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="address" value="Address" />

                    <TextInput
                        id="address"
                        type="text"
                        class="block w-full mt-1"
                        v-model="form.address"
                        required
                        autofocus
                        autocomplete="address"
                    />

                    <InputError class="mt-2" :message="form.errors.address" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="mobile" value="Mobile" />

                    <TextInput
                        id="mobile"
                        type="number"
                        class="block w-full mt-1"
                        v-model="form.mobile"
                        required
                        autofocus
                        autocomplete="mobile"
                    />

                    <InputError class="mt-2" :message="form.errors.mobile" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full mt-1"
                        v-model="form.email"
                        required
                        autocomplete="email"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="city" value="City" />

                    <TextInput
                        id="city"
                        type="text"
                        class="block w-full mt-1"
                        v-model="form.city"
                        required
                        autocomplete="city"
                    />

                    <InputError class="mt-2" :message="form.errors.city" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="pin" value="Pin" />

                    <TextInput
                        id="pin"
                        type="text"
                        class="block w-full mt-1"
                        v-model="form.pin"
                        required
                        autocomplete="pin"
                    />

                    <InputError class="mt-2" :message="form.errors.pin" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="gstin" value="GSTIN" />

                    <TextInput
                        id="gstin"
                        type="text"
                        class="block w-full mt-1"
                        v-model="form.gstin"
                        required
                        autocomplete="gstin"
                    />

                    <InputError class="mt-2" :message="form.errors.gstin" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="iec" value="IEC" />

                    <TextInput
                        id="iec"
                        type="text"
                        class="block w-full mt-1"
                        v-model="form.iec"
                        required
                        autocomplete="iec"
                    />

                    <InputError class="mt-2" :message="form.errors.iec" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="invoice_series" value="Invoice Series" />

                    <TextInput
                        id="invoice_series"
                        type="text"
                        class="block w-full mt-1"
                        v-model="form.invoice_series"
                        required
                        autocomplete="invoice_series"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.invoice_series"
                    />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="bank_name" value="Bank Name" />

                    <TextInput
                        id="bank_name"
                        type="text"
                        class="block w-full mt-1"
                        v-model="form.bank_name"
                        required
                        autocomplete="bank_name"
                    />

                    <InputError class="mt-2" :message="form.errors.bank_name" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="bank_branch" value="Bank Branch" />

                    <TextInput
                        id="bank_branch"
                        type="text"
                        class="block w-full mt-1"
                        v-model="form.bank_branch"
                        required
                        autocomplete="bank_branch"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.bank_branch"
                    />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="bank_ifsc" value="Bank IFSC" />

                    <TextInput
                        id="bank_ifsc"
                        type="text"
                        class="block w-full mt-1"
                        v-model="form.bank_ifsc"
                        required
                        autocomplete="bank_ifsc"
                    />

                    <InputError class="mt-2" :message="form.errors.bank_ifsc" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="bank_account_no" value="Bank Account No" />

                    <TextInput
                        id="bank_account_no"
                        type="text"
                        class="block w-full mt-1"
                        v-model="form.bank_account_no"
                        required
                        autocomplete="bank_account_no"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.bank_account_no"
                    />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="adcode" value="ADCode" />

                    <TextInput
                        id="adcode"
                        type="text"
                        class="block w-full mt-1"
                        v-model="form.adcode"
                        required
                        autocomplete="adcode"
                    />

                    <InputError class="mt-2" :message="form.errors.adcode" />
                </div>
                <div class="mt-3 col-md-6">
                    <InputLabel for="formFile" value="Company Logo" />
                    <input
                        class="form-control"
                        id="formFile"
                        type="file"
                        @input="form.logo = $event.target.files[0]"
                    />
                    <InputError class="mt-2" :message="form.errors.logo" />
                    <img
                        v-if="props.editdata"
                        :src="form.logo"
                        alt=""
                        sizes=""
                        srcset=""
                    />
                </div>
                <div class="mt-3 col-md-6">
                    <InputLabel for="sign" value="Signature" />
                    <input
                        class="form-control"
                        id="sign"
                        type="file"
                        @input="form.sign = $event.target.files[0]"
                    />
                    <InputError class="mt-2" :message="form.errors.sign" />
                    <progress
                        v-if="form.progress"
                        :value="form.progress.percentage"
                        max="100"
                    >
                        {{ form.progress.percentage }}%
                    </progress>
                    <img
                        v-if="props.editdata"
                        :src="form.sign"
                        alt=""
                        sizes=""
                        srcset=""
                    />
                </div>
            </div>
            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">{{
                    props.editdata.company_name ? "Update" : "Save"
                }}</PrimaryButton>

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
    </section>
</template>
