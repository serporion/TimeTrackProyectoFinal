<script setup>
import {reactive, ref, onMounted, computed} from 'vue'
    import axios from 'axios'
    import { usePage } from '@inertiajs/vue3'

    const page = usePage()
    const user = computed(() => page.props.auth?.user)

    const desdeBotonProblemas = computed(() => page.props.desdeBotonProblemas)

    /*
    const form = reactive({
        nombre: user.value?.name || '',
        apellidos: '',
        email: user.value?.email || '',
        departamento: page.props.departamento || '',
        mensaje: page.props.mensajePredeterminado || '',
        telefono: ''
    })
    */

    const form = reactive({
        nombre: desdeBotonProblemas.value ? (user.value?.name || '') : '',
        apellidos: '',
        email: desdeBotonProblemas.value ? (user.value?.email || '') : '',
        departamento: desdeBotonProblemas.value ? (page.props.departamento || '') : '',
        mensaje: desdeBotonProblemas.value ? (page.props.mensajePredeterminado || '') : '',
        telefono: ''
    })

    const errors = reactive({})
    const exito = ref(false)

    const validate = () => {

        const soloNumeros = /^\d+$/;

        errors.nombre = form.nombre ? '' : 'El nombre es obligatorio.'
        //errors.apellidos = form.apellidos ? '' : 'Los apellidos son obligatorios.'
        errors.email = form.email.includes('@') ? '' : 'Correo inválido.'
        errors.mensaje = form.mensaje.length >= 10 ? '' : 'El mensaje debe tener al menos 10 caracteres.'
        //return !errors.nombre && !errors.apellidos && !errors.email && !errors.mensaje
        errors.telefono = user.value
            ? (!form.telefono
                ? 'Obligatorio para poder llamarte.'
                : (!soloNumeros.test(form.telefono)
                    ? 'El teléfono solo puede contener números.'
                    : ''))
            : '';
        return !errors.nombre && !errors.email && !errors.mensaje && (!user.value || !errors.telefono)
    }

const handleSubmit = async () => {
    if (validate()) {
        try {
            await axios.post('/contacto', { ...form })

            exito.value = true
            /* Muestra de nuevo el formulario. Mejor mostramos mensaje de que todo ok y volver. */
            //Object.keys(form).forEach(key => form[key] = '') // Limpia todos los campos.
            //form.departamento = 'soporte'
        } catch (e) {
            if (e.response && e.response.status === 422) {
                const backendErrors = e.response.data.errors;
                Object.keys(backendErrors).forEach(field => {
                    errors[field] = backendErrors[field][0];
                });
            } else {
                alert('Error al enviar el formulario. Intente más tarde.')
            }
        }
    }
}

</script>

<template>
    <div class="bg-gray-50 py-3 px-2 flex items-center justify-center">
        <div class="w-full max-w-2xl bg-white p-3 md:p-6 rounded-xl shadow-lg">
            <form v-if="!exito" @submit.prevent="handleSubmit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" v-model="form.nombre" class="input" :class="{ 'border-red-500': errors.nombre }" />
                    <p v-if="errors.nombre" class="text-red-500 text-sm mt-1">{{ errors.nombre }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Apellidos</label>
                    <input type="text" v-model="form.apellidos" class="input" :class="{ 'border-red-500': errors.apellidos }" />
                    <p v-if="errors.apellidos" class="text-red-500 text-sm mt-1">{{ errors.apellidos }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                    <input type="email" v-model="form.email" class="input" :class="{ 'border-red-500': errors.email }" />
                    <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Departamento</label>
                    <select v-model="form.departamento" class="input">
                        <option disabled value="">Selecciona uno</option>
                        <option value="comercial">Departamento Comercial</option>
                        <option value="tecnico">Departamento Técnico</option>
                        <option value="soporte">Incidencias</option>
                        <option value="otros">Otros</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Teléfono de contacto</label>
                    <input
                        type="tel"
                        v-model="form.telefono"
                        class="input"
                        placeholder="Introduce tu teléfono para poder llamarte"
                        pattern="[0-9]*"
                    />
                    <p v-if="errors.telefono" class="text-red-500 text-sm mt-1">{{ errors.telefono }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mensaje</label>
                    <textarea v-model="form.mensaje" class="input" rows="5" :class="{ 'border-red-500': errors.mensaje }"></textarea>
                    <p v-if="errors.mensaje" class="text-red-500 text-sm mt-1">{{ errors.mensaje }}</p>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success md:w-auto text-white font-semibold rounded hover:bg-blue-700">
                        Enviar
                    </button>
                </div>
            </form>
            <div v-else class="text-green-600 text-center font-medium mt-6 text-lg">
                ✅ Gracias por tu mensaje. Nos pondremos en contacto contigo lo antes posible.
            </div>
        </div>
    </div>
</template>

<style scoped>
    .input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #cbd5e0;
        border-radius: 0.375rem;
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .input {
            font-size: 0.70rem;
            padding: 0.2rem;
        }
        button {
            font-size: 0.75rem;
            padding: 0.5rem 1rem;
        }
        label {
            font-size: 0.75rem;
        }
    }
</style>

