<template>
    <Head title="Edit Ledger Entry" />
    <AuthenticatedLayout>
        <template #header> Edit Ledger Entry </template>

        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form @submit.prevent="submit">
                    <div class="shadow overflow-hidden sm:rounded-md bg-white">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <!-- Customer -->
                                <div class="col-span-6 sm:col-span-4">
                                    <InputLabel for="customer" value="Select Customer" />
                                    <select
                                        id="customer"
                                        v-model="form.seller_customer_id"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                        required
                                    >
                                        <option value="" disabled>Select a customer</option>
                                        <option v-for="customer in customers" :key="customer.id" :value="customer.id">
                                            {{ customer.customer_detail?.name || 'Unnamed Customer' }} ({{ customer.customer_detail?.mobile || 'No Mobile' }})
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.seller_customer_id" class="mt-2" />
                                </div>

                                <!-- Date -->
                                <div class="col-span-6 sm:col-span-2">
                                    <InputLabel for="date" value="Date" />
                                    <TextInput
                                        id="date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.date"
                                        required
                                    />
                                    <InputError :message="form.errors.date" class="mt-2" />
                                </div>

                                <!-- Row: Type and Payment Type -->
                                <div class="col-span-6 sm:col-span-3">
                                    <InputLabel for="type" value="Entry Type (Debit/Credit)" />
                                    <select
                                        id="type"
                                        v-model="form.type"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                        required
                                    >
                                        <option value="debit">Debit (Payment Out)</option>
                                        <option value="credit">Credit (Payment In)</option>
                                    </select>
                                    <InputError :message="form.errors.type" class="mt-2" />
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <InputLabel for="payment_type" value="Payment Type" />
                                    <select
                                        id="payment_type"
                                        v-model="form.payment_type"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                        required
                                    >
                                        <option value="cash">Cash</option>
                                        <option value="transfer">Bank Transfer/Online</option>
                                    </select>
                                    <InputError :message="form.errors.payment_type" class="mt-2" />
                                </div>

                                <!-- Amount -->
                                <div class="col-span-6 sm:col-span-6">
                                    <InputLabel for="amount" value="Amount (₹)" />
                                    <TextInput
                                        id="amount"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="mt-1 block w-full text-lg font-bold"
                                        v-model="form.amount"
                                        placeholder="0.00"
                                        required
                                    />
                                    <InputError :message="form.errors.amount" class="mt-2" />
                                </div>

                                <!-- Description -->
                                <div class="col-span-6">
                                    <InputLabel for="description" value="Description" />
                                    <TextareaInput
                                        id="description"
                                        class="mt-1 block w-full"
                                        v-model="form.description"
                                        rows="3"
                                        placeholder="Add any additional notes here..."
                                    />
                                    <InputError :message="form.errors.description" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="px-4 py-3 bg-gray-50 flex items-center justify-end gap-3 sm:px-6">
                            <Link
                                :href="route('ledgers.index')"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150 no-underline"
                            >
                                Cancel
                            </Link>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Update Entry
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
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
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    ledger: Object,
    customers: Array,
});

const form = useForm({
    seller_customer_id: props.ledger.seller_customer_id,
    type: props.ledger.type,
    payment_type: props.ledger.payment_type,
    amount: props.ledger.amount,
    date: props.ledger.date,
    description: props.ledger.description || "",
});

const submit = () => {
    form.put(route("ledgers.update", props.ledger.id));
};
</script>
