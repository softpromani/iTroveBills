<template>
    <Head title="Log in" />

    <GuestLayout>
        <div class="row">
            <Link href="/" class="flex items-center justify-center">
               <img src="#" alt="" class="h-20 text-gray-500 fill-current w-30">
               <!-- <img src="itimages/itlogo.png" alt="" class="h-20 text-gray-500 fill-current w-30"> -->
            </Link>

            <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="email" class="block w-full mt-1" v-model="form.email" required autofocus autocomplete="username" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-3">
                    <InputLabel for="password" value="Password" />
                    <TextInput id="password" type="password" class="block w-full mt-1" v-model="form.password" required autocomplete="current-password" />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="flex justify-between mt-4">
                    <label class="inline-flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="mx-2 text-sm text-gray-600">Remember me</span> </label>

                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm text-gray-600 underline hover:text-gray-900">
                        Forgot your password?
                    </Link>
                </div>

                <div class="mt-6">
                    <PrimaryButton class="w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Log in
                    </PrimaryButton>
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
