<?php
	session_start();
	$page_title = 'QuickBuyOnline';
	include ('./includes/header.php');
	include ('search.php');
	require_once ('mysqli_connect.php');
?>
<?php
	if ( (isset($_SESSION['user_id'])) && (basename($_SERVER['PHP_SELF']) != 'logout.php') ) {
		$is_admin = (int)$_SESSION['administrator'];
		if ($is_admin == 1){
			echo '<form action="inventory.php">
			<input type="submit" value="Manage Inventory" />
			</form>';
		}
	}
?>
<h3>Search for products:</h3>
<form  class=".navbar-right" method="post" action=""  id="searchform" style="padding-top: 10px; width: 310px;"> 
	<input  type="text" name="name"> 
	<input  type="submit" name="search" value="Search Products"> 
</form>
<h3>Available products:</h3>
<?php
	$query2 = "SELECT * FROM products";
	$result = mysqli_query($dbc, $query2) or die ("Search unsuccessful");
	$track = mysqli_num_rows($result);
	if ($track == 0) {
		echo "No products!";
	}else {
		$x = 1;
		while($row = mysqli_fetch_array($result)) {
			echo $x . ". " . "<b>Product name: </b>" . "<a href='"."product_page.php?id=".$row['product_id']."'>".$row['name']."</a>"."<br>";
			$x++;
		}
	}
	mysqli_close($dbc);
?>
<?php
include ('includes/footer.html');
?>