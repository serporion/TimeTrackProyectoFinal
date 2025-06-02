<script setup>
import {ref} from 'vue'
import {QrcodeStream} from 'vue-qrcode-reader'

const result = ref('')

const emit = defineEmits(['fichaje-completo'])

//paso1
/*
function onDetect(detectedCodes) {

    //Comun
    /*
    try {
        new Audio('/notification.mp3').play()
    } catch (e) {
        console.warn('[DEBUG] No se pudo reproducir sonido:', e)
    }



    if (detectedCodes.length) {
        result.value = detectedCodes[0].rawValue
        console.log('✅ QR detectado:', result.value)
        alert(`✅ QR detectado: ${result.value}`)

        emit('fichaje-completo', result.value)
    }
}*/

function onDetect(detectedCodes) {
    if (!detectedCodes.length) return;

    const contenido = detectedCodes[0].rawValue;
    console.log('✅ QR detectado:', contenido);

    let qrData;
    try {
        qrData = JSON.parse(contenido);
        console.log('Datos del QR:', qrData)
    } catch (e) {
        alert('❌ QR inválido o mal formado. Asegúrate de escanear un código válido.');
        return;
    }

    // Puedes mostrar info del QR si quieres
    console.log('📦 Datos decodificados:', qrData);
    alert(`✅ QR válido\n\nUsuario: ${qrData.usuario_id || 'desconocido'}`);

    emit('fichaje-completo', qrData); // Lo paso al padre para usarlo.
}



function onError(error) {
    alert('❌ Error cámara: ' + (error.message || error))
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
