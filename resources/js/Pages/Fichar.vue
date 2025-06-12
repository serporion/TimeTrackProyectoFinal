<script setup>
import { onMounted, ref } from 'vue'
import {Head, router} from '@inertiajs/vue3'
import Navbar from "@/Components/Landing/Navbar.vue";
import Footer from "@/Components/Landing/Footer.vue";
import { route } from "ziggy-js";

const tipo = ref('')
const fechaLegible = ref('')
const qrId = ref(null)
const usuarioId = ref(null) //PruebaFichaje
const qrSvg = ref('')
const mensaje = ref('')
let intervalId = null

//const qrUrl = ref('')

onMounted(async () => {
    const token = localStorage.getItem('token')
    const res = await fetch(route('qr.datos-fichaje'))
    const data = await res.json()

    tipo.value = data.tipo
    fechaLegible.value = data.fecha_legible
    qrId.value = data.qr_id // <--- Aquí guardamos el ID del qr relacionado
    //qrUrl.value = `/api/empleado/qr?tipo=${data.tipo}` // aún se usa para mostrar imagen
    //qrUrl.value = route('qr.imagen') + `?tipo=${data.tipo}`

    qrSvg.value = data.svg

    usuarioId.value = data.usuario_id //PruebaFichaje

    intervalId = setInterval(verificarFichaje, 2000) //Polling. Dará errores si se abre en el mismo dispositivo el
                                                            //terminal que lee qr, como el terminal que construye el qr.
})

const verificarFichaje = async () => {

    const res = await fetch(route('qr.verificar-fichaje', qrId.value))
    const data = await res.json()

    if (data.estado === 'confirmado') {
        clearInterval(intervalId)
        mensaje.value = `✅ Has fichado tu ${data.tipo} el ${data.hora}`
    }

    if (data.estado === 'expirado') {
        clearInterval(intervalId)
        alert('⚠️ El QR ha expirado. Genera uno nuevo navegando a "Fichar" ')
        //router.visit('/dashboard') // Redirige al inicio.
    }
}

// PruebaFichaje. No para producción.
const ficharManual = async () => {

    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

    const qrData = {
        usuario_id: usuarioId.value, // localStorage.getItem('usuario_id'),
        tipo: tipo.value,
        qr_id: qrId.value
    }

    const formData = new FormData()
    formData.append('_token', csrf)
    formData.append('qr_data', JSON.stringify(qrData))
    formData.append('clave', localStorage.getItem('clave_token_persistente')) //tokenPX

    /*
    formData.append('qr_data', JSON.stringify({ //nuevo
        ...qrData.value,
        foto_id: fotoId.value
    }))
    */

    // No enviamos imagen real. Modo prueba
    /*
    const res = await fetch('/api/fichaje/completo', {
        method: 'POST',
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`
        },
        body: formData
    })
    */

    const res = await fetch(route('fichaje.completo'), {
        method: 'POST',
        body: formData
    })


    const data = await res.json()

    if (res.ok) {
        mensaje.value = `✅ Fichaje simulado: ${qrData.tipo} a las ${data.fichaje.timestamp}`
    } else {
        alert('❌ Error al fichar (modo prueba)')
        console.error(data)
    }
}

const salirAYuda = () => {
    clearInterval(intervalId)
    router.visit(route('ayuda.fichaje'))
}

const salirAContacto = () => {
    clearInterval(intervalId)
    router.visit(route('contacto', {
        desdeBotonProblemas: true,
        departamento: 'soporte',
        mensajePredeterminado: `Estoy teniendo problemas con mi fichaje. Pónganse en contacto conmigo. Gracias`
    }))
}

</script>

<template>
    <Head title="Fichar "/>
    <div class="min-h-screen">
        <Navbar />
        <div class="flex-grow">
            <div id="qrIma" class="text-center">
                <h2 class="text-xl font-bold mb-4">
                    Vas a fichar tu {{ tipo }} el día:
                </h2>
                <p class="mb-4 text-lg font-medium">{{ fechaLegible }}</p>

                <!--<img :src="qrUrl" alt="QR de fichaje" class="mx-auto w-64 h-64" />-->

                <!-- QR SVG incrustado -->
                <div class=" border-2 border-gray-300 rounded-xl p-4 shadow-md animate-fade-in max-w-xs w-full v-html" v-html="qrSvg"></div>

                <!-- //PruebaFichaje -->
                <div class="flex items-center justify-center w-3/4 gap-3 py-3">
                    <button
                        class="hidden bg-green-600 hover:bg-green-700 text-white font-semibold text-sm px-6 py-3 rounded-lg shadow"
                        @click="ficharManual"
                    >
                        Fichar ahora (modo prueba)
                    </button>
                    <button
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold text-sm px-6 py-2.5 rounded-lg shadow"
                        @click="salirAYuda"
                    >
                        ¿Dudas?
                    </button>
                    <button
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold text-sm px-6 py-2.5 rounded-lg shadow"
                        @click="salirAContacto"
                    >
                        ¿Problemas?
                    </button>
                </div>
            </div>

            <div v-if="mensaje" class="alert alert-success mt-4">
                {{ mensaje }}
            </div>

        </div>
        <Footer />
    </div>
</template>

<style scoped>

#qrIma {
    margin-top: 100px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

@keyframes fade-in {
    0% {
        opacity: 0;
        transform: scale(0.95);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-fade-in {
    animation: fade-in 0.4s ease-out forwards;
}

</style>
