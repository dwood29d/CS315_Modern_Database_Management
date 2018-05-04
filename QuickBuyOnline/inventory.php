<?php
	session_start();
	include ('includes/login_functions.inc.php');
	$page_title = 'Inventory';
	include ('./includes/header.php');
	require_once ('mysqli_connect.php');
?>
<?php
	$query2 = "SELECT * FROM products";
	$result = mysqli_query($dbc, $query2) or die ("Search unsuccessful");
	$track = mysqli_num_rows($result);
	if ($track == 0) {
		echo "No products!";
	}else {
		echo "<b>" . "Available products:" . "</b>" . "<br>" . "<br>";
		$x = 1;
		while($row = mysqli_fetch_array($result)) {
			echo $x . ". " . "<b>Product name: </b>" . "<a href='"."product_page.php?id=".$row['product_id']."'>".$row['name']."</a>"
			. "<b> Amount in inventory: </b>" . $row['inventory'] . "<br>";
			$x++;
		}
	}
	mysqli_close($dbc);
?>