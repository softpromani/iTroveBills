<template>
    <Head title="Register" />

    <GuestLayout>
        <div class="container">
            <div class="row">
                <Link href="/" class="flex items-center justify-center col-12">
                <img src="itimage/itlogo.png" alt="" class="h-20 text-gray-500 fill-current w-30" />
                </Link>

                <form @submit.prevent="submit">
                    <div class="row">
                        <div class="mt-3 col-md-6">
                            <InputLabel for="name" value="Name" />
                            <TextInput id="name" type="text" class="block w-full mt-1" v-model="form.name" required
                                autofocus autocomplete="name" />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="mt-3 col-md-6">
                            <InputLabel for="email" value="Email" />
                            <TextInput id="email" type="email" class="block w-full mt-1" v-model="form.email" required
                                autocomplete="username" />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                        <div class="mt-3 col-md-6">
                            <InputLabel for="mobile" value="Mobile" />
                            <TextInput id="mobile" type="number" class="block w-full mt-1" v-model="form.mobile" required
                                autocomplete="mobile" />
                            <InputError class="mt-2" :message="form.errors.mobile" />
                        </div>
                        <div class="mt-3 col-md-6">
                            <InputLabel for="password" value="Password" />
                            <TextInput id="password" type="password" class="block w-full mt-1" v-model="form.password"
                                required autocomplete="new-password" />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                        <div class="mt-3 col-md-6">
                            <InputLabel for="password_confirmation" value="Confirm Password" />
                            <TextInput id="password_confirmation" type="password" class="block w-full mt-1"
                                v-model="form.password_confirmation" required autocomplete="new-password" />
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>
                    </div>
                    <div class="flex flex-col items-end mt-4 col-md-12 justify-content-center align-items-center">
                        <PrimaryButton class="w-full col-md-6" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Register
                        </PrimaryButton>
                        <Link :href="route('login')" class="mt-4 text-sm text-gray-600 underline hover:text-gray-900">
                        Already registered?
                        </Link>
                    </div>
                </form>
            </div>
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

const form = useForm({
    name: "",
    email: "",
    password: "",
    mobile: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>
