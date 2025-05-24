<?php
session_start();
error_reporting(0);
include('includes/config.php');
if($_SESSION['alogin']!=''){
$_SESSION['alogin']='';
}
if(isset($_POST['login']))
{
$uname=$_POST['username'];
$password=md5($_POST['password']);
$sql ="SELECT UserName,Password FROM admin WHERE UserName=:uname and Password=:password";
$query= $dbh -> prepare($sql);
$query-> bindParam(':uname', $uname, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
$query-> execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
$_SESSION['alogin']=$_POST['username'];
echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
} else{

    echo "<script>alert('Invalid Details');</script>";

}

}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>AxleEye.com</title>
        <link rel="icon" href="images/hehe.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <style>
 body{
    background: rgb(2,0,36);
    background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(190,190,214,1) 58%, rgba(247,247,247,1) 89%);
}
.container{
    display: flex;
    background:#fff;
    width:300px;
    justify-content: space-between;
    padding:25px;
    margin:50px auto;
    border-radius:10px;
    box-shadow:0 20px 35px rgba(0,0,1,0.9);

}
form{
    margin:0.2rem;
}
.form-title{
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size:1.5rem;
    font-weight:bold;
    text-align:center;
    padding:0.80rem;
    margin-top: 2px;
    margin-bottom:0.1rem;
}
.recover{
    text-align:right;
    font-size:1rem;
    margin-bottom:1rem;

}
.recover a{
    text-decoration:none;
    color:rgb(125,125,235);
}
.recover a:hover{
    color:#34306b;
    text-decoration:none;
}
.links{
    display:flex;
    justify-content:space-around;
    padding:0 4rem;
    margin-top:45px;
    font-weight:bold;
}
button{
    color:rgb(125,125,235);
    border:none;
    background-color:transparent;
    font-size:2rem;
    font-weight:bold;
    cursor:pointer;

}
button:hover{
    text-decoration:none;
    color:#34306b;
}
        </style>
    </head>
    <body>
    <div class="wrapper">
        <a href="login.php">
            <img src="images/HOHO.png" style="width: 240px; height:140px;" >

        </a>
            </div>
           
                
                                                <div class="container">
                                                <h1 class="form-title">Get Your Result</h1>
                                               
                                                    <div class="links">
                                                    <a href="find-result.php">
                                                    <button>Ok</button>
                                                    </a>
                                                </div>
                                                </div>
                                               
                                                </div>

                                            
                                
                                    
                                     
           


        
    </body>
</html>