<?php

session_start();
if(isset($_SESSION['student_id'])){
    
    include('include/config.php');
    include('include/navbar.php');
    include('include/header.php');
    
    $do='';
    
    
    if(isset($_GET['do'])){
        
         $do=$_GET['do'];
        
    }else{
        
        
         $do='result';
    }
     
   
   if($do=='result'){
   
    ?>
    
    
    
    
    
    
     <div class="container">
           <center><h4>Result & model answer</h4><hr><br><br></center>
          <table class="table table-striped">
 
  <thead>
    <tr class="thead-dark">
      
      <th scope="col">Exam </th>
      <th scope="col">Percentage </th>
      <th scope="col">Mark</th>
      <th scope="col">Model answer</th>
    </tr>
  </thead>
  <tbody>
    
    <?php
    $stmt=$conn->prepare('select * from student_result where student_id=? ');
    $stmt->execute(array($_SESSION['student_id']));
    $rows=$stmt->fetchAll();
    
    
    
    foreach($rows as $row ){?>
        <tr>
             <td> <?php $stmt=$conn->prepare('select exam_name from exam where id=? ');
                         $stmt->execute(array($row['exam_id']));
                         $name=$stmt->fetch();
                         echo $name['exam_name'];
                         
                         
                         ?></td>
             
             
             
             <td> <?php echo $row['percentage'] .' %';?></td>
             <td> <?php echo $row['outof']; ?></td>
             <td> 
        <a href="?do=show&id=<?php echo $row['exam_id']; ?>" class="btn btn-primary btn-sm"> show model answer</a> 
             
             </td>
             
        </tr>
        <?php
    } ?>
          </tbody>
</table>
           
          
          
          
         </div>
         
    <?php
   
    
    
    
   
    
   }elseif($do=='show'){
    
    
    $id=$_GET['id'];
    
  
 ?>
 
 <div class="container">
  <center><h2>Exam with model answer</h2><br>
  
  <br><br></center>
 <?php
$stmt=$conn->prepare('
                             SELECT * FROM `question` 
                             where exam_id=?
                             
                             
                             
                             ');
$id=$_GET['id'];
$stmt->execute(array($id));
$show=$stmt->fetchAll();



  foreach($show as $sho){
   
    echo '<h4 style="display:inline">'.$sho['question'].' ? </h4><br><br>';
    $one="<span style='color : red' >".$sho['answer']."</span><br>";
      $two="<span  >".$sho['a1']."</span><br>";
        $three="<span  >".$sho['a2']."</span><br>";
          $four="<span  >".$sho['a3']."</span><br>";
    
  $my_array = array($one,$two,$three, $four);


echo $strs =  implode(" ",$my_array);

?>

    <?php
 echo '<br><hr><br>';
 }
 ?>
 </div>
 
 <?php
    
    
   }
    else{
        
        header('location:result.php');
        
    }
    
    
    
    
   
    include('include/footer.php');
    }else{
    
    
    header('location:login.php');
    
    
}




?>