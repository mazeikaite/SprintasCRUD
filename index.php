<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "project_manager";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// project delete 
if (isset($_GET['action']) and $_GET['action'] == 'deletePro') {
    $sql = 'DELETE FROM projects WHERE idprojects = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['idprojects']);
    $res = $stmt->execute();

    $stmt->close();


    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    die();
}

//employee delete
if (isset($_GET['action']) and $_GET['action'] == 'delete') {
    $sql = 'DELETE FROM employees WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_GET['id']);
    $res = $stmt->execute();

    $stmt->close();


    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));

    die();
}
//ADD employee
if (isset($_POST['save'])) {
    $firstName = $_POST['firstname'];
    $projectName = $_POST['project'];

    mysqli_query($conn, "INSERT INTO employees (firstname, project) 
    VALUES ('$firstName', '$projectName')");
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
};


// update employee 
// if (isset($_POST['update'])) {
//     $name = $_POST['firstname'];
//     $project = $_POST['project'];
//     $id = $_POST['id'];
//     $sql_update = "UPDATE employees SET firstname ='$name' WHERE id=$id";
//     $que = "UPDATE employees SET project='$project' WHERE id=$id";
//     $stmt = $conn->prepare($que);
//     $stmt->execute();
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();

//     header('location: index.php');
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>CRUD</title>
</head>

<body>

    <ul class="tabs">
        <li data-tab-target="#projects" class="active tab">Projects

        </li>
        <li data-tab-target="#employees" class="tab">Employees</li>
    </ul>

    <div class="tab-content">
        <div id="projects" data-tab-content class="active">
            <?php

            $sql_projects = "SELECT idprojects, project_name, group_concat(employees.firstname SEPARATOR ', ') as empl
FROM project_manager.projects
JOIN employees on employees.project = projects.project_name
Group by projects.project_name, idprojects";

            $result_projects = mysqli_query($conn, $sql_projects);
            if (mysqli_num_rows($result_projects) > 0) {
                print("<table>");
                print("<thead>");
                print("<tr><th>NR</th><th>Name</th><th>Employees</th><th>Action</th></tr>");
                print("</thead>");
                print("<tbody>");
                $idx = 1;
                while ($row = mysqli_fetch_assoc($result_projects)) {
                    print("<tr>"
                        . "<td>" . $idx++ . "</td>"
                        . "<td>" . $row["project_name"] . "</td>"
                        . "<td>" . $row["empl"] . "</td>"
                        . "<td><a href='?action=deletePro&id=" . $row["idprojects"] . "'><button type='submit'>DELETE</button></a>" . "</td>"
                        . "</tr>");
                }
                print("</tbody>");
                print("</table>");
            } else {
                echo "0 results";
            }
            ?>
        </div>
        <div id="employees" data-tab-content>
            <?php

            $sql_employees = "SELECT * FROM project_manager.employees";
            $result_employees = mysqli_query($conn, $sql_employees);

            if (mysqli_num_rows($result_employees) > 0) {
                print("<table>");
                print("<thead>");
                print("<tr><th>NR</th><th>Name</th><th>Projects</th><th>Action</th></tr>");
                print("</thead>");
                print("<tbody>");
                $idx = 1;
                while ($row = mysqli_fetch_assoc($result_employees)) {
                    print("<tr>"
                        . "<td>" . $idx++ . "</td>"
                        . "<td>" . $row["firstname"] . "</td>"
                        . "<td>" . $row["project"] . "</td>"
                        . "<td><a href='?action=delete&id=" . $row['id'] . " '><button type='submit'>DELETE</button></a>
                    <a href='?action=update&id=" . $row['id'] . " '><button type='submit' name='update'>UPDATE</button></a>
            
            </td>"
                        . "</tr>");
                }
                print("</tbody>");
                print("</table>");
            } else {
                echo "0 results";
            };



            ?>
            <!-- ADD  -->
            <form action="" method="POST">
                <div>
                    <label class="" for="firstname">Name</label>
                    <input class="" type="text" name="firstname" value="">
                </div>
                <div>
                    <label class="" for="project">Project Name</label>
                    <input class="" type="text" name="project" value="">

                </div>
                <div class="">
                    <button class="" type="submit" name="save">Submit</button>
                </div>

            </form>
        </div>
    </div>
    <!-- Update  -->
    <!-- <form action="" method="POST">
        <div>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="text" name="firstname" value="<?php echo $firstName; ?>">
            <input type="text" name="project" value="<?php echo $project; ?>">

        </div>

        <button class="" type="submit" name="update">Submit</button>


    </form> -->


    <script src="script.js"> </script>
</body>
<?php
mysqli_close($conn);
?>

</html>