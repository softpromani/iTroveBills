<template>
    <Head title="Log in" />

    <GuestLayout>
        <div class="space-y-6">
            <div class="flex justify-center mb-4">
                <Link href="/">
                    <img src="itimages/itlogo.png" alt="Innovation Trove" class="h-16 w-auto drop-shadow-sm" />
                </Link>
            </div>

            <div class="mb-4 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tightest uppercase mb-1">
                    Welcome <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-800 to-pink-700">Back</span>
                </h2>
                <div class="h-1.5 w-16 bg-gradient-to-r from-purple-800 to-pink-700 mx-auto rounded-full shadow-sm"></div>
                <p class="mt-4 text-sm text-gray-500 font-medium">
                    Please sign in to access your dashboard
                </p>
            </div>

            <div v-if="status" class="mb-4 text-sm font-medium text-green-600 text-center bg-green-50 p-2 rounded">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <InputLabel for="email" value="Email Address" />
                    <TextInput id="email" type="email" class="block w-full mt-1.5" v-model="form.email" required autofocus autocomplete="username" placeholder="name@company.com" />
                    <InputError class="mt-1.5" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Password" />
                    <TextInput id="password" type="password" class="block w-full mt-1.5" v-model="form.password" required autocomplete="current-password" placeholder="••••••••" />
                    <InputError class="mt-1.5" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>

                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-medium text-purple-700 hover:text-pink-600 transition-colors duration-200">
                        Forgot your password?
                    </Link>
                </div>

                <div>
                    <PrimaryButton class="w-full flex justify-center py-3 text-base" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Log in
                    </PrimaryButton>
                </div>
                
                <div class="text-center pt-2">
                    <Link :href="route('register')" class="text-sm font-medium text-gray-600 hover:text-purple-700 transition-all duration-200">
                        New customer? <span class="underline decoration-purple-300 decoration-2 underline-offset-4">Sign up here</span>
                    </Link>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>
