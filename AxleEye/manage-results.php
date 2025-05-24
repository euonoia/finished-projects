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
            $marks = array();
            $class = $_POST['class'];
            $studentid = $_POST['studentid']; 
            $mark = $_POST['marks'];
        
            // validation for input grade to be minimum 60 and maximum 90
            foreach ($mark as $m) {
                if ($m < 60 || $m > 90) {
                    $error = "Grade must be between 60 and 90.";
                    break;
                }
            }

            if (!isset($error)) {
                $stmt = $dbh->prepare("SELECT tblsubjects.SubjectName,tblsubjects.id FROM tblsubjectcombination join  tblsubjects on  tblsubjects.id=tblsubjectcombination.SubjectId WHERE tblsubjectcombination.ClassId=:cid order by tblsubjects.SubjectName");
                $stmt->execute(array(':cid' => $class));
                $sid1=array();
                while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                {
                    array_push($sid1,$row['id']);
                } 
          
                for($i=0;$i<count($mark);$i++){
                    $mar=$mark[$i];
                    $sid=$sid1[$i];
                    $sql="INSERT INTO  tblresult(StudentId,ClassId,SubjectId,marks) VALUES(:studentid,:class,:sid,:marks)";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':studentid',$studentid,PDO::PARAM_STR);
                    $query->bindParam(':class',$class,PDO::PARAM_STR);
                    $query->bindParam(':sid',$sid,PDO::PARAM_STR);
                    $query->bindParam(':marks',$mar,PDO::PARAM_STR);
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
        }
    }
?>