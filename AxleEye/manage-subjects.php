<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])=="")
    {   
    header("Location: index.php"); 
    }
    else{
        if(isset($_POST['submit']))
        {
        $subjectname=$_POST['subjectname'];
        $subjectcode=$_POST['subjectcode']; 
        $sql="INSERT INTO  tblsubjects(SubjectName,SubjectCode) VALUES(:subjectname,:subjectcode)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':subjectname',$subjectname,PDO::PARAM_STR);
        $query->bindParam(':subjectcode',$subjectcode,PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId)
        {
        }
        else 
        {
        $error="Something went wrong. Please try again";
        }
        
        }
    }
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Subject</title>
    <link rel="icon" href="images/hehe.png">
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="css/toastr/toastr.min.css" media="screen" >
        <link rel="stylesheet" href="css/icheck/skins/line/blue.css" >
        <link rel="stylesheet" href="css/icheck/skins/line/red.css" >
        <link rel="stylesheet" href="css/icheck/skins/line/green.css" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="home/style.css">
    <script src="js/modernizr/modernizr.min.js"></script>
    <style>
    .errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
    </style>
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">AxleEye</a>
                </div>
            </div>
            <ul class="sidebar-nav">
            <li class="sidebar-item">
                        <a href="dashboard.php" class="sidebar-link">
                            <i class="lni lni-user"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#multi" aria-expanded="false" aria-controls="multi">
                        <i class="lni lni-layout"></i>
                        <span>Manage</span>
                    </a>
                    <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <ul class="child-nav">
                                <li><a href="manage-students.php"><i class="fa fa fa-server"></i> <span>Students</span></a></li>        
                             </ul>
                            <ul class="child-nav">
                                            <li><a href="manage-classes.php"><i class="fa fa-file-text"></i> <span>Classes</span></a></li>
                            </ul>
                            <ul class="child-nav">
                                            <li><a href="manage-subjects.php"><i class="fa fa fa-server"></i> <span>Subjects</span></a></li>
                            </ul>
                            <ul>
                                           <li><a href="manage-subjectcombination.php"><i class="fa fa-newspaper-o"></i> <span>Integrate Subject</span></a></li></li>
                            </ul>
                            <ul class="child-nav">
                                            <li><a href="add-result.php"><i class="fa fa fa-server"></i> <span>Result</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="change-password.php" class="sidebar-link">
                        <i class="lni lni-cog"></i>
                        <span>Change Password</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="logout.php" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                </a>
            </div>
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-4 py-3">
                <form action="#" class="d-none d-sm-inline-block">

                </form>
                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav ms-auto">
                        <H2>AxleEye</H2>
                    </ul>
                </div>
            </nav>       
            
            
            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <div class="mb-3">
                        
                        
                        
                    <div class="col-md-12">
                                           <div class="panel">
                                               <div class="panel-heading">
                                                   <div class="panel-title">
                                                       <h5>Create Subject</h5>
                                                   </div>
                                               </div>
                                               <div class="panel-body">
   <?php if($msg){?>
   <div class="alert alert-success left-icon-alert" role="alert">
    <strong>Well done!</strong><?php echo htmlentities($msg); ?>
    </div><?php } 
   else if($error){?>
       <div class="alert alert-danger left-icon-alert" role="alert">
                                               <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                           </div>
                                           <?php } ?>
                                                   <form class="form-horizontal" method="post">
                                                       <div class="form-group">
                                                           <label for="default" class="col-sm-2 control-label">Subject Name</label>
                                                           <div class="col-sm-10">
    <input type="text" name="subjectname" class="form-control" id="default" placeholder="Subject Name" required="required">
                                                           </div>
                                                       </div>
   <div class="form-group">
                                                           <label for="default" class="col-sm-2 control-label">Subject Code</label>
                                                           <div class="col-sm-10">
    <input type="text" name="subjectcode" class="form-control" id="default" placeholder="Subject Code" required="required">
                                                           </div>
                                                       </div>
                                                       
   
                                                       
                                                       <div class="form-group">
                                                           <div class="col-sm-offset-2 col-sm-10">
                                                               <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                                           </div>
                                                       </div>
                                                   </form>
  
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5>View Subjects Info</h5>
                                                </div>
                                            </div>
<?php if($msg){?>
<div class="alert alert-success left-icon-alert" role="alert">
 <strong>Well done!</strong><?php echo htmlentities($msg); ?>
 </div><?php } 
else if($error){?>
    <div class="alert alert-danger left-icon-alert" role="alert">
                                            <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                        </div>
                                        <?php } ?>
                                            <div class="panel-body p-20">

                                                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Subject Name</th>
                                                            <th>Subject Code</th>
                                                            <th>Creation Date</th>
                                                            <th>Updation Date</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                
                                                    <tbody>
<?php $sql = "SELECT * from tblsubjects";
$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{   ?>
<tr>
 <td><?php echo htmlentities($cnt);?></td>
                                                            <td><?php echo htmlentities($result->SubjectName);?></td>
                                                            <td><?php echo htmlentities($result->SubjectCode);?></td>
                                                            <td><?php echo htmlentities($result->Creationdate);?></td>
                                                            <td><?php echo htmlentities($result->UpdationDate);?></td>
<td>
<a href="edit-subject.php?subjectid=<?php echo htmlentities($result->id);?>"><i class="fa fa-edit" title="Edit Record"></i> </a> 

</td>
</tr>
<?php $cnt=$cnt+1;}} ?>
                                                       
                                                    
                                                    </tbody>
                                                </table>

                                         
                                                <!-- /.col-md-12 -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->

                                                               
                                                </div>
                                                <!-- /.col-md-12 -->
                                            </div>
                                        </div>
                                        <!-- /.panel -->
                                    </div>

                    </div>
                </div>
            </main>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-body-secondary">
                        <div class="col-6 text-start ">
                            <a class="text-body-secondary" href=" #">
                               
                            </a>
                        </div>
                        
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="home/script.js"></script>
</body>

</html>
