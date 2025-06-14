<script setup>
import ContactForm from '@/Components/ContactForm.vue'
import Navbar from "@/Components/Landing/Navbar.vue";
import {Head} from "@inertiajs/vue3";
import Footer from "@/Components/Landing/Footer.vue";

</script>

<template>
    <Head title="Contacto" />
    <div class="min-h-screen flex flex-col">
        <Navbar />
        <div class="flex-grow hero">
            <div class="hero-overlay"></div>
                <div class="hero-content min-h-screen flex flex-col justify-center items-center bg-gray-100 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-end gap-4 mb-4">
                        <a :href="route('landing')" class="btn btn-sm btn-circle bg-white text-blue-600 hover:bg-gray-100" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <h1 class="text-white text-2xl md:text-3xl font-bold">Escríbanos</h1>
                    </div>
                    <div class="max-w-md w-full space-y-8 bg-white p-2 shadow-lg rounded-xl mb-4">
                        <ContactForm />
                    </div>
                </div>
            </div>
        <Footer />
    </div>
</template>

<style scoped>

    .btn-circle {
        border-radius: 50%;
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    @media (max-width: 768px) {
        h1{
            font-size: 1.45rem;
            padding: 0.2rem;
        }
    }

</style>
