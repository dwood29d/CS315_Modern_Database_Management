<?php
	session_start();
	include ('includes/login_functions.inc.php');
	$page_title = 'Product Page';
	include ('./includes/header.php');
	require_once ('mysqli_connect.php');
?>
<?php
	$prodID = $_GET['id'];
	#$userID = (int)$_SESSION['user_id'];
	$query1 = "SELECT * FROM products WHERE product_id = $prodID";
	$result = mysqli_query($dbc, $query1) or die("Unsuccessful fetch");
	$row = mysqli_fetch_array($result);
	Echo "<b>"."Product: "."</b>".$row['name']."<br>"."<br>";
	Echo "<b>"."Price: $"."</b>".$row['price']."<br>"."<br>";
	Echo "<b>"."Description: "."</b>".$row['description']."<br>"."<br>";
	#Echo "<b>"."Rating: "."</b>".$row['video_link']."<br>";
?>
<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['addToCart'])) {
			if (!isset($_SESSION['agent']) OR ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']) )) {
			  echo "<script type="."text/javascript".">window.location.href = 'login.php';</script>";
			}
			$prodID = $_GET['id'];
			$user_id = (int)$_SESSION['user_id'];
			$query2 = "SELECT * FROM carts c JOIN carts_products cp ON cp.cart_id = c.cart_id JOIN products p ON p.product_id = cp.product_id WHERE c.user_id = $user_id";
			$r = @mysqli_query($dbc, $query2) or die("Query2 is wrong");
			$row = mysqli_fetch_array($r);
			$cartID = $row['cart_id'];
			$query3 = "INSERT INTO carts_products (cart_id, product_id) VALUES ($cartID, $prodID)";
			$r2 = @mysqli_query($dbc, $query3) or die("Query3 is wrong");
			echo "<font color='red'>"."1 instance of this product has been added to your cart!"."</font>";
		}
		elseif (isset($_POST['removeFromCart'])) {
			if (!isset($_SESSION['agent']) OR ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']) )) {
			  echo "<script type="."text/javascript".">window.location.href = 'login.php';</script>";
			}
			$prodID = $_GET['id'];
			$user_id = (int)$_SESSION['user_id'];
			$query2 = "SELECT * FROM carts c JOIN carts_products cp ON cp.cart_id = c.cart_id JOIN products p ON p.product_id = cp.product_id WHERE c.user_id = $user_id";
			$r = @mysqli_query($dbc, $query2) or die("Query2 is wrong");
			$row = mysqli_fetch_array($r);
			$cartID = $row['cart_id'];
			$query3 = "DELETE FROM carts_products WHERE $prodID = product_id LIMIT 1";
			$r2 = @mysqli_query($dbc, $query3) or die("Query3 is wrong");
			echo "<font color='red'>"."1 instance of this product has been removed from your cart!"."</font>";
		}
	}
?>
<br>
<form name="addToCartForm" action="" method="post">
	<p><input type="submit" name="addToCart" value="Add to cart" /></p>
	<p><input type="submit" name="removeFromCart" value="Remove from cart" /></p>
</form>
<?php include ('includes/footer.html'); ?>