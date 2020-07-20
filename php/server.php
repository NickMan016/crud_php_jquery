<?php

function connection(){
    $conexion = new mysqli('localhost', 'root', '', 'crud_php');
    $conexion->set_charset('utf8');
    if ($conexion->connect_errno) {
        die("Hubo un error al conectar");
    }else{
        return $conexion;
    }
}

function limpiarDatos($datos){
	$datos = trim($datos);
	$datos = stripslashes($datos);
	$datos = htmlspecialchars($datos);
	return $datos;
}

function create($conexion){
    $code = $_POST['code'];
    $name = $_POST['name'];
    $staff = $_POST['staff'];
    $salary = $_POST['salary'];

    $statement = $conexion->prepare("INSERT INTO employees (code, name, staff, salary) VALUES (?, ?, ?, ?)");
    $statement->bind_param('sssi', $code, $name, $staff, $salary);
    $statement->execute();

    if ($conexion->affected_rows >= 1) {
        return "<div class=\"res success\">Acción realizada con éxito.</div>";
    }else{
        return "<div class=\"res error\">Error al realizar esta acción.</div>";
    }
}

function read($conexion){
    $query = "SELECT * FROM employees ORDER BY code DESC";

    if (isset($_POST['filter']) || $_POST['filter'] !== "") {
        $q = $_POST['filter'];

        $query = "SELECT code, name, staff, salary FROM employees WHERE code LIKE '%" . $q . "%' OR name LIKE '%" . $q . "%' OR staff LIKE '%" . $q . "%' OR salary LIKE '%" . $q . "%' ORDER BY code DESC";
    }

    $data = $conexion->query($query);

    if ($data->num_rows > 0) {
        $response = '<table><thead>
        <th>Código</th>
        <th>Nombre</th>
        <th>Puesto</th>
        <th>Salario</th>
        <th>Borrar</th>
        </thead><tbody>';
        while ($fila = $data->fetch_assoc()) {
            $response .= "<tr>
            <td>" . $fila['code'] . "</td>
            <td>" . $fila['name'] . "</td>
            <td>" . $fila['staff'] . "</td>
            <td>$" . $fila['salary'] . "</td>
            <td class=\"btns-table\">
                <button onClick=\"delet(" . $fila['code'] . ")\"><i class=\"fas fa-trash-alt\"></i></button>
            </td>
            </tr>";
        }
        $response .= '</tbody></table>';
        return $response;
    }else{
        return "<div class=\"res error\">No hay resultados.</div>";
    }
}

function update($conexion){
    $code = $_POST['code'];
    $name = $_POST['name'];
    $staff = $_POST['staff'];
    $salary = $_POST['salary'];

    $statement = $conexion->prepare("UPDATE employees SET name = ?, staff = ?, salary = ? WHERE code = ?");
    $statement->bind_param('ssis', $name, $staff, $salary, $code);
    $statement->execute();

    if ($conexion->affected_rows >= 1) {
        return "<div class=\"res success\">Acción realizada con éxito.</div>";
    }else{
        return "<div class=\"res error\">Error al realizar esta acción.</div>";
    }
}

function delete($conexion){
    $code = $_POST['code'];

    $statement = $conexion->prepare("DELETE FROM employees WHERE code = ?");
    $statement->bind_param('s', $code);
    $statement->execute();

    if ($conexion->affected_rows >= 1) {
        return "<div class=\"res success\">Acción realizada con éxito.</div>";
    }else{
        return "<div class=\"res error\">Error al realizar esta acción.</div>";
    }
}

$conexion = connection();

if ($_GET['query'] === "create") {
    echo create($conexion);
}
elseif ($_GET['query'] === "read") {
    echo read($conexion);
} 
elseif ($_GET['query'] === "delete") {
    echo delete($conexion);
}