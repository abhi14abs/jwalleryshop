<?php
session_start(); // make sure sessions are active

// Get user id safely
if (isset($_SESSION['user_id'])) {
	$userid = (int) $_SESSION['user_id']; // cast to int for safety
} else {
	$userid = 0; // guest
}

// Get username safely
if (isset($_SESSION['username'])) {
	$User = $_SESSION['username'];
} else {
	$User = "";
}

include("includes/mysqli_connection.php");

// Get cart item count
$sql = "SELECT COUNT(*) FROM cart WHERE cust_id = $userid AND checkout = 'n'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_row($query);
$countrows = $row[0];

// Initialize totals
$totalquantity = 0;
$subtotal = 0;
$totalamount = 0;
$vat = 0.15;
$delivery = 500;

// Get product details
$selectproducts = "SELECT * 
                   FROM cart 
                   JOIN jewellery ON jewellery.id = cart.jewel_id 
                   WHERE cart.cust_id = $userid AND checkout = 'n'";

$query = mysqli_query($db_conx, $selectproducts);

while ($row = mysqli_fetch_assoc($query)) {
	$jewelid = $row["jewel_id"];
	$qty = $row["qty"];
	$userid = $row["cust_id"];
	$checkout = $row["checkout"];

	$prodname = $row["prodname"];
	$path = $row["path"];
	$category = $row["category"];
	$price = $row["price"];
	$desc = $row["descr"];
	$width = "150px";
	$height = "150px";

	$amount = round($qty * $price);
	if (round($amount * 10) == $amount * 10 && round($amount) != $amount) {
		$amount = "$amount" . "0"; // ensure .50 instead of .5
	}
	if (round($amount) == $amount) {
		$amount  = "$amount" . ".00"; // add .00
	}

	$totalquantity += $qty;
	$subtotal += $amount;
	$vat = round(0.15 * $subtotal);
	$totalamount = ($subtotal + $vat + $delivery);
}
?>
<!-- Header Part Start-->
<header id="headerWrapper">
	<div id="header">
		<div id="logo">
			<a href="index-1.php"><img src="image/logo.png" title="BB Jewellery Logo" alt="Our Logo" /></a>
		</div>
		<!-- Mini Cart Start-->
		<div id="cart">
			<div class="heading">
				<a href="cart.php">
					<span id="cart-total">
						<?php echo $countrows; ?> item(s) - Rs <?php echo $totalamount; ?>
					</span>
				</a>
			</div>
		</div>
		<!-- Mini Cart End-->
		<?php
		// Display Username
		echo '<div id="welcome"> Welcome <b>' . $User . '</b> || <a href="logout.php">Log Out</a></div>';
		?>
	</div>

	<!-- Main Navigation Start-->
	<?php include("navigation.php"); ?>
	<!-- Main Navigation End-->

	<div id="SearchDiv" class="SearchDiv"></div>
</header>
<!-- Header Part End-->