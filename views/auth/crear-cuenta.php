<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">Llena el siguiente formulario para crear una cuenta</p>

<?php 
    include_once __DIR__ ."/../templates/alertas.php";
?>

<form class="formulario" method="POST" action="/crear-cuenta">
    <div class="campo">
        <label for="nombre">Nombre</label>
        <input
        type="text"
        id="nombre"
        name="nombre"
        placeholder="Tu Nombre"
        value= "<?php echo s($usuario->nombre) ?>" 
        > <!-- el echo es super importante-->
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input
        type="text"
        id="apellido"
        name="apellido"
        placeholder="Tu Apellido"
        value= "<?php echo s($usuario->apellido) ?>"
        >
    </div>

    <div class="campo">
        <label for="telefono">Telefono</label>
        <input
        type="text"
        id="telefono"
        name="telefono"
        placeholder="Tu Telefono"
        value= "<?php echo s($usuario->telefono) ?>"
        >
    </div>

    <div class="campo">
        <label for="email">E-mail</label>
        <input
        type="text"
        id="email"
        name="email"
        placeholder="Tu E-mail"
        value= "<?php echo s($usuario->email) ?>"
        >
    </div>

    <div class="campo">
        <label for="password">password</label>
        <input
        type="text"
        id="password"
        name="password"
        placeholder="Tu Contraseña"
        >
    </div>

    <input type="submit" value="Crear Cuenta" class="boton">
    
</form>

<div class="acciones">
    <a href="/">¿Ya tienes Cuenta? Iniciar Sesion</a>
    <a href="/olvide">Olvide la Contraseña</a>
</div>