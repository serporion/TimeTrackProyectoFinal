<h2>Nuevo mensaje de contacto</h2>

<p><strong>Nombre:</strong> {{ $datos['nombre'] }} {{ $datos['apellidos'] }}</p>
<p><strong>Email:</strong> {{ $datos['email'] }}</p>
<p><strong>Departamento:</strong> {{ ucfirst($datos['departamento']) }}</p>

@if(!empty($datos['telefono']))
    <p><strong>Teléfono:</strong> {{ $datos['telefono'] }}</p>
@endif

<p><strong>Mensaje:</strong></p>
<p>{{ $datos['mensaje'] }}</p>
