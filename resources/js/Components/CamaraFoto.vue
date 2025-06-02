<script setup>
import { ref, onMounted, onUnmounted, useAttrs } from 'vue'

const props = defineProps({
    nombreEmpleado: [String, Number]
})

const emit = defineEmits(['foto-subida'])

const video = ref(null)
const canvas = ref(null)


let stream = null

onMounted(async () => {
    try {
        // 🟢 Solicita acceso a la cámara
        stream = await navigator.mediaDevices.getUserMedia({ video: true })
        video.value.srcObject = stream

        // 🕒 Espera breve y luego captura automáticamente
        setTimeout(() => {
            capturarYSubir()
        }, 1500)
    } catch (error) {
        alert('Error al acceder a la cámara: ' + error.message)
    }
})


// Libera la cámara si el componente se desmonta.
onUnmounted(() => {
    detenerCamara()
})

const detenerCamara = () => {
    if (stream) {
        stream.getTracks().forEach(track => track.stop())
        stream = null
    }
}



const capturarYSubir = async () => {
    const context = canvas.value.getContext('2d')
    canvas.value.width = video.value.videoWidth
    canvas.value.height = video.value.videoHeight
    context.drawImage(video.value, 0, 0, canvas.value.width, canvas.value.height)

    const dataUrl = canvas.value.toDataURL('image/jpeg')
    const blob = await (await fetch(dataUrl)).blob()

    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    const formData = new FormData()
    formData.append('imagen', blob, 'captura.jpg')
    formData.append('_token', csrf)
    formData.append('nombre_empleado', props.nombreEmpleado || 'desconocido')


    try {
        const res = await fetch('/foto', {
            method: 'POST',
            body: formData,
            credentials: 'include'
        })

        /*
        if (!res.ok) {
            const html = await res.text()
            console.error('Respuesta inesperada (no JSON):', html)
            return
        }
        */

        /*
        if (!res.ok) {
            if (res.status === 419) {
                alert('⚠️ La sesión ha expirado. Refresca la página e inténtalo de nuevo.')
            } else {
                const html = await res.text()
                console.error('Respuesta inesperada (no JSON):', html)
            }
            return
        }

         */

        const data = await res.json() //nuevo
        const fotoId = data.foto_id //nuevo

        alert('Foto subida correctamente ✅')

        console.log('Token tras subir foto:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'))

        emit('foto-subida', fotoId)
    } catch (error) {
        alert('Error al subir la imagen ❌')
        console.error(error)
    }
}

</script>

<template>
    <div class="p-4 border rounded-2xl shadow-md max-w-md mx-auto">
        <h2 class="text-xl font-semibold mb-4">Capturando imagen...</h2>
        <video ref="video" autoplay class="rounded-xl w-full h-auto"></video>
        <canvas ref="canvas" class="hidden"></canvas>
    </div>
</template>


<style scoped>

</style>
