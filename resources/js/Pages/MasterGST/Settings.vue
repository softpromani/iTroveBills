<template>
    <Head title="E-Way Bill Settings" />
    <AuthenticatedLayout>
        <div class="space-y-6">
            <div class="bg-white shadow-sm border border-gray-100 rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-800 to-pink-700 py-3 px-6">
                    <h4 class="text-white font-bold text-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        MasterGST API Credentials
                    </h4>
                </div>
                
                <div class="p-6">
                    <p class="text-gray-600 text-sm mb-6">
                        Configure your MasterGST GSP credentials to enable automated E-Way Bill generation directly from your invoices.
                    </p>

                    <div v-for="company in companies" :key="company.id" class="mb-10 last:mb-0 border-b border-gray-100 last:border-0 pb-10 last:pb-0">
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-700 font-bold mr-3">
                                {{ company.company_name.charAt(0) }}
                            </div>
                            <div>
                                <h5 class="text-gray-900 font-bold text-base">{{ company.company_name }}</h5>
                                <p class="text-xs text-gray-500">GSTIN: {{ company.gstin }}</p>
                            </div>
                        </div>

                        <form @submit.prevent="submit(company.id)" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <InputLabel value="Client ID" />
                                <TextInput type="text" v-model="forms[company.id].client_id" class="mt-1 block w-full" placeholder="MasterGST Client ID" />
                                <InputError :message="forms[company.id].errors.client_id" />
                            </div>

                            <div>
                                <InputLabel value="Client Secret" />
                                <TextInput type="password" v-model="forms[company.id].client_secret" class="mt-1 block w-full" placeholder="••••••••••••" />
                                <InputError :message="forms[company.id].errors.client_secret" />
                            </div>

                            <div>
                                <InputLabel value="API Username" />
                                <TextInput type="text" v-model="forms[company.id].username" class="mt-1 block w-full" placeholder="Portal Username" />
                                <InputError :message="forms[company.id].errors.username" />
                            </div>

                            <div>
                                <InputLabel value="API Password" />
                                <TextInput type="password" v-model="forms[company.id].password" class="mt-1 block w-full" placeholder="••••••••" />
                                <InputError :message="forms[company.id].errors.password" />
                            </div>

                            <div>
                                <InputLabel value="Registered Email" />
                                <TextInput type="email" v-model="forms[company.id].email" class="mt-1 block w-full" placeholder="email@example.com" />
                                <InputError :message="forms[company.id].errors.email" />
                            </div>

                            <div>
                                <InputLabel value="GSTIN (for API)" />
                                <TextInput type="text" v-model="forms[company.id].gstin" class="mt-1 block w-full" placeholder="27XXXXX0000X1Z1" />
                                <InputError :message="forms[company.id].errors.gstin" />
                            </div>

                            <div>
                                <InputLabel value="Base URL" />
                                <TextInput 
                                    type="text" 
                                    v-model="forms[company.id].gst_base_url" 
                                    class="mt-1 block w-full" 
                                    :class="{'border-red-500 text-red-600': isInvalidUrl(forms[company.id].gst_base_url) || isDummyData(forms[company.id].gst_base_url)}"
                                    placeholder="https://apisandbox.whitebooks.in" 
                                />
                                <p v-if="isDummyData(forms[company.id].gst_base_url)" class="mt-1 text-xs text-red-500 font-medium">
                                    ⚠️ Warning: This looks like placeholder/dummy data.
                                </p>
                                <InputError :message="forms[company.id].errors.gst_base_url" />
                            </div>

                            <div>
                                <InputLabel value="IP Address" />
                                <TextInput type="text" v-model="forms[company.id].ip_address" class="mt-1 block w-full" placeholder="127.0.0.1" />
                                <InputError :message="forms[company.id].errors.ip_address" />
                            </div>

                            <div class="md:col-span-2 lg:col-span-3 flex justify-end">
                                <PrimaryButton :class="{ 'opacity-25': forms[company.id].processing }" :disabled="forms[company.id].processing">
                                    <span v-if="forms[company.id].processing">Saving...</span>
                                    <span v-else>Save Configuration</span>
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>

                    <div v-if="companies.length === 0" class="text-center py-12">
                        <p class="text-gray-500">No firms found. Please create a firm first.</p>
                        <Link :href="route('company.index')" class="text-purple-700 font-bold hover:underline mt-2 inline-block">Create Firm</Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { reactive } from "vue";
import Swal from 'sweetalert2';

const props = defineProps({
    companies: Array,
    configs: Object
});

const forms = reactive({});

props.companies.forEach(company => {
    const config = props.configs[company.id] || {};
    forms[company.id] = useForm({
        company_id: company.id,
        client_id: config.client_id || '',
        client_secret: config.client_secret || '',
        username: config.username || '',
        password: config.password || '',
        gstin: config.gstin || company.gstin || '',
        email: config.email || company.email || '',
        gst_base_url: config.gst_base_url || 'https://apisandbox.whitebooks.in',
        ip_address: config.ip_address || '127.0.0.1'
    });
});

const isInvalidUrl = (url) => {
    if (!url) return false;
    try {
        new URL(url);
        return false;
    } catch (e) {
        return true;
    }
};

const isDummyData = (text) => {
    if (!text) return false;
    const dummyWords = ['esse', 'iste', 'suscipit', 'faker', 'Lorem'];
    return dummyWords.some(word => text.toLowerCase().includes(word.toLowerCase()));
};

const submit = (companyId) => {
    forms[companyId].post(route('mastergst.settings.store'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Settings Saved',
                text: 'MasterGST configuration updated successfully.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    });
};
</script>
