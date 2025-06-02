<script setup>
import { onMounted } from 'vue'

onMounted(() => {
    const alertPlaceholder = document.getElementById('liveAlertPlaceholder')

    const appendAlert = (message, type) => {
        if (!alertPlaceholder) return

        const wrapper = document.createElement('div')
        wrapper.innerHTML = `
      <div class="alert alert-${type} alert-dismissible" role="alert">
        <div>${message}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    `
        alertPlaceholder.append(wrapper)
    }

    const understoodBtn = document.getElementById('understoodBtn')
    const modalElement = document.getElementById('whyModal')

    if (understoodBtn && modalElement) {
        understoodBtn.addEventListener('click', () => {
            const modal = bootstrap.Modal.getInstance(modalElement);
            modal.hide();

            setTimeout(() => {
                // 1. Eliminar el backdrop
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();

                // 2. Restaurar estilos del body como el scroll que no aparecía.
                document.body.style.overflow = 'auto';
                document.body.style.paddingRight = '';

                // 3. Eliminar padding-right del header que aparece
                const header = document.querySelector('header');
                if (header) {
                    header.style.paddingRight = '0';
                }

                appendAlert('Se ha registrado la lectura.', 'success');
            }, 150);
        });
    }

    //document.body.style.overflow = 'auto !important';
})

</script>

<template>
    <div class="modal fade" id="whyModal" tabindex="-1" aria-labelledby="whyModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="whyModalLabel">¿Por qué es importante fichar?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>El fichaje es una obligación legal establecida en el Real Decreto-ley 8/2019, que exige a las empresas llevar un registro diario de la jornada laboral de sus empleados. Esto incluye el horario concreto de inicio y finalización de la jornada de trabajo de cada persona trabajadora.</p>
            <p>Beneficios del fichaje:</p>
            <ul>
              <li>Garantiza el cumplimiento de la jornada laboral acordada</li>
              <li>Facilita el control de horas extras</li>
              <li>Mejora la conciliación laboral y familiar</li>
              <li>Aumenta la productividad y la satisfacción de los empleados</li>
              <li>Evita sanciones por incumplimiento de la normativa laboral</li>
            </ul>
            <p>Con TimeTrack, cumplir con esta obligación legal es fácil y beneficioso para tu negocio.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" id="understoodBtn">Entendido</button>
          </div>
        </div>
      </div>
    </div>


    <!-- Alerta visual -->
    <div id="liveAlertPlaceholder"></div>

</template>

<style scoped>

</style>
