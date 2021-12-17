<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'cjk5807';
$password = 'password';
$host = 'e5-cse-cs431fa21s1-39';
$dbname = 'cjk5807_431W';

?>

<!DOCTYPE html>
<html>
   <head>
        <title>Referral Program</title>
   </head>
   <body style="background-color: antiquewhite">
   <p><?php
       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          if (!empty($_POST["email"])) {
            echo 'Adding email ' .  $_POST["email"];
            $user_id = $_POST["user_id"];
            $sqlSelect1 = 'SELECT email FROM cjk5807_account WHERE user_id = "' . $user_id . '"';
            $sqlSelect2 = 'SELECT email FROM cjk5807_referral WHERE email = "' . $_POST["email"] . '"';
            
            $sqlInsert = 'INSERT INTO cjk5807_referral (user_id, email) ';
            $sqlInsert = $sqlInsert . 'VALUES ("' . $user_id . '","' . $_POST["email"] . '")';
            $sqlUpdate = 'UPDATE cjk5807_account SET total_saving = ROUND(total_saving + 5,2) WHERE user_id = "' . $user_id . '"';
            try {
              $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
       		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $conn->beginTransaction();
              //inserting email into referral db
  		      	$conn->exec($sqlInsert);
              //updating total savings with the 5 dollar bonus
              $conn->exec($sqlUpdate);
              //now checking if the email is either active or have been used previously
              $q = $conn->query($sqlSelect1);
              $q->setFetchMode(PDO::FETCH_ASSOC);
              $email = $q->fetch();
              
              $q = $conn->query($sqlSelect2);
              $q->setFetchMode(PDO::FETCH_ASSOC);
              $count = 0;
              while ($row = $q->fetch()) {
                ++$count;
              }
              if ($email["email"] === $_POST["email"] or $count > 1)
              {
                ?>
                <script>
                  alert('Error: Email already used');
                </script>
                <?php
                $conn->rollBack();
              }
              else
              {
                $conn->commit();
                echo "<br> success";   
              }  
           	  ?>
           <p>You will be redirected in 3 seconds</p>
  				    <script>
                var username = "<?php echo $user_id; ?>";
  					    var timer = setTimeout(function() {
  						  window.location.href='index.php?username=' + username
  					  }, 3000);
  				    </script>
  	       <?php
  		    } catch(PDOException $e) {
            $conn->rollBack();
  			    echo "<br>" . $e->getMessage();
  		    }
        }
        else
          {
    			?>
         <p>Need to have an email</p>
         <script>
    					var timer = setTimeout(function() {
    					 window.location.href='index.php?username=' + username
    					}, 3000);
          </script>
      <?php
      }
      }
  ?></p>
  </div>
</html>
