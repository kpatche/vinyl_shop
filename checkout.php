<?php
require_once('database.php');
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $db->beginTransaction();
        
        $total_amount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }
        
        $query = 'INSERT INTO vinyl_orders (userID, totalAmount) VALUES (:user_id, :total_amount)';
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $_SESSION['user_id']);
        $statement->bindValue(':total_amount', $total_amount);
        $statement->execute();
        
        $order_id = $db->lastInsertId();
        
        foreach ($_SESSION['cart'] as $record_id => $item) {
            $query = 'INSERT INTO vinyl_order_items (orderID, recordID, quantity, price) 
                     VALUES (:order_id, :record_id, :quantity, :price)';
            $statement = $db->prepare($query);
            $statement->bindValue(':order_id', $order_id);
            $statement->bindValue(':record_id', $record_id);
            $statement->bindValue(':quantity', $item['quantity']);
            $statement->bindValue(':price', $item['price']);
            $statement->execute();
        }
        
        $db->commit();
        $_SESSION['cart'] = array();
        header('Location: order_confirmation.php?order_id=' . $order_id);
        exit();
    } catch (Exception $e) {
        $db->rollBack();
        $error = "Error processing your order";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Vinyl Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <header>
        <h1>Vinyl Shop</h1>
        <nav class="main-nav">
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="cart.php">Back to Cart</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="checkout-section">
            <h2>Checkout</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            
            <div class="order-summary">
                <h3>Order Summary</h3>
                <?php foreach ($_SESSION['cart'] as $record_id => $item): ?>
                    <div class="cart-item">
                        <p><?php echo $item['title']; ?></p>
                        <p>Quantity: <?php echo $item['quantity']; ?></p>
                        <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                    </div>
                <?php endforeach; ?>
                
                <div class="total">
                    <h4>Total: $<?php echo number_format($total_amount, 2); ?></h4>
                </div>
            </div>
            
            <form action="checkout.php" method="post">
                <button type="submit" class="submit-button">Confirm Order</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Vinyl Shop. All rights reserved.</p>
    </footer>
</body>
</html>
