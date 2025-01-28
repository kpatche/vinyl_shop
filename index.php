<?php
require_once('database.php');
session_start();

$query = 'SELECT * FROM vinyl_genres ORDER BY genreName';
$statement = $db->prepare($query);
$statement->execute();
$genres = $statement->fetchAll();
$statement->closeCursor();

$query = 'SELECT r.*, g.genreName 
          FROM vinyl_records r 
          JOIN vinyl_genres g ON r.genreID = g.genreID 
          ORDER BY r.title';
$statement = $db->prepare($query);
$statement->execute();
$records = $statement->fetchAll();
$statement->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vinyl Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <header>
        <h1>Vinyl Shop</h1>
        <nav class="main-nav">
            <?php if (isset($_SESSION['user_id'])): ?>
                <p>Welcome, <?php echo $_SESSION['username']; ?></p>
                <div class="nav-links">
                    <a href="cart.php" class="cart-button">View Cart</a>
                    <a href="logout.php">Log out</a>
                </div>
            <?php else: ?>
                <div class="nav-links">
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                </div>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <section class="genres-section">
            <h2>Genres</h2>
            <div class="genre-links">
                <?php foreach ($genres as $genre) : ?>
                    <a href="vinyl_list.php?genre_id=<?php echo $genre['genreID']; ?>">
                        <?php echo $genre['genreName']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="collection">
            <h2>Our Collection</h2>
            <div class="records-grid">
                <?php foreach ($records as $record) : ?>
                    <div class="record-card">
                        <h3><a href="vinyl_details.php?record_id=<?php echo $record['recordID']; ?>">
                            <?php echo $record['title']; ?>
                        </a></h3>
                        <div class="record-info">
                            <p><strong>Artist:</strong> <?php echo $record['artist']; ?></p>
                            <p><strong>Genre:</strong> <?php echo $record['genreName']; ?></p>
                            <p><strong>Price:</strong> $<?php echo number_format($record['price'], 2); ?></p>
                            <p><strong>Stock:</strong> <?php echo $record['stock']; ?></p>
                        </div>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form action="cart.php" method="post">
                                <input type="hidden" name="record_id" value="<?php echo $record['recordID']; ?>">
                                <button type="submit" class="add-to-cart">Add to Cart</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Vinyl Shop. All rights reserved.</p>
    </footer>
</body>
</html>
