<script setup>
import { Head } from '@inertiajs/vue3'
import Navbar from '@/Components/Landing/Navbar.vue'
import Footer from '@/Components/Landing/Footer.vue'
import {onMounted} from "vue";

onMounted(() => {
    const cards = document.querySelectorAll('#hero-caracteristicas .card-custom')

    if (cards.length > 0) {
        setTimeout(() => {
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('animated')
                }, 100 * index)
            })
        }, 300)

        if (window.innerWidth >= 768) {
            cards.forEach(card => {
                card.addEventListener('mousemove', function (e) {
                    const rect = this.getBoundingClientRect()
                    const x = e.clientX - rect.left
                    const y = e.clientY - rect.top
                    const xc = rect.width / 2
                    const yc = rect.height / 2
                    const dx = x - xc
                    const dy = y - yc
                    this.style.transform = `perspective(1000px) rotateY(${dx / 20}deg) rotateX(${-dy / 20}deg) translateY(-8px)`
                })
                card.addEventListener('mouseleave', function () {
                    this.style.transform = ''
                })
            })
        }
    }
})

</script>

<template>
    <Head title="Características" />
    <div class="min-h-screen flex flex-col">
        <Navbar />

        <section id="hero-caracteristicas" class="py-12 bg-white mt-16">
            <div class="max-w-4xl mx-auto px-4">
                <div class="bg-white shadow-lg rounded-xl p-6 md:p-10">
                    <div class="text-center mb-10">
                        <h1 class="text-3xl sm:text-4xl font-bold mb-4 !text-[#4a5568]">
                            Cumplimiento y Gestión
                        </h1>
                        <p class="text-lg text-gray-700">
                            Nuestra solución combina tecnología QR, fotografía en fichajes, y una gestión de horarios intuitiva para ayudarte a cumplir la ley y optimizar tu empresa.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6">
                        <!-- Card 1 -->
                        <div class="card-custom">
                            <h5 class="card-title">✔️ Fichaje fácil y seguro</h5>
                            <p class="card-text">
                                Los empleados fichan escaneando un QR desde un terminal autorizado. El sistema capta su imagen con lo que se garantizar la autenticidad y prevenir fraudes.
                            </p>
                        </div>

                        <!-- Card 2 -->
                        <div class="card-custom">
                            <h5 class="card-title">📊 Informes mensuales</h5>
                            <p class="card-text">
                                Posibilidad de generar resúmenes de horas trabajadas, ver las diferencias con el horario pactado y alertas en caso de irregularidades.
                            </p>
                        </div>

                        <!-- Card 3 -->
                        <div class="card-custom">
                            <h5 class="card-title">🔒 Cumplimiento legal</h5>
                            <p class="card-text">
                                Adaptado a la normativa española vigente y preparado para la nueva ley de 2026 sobre control horario digital obligatorio.
                            </p>
                        </div>

                        <!-- Card 4 -->
                        <div class="card-custom">
                            <h5 class="card-title">⚙️ Automatización y control</h5>
                            <p class="card-text">
                                Detecta de forma automática si el fichaje es de entrada o salida. Gestiona contratos, jornadas semanales y seguimiento del personal en tiempo real.
                            </p>
                        </div>

                        <!-- Card 5 -->
                        <div class="card-custom col-span-2 md:col-span-2">
                            <h5 class="card-title">🌐 Adaptable a cualquier dispositivo</h5>
                            <p class="card-text">
                                Funciona perfectamente en móviles, tablets y ordenadores. Ideal para entornos como restaurantes, bares, hoteles y pymes con necesidades reales.
                            </p>
                        </div>
                    </div>
                    <div class="text-center mt-8">
                        <a :href="route('contacto')" id="boton-demo" class="btn-demo">
                            Quiero una demo
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <Footer />
    </div>
</template>

<style scoped>

    #hero-caracteristicas .card-custom {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease;
    }

    #hero-caracteristicas .card-custom.animated {
        opacity: 1;
        transform: translateY(0);
    }

    #hero-caracteristicas .card-custom {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        padding: 1rem;
        position: relative;
        overflow: hidden;
    }

    #hero-caracteristicas .card-custom::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(to right, #4a5568, #6c757d);
        opacity: 0.8;
    }

    #hero-caracteristicas .card-custom:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
    }

    #hero-caracteristicas .card-title {
        font-weight: 700;
        margin-bottom: 0.8rem;
        font-size: 0.95rem;
        position: relative;
        padding-bottom: 0.5rem;
    }

    #hero-caracteristicas .card-title::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 30px;
        height: 2px;
        background: #4a5d68;
        border-radius: 2px;
    }

    #hero-caracteristicas .card-text {
        color: #566a88;
        font-size: 0.8rem;
        line-height: 1.5;
    }

    .btn-demo {
        border-radius: 10px;
        text-decoration: none !important;
        padding: 0.6rem 1rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        background: linear-gradient(to right, #4a5568, #5d636b);
        border: none;
        box-shadow: 0 4px 15px rgba(58, 123, 213, 0.4);
        color: #fff;
    }

    .btn-demo::after {
        content: "";
        position: absolute;
        width: 0;
        height: 100%;
        top: 0;
        left: 0;
        background: rgba(255, 255, 255, 0.1);
        transition: width 0.3s ease;
        z-index: 0;
        border-radius: inherit;
    }

    .btn-demo:hover::after {
        width: 100%;
    }

    .btn-demo:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(58, 123, 213, 0.6);
    }

    .btn-demo:active {
        transform: translateY(1px);
    }

    @media (min-width: 768px) {
        #hero-caracteristicas .card-title {
            font-size: 1.4rem;
            margin-bottom: 1rem;
            padding-bottom: 0.8rem;
        }

        #hero-caracteristicas .card-text {
            font-size: 0.95rem;
            line-height: 1.6;
        }

        #hero-caracteristicas .card-custom {
            padding: 1.8rem;
        }

        #hero-caracteristicas .card-title::after {
            width: 40px;
            height: 3px;
        }
    }
</style>

