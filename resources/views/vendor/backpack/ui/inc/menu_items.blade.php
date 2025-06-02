{{-- This file is used for menu items by any Backpack v6 theme --}}
<!--<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>-->
<li class="nav-item">
    <a class="nav-link" href="{{ route('dashboard') }}">
        <i class="la la-home nav-icon"></i> Mi Dashboard
    </a>
</li>

<x-backpack::menu-item title="Usuarios" icon="la la-question" :link="backpack_url('usuario')" />
<!--<x-backpack::menu-item title="Credenciales" icon="la la-question" :link="backpack_url('credencial')" />-->
<x-backpack::menu-item title="Administradores" icon="la la-question" :link="backpack_url('administrador')" />
<x-backpack::menu-item title="Contratos" icon="la la-question" :link="backpack_url('contrato')" />
<x-backpack::menu-item title="Informes" icon="la la-question" :link="backpack_url('informe')" />
<x-backpack::menu-item title="Auditorías" icon="la la-question" :link="backpack_url('auditoria')" />
