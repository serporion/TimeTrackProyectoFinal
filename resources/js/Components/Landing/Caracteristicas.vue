<script setup>
import '../../../css/style.css'
//import './bootstrap'; // CSSCAMBIO
import '@/bootstrap'; // CSSCAMBIO

import { onMounted } from 'vue'

const cards = [
    {
        title: 'Fichaje Fácil',
        text: 'Registro de entrada y salida con un solo clic. Interfaz intuitiva para todos los empleados.',
        popover: 'Nuestra app permite a los empleados fichar de forma rápida y sencilla, ahorrando tiempo y reduciendo errores.',
        type: 'popover'
    },
    {
        title: 'Gestión Eficiente',
        text: 'Control de horarios, turnos y horas extras en tiempo real. Reportes automáticos para la dirección.',
        popover: 'Optimiza la gestión de personal con informes detallados y control de horas trabajadas en tiempo real.',
        type: 'popover'
    },
    {
        title: '¿Por qué Fichar?',
        text: 'Cumplimiento legal y beneficios para tu negocio.',
        type: 'modal'
    }
]

onMounted(() => {
    // Inicializar popovers manualmente
    const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');

    popovers.forEach(p => {
        const popoverInstance = new bootstrap.Popover(p, {
            trigger: 'manual' // Desactivamos el trigger automático
        });

        // Mostrar popover al hacer clic
        p.addEventListener('click', function(e) {
            e.stopPropagation();
            popoverInstance.toggle();
        });

        // Evitar que el popover se cierre al tocar su contenido
        p.addEventListener('shown.bs.popover', function() {
            const popoverElement = document.querySelector('.popover');
            if (popoverElement) {
                popoverElement.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    });

    // Cerrar popovers al hacer clic fuera de ellos
    document.addEventListener('click', function(e) {
        // Verificar si el clic fue en un popover o en su botón trigger
        const isPopover = e.target.closest('.popover') ||
            e.target.closest('[data-bs-toggle="popover"]');

        if (!isPopover) {
            popovers.forEach(p => {
                const instance = bootstrap.Popover.getInstance(p);
                if (instance) {
                    instance.hide();
                }
            });
        }
    });

    // Animaciones en tarjetas
    const cards = document.querySelectorAll('.caracteristicas .card');
    if (cards.length > 0) {
        setTimeout(() => {
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('animated');
                }, 100 * index);
            });
        }, 300);

        if (window.innerWidth >= 768) { // Efectos quitados en móvil. Descarga de recursos.
            cards.forEach(card => {
                card.addEventListener('mousemove', function(e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const xc = rect.width / 2;
                    const yc = rect.height / 2;
                    const dx = x - xc;
                    const dy = y - yc;
                    this.style.transform = `perspective(1000px) rotateY(${dx / 20}deg) rotateX(${-dy / 20}deg) translateY(-8px)`;
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = '';
                });
            });
        }
    }

})
</script>

<template>
    <section id="caracteristicas" class="py-5 caracteristicas">
        <div class="container">
            <div class="row g-3 justify-content-center">
            <!-- Tarjeta 1 -->
                <div class="col-md-4" v-for="(card, index) in cards" :key="index"
                     :class="[
                          'col-6',
                          index === 2 ? 'col-12' : '',
                          'mb-3'
                    ]"
                >
                    <div class="card h-100 d-flex flex-column">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ card.title }}</h5>
                            <p class="card-text flex-grow-1">{{ card.text }}</p>
                            <button
                                v-if="card.type === 'popover'"
                                type="button"
                                class="btn btn-outline-primary"
                                :data-bs-toggle="'popover'"
                                :data-bs-placement="'top'"
                                data-bs-custom-class="custom-popover"
                                :data-bs-title="card.title"
                                :data-bs-content="card.popover"
                            >
                                Más información
                            </button>
                            <button
                                v-else
                                type="button"
                                class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#whyModal"
                            >
                                Descubre por qué
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>



<style scoped>

@media (max-width: 767px) {
    #caracteristicas .card .card-title {
        font-size: 0.9rem;
    }

    #caracteristicas .card .card-text {
        font-size: 0.8rem;
    }

    #caracteristicas .card .btn {
        font-size: 0.8rem;
        padding: 0.5rem 1.2rem;
    }
    #caracteristicas .card-body {
        padding: 0.5rem;
    }
}


</style>
