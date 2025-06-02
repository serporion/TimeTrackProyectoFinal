<script setup>
import { ref, onMounted } from 'vue'
import Lector from '@/components/LectorQR.vue'
import CamaraFoto from '@/components/CamaraFoto.vue'
import Navbar from "@/Components/Landing/Navbar.vue";
import Footer from "@/Components/Landing/Footer.vue";


const handleInit = (event) => {
    console.log('Evento INIT recibido en padre:', event)
}

const handleDecode = (content) => {
    console.log('Evento DECODE recibido en padre:', content)
}

const handleError = (error) => {
    console.error('Evento ERROR recibido en padre:', error)
}

const modo = ref('lector')
const finalizado = ref(false)
const qrData = ref(null)

function onFichajeCompleto(data) {
    qrData.value = data
    modo.value = 'foto'
}

const result = ref('')

onMounted(() => {
    if ('wakeLock' in navigator) {
        navigator.wakeLock.request('screen').catch(err => {
            console.warn('No se pudo mantener la pantalla encendida:', err)
        })
    }
})

/*
function onDetect(detectedCodes) {
    if (detectedCodes.length) {
        result.value = detectedCodes[0].rawValue
        alert(`✅ QR detectado: ${result.value}`)
    }
}
 */

function onError(error) {
    alert('❌ Error cámara: ' + (error.message || error))
    console.error(error)
}


const finalizarProceso = () => {
    finalizado.value = true
    modo.value = 'lector'
}


const fotoId = ref(null) //nuevo

function onFotoSubida(id) {
    fotoId.value = id
    enviarFichaje()
}

async function enviarFichaje() {
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


        console.log('Token justo antes del fichaje:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'))

        const res = await fetch('/fichaje/completo', {
            method: 'POST',
            body: formData,
            credentials: 'include'
        })



        if (!res.ok) {
            if (res.status === 419) {
                alert('⚠️ La sesión ha expirado. Refresca la página e inténtalo de nuevo.')
            } else {
                const html = await res.text()
                console.error('Respuesta inesperada (no JSON):', html)
            }
            return
        }

        const json = await res.json()

        if (json.error) {
            alert(`⛔ Error: ${json.error}`)
        } else {
            alert('✅ Fichaje completado correctamente')
            if (json.advertencia) {
                alert(json.advertencia)
            }
        }
    } catch (err) {
        alert('❌ Error al registrar el fichaje')
        console.error(err)
    } finally {
        finalizarProceso()
    }
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
                    <!--
                    <Lector @fichaje-completo="modo = 'foto'"
                        @init="handleInit"
                        @decode="handleDecode"
                        @error="handleError"
                    />-->

                    <Lector
                        @fichaje-completo="onFichajeCompleto"
                        @init="handleInit"
                        @decode="handleDecode"
                        @error="handleError"
                    />

                </div>

                <div v-else-if="modo === 'foto'">
                    <!--
                    <CamaraFoto @foto-subida="finalizarProceso" />
                    -->
                    <CamaraFoto :nombreEmpleado="qrData?.usuario_id" @foto-subida="onFotoSubida" />
                </div>

                <div v-if="finalizado" class="text-green-600 text-center mt-4 font-semibold">
                    ✅ Proceso completo
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>
