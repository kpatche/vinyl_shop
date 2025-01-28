<?php
require_once('database.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$order_id = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);
if (!$order_id) {
    header('Location: index.php');
    exit();
}

$query = 'SELECT * FROM vinyl_orders WHERE orderID = :order_id AND userID = :user_id';
$statement = $db->prepare($query);
$statement->bindValue(':order_id', $order_id);
$statement->bindValue(':user_id', $_SESSION['user_id']);
$statement->execute();
$order = $statement->fetch();
$statement->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation - Vinyl Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <header>
        <h1>Vinyl Shop</h1>
        <nav class="main-nav">
            <div class="nav-links">
                <a href="index.php">Home</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="confirmation-section">
            <h2>Order Confirmation</h2>
            <div class="confirmation-details">
                <h3>Thank you for your order!</h3>
                <p>Order Number: <?php echo $order_id; ?></p>
                <p>Total Amount: $<?php echo number_format($order['totalAmount'], 2); ?></p>
                <p>A confirmation email has been sent to your email address.</p>
                <a href="index.php" class="button">Continue Shopping</a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Vinyl Shop. All rights reserved.</p>
    </footer>
</body>
</html>
