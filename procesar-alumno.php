<?php
// Abrir conexión con los parámetros
$con = mysqli_connect("localhost", "php", "", "somorrostro_eso");

// Validar la conexión
if (!$con) {
	die("Error de conexión: " . mysqli_connect_error());
}

// Recoger datos con post (datos sensibles) y guardar en variables, trim elimina espacios
$nombre = trim($_POST["nombre"]);
$apellidos = trim($_POST["apellidos"]);
$fecha_nacimiento = trim($_POST["fecha_nacimiento"]);
$curso = trim($_POST["curso"]);
$email_educamos = trim($_POST["email_educamos"]);
$password_educamos = $_POST["password_educamos"];

// Preparar insert parra simplificar ejecución más tarde
$sql_insert = "INSERT INTO alumnos (nombre, apellidos, fecha_nacimiento, curso, email_educamos, password_educamos)
				VALUES ('$nombre', '$apellidos', '$fecha_nacimiento', '$curso', '$email_educamos', '$password_educamos')";
?>

<!-- Html listado de alumnos -->
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
    <meta name="practica-php-David" content="width=device-width, initial-scale=1.0">
	<title>Resultado del registro</title>
	<link rel="stylesheet" href="formulario-alumnos.css">
</head>
<body>
	<div class="respuesta-pagina">
		<header class="cabecera">
			<!-- Logo Somorrostro -->
			<img src="https://www.somorrostro.com/wp-content/uploads/2023/06/logo.png" alt="Centro de Formación Somorrostro">
		</header>

		<div>
			<div class="mensaje mensaje-exito">
				<span class="mensaje-icono" aria-hidden="true">✓</span>
				<div>
					<h1>
						<!-- Apartado php que gestiona la insercción, indica en este h1 mensaje de resultado del insert -->
						<?php
						// Validar campos
						if ($nombre === "" || $apellidos === "" || $fecha_nacimiento === "" ||
							$curso === "" || $email_educamos === "" || $password_educamos === "") {
							echo "Todos los campos son obligatorios";
						} elseif ($fecha_nacimiento > date("Y-m-d")) {
							echo "La fecha de nacimiento no puede ser futura";
						} else {
							// Guardar todo los registros filtrado por el curso seleccionado
							$consulta_curso = mysqli_query($con, "SELECT * FROM alumnos WHERE curso = '$curso'");

							if (!$consulta_curso) {
								echo "Error al comprobar el curso: " . mysqli_error($con);
							} elseif (mysqli_num_rows($consulta_curso) >= 25) {
								// Revisar cuantas lineas tienen esos registros guardados y validar
								echo "Este curso ya tiene 25 alumnos";
							} else {
								if (!mysqli_query($con, $sql_insert)) {
									echo "Error al insertar: " . mysqli_error($con);
								} else {
									echo "Alumno registrado correctamente";
								}
							}
						}
						?>
					</h1>
				</div>
			</div>

			<div class="tabla-container">
				<div class="tabla-titulo">
					<div>
						<!-- Listado de alumnos -->
						<span class="etiqueta-seccion">Registro académico</span>
						<h2>Alumnos registrados</h2>
					</div>
				</div>
				<div class="tabla-scroll">
					<table>
						<tr>
							<th>Nombre</th>
							<th>Apellidos</th>
							<th>Fecha de nacimiento</th>
							<th>Curso</th>
							<th>Email Educamos</th>
						</tr>
							<?php
							// Recoger los datos de los alumnos
							$registros = mysqli_query($con, "SELECT nombre, apellidos, fecha_nacimiento, curso, email_educamos FROM alumnos ORDER BY apellidos, curso");

							// Los registros se guardan en arrays y si printean
							while($reg = mysqli_fetch_array($registros)){
								echo "<tr>";
								echo "<td>" . $reg['nombre'] . "</td>";
								echo "<td>" . $reg['apellidos'] . "</td>";
								echo "<td>" . $reg['fecha_nacimiento'] . "</td>";
								echo "<td>" . $reg['curso'] . "</td>";
								echo "<td>" . $reg['email_educamos'] . "</td>";
								echo "</tr>";
							}
							?>
					</table>
				</div>
				<!-- Enlace para volver al formulario inicial -->
				<a class="volver-formulario" href="formulario-alumnos.html">Volver al formulario</a>
			</div>
		</div>
	</div>
</body>
</html>

<?php
// Cerrar conexión, Importante
mysqli_close($con);
?>
			
