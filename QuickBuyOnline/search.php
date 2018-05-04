<?php
	require_once ('mysqli_connect.php');
?>
<?php
	if (isset($_POST['search'])) {
		$word = $_POST['name'];
		$query1 = "SELECT * FROM products WHERE name LIKE '%$word%'";
		$result = mysqli_query($dbc, $query1) or die ("Search unsuccessful");
		$track = mysqli_num_rows($result);
		if ($track == 0) {
			echo "No matches!";
		}else {
			echo "Matching results:" . "<br>";
			$x = 1;
			while($row = mysqli_fetch_array($result)) {
				echo $x . ". " . "<a href='"."product_page.php?id=".$row['product_id']."'>".$row['name']."</a>"."<br>";
				$x++;
			}
		}
	}
?>