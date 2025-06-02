<?php
//dd('Botón cargado dentro de la vista')?>
{{-- dd('Vista encontrada correctamente.') --}}

@if (!isset($entry))
    <div style="color: red;">No hay $entry disponible</div>
@endif

<button class="btn btn-warning">Probando botón</button>

@if (isset($entry))
    <a href="{{ url($crud->route.'/'.$entry->getKey().'/regenerar-credencial') }}"
       class="btn btn-sm btn-outline-primary"
       data-style="zoom-in">
        <i class="la la-refresh"></i> Regenerar Credencial
    </a>
@endif


<?php
//dd('Sale de la busqueda del Botón') ?>
