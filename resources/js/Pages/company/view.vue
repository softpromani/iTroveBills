<template>
    <Head title="Company view" />
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
                        >Sample Company View Info</span
                    >
                </div>
            </div>
        </div>
        <div class="table-responsive rounded-lg shadow">
            <table class="table">
                <thead class="table-dark">
                    <tr
                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                    >
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Company Name
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Email
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Address
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            City
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            PIN
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            GSTIN
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            IEC
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Mobile
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            invoice_series
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            logo
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Sign
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            bank_name
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            bank_branch
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            bank_ifsc
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            bank_account_no
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            ad_code
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            status
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="company in company_details.data"
                        :key="company.id"
                        class="text-gray-700"
                    >
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.company_name }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.email }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.address }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.city }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.pin }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.gstin }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.iec }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.mobile }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.invoice_series }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <img
                                :src="company.logo"
                                alt=""
                                srcset=""
                                style="height: 70px"
                            />
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <img
                                :src="company.sign"
                                alt=""
                                srcset=""
                                style="height: 70px"
                            />
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.bank_name }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.bank_branch }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.bank_ifsc }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.bank_account_no }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.ad_code }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ company.status }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
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
                                            :href="route('company.edit')"
                                            method="get"
                                            :data="{ id: company.id }"
                                            class="dropdown-item"
                                            ><i
                                                class="fa-solid fa-pen-to-square" style="color:blueviolet"
                                            ></i>
                                            Edit</Link
                                        >
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            style="cursor: pointer"
                                            @click="
                                                confirmUserDeletion(company.id)
                                            "
                                            ><i class="fa-solid fa-trash" style="color:rgb(187, 25, 25)"></i>
                                            Delete</a
                                        >
                                    </li>
                                    <li>
                                        <a
                                            class="dropdown-item"
                                            style="cursor: pointer"
                                            @click="addCompanyLUT(company.id)"
                                            ><i class="fa-solid fa-list" style="color:blueviolet"></i>
                                            LUT</a
                                        >
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <Modal :show="addingCompanyLUT" @close="closelutModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Add LUT Of Company?
                </h2>
                <form @submit.prevent="submitlut" class="mt-6 space-y-6">
                    <div class="mt-6">
                        <div class="row">
                            <div class="col-md-6">
                                <InputLabel
                                    for="lut_no"
                                    value="LUT No"
                                    class="sr-only"
                                />
                                <TextInput
                                    id="lut_no"
                                    v-model="form.lut_no"
                                    type="text"
                                    class="mt-1 block w-3/4"
                                    placeholder="LUT NO"
                                />
                                <InputError
                                    :message="form.errors.lut_no"
                                    class="mt-2"
                                />
                            </div>
                            <div class="col-md-6">
                                <InputLabel
                                    for="expiry_date"
                                    value="Expiry Date"
                                    class="sr-only"
                                />
                                <TextInput
                                    id="expiry_date"
                                    v-model="form.expiry_date"
                                    type="date"
                                    class="mt-1 block w-3/4"
                                    placeholder="expiry_date"
                                />
                                <InputError
                                    :message="form.errors.expiry_date"
                                    class="mt-2"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-4">
                        <SecondaryButton @click="closelutModal">
                            Cancel
                        </SecondaryButton>

                        <div class="flex items-center gap-4">
                            <PrimaryButton
                                type="submit"
                                :disabled="form.processing"
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
                    </div>
                </form>
            </div>
        </Modal>
        <Modal :show="confirmingCompanyDeletion" @close="closeModal" >
            <div class="p-6" >
                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete your Company?
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
                        @click="deleteCompany"
                    >
                        Delete Company
                    </DangerButton>
                </div>
            </div>
        </Modal>
        <div
            class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between"
        >
            <pagination :links="company_details.links" />
        </div>
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
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    company_details: Object,
});
const confirmingCompanyDeletion = ref(false);
const addingCompanyLUT = ref(false);
const form = useForm({
    expiry_date: "",
    lut_no: "",
});

let delete_id = null;
const confirmUserDeletion = (id) => {
    delete_id = id;
    confirmingCompanyDeletion.value = true;
};

let lut_id = null;
const addCompanyLUT = (id) => {
    lut_id = id;
    addingCompanyLUT.value = true;
};

const deleteCompany = () => {
    form.post(route("company.delete", delete_id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const submitlut = () => {
    form.post(route("company.lut", lut_id), {
        preserveScroll: true,
        onSuccess: () => closelutModal(),
        onError: () => lut_no.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingCompanyDeletion.value = false;
};
const closelutModal = () => {
    addingCompanyLUT.value = false;
};
</script>
