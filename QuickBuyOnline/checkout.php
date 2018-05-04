<?php
session_start();
// This page will only be available to registered users. If they are not registered, redirect to the login page.

include ('includes/login_functions.inc.php');
require_once('mysqli_connect.php');

if (!isset($_SESSION['agent']) OR ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']) )) {

  // we need the login_functions
  require ('includes/login_functions.inc.php');
  redirect_user('login.php');
}

$page_title = "Checkout";
include ('includes/header.php');

$user_id = (int)$_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $errors = array();
// card_number security_code expiration_date
  $name = mysqli_real_escape_string($dbc, trim($_POST['cardholder_name']));

  $number = mysqli_real_escape_string($dbc, trim($_POST['card_number']));

  $code = mysqli_real_escape_string($dbc, trim($_POST['security_code']));

  $date = mysqli_real_escape_string($dbc, trim($_POST['expiration_date']));

  $query = "SELECT * FROM checkout_accounts WHERE card_number = $number";
  $result = @mysqli_query($dbc, $query);

  if ($result AND mysqli_num_rows($result) == 0) {
    $query = "INSERT INTO checkout_accounts (user_id, cardholder_name, card_number, security_code, expiration_date) VALUES ($user_id, '$name', '$number', '$code', '$date')";
    $result = @mysqli_query($dbc, $query);

    // Create a purchase record
    $query2 = "INSERT INTO purchases (product_id) SELECT p.product_id FROM carts c JOIN carts_products cp on cp.cart_id = c.cart_id JOIN products p on p.product_id = cp.product_id WHERE c.user_id = $user_id";
    $result2 = @mysqli_query($dbc, $query2);

    $query3 = "DELETE FROM carts_products WHERE cart_id = (SELECT cart_id FROM carts WHERE user_id = $user_id)";
    $result3 = @mysqli_query($dbc, $query3);

    echo "<h1>Purchase Complete</h1>
    <p>Thank you for your purchase! Your cart has been emptied, and your credit card has been charged and your card information has been saved for future use!</p>";

    mysqli_close($dbc);

    include ('includes/footer.html');
    exit();
  } else if ($results AND mysqli_num_rows($result) != 0) {

    // Create a purchase record
    $query = "INSERT INTO purchases (product_id) SELECT p.product_id FROM carts c JOIN carts_products cp on cp.cart_id = c.cart_id JOIN products p on p.product_id = cp.product_id WHERE c.user_id = $user_id";
    $result = @mysqli_query($dbc, $query);

    echo '<h1>Purchase Complete</h1>
    <p>Thank you for your purchase! your cart has been emptied. We value your business!</p>';
  } else {

    echo '<h1>System Error</h1>
    <p class="error">There was a system error and we could not complete your purchase. We apologize for any inconvenience.</p>';
  }
}

// We need: sum of the cost, credit card info if it exist, else we need forms to input credit card info
$q = "SELECT cart_id FROM carts where user_id = $user_id";
$r = @mysqli_query ($dbc, $q);

if ($r AND mysqli_num_rows($r) == 1) { // If we have a valid cart_id for the user.
  $row = mysqli_fetch_array($r, MYSQLI_NUM);
  $cart_id = $row[0];

  $q = "SELECT SUM(p.price) FROM carts_products cp JOIN products p ON p.product_id = cp.product_id WHERE cp.cart_id = $cart_id";
  // Use the cart_id we pulled to figure out the prices of the products in the cart.
  $r = @mysqli_query($dbc, $q); // Run the query
  $rows = mysqli_fetch_array($r, MYSQLI_NUM);
}

$total = round($rows[0], 2);
// Display the sum and forms for
echo "<h1>Checkout</h1>
<p>Your total is \$$total, please enter your payment information below!</p>";
?>
<!-- Things I still need to do for this page:
        -Create the form
        -Check all the inputs for if Postback
        -If checkbox to save credit card info is checked AND there isn't already a matching credit card number in the system, save the info to the database
-->
<form action="checkout.php" method="post">
  <label for="cardholder_name">Name on card: </label>
  <input type="text" name="cardholder_name" maxlength="100" required /><br /><br />
  <label for="card_number">Card Number(no dashes): </label>
  <input type="text" name="card_number" maxlength="20" required pattern="^[0-9]{16}$"/><br /><br />
  <label for="security_code" >Security Code: </label>
  <input type="text" name="security_code" maxlength="3" required /><br /><br />
  <label for="expiration_date" >Expiration Date: </label>
  <input type="date" name="expiration_date" required />
  <input type="submit" name="submit" value="Submit" class="btn btn-default" />
</form>

<?php
include ('includes/footer.html');
?>
