<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Navbar from "@/Components/Landing/Navbar.vue";
import Footer from "@/Components/Landing/Footer.vue";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>

    <div class="min-h-screen flex flex-col">
        <Navbar />

        <div class="hero flex-grow hero-content">
            <div class="hero-overlay"></div>

            <div class="hero-content">
                <h1 class="text-white text-3xl text-center font-semibold">
                    Cambie su contraseña
                </h1>
            </div>

            <GuestLayout class="hero-content">
                <Head title="Forgot Password" />

                <div class="mb-4 text-sm text-gray-600">
                    ¿Olvidó su contraseña o, simplemente quiere cambiarla?. Escríbanos
                    su correo electrónico con el que esté dado de alta en el sistema y
                    recibirá un link para poder cambiarla.
                </div>

                <div
                    v-if="status"
                    class="mb-4 text-sm font-medium text-green-600"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit">
                    <div>
                        <InputLabel for="email" value="Email" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mt-4 flex items-center justify-end">
                        <PrimaryButton
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Email Password Reset Link
                        </PrimaryButton>
                    </div>
                </form>
            </GuestLayout>
        </div>
        <Footer />
    </div>
</template>
