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
    company_name: props.editdata?.company_name ?? "",
    email: props.editdata?.email ?? "",
    mobile: props.editdata?.mobile ?? "",
    logo: props.editdata?.logo ?? "",
    sign: props.editdata?.sign ?? "",
    bank_qr: props.editdata?.bank_qr ?? "",
    address: props.editdata?.address ?? "",
    city: props.editdata?.city ?? "",
    pin: props.editdata?.pin ?? "",
    gstin: props.editdata?.gstin ?? "",
    iec: props.editdata?.iec ?? "",
    firm_type: props.editdata?.firm_type ?? "",
    bank_name: props.editdata?.bank_name ?? "",
    bank_branch: props.editdata?.bank_branch ?? "",
    bank_ifsc: props.editdata?.bank_ifsc ?? "",
    bank_account_no: props.editdata?.bank_account_no ?? "",
    adcode: props.editdata?.ad_code ?? "",
    invoice_series: props.editdata?.invoice_series ?? "",
    gst_invoice_series: props.editdata?.gst_invoice_series ?? "",
    proforma_invoice_series: props.editdata?.proforma_invoice_series ?? "",
    brand_banner: [], // This will only hold NEWLY selected files
});

// handle file input for multiple images
function handleFileInput(event) {
    const files = Array.from(event.target.files);
    form.brand_banner.push(...files);
}

function removeBanner(index) {
    form.brand_banner.splice(index, 1);
}

const getImageUrl = (fileOrPath) => {
    if (!fileOrPath) return '';
    if (fileOrPath instanceof File || fileOrPath instanceof Blob) {
        return URL.createObjectURL(fileOrPath);
    }
    // If it's a string path from DB (e.g. storage/company_file/...)
    // In production, we should ensure it has a leading slash
    if (typeof fileOrPath === 'string') {
        if (fileOrPath.startsWith('http')) return fileOrPath;
        const path = fileOrPath.startsWith('/') ? fileOrPath : '/' + fileOrPath;
        return path;
    }
    return '';
};

function submit() {
    const isUpdate = props.editdata && props.editdata.id;
    const routeName = isUpdate ? "company.update" : "company.store";
    const routeParams = isUpdate ? props.editdata.id : undefined;

    form.post(route(routeName, routeParams), {
        preserveScroll: true,
        onSuccess: () => {
             if (!isUpdate) form.reset();
        },
    });
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

                    <TextInput id="company_name" type="text" class="block w-full mt-1" v-model="form.company_name"
                        required autofocus autocomplete="company_name" />

                    <InputError class="mt-2" :message="form.errors.company_name" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="address" value="Address" />

                    <TextInput id="address" type="text" class="block w-full mt-1" v-model="form.address" required
                        autofocus autocomplete="address" />

                    <InputError class="mt-2" :message="form.errors.address" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="mobile" value="Mobile" />

                    <TextInput id="mobile" type="number" class="block w-full mt-1" v-model="form.mobile" required
                        autofocus autocomplete="mobile" />

                    <InputError class="mt-2" :message="form.errors.mobile" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="email" value="Email" />

                    <TextInput id="email" type="email" class="block w-full mt-1" v-model="form.email" required
                        autocomplete="email" />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="city" value="City" />

                    <TextInput id="city" type="text" class="block w-full mt-1" v-model="form.city" required
                        autocomplete="city" />

                    <InputError class="mt-2" :message="form.errors.city" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="pin" value="Pin" />

                    <TextInput id="pin" type="text" class="block w-full mt-1" v-model="form.pin" required
                        autocomplete="pin" />

                    <InputError class="mt-2" :message="form.errors.pin" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="gstin" value="GSTIN" />

                    <TextInput id="gstin" type="text" class="block w-full mt-1" v-model="form.gstin" required
                        autocomplete="gstin" />

                    <InputError class="mt-2" :message="form.errors.gstin" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="iec" value="IEC" />

                    <TextInput id="iec" type="text" class="block w-full mt-1" v-model="form.iec" required
                        autocomplete="iec" />

                    <InputError class="mt-2" :message="form.errors.iec" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="firm_type" value="Firm Type" />

                    <select id="firm_type" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="form.firm_type" required>
                        <option value="" disabled>Select Firm Type</option>
                        <option value="IT">IT</option>
                        <option value="Non IT">Non IT</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.firm_type" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="invoice_series" value="Export Invoice Series" />

                    <TextInput id="invoice_series" type="text" class="block w-full mt-1" v-model="form.invoice_series"
                        required autocomplete="invoice_series" />

                    <InputError class="mt-2" :message="form.errors.invoice_series" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="gst_invoice_series" value="GST Invoice Series" />

                    <TextInput id="gst_invoice_series" type="text" class="block w-full mt-1" v-model="form.gst_invoice_series"
                        required autocomplete="gst_invoice_series" />

                    <InputError class="mt-2" :message="form.errors.gst_invoice_series" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="proforma_invoice_series" value="Proforma Invoice Series" />

                    <TextInput id="proforma_invoice_series" type="text" class="block w-full mt-1" v-model="form.proforma_invoice_series"
                        required autocomplete="proforma_invoice_series" />

                    <InputError class="mt-2" :message="form.errors.proforma_invoice_series" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="bank_name" value="Bank Name" />

                    <TextInput id="bank_name" type="text" class="block w-full mt-1" v-model="form.bank_name" required
                        autocomplete="bank_name" />

                    <InputError class="mt-2" :message="form.errors.bank_name" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="bank_branch" value="Bank Branch" />

                    <TextInput id="bank_branch" type="text" class="block w-full mt-1" v-model="form.bank_branch"
                        required autocomplete="bank_branch" />

                    <InputError class="mt-2" :message="form.errors.bank_branch" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="bank_ifsc" value="Bank IFSC" />

                    <TextInput id="bank_ifsc" type="text" class="block w-full mt-1" v-model="form.bank_ifsc" required
                        autocomplete="bank_ifsc" />

                    <InputError class="mt-2" :message="form.errors.bank_ifsc" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="bank_account_no" value="Bank Account No" />

                    <TextInput id="bank_account_no" type="text" class="block w-full mt-1" v-model="form.bank_account_no"
                        required autocomplete="bank_account_no" />

                    <InputError class="mt-2" :message="form.errors.bank_account_no" />
                </div>
                <div class="mt-2 col-md-6">
                    <InputLabel for="adcode" value="ADCode" />

                    <TextInput id="adcode" type="text" class="block w-full mt-1" v-model="form.adcode" required
                        autocomplete="adcode" />

                    <InputError class="mt-2" :message="form.errors.adcode" />
                </div>
                <div class="mt-3 col-md-6">
                    <InputLabel for="formFile" value="Company Logo" />
                    <input class="form-control" id="formFile" type="file" @change="form.logo = $event.target.files[0]" />
                    <InputError class="mt-2" :message="form.errors.logo" />
                    <img v-if="form.logo" :src="getImageUrl(form.logo)" alt="Logo Preview" class="mt-2 img-thumbnail" style="width: 150px;" />
                </div>
                <div class="mt-3 col-md-6">
                    <InputLabel for="sign" value="Signature" />
                    <input class="form-control" id="sign" type="file" @change="form.sign = $event.target.files[0]" />
                    <InputError class="mt-2" :message="form.errors.sign" />
                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                        {{ form.progress.percentage }}%
                    </progress>
                    <img v-if="form.sign" :src="getImageUrl(form.sign)" alt="Signature Preview" class="mt-2 img-thumbnail" style="width: 150px;" />
                </div>

                <div class="mt-3 col-md-6">
                    <InputLabel for="bank_qr" value="Bank QR" />
                    <input class="form-control" id="bank_qr" type="file" @change="form.bank_qr = $event.target.files[0]" />
                    <InputError class="mt-2" :message="form.errors.bank_qr" />
                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                        {{ form.progress.percentage }}%
                    </progress>
                    <img v-if="form.bank_qr" :src="getImageUrl(form.bank_qr)" alt="Bank QR Preview" class="mt-2 img-thumbnail" style="width: 150px;" />
                </div>

                <div class="mt-3 col-md-6">
                    <InputLabel for="brand_banner" value="Brand Banner" />
                    <input class="form-control" id="brand_banner" type="file" multiple @change="handleFileInput" />
                    <InputError class="mt-2" :message="form.errors.brand_banner" />

                    <!-- Existing Banners (if editing) -->
                    <div v-if="props.editdata?.brand_banner?.length" class="mt-2">
                        <p class="text-sm font-semibold text-gray-600">Existing Banners:</p>
                        <div class="flex flex-wrap">
                            <img
                                v-for="(path, index) in props.editdata.brand_banner"
                                :key="'existing-' + index"
                                :src="getImageUrl(path)"
                                alt="Existing Brand Banner"
                                class="img-thumbnail"
                                style="width: 100px; height: auto; margin: 5px;"
                            />
                        </div>
                        <p class="text-xs text-red-500">* Uploading new banners will replace existing ones.</p>
                    </div>

                    <!-- New Banners Preview -->
                    <div v-if="form.brand_banner.length" class="mt-2">
                        <p class="text-sm font-semibold text-gray-600">New Banners to Upload:</p>
                        <div class="flex flex-wrap">
                            <div v-for="(file, index) in form.brand_banner" :key="'new-' + index" class="relative">
                                <img
                                    :src="getImageUrl(file)"
                                    alt="New Brand Banner"
                                    class="img-thumbnail"
                                    style="width: 100px; height: auto; margin: 5px;"
                                />
                                <button
                                    type="button"
                                    @click="removeBanner(index)"
                                    class="absolute top-0 right-0 p-1 text-white bg-red-500 rounded-full"
                                    style="transform: translate(25%, -25%);"
                                >
                                    &times;
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="flex items-center gap-4">
                    <PrimaryButton :disabled="form.processing">{{
                        props.editdata.company_name ? "Update" : "Save"
                    }}</PrimaryButton>

                    <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                        <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                            Saved.
                        </p>
                    </Transition>
                </div>
            </div>

        </form>
    </section>
</template>
