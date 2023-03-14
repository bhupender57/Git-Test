<?php 
  session_start();
  if(empty($_SESSION['email']))
  {
   header("location:index.php");
  } 
  include('header.php');
  include('Sidemenu.php');
  include ('config.php');
  $id= $_GET['id'];


$res = $dbConn->query("SELECT * FROM Queries where ID='$id'");
$resu = $res->fetch(PDO::FETCH_ASSOC);

//for getting user id
$userid = $resu['USERID'];
$result = $dbConn->query("SELECT * FROM patient where id='$userid'");
$row    = $result->fetch(PDO::FETCH_ASSOC);




if(isset($_POST['submit'])){

    $content = $_POST['reply'];
    $queryid = $_POST['queryid'];
    $userid  = $_POST['userid']; 
    $dates   = date('d-m-Y');

    //Upload doc
    $file_name = $_FILES['doc']['name'];  
    $file_size = $_FILES['doc']['size']; 
    $file_tmp  = $_FILES['doc']['tmp_name'];
    $file_type = $_FILES['doc']['type'];
    $filepath  = "/query";
    move_uploaded_file($file_tmp,"../query/$file_name");

        $sql = "INSERT INTO queries_reply (`QUERY_ID`,`ADMIN_REPLY`,`ADMIN_DOC`,`USER_REPLY`,`USER_DOC`,`USER_ID`,`DATES`,`STATUS`) VALUES ('$queryid','$content','$file_name','','','$userid','$dates','1')"; 
        $query = $dbConn->prepare($sql);
            
            if($query->execute()){
                 header('location:reply_queries.php?id='.$queryid);
            }


}


?>


<div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header"
                <h3 class="page-title">Reply Query</h3>
             </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                
                <form method="post" action="#" enctype="multipart/form-data" class="forms-sample"> 
                    <div class="form-group">
                    <div class="reply-text-box">

                    <div class="reply-text-user"><p><span>User Reply</span><?=$resu['MESSAGE'];?></p></div>


                    <?php  
                            $fetch_query = $dbConn->query("SELECT * FROM queries_reply where QUERY_ID ='$id' and USER_ID ='$userid' order by CREATED_DATE");
                            $rowcnt  = $fetch_query->rowcount();

                            if($rowcnt >0){
                                $cnt= 1;
                            while($fetch_query_row = $fetch_query->fetch(PDO::FETCH_ASSOC)){ 

                    //if admin reply is there
                     if(!empty($fetch_query_row['ADMIN_REPLY'])){  ?>

                    <div class="reply-text-admin"><span>Admin Reply</span><?=$fetch_query_row['ADMIN_REPLY'];?>
                    <?php if(!empty($fetch_query_row['ADMIN_DOC'])){ ?>


                    <a href="https://www.universalunitytrust.com/query/<?=$fetch_query_row['ADMIN_DOC'];?>" target="_blank"><span><i class="fa fa-paperclip"></i></span> view Attachment</a>

                    <?php } ?>

                    </div>

                <?php    } 

                 if(!empty($fetch_query_row['USER_REPLY'])){  ?>
                    

                    <div class="reply-text-user"><p><span>User Reply</span><?=$fetch_query_row['USER_REPLY'];?></p>
                        
                        <?php if(!empty($fetch_query_row['USER_DOC'])){ ?>
                         <a href="https://www.universalunitytrust.com/query/<?=$fetch_query_row['USER_DOC'];?>" target="_blank"><span><i class="fa fa-paperclip"></i></span> view Attachment</a>
                     <?php } ?>

                </div>

                          <?php  }//user empty end here

                               $cnt++;
                          
                            }// while loop end here
    
                            }//rowcnt end here
                            

                    ?>

                   
                   
                    

                    </div>
                    </div>

 
                  <div class="form-group">
                      <label for="exampleInputName1" class="sub-head">Query Reply</label>
                      <textarea class="span12 ckeditor" name="reply"></textarea>
                      </div>

                    <div class="form-group">
                    <label for="exampleInputName1" class="sub-head">Document</label>  
                    <input type="file" name="doc" class="file-up">
                    </div>


                    <input type="hidden" name="queryid" value="<?=$id;?>">
                    <input type="hidden" name="userid" value="<?=$userid;?>">


                   <button type="submit" name="submit" class="btn btn-primary mr-2">Submit</button>
                 </form>
                </div>
              </div>
            </div>
        </div>	

<?php include('footer.php'); ?>