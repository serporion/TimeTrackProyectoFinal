<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { route } from 'ziggy-js'

const props = defineProps({
    nombreEmpleado: [String, Number]
})

const emit = defineEmits(['foto-subida', 'foto-subida-error'])


const video = ref(null)
const canvas = ref(null)


let stream = null

onMounted(async () => {

    //console.log('Ruta foto.store:', route('foto.store'));
    //console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.content)

    try {
        // Se pide acceso a la cámara
        stream = await navigator.mediaDevices.getUserMedia({ video: true })
        video.value.srcObject = stream

        // Pongo un delay breve y luego capturo la imagen.
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
        //const res = await fetch('/foto', {
        //const res = await fetch(route('foto.store'), {
        const res = await fetch(`${window.location.origin}/foto`, {
            method: 'POST',
            body: formData,
            credentials: 'include',
            headers: { //NGROKPRUEBA
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })


        const data = await res.json() //nuevo
        const fotoId = data.foto_id //nuevo

        console.log('Foto subida correctamente ✅')

        console.log('Token tras subir foto:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'))

        emit('foto-subida', fotoId)
    } catch (error) {
        console.error('Error al subir la imagen ❌', error)
        emit('foto-subida-error', error.message || 'Error desconocido')
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
