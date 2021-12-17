<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = 'cjk5807';
$password = 'password';
$host = 'e5-cse-cs431fa21s1-39';
$dbname = 'cjk5807_431W';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $pdo->beginTransaction();
  $selectUsers = 'SELECT * FROM cjk5807_account ORDER BY CAST(total_saving as decimal) DESC;';
  $users = $pdo->query($selectUsers);
  $users->setFetchMode(PDO::FETCH_ASSOC);
  if (isset($_GET["username"]) and !empty($_GET["username"])) {
  	$sql1 = 'SELECT * FROM cjk5807_account a, cjk5807_location l '
  		.'WHERE a.user_id = "' . $_GET["username"] . '" and a.location_id = l.location_id';
	  $q0 = $pdo->query($sql1);
	  $q0->setFetchMode(PDO::FETCH_ASSOC);
	  $user = $q0->fetch();
    $sql2 = 'SELECT s.store, i.name, i.price, c.discount, c.coupon_id ' .
    'FROM (SELECT s.store, i.name, i.price, MAX(c.discount) AS discount FROM cjk5807_account a, cjk5807_store2coupon t, cjk5807_stores s, cjk5807_coupon c, cjk5807_item i, cjk5807_location l ' .
    'WHERE a.user_id = "' . $user["user_id"] . '" and a.location_id = l.location_id and s.location_id = l.location_id and s.store_id = t.store_id and t.coupon_id = c.coupon_id and c.item_id = i.item_id ' .
    'GROUP BY s.store, i.name, i.price) r, cjk5807_stores s, cjk5807_coupon c, cjk5807_store2coupon t, cjk5807_item i ' .
    'WHERE s.location_id = "' . $user["location_id"] . '" and s.store=r.store and s.store_id = t.store_id and t.coupon_id = c.coupon_id and c.discount = r.discount and c.item_id = i.item_id and i.name = r.name ' .
    'ORDER BY s.store, c.discount DESC, i.name';
     $q = $pdo->query($sql2);
     $q->setFetchMode(PDO::FETCH_ASSOC);
     
    
     $sql3 = 'SELECT DISTINCT * FROM cjk5807_usedCoupon u, cjk5807_coupon c, cjk5807_item i ' .
  		'WHERE u.user_id = "' . $_GET["username"] . '" and u.coupon_id = c.coupon_id and c.item_id = i.item_id ' . 
      'ORDER BY c.discount DESC, i.name';
     $usedCoupons = $pdo->query($sql3);
     $usedCoupons->setFetchMode(PDO::FETCH_ASSOC);
     
     $sql4 = 'SELECT * FROM cjk5807_account a, cjk5807_stores s ' .
     'WHERE a.user_id = "' . $_GET["username"] . '" and a.location_id = s.location_id ' .
     'ORDER BY s.store';
     $stores = $pdo->query($sql4);
     $stores->setFetchMode(PDO::FETCH_ASSOC);
     
     $pdo->commit();
  }
  else
  {
    $sql = 'SELECT i.name, c.discount FROM cjk5807_coupon c, cjk5807_item i WHERE c.item_id=i.item_id';
    $q = $pdo->query($sql);
    $q->setFetchMode(PDO::FETCH_ASSOC);
    $pdo->commit();
  }
} catch (PDOException $e) {
  $pdo->rollBack();
  die("Could not connect to the database $dbname :" . $e->getMessage());
}

?>
<!DOCTYPE html>
<html >
	<link rel="stylesheet" href="./css/style.css">
    <head>
        <title>Discount</title>
    </head>
  
    <body>
        <div id='container'>
        
          <div class="header">
            <h1 style="font-weight: 900">Save now on DISCOUNTS.COM</h1>
          </div>
        
        <div class="navigation">
            <input class="button" type="button" value='Account Manager' onclick="openLogin()" />
           
			       <?php if(isset($_GET["username"]) and !empty($_GET["username"])) : ?>
               <input class="button" type="button" value='Add Discount' onclick="openDiscount()" />
               <input class="button" type="button" value='Referral' onclick="openReferral()" />
				       <?php echo '<h style="font-weight: bold; font-size: 25px;">Total Savings: $' . $user["total_saving"] . '</h>' ?> 
		      	 <?php else : ?>
               <input class="button" type="button" value='Add Discount' onclick="loginAlert()" />
               <input class="button" type="button" value='Referral' onclick="loginAlert()" />
            <?php endif; ?>
        </div>
        
        <div class="row">
          <div class="column">
            <h2>TOP USERS<h2>
             <table class="pretty-table">
	              <thead>
	                  <tr>
						          <th>Place</th>
		                  <th>User</th>
	                  </tr>
	              </thead>
	              <tbody>
                 <?php
                 if (isset($users)) {                 
                 $count=0; 
                 while ($row = $users->fetch() and $count < 5): 
                 ++$count;
                 ?>
  	                      <tr>
  	                          <td><?php echo htmlspecialchars($count) ?></td>
  	                          <td><?php echo htmlspecialchars($row['user_id']); ?></td>
  	                      </tr>
                 <?php 
                 endwhile; 
                 }
                 ?>
               </tbody>
            </table>
           </div>
           
           <div class="column">
              <?php if(isset($_GET["username"]) and !empty($_GET["username"])) : ?>
                <h2><?php echo "The best coupons near " . $user['city'] . ", " . $user['state_id']?></h2>
	            <table class="pretty-table">
	              <thead>
	                  <tr>
						          <th>Store</th>
		                  <th>Product</th>
		                  <th>Discount</th>
						          <th>Savings</th>
						          <th></th>
                      <th></th>
	                  </tr>
	              </thead>
	              <tbody>
	                  <?php while ($row = $q->fetch()): ?>
	                      <tr>
                          <td><?php echo htmlspecialchars($row['store']) ?></td>
                          <td><?php echo htmlspecialchars($row['name']); ?></td>
						              <td><?php echo htmlspecialchars(100*$row['discount']) . "%"; ?></td>
						              <td><?php echo "$" . htmlspecialchars(number_format($row['price']*$row['discount'],2)); ?></td>
						              <td><?php echo '<form action="/discount.php" method="post"><input type="submit" value="Apply">
							            <input type="hidden" name="user_id" value="' . htmlspecialchars($user['user_id']) . '">
                          <input type="hidden" name="saving" value="' . htmlspecialchars(number_format($row['price']*$row['discount'],2)) . '">
							            <input type="hidden" name="coupon_id" value="' . htmlspecialchars($row["coupon_id"]) . '"></form>'; ?></td>
                          <td><?php echo '<form action="/deleteCoupon.php" method="post"><input type="submit" value="Delete">
							            <input type="hidden" name="user_id" value="' . htmlspecialchars($user['user_id']) . '">
                          <input type="hidden" name="coupon_id" value="' . htmlspecialchars($row["coupon_id"]) . '"></form>'; ?></td>
	                      </tr>
	                  <?php endwhile; ?>
	              </tbody>
	            </table>
             <?php else : ?>
            <h2>We have many discounts to choose from, Sign Up TODAY!</h2>
            <table class="pretty-table">
              <thead>
                  <tr>
                      <th>Products</th>
                      <th>Discount</th>
                  </tr>
              </thead>
              <tbody>
                  <?php while ($row = $q->fetch()): ?> 
                      <tr>
                          <td><?php echo htmlspecialchars($row['name']) ?></td>
                          <td><?php echo htmlspecialchars(100*$row['discount']) . "% off"; ?></td>
                      </tr>
                  <?php endwhile; ?>
              </tbody>
            </table>
          <?php endif; ?>
          </div>

         <div class="column">
          <?php if(isset($_GET["username"]) and !empty($_GET["username"])) : ?>
            <h2>Used Coupons<h2>
             <table class="pretty-table">
	              <thead>
	                  <tr>
		                  <th>Product</th>
		                  <th>Discount</th>
	                  </tr>
	              </thead>
	              <tbody>
                 <?php
                 if (isset($usedCoupons) and $usedCoupons) {                 
                 while ($row = $usedCoupons->fetch()): 
                 ?>
  	                      <tr>
  	                          <td><?php echo htmlspecialchars($row['name']); ?></td>
                              <td><?php echo htmlspecialchars(100*$row['discount']) . "% off"; ?></td>
  	                      </tr>
                 <?php 
                 endwhile; 
                 }
                 ?>
               </tbody>
            </table>
              <?php endif; ?>
           </div>
           
        </div>
        <div id='accountManager' class="popup">
            <?php if(isset($_GET["username"]) and !empty($_GET["username"])) : ?>
               <h2><?php echo "Welcome back " . $user['fname'] . " " . $user['lname']?></h2>
               <button style="background-color: red" onclick="window.location.href='index.php'">Logout</button>
               <form action="deleteAccount.php" method="post">
                 <?php echo '<input type="hidden" name="user_id" value="' . htmlspecialchars($user['user_id']) . '">'?>
                 <button onclick="closeLogin()" style="background-color: blue" type="close" value="delete">Delete</button>
               </form>
               <button onclick="closeLogin()" style="background-color: black" type="close" value="cancel">Close</button>
               
            <?php else : ?>
            <section class="login-popup">
              <h2>Login</h2>
                <form action="account.php" method="get">
                  <input type="text" name="username" placeholder="Username...">
                  <input type="text" name="password" placeholder="Password...">
                  <button onclick="closeLogin()" type="submit" name="accountSubmit" value="login">Login</button>
                  <span><a href="#" onclick="openSignUp()">sign up</a></span>
                </form>
                <button onclick="closeLogin()" style="background-color: red" type="close" value="cancel">Cancel</button>
            </section>
           <?php endif; ?>
        </div>
         
        <div id='signup-form' class="popup">
          <section class="sign-up">
          <h2>Sign up</h2>
            <form action="account.php" method="post">
              <input type="text" name="fname" placeholder="First Name...">
              <input type="text" name="lname" placeholder="Last Name...">
              <input type="text" name="city" placeholder="City...">
              <input type="text" name="state" placeholder="State...">
              <input type="text" name="email" placeholder="Email...">
              <input type="text" name="username" placeholder="Username...">
              <input type="text" name="password" placeholder="Password...">
              <button onclick="closeSignUp()" type="submit" name="accountSubmit" value="submit">Sign Up</button>
            </form>
            <button onclick="closeSignUp()" style="background-color: red" type="close" value="cancel">Cancel</button>
          </section>
        </div>
        
        <div id='discount-form' class="popup">
          <?php if(isset($_GET["username"]) and !empty($_GET["username"])) : ?>
          <section class="add-discount">
          <h2>Record discount</h2>
            <form action="coupon.php" method="post">
              <input type="text" name="name" placeholder="Product Name">
              <input type="text" name="type" placeholder="Description">
              <select style="width: 75%; padding: 5px 5px; font-size: 18px;" name="store_id">
                <?php       
                 while ($row = $stores->fetch()): 
                 ?>
                   <option value=<?php echo htmlspecialchars($row['store_id']); ?>> <?php echo htmlspecialchars($row['store']); ?></option>
                 <?php endwhile; ?>
              </select>
              <label for="stores">Choose a store</label>
              <input type="text" name="price" placeholder="Price">
              <input type="text" name="discount" placeholder="Discount">
               <?php echo '<input type="hidden" name="user_id" value="' . htmlspecialchars($user['user_id']) . '">'?>
              <button onclick="closeDiscount()" type="submit" name="accountSubmit" value="submit">Submit</button>
            </form>
            <button onclick="closeDiscount()" style="background-color: red" type="close" value="cancel">Cancel</button>
          </section>
          <?php endif; ?>
        </div>
       
       <div id='referral-form' class="popup">
          <?php if(isset($_GET["username"]) and !empty($_GET["username"])) : ?>
          <section class="referral">
          <h2>Referral Program</h2>
           <h3>Make a referral for $5 of extra savings!</h3>
            <form action="referral.php" method="post">
              <input type="text" name="email" placeholder="Email">
              <?php echo '<input type="hidden" name="user_id" value="' . htmlspecialchars($user['user_id']) . '">'?>
              <button onclick="closeReferral()" type="submit" name="accountSubmit" value="submit">Submit</button>
            </form>
            <button onclick="closeReferral()" style="background-color: red" type="close" value="cancel">Cancel</button>
          </section>
          <?php endif; ?>
        </div>
        

</div>
</html>

<script>
function loginAlert() {
 alert("Need to login first");
}
function openReferral() {
  document.getElementById("referral-form").style.display = "block";
}
function closeReferral() {
  document.getElementById("referral-form").style.display = "none";
}
function openDiscount() {
  document.getElementById("discount-form").style.display = "block";
}
function closeDiscount() {
  document.getElementById("discount-form").style.display = "none";
}
function openLogin() {
  document.getElementById("accountManager").style.display = "block";
}
function closeLogin() {
  document.getElementById("accountManager").style.display = "none";
}
function openSignUp() {
  closeLogin();
  document.getElementById("signup-form").style.display = "block";
}
function closeSignUp() {
  document.getElementById("signup-form").style.display = "none";
}
</script>