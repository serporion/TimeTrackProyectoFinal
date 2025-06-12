<script setup>
import { ref, onMounted } from 'vue'
import Lector from '@/components/LectorQR.vue'
import CamaraFoto from '@/components/CamaraFoto.vue'
import Navbar from "@/Components/Landing/Navbar.vue";
import Footer from "@/Components/Landing/Footer.vue";


const mensajeFinal = ref(null) // Recoje el mensaje del proceso (éxito o error)
const modo = ref('lector')
const finalizado = ref(false)
const qrData = ref(null)


async function registrarFichajeConFoto(data){

    try {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        const formData = new FormData()
        formData.append('_token', csrf)
        //formData.append('qr_data', JSON.stringify(qrData.value))
        //formData.append('foto_id', fotoId.value)
        formData.append('qr_data', JSON.stringify({
            ...qrData.value,
            foto_id: fotoId.value
        }))

        console.log('➡️ Enviando Fichaje:', {
            qr_data: JSON.stringify({
                ...qrData.value,
                foto_id: fotoId.value
            })
        })

        formData.append('clave', qrData.value.clave)


        console.log('Token justo antes del fichaje:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'))

        const res = await fetch('/fichaje/completo', {
            method: 'POST',
            body: formData,
            credentials: 'include',
            //headers: { //NGROKPRUEBA
            //    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //}
        })

        if (!res.ok) {
            if (res.status === 419) {
                console.log('⚠️ La sesión ha expirado. Refresca la página e inténtalo de nuevo.');
                mensajeFinal.value = '⚠️ La sesión ha expirado. Refresca la página e inténtalo de nuevo.';
            } else {
                const html = await res.text()
                console.error('Respuesta inesperada (no JSON):', html)
                mensajeFinal.value = '⛔ Error inesperado al registrar el fichaje.'
            }
            finalizarProceso()
            return
        }

        const json = await res.json()

        if (json.estado === 'ya_usado') {
            mensajeFinal.value = '⚠️ Este código QR ya ha sido usado. Pide uno nuevo.'
            finalizarProceso()
            return
        } else if (json.estado === 'expirado') {
            mensajeFinal.value = '⏱️ El código QR ha expirado. Vuelva a generar uno nuevo.'
            finalizarProceso()
            return
        } else if (json.estado === 'no_existe') {
            mensajeFinal.value = '❌ QR no válido o no encontrado.'
            finalizarProceso()
            return
        } else if (json.estado === 'confirmado') {
            mensajeFinal.value = `✅ Fichaje completado correctamente\n👋 Hola, ${json.nombre || ''}`
            qrData.value = {
                ...data,
                nombre: json.nombre || '',
                advertencia: json.advertencia || ''
            }
            modo.value = 'foto'

            if (json.advertencia) {
                mensajeFinal.value += `\n\n⚠️ ${json.advertencia}`
            }

            try {
                new Audio('/notification.m4a').play()
            } catch (e) {
                console.warn('[DEBUG] No se pudo reproducir sonido:', e)
            }

            finalizarProceso()

        } else {
            mensajeFinal.value = '❌ Error desconocido al registrar el fichaje.'
            finalizarProceso()
        }

    } catch (err) {
        mensajeFinal.value = '❌ Error al validar el QR'
        console.error(err)
        finalizarProceso()
    } finally {
        //finalizarProceso()
    }
}

async function onFichajeEscaneado(data) {
    try {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        const formData = new FormData()
        formData.append('_token', csrf)
        formData.append('qr_data', JSON.stringify(data))
        formData.append('clave', data.clave)

        const res = await fetch('/fichaje/validar', {
            method: 'POST',
            body: formData,
            credentials: 'include'
        })

        if (!res.ok) {
            const json = await res.json()
            mensajeFinal.value = `❌ Error: ${json.error || 'Fallo al validar QR'}`
            finalizarProceso()
            return
        }

        const json = await res.json()

        if (json.estado === 'expirado') {
            mensajeFinal.value = '⏱️ El código QR ha expirado.'
            finalizarProceso()
            return
        }

        if (json.estado === 'ya_usado') {
            mensajeFinal.value = '⚠️ Este código QR ya ha sido usado.'
            finalizarProceso()
            return
        }

        if (json.estado === 'no_existe') {
            mensajeFinal.value = '❌ QR no válido o no encontrado.'
            finalizarProceso()
            return
        }

        // ✅ QR válido: paso al modo de captura
        qrData.value = {
            ...data,
            nombre: json.nombre || '',
        }
        modo.value = 'foto'

    } catch (err) {
        console.log('hola')
        console.error(err)
        mensajeFinal.value = '❌ Error al validar el QR'
        finalizarProceso()
    }
}


const result = ref('')

onMounted(() => {
    if ('wakeLock' in navigator) {
        navigator.wakeLock.request('screen').catch(err => {
            console.warn('No se pudo mantener la pantalla encendida:', err)
        })
    }
})


function onError(mensaje) {
    mensajeFinal.value = '❌ ' + mensaje
    console.log('[ERROR LECTOR]', mensaje)
    finalizarProceso()
}

const reiniciarProceso = () => {
    mensajeFinal.value = '📷 Aproxime su QR para fichar'
    finalizado.value = false
    qrData.value = null
}


const finalizarProceso = () => {
    finalizado.value = true
    modo.value = 'lector'
    setTimeout(() => {
        reiniciarProceso()
    }, 7000)
}


const fotoId = ref(null) //nuevo

function onFotoSubida(id) {
    fotoId.value = id
    //enviarFichaje()
    registrarFichajeConFoto()
}

function onFotoSubidaError(mensaje) {
    mensajeFinal.value = '❌ Error al subir la imagen: ' + mensaje
    console.log('[ERROR FOTOGRAFÍA]', mensaje)
    finalizarProceso()
}

</script>

<template>
    <div class="min-h-screen flex flex-col">
        <Navbar />
        <div class="hero-overlay"></div>
        <div class="flex-grow hero-content">
            <div class="p-4 max-w-lg mx-auto">
                <h1 class="text-2xl font-bold text-center mb-4">Modo Terminal</h1>

                <div v-if="modo === 'lector'">

                    <Lector
                        @fichaje-completo="onFichajeEscaneado"
                        @error="onError"
                    />

                </div>

                <div v-else-if="modo === 'foto'">

                    <CamaraFoto
                        :nombreEmpleado="qrData?.usuario_id"
                        @foto-subida="onFotoSubida"
                        @foto-subida-error="onFotoSubidaError"
                    />
                </div>

                <div v-if="finalizado" :class="mensajeFinal?.includes('Error') ? 'text-red-600' : 'text-blue-800'" class="text-center mt-4 mb-4 font-semibold whitespace-pre-line">
                    {{ mensajeFinal }}
                </div>

                <div v-else
                     class="text-center mt-4 mb-4 font-semibold text-blue-800">
                    📷 Aproxime su QR para fichar
                </div>

            </div>
        </div>
        <Footer />
    </div>
</template>
