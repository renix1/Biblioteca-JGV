<?php
        session_start();
        $ra = $_POST['user'];
        $pass = $_POST['pass'];
        $stay = $_POST['stay'];
        include 'connection.php';
        $sql = "SELECT * FROM alunos WHERE ra_aluno LIKE '$ra' AND senha LIKE '$pass'";
        $sql = mysqli_query($conn, $sql);
        $row = mysqli_num_rows($sql);
        if ($row > 0) {
            $_SESSION['user'] = $ra;
            $_SESSION['pass'] = $pass;
            header("Location:home.php");
        } else {
            header("Location:index.php?try=true");
        }
?>
