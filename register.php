<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = $nombre = $apellido = $correo_e = $domicilio = $edad = $genero = $telefono = $techa_nac = $ciudad = $pais = $ocupación = $intereses = "";
$username_err = $password_err = $confirm_password_err = $nombre_err = $apellido_err = $correo_e_err = $domicilio_err = $edad_err = $genero_err = $telefono_err = $techa_nac_err = $ciudad_err = $pais_err = $ocupación_err = $intereses_err = $terminos_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Por favor ingrese un usuario.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parametersz
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "Este usuario ya fue tomado.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Al parecer algo salió mal.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // ... (Rest of your input validations)

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Por favor ingresa una contraseña.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "La contraseña al menos debe tener 6 caracteres.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Confirma tu contraseña.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "No coincide la contraseña.";
        }
    }

    // Check if terms and conditions are accepted
    if (!isset($_POST["aceptar"])) {
        $terminos_err = "Debes aceptar los términos y condiciones.";
    }


    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($nombre_err) && empty($apellido_err) && empty($correo_e_err) && empty($domicilio_err) && empty($edad_err) && empty($genero_err) && empty($telefono_err) && empty($techa_nac_err) && empty($ciudad_err) && empty($pais_err) && empty($ocupación_err) && empty($intereses_err) && empty($terminos_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, nombre, apellido, correo_e, domicilio, edad, genero, telefono, techa_nac, ciudad, pais, ocupación, intereses) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssssss", $param_username, $param_password, $param_nombre, $param_apellido, $param_correo_e, $param_domicilio, $param_edad, $param_genero, $param_telefono, $param_techa_nac, $param_ciudad, $param_pais, $param_ocupación, $param_intereses);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_nombre = $nombre;
            $param_apellido = $apellido;
            $param_correo_e = $correo_e;
            $param_domicilio = $domicilio;
            $param_edad = $edad;
            $param_genero = $genero;
            $param_telefono = $telefono;
            $param_techa_nac = $techa_nac;
            $param_ciudad = $ciudad;
            $param_pais = $pais;
            $param_ocupación = $ocupación;
            $param_intereses = $intereses;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                header("location: login.php");
            } else {
                echo "Algo salió mal, por favor inténtalo de nuevo.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>
<!-- Rest of your HTML form remains the same -->


 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
      body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .wrapper {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            width: 400px;
            margin: 0 auto;
            margin-top: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .help-block {
            color: #dc3545;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-default {
            background-color: #ccc;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .login-link {
            margin-top: 10px;
        }

        .login-link a {
            color: #007bff;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Agrega estas reglas de estilo para el textarea */
textarea#terminos {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    resize: vertical; /* Permite redimensionar verticalmente el textarea */
}

/* También puedes aplicar estilos similares a los input de texto */
input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Ajusta el estilo del checkbox */
.checkbox-container {
    margin-top: 10px;
}

.checkbox-container input[type="checkbox"] {
    margin-right: 5px;
}

    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Registro</h2>
        <p>Por favor complete este formulario para crear una cuenta.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Usuario</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>">
                <span class="help-block"><?php echo $nombre_err; ?></span>
            </div> 
            <div class="form-group <?php echo (!empty($apellido_err)) ? 'has-error' : ''; ?>">
                <label>Apellidos</label>
                <input type="text" name="apellido" class="form-control" value="<?php echo $apellido; ?>">
                <span class="help-block"><?php echo $apellido_err; ?></span>
            </div>      
            <div class="form-group <?php echo (!empty($correo_e_err)) ? 'has-error' : ''; ?>">
                <label>Correo Electrónico</label>
                <input type="text" name="correo_e" class="form-control" value="<?php echo $correo_e; ?>">
                <span class="help-block"><?php echo $correo_e_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($domicilio_err)) ? 'has-error' : ''; ?>">
                <label>Domicilio</label>
                <input type="text" name="domicilio" class="form-control" value="<?php echo $domicilio; ?>">
                <span class="help-block"><?php echo $domicilio_err; ?></span>
            </div> 
            <div class="form-group <?php echo (!empty($edad_err)) ? 'has-error' : ''; ?>">
                <label>Edad</label>
                <input type="text" name="edad" class="form-control" value="<?php echo $edad; ?>">
                <span class="help-block"><?php echo $edad_err; ?></span>
            </div> 
            <div class="form-group <?php echo (!empty($genero_err)) ? 'has-error' : ''; ?>">
                <label for="genero">Género</label>
                    <select name="genero" class="form-control">
                        <option value="male" <?php if($genero === 'male') echo 'selected'; ?>>Masculino</option>
                        <option value="female" <?php if($genero === 'female') echo 'selected'; ?>>Femenino</option>
                        <option value="other" <?php if($genero === 'other') echo 'selected'; ?>>Otro</option>
                    </select>
                <span class="help-block"><?php echo $genero_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($telefono_err)) ? 'has-error' : ''; ?>">
                <label>Telefono</label>
                <input type="text" name="telefono" class="form-control" value="<?php echo $telefono; ?>">
                <span class="help-block"><?php echo $telefono_err; ?></span>
            </div> 
            <div class="form-group <?php echo (!empty($techa_nac_err)) ? 'has-error' : ''; ?>">
                <label>Fecha de Nacimiento</label>
                <input type="date" name="techa_nac" class="form-control" value="<?php echo $techa_nac; ?>">
                <span class="help-block"><?php echo $techa_nac_err; ?></span>
            </div> 
            <div class="form-group <?php echo (!empty($ciudad_err)) ? 'has-error' : ''; ?>">
                <label>Ciudad</label>
                <input type="text" name="ciudad" class="form-control" value="<?php echo $ciudad; ?>">
                <span class="help-block"><?php echo $ciudad_err; ?></span>
            </div> 
            <div class="form-group <?php echo (!empty($pais_err)) ? 'has-error' : ''; ?>">
                <label>Pais</label>
                <input type="text" name="pais" class="form-control" value="<?php echo $pais; ?>">
                <span class="help-block"><?php echo $pais_err; ?></span>
            </div> 
            <div class="form-group <?php echo (!empty($ocupación_err)) ? 'has-error' : ''; ?>">
                <label>Ocupación</label>
                <input type="text" name="ocupación" class="form-control" value="<?php echo $ocupación; ?>">
                <span class="help-block"><?php echo $ocupación_err; ?></span>
            </div> 
            <div class="form-group <?php echo (!empty($intereses_err)) ? 'has-error' : ''; ?>">
                <label>Intereses</label>
                <input type="text" name="intereses" class="form-control" value="<?php echo $intereses; ?>">
                <span class="help-block"><?php echo $intereses_err; ?></span>
            </div> 

            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirmar Contraseña</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>

            <h2>Términos y Condiciones</h2>
        <p>Por favor, lea y acepte los términos y condiciones antes de continuar:</p>
        <textarea id="terminos" name="terminos" rows="6" cols="70" readonly>
            Términos y Condiciones de Uso

            Última actualización: 19/09/2023

            Por favor, lea atentamente estos términos y condiciones antes de utilizar nuestro sitio web. Al acceder o utilizar nuestro sitio web, usted acepta cumplir y estar sujeto a estos términos y condiciones. Si no está de acuerdo con estos términos y condiciones, por favor, no utilice nuestro sitio web.

            1. Información del Usuario

            1.1. Al utilizar nuestro sitio web, usted acepta proporcionar información precisa y actualizada cuando se le solicite, incluyendo, entre otros, su nombre, dirección de correo electrónico, dirección postal y otra información de contacto.

            1.2. Usted comprende y acepta que somos responsables de proteger su privacidad y su información personal, de acuerdo con nuestra Política de Privacidad, que se encuentra disponible en nuestro sitio web.

            2. Uso Adecuado

            2.1. Usted se compromete a utilizar nuestro sitio web de manera legal y ética, y se abstendrá de realizar actividades que puedan dañar, interferir o comprometer la seguridad y el funcionamiento de nuestro sitio.

            2.2. No está permitido utilizar nuestro sitio web para distribuir contenido ilegal, ofensivo, difamatorio, amenazante, o que viole los derechos de propiedad intelectual de terceros.

            3. Propiedad Intelectual

            3.1. Todo el contenido publicado en nuestro sitio web, incluyendo, pero no limitándose a, textos, gráficos, logotipos, imágenes, videos, y software, está protegido por derechos de autor y otras leyes de propiedad intelectual.

            3.2. Usted acepta no copiar, modificar, distribuir, transmitir, exhibir, vender o realizar cualquier otro uso no autorizado de nuestro contenido sin nuestro previo consentimiento por escrito.

            4. Enlaces a Terceros

            4.1. Nuestro sitio web puede contener enlaces a sitios web de terceros. Estos enlaces son proporcionados solo para su conveniencia y no implican ningún respaldo por nuestra parte.

            4.2. No somos responsables de los contenidos, políticas de privacidad o prácticas de sitios web de terceros. Le recomendamos revisar las políticas y términos de uso de estos sitios antes de interactuar con ellos.

            5. Cambios en los Términos y Condiciones

            5.1. Nos reservamos el derecho de modificar o actualizar estos términos y condiciones en cualquier momento sin previo aviso. Cualquier cambio será efectivo inmediatamente después de su publicación en nuestro sitio web.

            6. Contacto

            Si tiene alguna pregunta o comentario sobre estos términos y condiciones, por favor contáctenos a través de la información de contacto proporcionada en nuestro sitio web.
        </textarea>
        <br>
        <div class="checkbox-container">
            <input type="checkbox" id="aceptar" name="aceptar">
            <label for="aceptar">Acepto los términos y condiciones</label>
        </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Ingresar">
                <input type="reset" class="btn btn-default" value="Borrar">
            </div>
          
            <p>¿Ya tienes una cuenta? <a href="login.php">Ingresa aquí</a>.</p>


        </form>
    </div>    
</body>
</html>