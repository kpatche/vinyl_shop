<?php
require_once('database.php');
session_start();

$genre_id = filter_input(INPUT_GET, 'genre_id', FILTER_VALIDATE_INT);

$query = 'SELECT r.*, g.genreName 
          FROM vinyl_records r 
          JOIN vinyl_genres g ON r.genreID = g.genreID';

if ($genre_id) {
    $query .= ' WHERE r.genreID = :genre_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':genre_id', $genre_id);
} else {
    $statement = $db->prepare($query);
}

$statement->execute();
$records = $statement->fetchAll();
$statement->closeCursor();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vinyl Records by Genre</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <header>
        <h1>Vinyl Records</h1>
        <nav>
            <a href="index.php">Home</a> |
            <a href="cart.php" class="cart-button">View Cart 
                <?php 
                if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                    echo '(' . count($_SESSION['cart']) . ')';
                }
                ?>
            </a>
        </nav>
    </header>
    
    <main>
        <div class="records-container">
            <?php foreach ($records as $record) : ?>
                <div class="record-card">
                    <h3><?php echo $record['title']; ?></h3>
                    <p>Artist: <?php echo $record['artist']; ?></p>
                    <p>Genre: <?php echo $record['genreName']; ?></p>
                    <p>Price: $<?php echo number_format($record['price'], 2); ?></p>
                    <form action="cart.php" method="post">
                        <input type="hidden" name="record_id" value="<?php echo $record['recordID']; ?>">
                        <input type="submit" value="Add to Cart" class="button">
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
