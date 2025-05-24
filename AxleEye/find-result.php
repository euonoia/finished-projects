<?php
session_start();
error_reporting(0);
include('includes/config.php');?>
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
.main-wrapper{
    font-family: Georgia, 'Times New Roman', Times, serif;
    display: flex;
    justify-content: center;
    width: 100%;
}
.panel{
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow:0 20px 35px rgba(0,0,1,0.9);

}
.panel-heading{
    text-align: center;
}
.panel-body{
    background-color: rgba(190,190,214,1);
    margin: 30px;
    padding: 20px;
    width: 300px;
    height: 200px;
    border-radius: 20px;
}
form{
    margin: 20px;
}
.form-group{
    padding: 5px;
}   
label{
    color:black;
    padding: 3px;
    position:relative;
    cursor:auto;
    transition:0.3s ease all;
}
input{
    color:inherit;
    margin-top: 15px;
    width:230px;
    background-color:transparent;
    border:none;
    border-bottom:2px solid #757575;
    padding-left:1.4rem;
    font-size:15px;
    font-weight: 200px;
}
select{
    color:inherit;
    margin-top: 15px;
    width:250px;
    background-color:transparent;
    border:none;
    border-bottom:2px solid #757575;
    padding-left:1.4rem;
    font-size:15px;
    font-weight: 200px;
}
.btn{
    font-size:1.1rem;
    padding:2px 0;
    border-radius:5px;
    outline:none;
    border:none;
    width:100%;
    background: rgb(125,125,235) ;
    color:white;
    cursor:pointer;
    transition:0.9s;
}
.btn:hover{
    background: rgb(70, 70, 134);
}
button{
    border:none;
    background-color:transparent;
    font-size:1rem;
    font-weight:bold;
    cursor:pointer;
}
a{
    text-decoration: none;
}
.links{
    display:flex;
    margin-top:5px;
    font-weight:bold;
}

        </style>
    </head>
    <body>
    <div class="wrapper">
            <img src="images/HOHO.png" style="width: 240px; height:140px;" >
            </div>
            <div class="main-wrapper">
        
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="panel login-box">
                            <div class="panel-heading">
                                <div class="panel-title text-center">
                                    <h4>AxleEye</h4>
                                </div>
                            </div>
                            <div class="panel-body p-20">

                           

                                <form action="result.php" method="post">
                                	<div class="form-group">
                                		<label for="rollid">Your Student ID</label>
                                        <input type="text" class="form-control" id="rollid" placeholder="" autocomplete="off" name="rollid">
                                	</div>
                               <div class="form-group">
                                                        <label for="default" class="col-sm-2 control-label">Class</label>
 <select name="class" class="form-control" id="default" required="required">
<option value="">Select Class</option>
<?php $sql = "SELECT * from tblclasses";
$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
if($query->rowCount() > 0)
{
foreach($results as $result)
{   ?>
<option value="<?php echo htmlentities($result->id); ?>"><?php echo htmlentities($result->ClassName); ?>&nbsp; Section-<?php echo htmlentities($result->Section); ?></option>
<?php }} ?>
 </select>
</div>

    
                                    <div class="form-group mt-20">
                                        <hr>
                                        <div class="btn">
                                      
                                            <button type="submit" class="btn btn-success btn-labeled pull-right">Search<span class="btn-label btn-label-right"><i class="fa fa-check"></i></span></button>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                </form>

                                

                            </div>
                            <div class="links">
                            <div class="col-sm-6">
                                <a href="index.php">Return</a>
                            </div>
                            </div>
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-md-6 col-md-offset-3 -->
                </div>
                <!-- /.row -->    
                                       
    </div>

                                            
                                
                                    
                                     
           


        <!-- ========== THEME JS ========== -->
        <script src="js/main.js"></script>
        <script>
            $(function(){
                $('input.flat-blue-style').iCheck({
                    checkboxClass: 'icheckbox_flat-blue'
                });
            });
        </script>

        <!-- ========== ADD custom.js FILE BELOW WITH YOUR CHANGES ========== -->
    </body>
</html>

