<?php
include("config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="icon" href="img/cropped.png" type="image/x-icon">
</head>
<body>
    <?php

    $sql = "SELECT * FROM patients";
    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    $cnt = 1;
    if ($query->rowCount() > 0) {
    ?>
    <table border="1">
        <tr>
            <th>#</th>
            <th>firstName</th>
            <th>lastName</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Condition</th>
           
        </tr>
        <?php foreach($results as $result) { ?>
        <tr>
            <td><?php echo htmlentities($cnt);?></td>
            <td><?php echo htmlentities($result->firstName);?></td>
            <td><?php echo htmlentities($result->lastName);?></td>
            <td><?php echo htmlentities($result->age);?></td>
            <td><?php echo htmlentities($result->gender);?></td>
            <td><?php echo htmlentities($result->condition);?></td>
        </tr>
        <?php $cnt++; } ?>
    </table>
    <?php } ?>
</body>
</html>