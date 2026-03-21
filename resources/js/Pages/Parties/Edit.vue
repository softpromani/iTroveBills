<template>
    <Head title="Edit Party" />

    <AuthenticatedLayout>
        <template #header> Edit Party </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <InputLabel for="name" value="Party Name" />
                                    <TextInput id="name" type="text" v-model="form.name" class="mt-1 block w-full" required />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>

                                <div>
                                    <InputLabel for="address" value="Address" />
                                    <TextareaInput id="address" v-model="form.address" class="mt-1 block w-full" rows="3" />
                                    <InputError class="mt-2" :message="form.errors.address" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <Link :href="route('parties.index')" class="mr-4 text-sm text-gray-600 hover:text-gray-900">Cancel</Link>
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Update Party
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
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    party: Object,
});

const form = useForm({
    name: props.party.name,
    address: props.party.address || "",
});

const submit = () => {
    form.put(route("parties.update", props.party.id));
};
</script>
