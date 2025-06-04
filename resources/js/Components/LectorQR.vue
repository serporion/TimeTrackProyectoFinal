<script setup>
import {ref} from 'vue'
import {QrcodeStream} from 'vue-qrcode-reader'

const result = ref('')

const emit = defineEmits(['fichaje-completo', 'error'])

function onDetect(detectedCodes) {
    if (!detectedCodes.length) return;

    const contenido = detectedCodes[0].rawValue;
    console.log('✅ QR detectado:', contenido);

    let qrData;
    try {
        qrData = JSON.parse(contenido);
        console.log('Datos decodificados:', qrData)

        if (!qrData.usuario_id) {
            emit('error', 'El QR no contiene un usuario_id válido.');
            return;
        }

        emit('fichaje-completo', qrData); // Lo paso al padre para usarlo.
    } catch (e) {
        //alert('❌ QR inválido o mal formado. Asegúrate de escanear un código válido.');
        emit('error', 'QR inválido o mal formado. Asegúrate de escanear un código válido.')

    }
}

function onError(error) {
    //alert('❌ Error cámara: ' + (error.message || error))
    emit('error', 'Error de cámara: ' + (error.message || 'desconocido'))
    console.error(error)
}

function paintOutline(detectedCodes, ctx) {
    for (const detectedCode of detectedCodes) {
        const [firstPoint, ...otherPoints] = detectedCode.cornerPoints

        ctx.strokeStyle = 'red'
        ctx.lineWidth = 4

        ctx.beginPath()
        ctx.moveTo(firstPoint.x, firstPoint.y)
        for (const { x, y } of otherPoints) {
            ctx.lineTo(x, y)
        }
        ctx.lineTo(firstPoint.x, firstPoint.y)
        ctx.stroke()
    }
}

</script>

<template>
    <div>
        <qrcode-stream
            :constraints="{ facingMode: 'user' }"
            @detect="onDetect"
            @error="onError"
            :track="paintOutline"
        />
        <div v-if="result">
            <p>Resultado: {{ result }}</p>
        </div>
    </div>
</template>
