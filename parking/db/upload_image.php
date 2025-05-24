<?php
include("connect.php");
if(isset($_POST['submit'])){
    $file_name = $_FILES['image']['name'];
    $tempname = $_FILES['image']['tmp_name'];
    $folder = 'image/'.$file_name;

    $query = mysqli_query($conn,"INSERT into images (image) VALUES ('$file_name')");

    if(move_uploaded_file($tempname,$folder)){
        echo "Image uploaded successfully";
    }
    else{
        echo "Failed to upload image";
    }
}
?>

s

