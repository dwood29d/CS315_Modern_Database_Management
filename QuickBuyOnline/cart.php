<?php # Script - cart.php
session_start();
// This page will display the contents of the cart of the user. It should only be available to those who are currently logged in, else it should redirect to the login page.

include ('includes/login_functions.inc.php');
require_once('mysqli_connect.php');

if (!isset($_SESSION['agent']) OR ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']) )) {

  redirect_user('login.php');
}

$page_title = "Cart";
include ('includes/header.php');

$user_id = (int)$_SESSION['user_id'];

echo '<h1>Your Shopping Cart</h1>';

$q = "SELECT p.name, p.price, p.description FROM carts c JOIN carts_products cp ON c.cart_id = cp.cart_id JOIN products p on p.product_id = cp.product_id WHERE c.user_id = $user_id";
$r = @mysqli_query($dbc, $q) or die("Cart q-query unsuccessful!");

// Count the number of returned rows:
$num = mysqli_num_rows($r);

if ($num > 0) {

  // Print the number of items in the cart:
  echo '<p class="text_center">There are currently ' . $num . ' items in your cart.</p>';

  // Table header:
  echo '<table align="center" cellspacing="3" cellpadding="3" width="75%">
  <tr>
    <td align="left"><b>Name</b></td>
    <td align="left"><b>Price</b></td>
    <td align="left"><b>Description</b></td>
  </tr>
  ';

  // Fetch and print all items in cart:
  while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
    echo '<tr>
      <td align="left">' . $row['name'] . '</td>
      <td align="left"><em>' . $row['price'] . '</em></td>
      <td align="left">' . $row['description'] . '</td>
    </tr>
    ';
  }

  echo '</table>'; // Close the table.
  mysqli_free_result ($r); // Free up the resources

  // Show total and create a purchase button

  $q = "SELECT SUM(p.price) FROM carts c JOIN carts_products cp ON cp.cart_id = c.cart_id JOIN products p ON p.product_id = cp.product_id WHERE c.user_id = $user_id";
  $r = @mysqli_query($dbc, $q);
  $rows = mysqli_fetch_array($r, MYSQLI_NUM);

  $total = round($rows[0], 2);

  echo "<h2>Total</h2>
  <p>The total amount of all items in your cart is \$$total. Press the checkout button to check out.</p>";

  echo '<a href="checkout.php"><button type="button" class="btn btn-default">Checkout</button></a>';

} else { // If the cart is empty

  echo '<p>Your cart is empty</p>';
}

mysqli_close($dbc);

include ('includes/footer.html');
?>
