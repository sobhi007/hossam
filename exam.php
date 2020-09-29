



<?php

session_start();
if(isset($_SESSION['student_id'])){
    
    include('include/config.php');
    
    include('include/header.php');
    
    include('include/navbar.php');
    
    
    $do='';
    
    
    if(isset($_GET['do'])){
        
         $do=$_GET['do'];
        
    }else{
        
        
         $do='exam';
    }
    
    
    
    
    
    if($do=='exam'){
        
        echo '<center><h1>Exam</h1></center><br>';
          

          
      ?>
         
         <div class="container">
           
          <table class="table table-striped">
 
  <thead>
    <tr class="thead-dark">
      
      <th scope="col">Exam</th>
      <th scope="col">Duration</th>
      <th scope="col">Best wishes</th>
    </tr>
  </thead>
  <tbody>
  <?php
    $center_id=$_SESSION['center'];
         $stmt=$conn->prepare('SELECT * from exam  where v=1 AND  exam.group=?  ');
    $stmt->execute(array($center_id));
    $rows=$stmt->fetchAll();
        foreach($rows as $row){
         
         $stmt=$conn->prepare('SELECT * from student_result  where student_id=? AND  exam_id=?  ');
    $stmt->execute(array($_SESSION['student_id'] , $row['id']));
    $find=$stmt->rowCount();
          
          if($find>0){}else{
          
             ?>
             <tr >
             
                    <td > <?php echo ucWords($row['exam_name']," "); ?> </td>
                    <td>
                      15 Minutes 
                    </td>
                   <td>
                     <form method="post" action="?do=take" >
                          <input value="<?php echo $row['id'] ?>" name="exam_id" hidden >
                          <button type="submit"  class="btn btn-primary btn-sm"  >Take exam</button>
                     </form>
                    </td>
                </tr>
             
             
            
                <?php
            }
                 }
            
                
                  ?>
        </tbody>
</table>
           
          
          
          
         </div>
         
        <?php
        
                
    }elseif($do=='take'){
        
        
        
        ?>
        
      <center>  <h3>Best weshes</h3><br> </center>

   <div class="container">
    
      
   
    <form method="post" id='myForm' action="?do=answer">
     <style>
    .sticky {
  position: -webkit-sticky; 
  position: sticky;
  top: 0;
  background-color: #cec4c43d;
}</style>
     <div class="sticky" class="container">
     <div style="padding:30px">
     <center> <b>You have:</b>
        <span id="min" ></span> <b>Minutes</b>
        <span id="remain"></span> <b>Seconds</b></center>
    </div>
     </div>
   <br>
    <?php
    


    
    
    
    $exam=$_POST['exam_id'];
         
          
          
         
          
          
        $stmt=$conn->prepare('SELECT * from question 
                              
                             
                             where question.exam_id=? order by rand()
                             ');

$stmt->execute(array($exam));
$show=$stmt->fetchAll();

   
  
    
        
    $num=1;
  foreach($show as $sho){
    echo '<h4> '.$num++.' ) '.$sho['question'].' ?</h4><br>';
    
    
    
    $one=
    '<input type="hidden" name="'.$sho['q_id'].'" value="no" />
    <input type="radio"  name="'.$sho['q_id'].'" value="'.$sho['answer'].'">
    <lable for="'.$sho['answer'].'" >'.$sho['answer'].'</lable><br>';
    
    
    
      $two=
      '<input type="radio"  name="'.$sho['q_id'].'" value="'.$sho['a1'].'">
      <lable for="'.$sho['a1'].'" >'.$sho['a1'].'</lable><br>';
      
      
      
        $three=
         '<input type="radio"  name="'.$sho['q_id'].'" value="'.$sho['a2'].'">
         <lable for="'.$sho['a2'].'" >'.$sho['a2'].'</lable><br>';
    
          $four=
          '<input type="radio"  name="'.$sho['q_id'].'" value="'.$sho['a3'].'">
           <lable for="'.$sho['a3'].'" >'.$sho['a3'].'</lable><br>';
    
    
  $my_array = array($one,$two,$three, $four);

shuffle($my_array);
echo $strs =  implode(" ",$my_array);




    ?>
    
   <br><br>
    <?php
   
   
   }
 echo '<br><br><br>';  
  ?>
   <input name="center" value="<?php echo $exam ;?>" hidden>
  <button  type="submit" id='myForm'  class="btn btn-primary">submit your answer</button>
  
  </form>
 
  <?php
  echo"</div>";
  
    }elseif($do=='answer'){
     
       $num=-1;
       $right=0;
        
        foreach ($_POST as $key => $value){
            
             $num++;
   "{$key} = {$value}\r\n";
  
  
 
  
  
  $stmt=$conn->prepare('select answer from question where q_id= ?');
  $stmt->execute(array($key));
  $answer = $stmt->fetch();
 
  
  if($value==$answer['answer']){
    
   
    $right++;
  }else{
   
  }
  
  
  
  
  
  
}
?>
    
    <div class="container">
  <?php
 
      $result=$right/$num;
     echo'<br>';
       $center=$_POST['center'];
    echo $percentage=floor($result*100);
    echo' % <br>';
    echo  $outof=$right.' out of  '.$num;
      echo'</div>';
      $date= date("d/m/Y");
      $stmt=$conn->prepare('INSERT INTO `student_result` (`percentage`, `outof`,`student_id`, `exam_id`)
                           VALUES (:percentage, :outof, :student_id , :exam_id)');
      $stmt->execute(array('percentage'=>$percentage,'outof'=>$outof,'student_id'=>$_SESSION['student_id'],
                           'exam_id'=> $_POST['center']));
       header('location:result.php');
      
    }
   
    
    
    else{
        
        
        header('location:dashboard.php');
        
        
    }
    
    
    
    
    
    
    
    
    
    
    
    include('include/footer.php');
    
}else{
    
    
    
    header('location:login.php');
    
    
}





?>








 <script type="text/javascript">
window.onload=counter;
function counter()
{
minutes=15;
seconds =1;
countDown();
}
</script>
<script type="text/javascript">
function countDown(){
document.getElementById("min").innerHTML= minutes;
document.getElementById("remain").innerHTML= seconds;
setTimeout("countDown()",1000);
    if(minutes == 0 && seconds == 0)
        { 
           
        document.getElementById("myForm").submit();
        }
    else    
        {
        seconds--;  
        if(seconds ==0 && minutes > 0)
        {
            minutes--;
            seconds=60;
        }
        }
}
</script>
