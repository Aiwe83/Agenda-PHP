<?php
// Declarar variables con un valor inicial
$nombre = "";
$numero = "";
$accesos = [];

// Comprobar si se ha enviado el formulario
if (isset($_POST["submit"])) {

    // Escapar los caracteres especiales del nombre y asignarlo a la variable $nombre
    $nombre = htmlspecialchars($_POST["nombre"]);
    $numero = htmlspecialchars($_POST["numero"]);
    $accesos =  $_POST["accesos"]  ?? [];

    /* echo "<pre>";
    print_r($accesos);
    echo "</pre>"; */

    // Estructura de control "switch" para manejar los diferentes casos posibles
    switch (true) {
        case empty($nombre):
            // Mostrar un mensaje de error si el nombre está vacío
            echo "<h4>Error: Porfavor ingresa un nombre</h4>";
            break;
        case (!is_numeric($numero)):
            // Mostrar un mensaje de error si el número no es numérico
            echo "<h4>Error: El numero de telefono tiene que ser numerico</h4>";
            break;
        case isset($accesos[$nombre]):
            // Mostrar un mensaje de advertencia si el nombre ya existe en la agenda
            echo "<h4>Atención: Este nombre ya existe en la agenda.</h4>";

            if (!empty($numero)) {
                // Actualizar el número si se ha proporcionado uno nuevo
                $accesos[$nombre] = $numero;
            } else {
                // Eliminar el contacto si no se proporciona un número
                unset($accesos[$nombre]);
            }
            break;
        case (!empty($numero)):
            // Añadir un nuevo contacto si se proporciona un nombre y un número
            $accesos[$nombre] = $numero;
            break;
    }
}

// Borrar todos los contactos
if (isset($_POST["clear"])) {
    $accesos = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agenda</title>
    <style>
        table {
            border-collapse: collapse;
            margin: 0 auto;
            /* Centra la tabla */
        }

        table,
        td,
        th {
            border: 1px solid black;
            padding: 5px;
        }

        th {
            text-align: left;
            background-color: lightgray;
        }
    </style>
</head>

<body>
    <?php
    // Comprobar si se han proporcionado contactos y mostrarlos en una tabla
    if (isset($accesos) && is_array($accesos) && !empty($accesos)) :
    ?>
        <table style="width:40%; float:left; margin-left: 20%;background:bisque">
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
            </tr>
            <?php
            foreach ($accesos as $nombre => $numero) :
            ?>
                <tr>
                    <td><?= $nombre ?></td>
                    <td><?= $numero ?></td>
                </tr>
            <?php
            endforeach;
            ?>
        </table>
    <?php
    endif;
    ?>
    <br />


    <fieldset style="width:40%; float:left; margin-left: 20%;background:bisque">
        <legend>
            <h2>Añade contactos a la agenda</h2>
        </legend>
        <form action="agenda.php" method="post">
            <!-- Añadir una etiqueta "label" para mejorar la accesibilidad y la experiencia del usuario -->
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre"><br />
            <label for="numero">Telefono:</label>
            <input type="text" name="numero" id="numero"><br />
            <button type="submit" name="submit">Enviar</button>


            <?php
            // Añadir campos ocultos para preservar los contactos existentes
            if (isset($accesos) && is_array($accesos)) {

                foreach ($accesos as $nombre => $numero) {
                    echo "<input type='hidden' name='accesos[$nombre]' value= $numero />";
                }
            }
            ?>
        </form>

        <!-- Formulario para borrar todos los contactos -->
        <?php
        if (isset($accesos) && is_array($accesos) && !empty($accesos)) :
        ?>
            <form action="agenda.php" method="post">
                <input type="submit" name="clear" value="Borrar todo">
            </form>
        <?php
        endif;
        ?>
    </fieldset>
</body>

</html>