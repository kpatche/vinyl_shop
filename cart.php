<?php
require_once('database.php');
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $record_id = filter_input(INPUT_POST, 'record_id', FILTER_VALIDATE_INT);
    if ($record_id) {
        $query = 'SELECT * FROM vinyl_records WHERE recordID = :record_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':record_id', $record_id);
        $statement->execute();
        $record = $statement->fetch();
        $statement->closeCursor();
        
        if ($record) {
            if (isset($_SESSION['cart'][$record_id])) {
                $_SESSION['cart'][$record_id]['quantity']++;
            } else {
                $_SESSION['cart'][$record_id] = array(
                    'title' => $record['title'],
                    'price' => $record['price'],
                    'quantity' => 1
                );
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart - Vinyl Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <header>
        <h1>Vinyl Shop</h1>
        <nav class="main-nav">
            <div class="nav-links">
                <a href="index.php">Continue Shopping</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="logout.php">Log out</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>
        <section class="cart-section">
            <h2>Your Shopping Cart</h2>
            <?php if (empty($_SESSION['cart'])) : ?>
                <p class="empty-cart">Your cart is empty.</p>
            <?php else : ?>
                <div class="cart-items">
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $id => $item) : 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <div class="cart-item">
                            <div class="item-info">
                                <h3><?php echo $item['title']; ?></h3>
                                <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                                <p>Quantity: <?php echo $item['quantity']; ?></p>
                                <p>Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
                            </div>
                            <form action="remove_from_cart.php" method="post">
                                <input type="hidden" name="record_id" value="<?php echo $id; ?>">
                                <button type="submit" class="remove-button">Remove</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-total">
                    <h3>Total: $<?php echo number_format($total, 2); ?></h3>
                    <form action="checkout.php" method="post">
                        <button type="submit" class="checkout-button">Proceed to Checkout</button>
                    </form>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Vinyl Shop. All rights reserved.</p>
    </footer>
</body>
</html>
