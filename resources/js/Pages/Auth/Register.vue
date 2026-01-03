<template>
    <Head title="Register" />

    <GuestLayout>
        <div class="space-y-6">
            <div class="flex justify-center mb-2">
                <Link href="/">
                    <img src="itimages/itlogo.png" alt="Innovation Trove" class="h-16 w-auto drop-shadow-sm" />
                </Link>
            </div>

            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tightest uppercase mb-1">
                    Get <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-800 to-pink-700">Started</span>
                </h2>
                <div class="h-1.5 w-16 bg-gradient-to-r from-purple-800 to-pink-700 mx-auto rounded-full shadow-sm"></div>
                <p class="mt-4 text-sm text-gray-500 font-medium">
                    Empower your business with smart billing
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <input type="hidden" v-if="company_id" v-model="form.company_id" />
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mt-1">
                        <InputLabel for="name" value="Name" />
                        <TextInput id="name" type="text" class="block w-full mt-1.5" v-model="form.name" required
                            autofocus autocomplete="name" placeholder="John Doe" />
                        <InputError class="mt-1.5" :message="form.errors.name" />
                    </div>

                    <div class="mt-1">
                        <InputLabel for="email" value="Email Address" />
                        <TextInput id="email" type="email" class="block w-full mt-1.5" v-model="form.email" required
                            autocomplete="username" placeholder="john@example.com" />
                        <InputError class="mt-1.5" :message="form.errors.email" />
                    </div>

                    <div class="mt-1">
                        <InputLabel for="mobile" value="Mobile Number" />
                        <TextInput id="mobile" type="number" class="block w-full mt-1.5" v-model="form.mobile" required
                            autocomplete="mobile" placeholder="9876543210" />
                        <InputError class="mt-1.5" :message="form.errors.mobile" />
                    </div>

                    <div class="mt-1">
                        <InputLabel for="password" value="Password" />
                        <TextInput id="password" type="password" class="block w-full mt-1.5" v-model="form.password"
                            required autocomplete="new-password" placeholder="••••••••" />
                        <InputError class="mt-1.5" :message="form.errors.password" />
                    </div>

                    <div class="mt-1 md:col-span-2">
                        <InputLabel for="password_confirmation" value="Confirm Password" />
                        <TextInput id="password_confirmation" type="password" class="block w-full mt-1.5"
                            v-model="form.password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                        <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
                    </div>
                </div>

                <div class="flex flex-col items-center pt-4 space-y-4">
                    <PrimaryButton class="w-full flex justify-center py-3 text-base" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                        Create Account
                    </PrimaryButton>
                    
                    <Link :href="route('login')" class="text-sm font-medium text-gray-600 hover:text-purple-700 transition-all duration-200">
                        Already registered? <span class="underline decoration-purple-300 decoration-2 underline-offset-4">Log in here</span>
                    </Link>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

// Receive company_id from the controller
const props = defineProps({
    company_id: String
});

const form = useForm({
    name: "",
    email: "",
    password: "",
    mobile: "",
    password_confirmation: "",
    terms: false,
    company_id: props.company_id ?? '',
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>
