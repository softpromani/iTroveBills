<template>
    <Head title="Create Ledger Entry" />

    <AuthenticatedLayout>
        <template #header> Create Ledger Entry </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Unified Selection for Customer or Party -->
                                <div class="col-span-2">
                                    <InputLabel for="account" value="Select Customer / Party" />
                                    <Dropdown
                                        id="account"
                                        v-model="selectedAccount"
                                        :options="groupedAccounts"
                                        optionLabel="label"
                                        optionGroupLabel="label"
                                        optionGroupChildren="items"
                                        optionValue="value"
                                        filter
                                        placeholder="Choose an account"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 h-11 d-flex align-items-center"
                                        required
                                    />
                                    <InputError class="mt-2" :message="form.errors.seller_customer_id || form.errors.party_id" />
                                </div>

                                <div>
                                    <InputLabel for="date" value="Date" />
                                    <TextInput id="date" type="date" v-model="form.date" class="mt-1 block w-full" required />
                                    <InputError class="mt-2" :message="form.errors.date" />
                                </div>

                                <div>
                                    <InputLabel for="type" value="Entry Type (Debit/Credit)" />
                                    <select
                                        id="type"
                                        v-model="form.type"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        required
                                    >
                                        <option value="debit">Debit (+)</option>
                                        <option value="credit">Credit (-)</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.type" />
                                </div>

                                <div>
                                    <InputLabel for="payment_type" value="Payment Type" />
                                    <select
                                        id="payment_type"
                                        v-model="form.payment_type"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        required
                                    >
                                        <option value="cash">Cash</option>
                                        <option value="transfer">Bank/Transfer</option>
                                        <option value="sales">Sales</option>
                                        <option value="purchase">Purchase</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.payment_type" />
                                </div>

                                <div>
                                    <InputLabel for="particular_type" value="Voucher / Bill" />
                                    <select
                                        id="particular_type"
                                        v-model="form.particular_type"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        required
                                    >
                                        <option value="Voucher">Voucher</option>
                                        <option value="Bill">Bill</option>
                                        <option value="Paid">Paid</option>
                                        <option value="Receipt">Receipt</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.particular_type" />
                                </div>

                                <div>
                                    <InputLabel for="voucher_no" value="Manual Voucher No." />
                                    <TextInput id="voucher_no" type="text" v-model="form.voucher_no" class="mt-1 block w-full" placeholder="Enter voucher number" />
                                    <InputError class="mt-2" :message="form.errors.voucher_no" />
                                </div>

                                <div>
                                    <InputLabel for="amount" value="Amount" />
                                    <TextInput id="amount" type="number" step="0.01" v-model="form.amount" class="mt-1 block w-full" required />
                                    <InputError class="mt-2" :message="form.errors.amount" />
                                </div>


                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <Link :href="route('ledgers.index')" class="mr-4 text-sm text-gray-600 hover:text-gray-900">Cancel</Link>
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Save Entry
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Dropdown from "primevue/dropdown";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";

const props = defineProps({
    customers: Array,
    parties: Array,
});

const selectedAccount = ref("");

const groupedAccounts = computed(() => {
    return [
        {
            label: 'Customers',
            items: props.customers.map(c => ({
                label: c.customer_detail?.name || 'Unknown Customer',
                value: 'customer_' + c.id
            }))
        },
        {
            label: 'Parties',
            items: props.parties.map(p => ({
                label: p.name || 'Unknown Party',
                value: 'party_' + p.id
            }))
        }
    ];
});

const form = useForm({
    seller_customer_id: "",
    party_id: "",
    type: "debit",
    payment_type: "cash",
    particular_type: "Voucher",
    voucher_no: "",
    amount: "",
    date: new Date().toISOString().substr(0, 10),
});

watch(selectedAccount, (val) => {
    if (val && val.startsWith("customer_")) {
        form.seller_customer_id = val.replace("customer_", "");
        form.party_id = "";
    } else if (val && val.startsWith("party_")) {
        form.party_id = val.replace("party_", "");
        form.seller_customer_id = "";
    } else {
        form.seller_customer_id = "";
        form.party_id = "";
    }
});

const submit = () => {
    form.post(route("ledgers.store"));
};
</script>
