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
        <title>Add Coupon</title>
   </head>
   <body style="background-color: antiquewhite">
   <p><?php
       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          if (!empty($_POST["name"]) and !empty($_POST["type"]) and !empty($_POST["store_id"]) and  !empty($_POST["price"]) and !empty($_POST["discount"])) {
            echo 'Adding coupon ' .  $_POST["store_id"] . ' ' . $_POST["user_id"] . ' ' . $_POST["name"] . ' ' . $_POST["type"] . ' ' .  $_POST["price"] . ' ' .  $_POST["discount"];
  		      $sqlItemSelect = '(SELECT MAX(item_id) AS id FROM cjk5807_item)';
  		      $sqlCouponSelect = '(SELECT MAX(coupon_id) AS id FROM cjk5807_coupon)';
            $savings =  $_POST["price"] * $_POST["discount"];
            $user_id = $_POST["user_id"];
            try {
              $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
       		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  			      //inserting into item
       		    $q = $conn->query($sqlItemSelect);
              $q->setFetchMode(PDO::FETCH_ASSOC);
              $item_id = $q->fetch()["id"] + 1;
    		    	$sqlItemInsert = 'INSERT INTO cjk5807_item (item_id, name, type, price) ';
              $sqlItemInsert = $sqlItemInsert . 'VALUES ("' . $item_id . '","' . $_POST["name"] . '","' . $_POST["type"] . '","' . $_POST["price"] . '")';
  		      	$conn->exec($sqlItemInsert);
  			      //now inserting into coupon
       	    	$q = $conn->query($sqlCouponSelect);
              $q->setFetchMode(PDO::FETCH_ASSOC);
              $coupon_id = $q->fetch()["id"] + 1;
  			      $sqlInsert = 'INSERT INTO cjk5807_coupon (coupon_id, item_id, discount) ';
        			$sqlInsert = $sqlInsert . 'VALUES ("' . $coupon_id . '","' . $item_id . '","' . $_POST["discount"] . '")';
  			      $conn->exec($sqlInsert);
  			      //Linking to store
              $sqlInsert = 'INSERT INTO cjk5807_store2coupon (store_id, coupon_id) ';
              $sqlInsert = $sqlInsert . 'VALUES ("' . $_POST["store_id"] . '","' . $coupon_id . '")';
              $conn->exec($sqlInsert);
  			      //now inserting into usedCoupon
  		      	$sqlItemInsert = 'INSERT INTO cjk5807_usedCoupon (user_id, coupon_id) ';
  		      	$sqlItemInsert = $sqlItemInsert . 'VALUES ("' . $_POST["user_id"] . '","' . $coupon_id . '")';
  		      	$conn->exec($sqlItemInsert);
              //now inserting into total_saving
              $sqlUpdate = 'UPDATE cjk5807_account SET total_saving = ROUND(total_saving + "' . $savings . '",2) WHERE user_id = "' . $_POST["user_id"] . '"';
              $conn->exec($sqlUpdate);
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
  			    echo "<br>" . $e->getMessage();
  		    }
        }
        else
          {
    			?>
         <p>Something went wrong </p>
         <script>
    					var timer = setTimeout(function() {
    						window.location='index.php'
    					}, 3000);
          </script>
      <?php
      }
      }
  ?></p>
  </div>
</html>
