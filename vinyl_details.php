<?php
require_once('database.php');
require_once('config.php');
session_start();

$record_id = filter_input(INPUT_GET, 'record_id', FILTER_VALIDATE_INT);
if ($record_id == NULL || $record_id == FALSE) {
    header('Location: index.php');
    exit();
}

$query = 'SELECT r.*, g.genreName 
          FROM vinyl_records r 
          JOIN vinyl_genres g ON r.genreID = g.genreID 
          WHERE recordID = :record_id';
$statement = $db->prepare($query);
$statement->bindValue(':record_id', $record_id);
$statement->execute();
$record = $statement->fetch();
$statement->closeCursor();

$discogsInfo = getDiscogsInfo($record['artist'], $record['title']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Record Details - Vinyl Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <h1>Record Details</h1>
    
    <nav>
        <a href="index.php">Home</a> | 
        <a href="cart.php">Cart</a> | 
        <a href="logout.php">Log out</a>
    </nav>

    <main>
        <h2><?php echo $record['title']; ?></h2>
        
        <div class="record-info">
            <p><strong>Artist:</strong> <?php echo $record['artist']; ?></p>
            <p><strong>Genre:</strong> <?php echo $record['genreName']; ?></p>
            <p><strong>Price:</strong> $<?php echo number_format($record['price'], 2); ?></p>
            <p><strong>Stock:</strong> <?php echo $record['stock']; ?> units</p>
        </div>

        <div class="additional-info">
            <h3>Additional Information</h3>
            <?php if ($discogsInfo && isset($discogsInfo['results'][0])): ?>
                <?php $result = $discogsInfo['results'][0]; ?>
                <p><strong>Release Year:</strong> <?php echo $result['year']; ?></p>
                <p><strong>Format:</strong> <?php echo implode(', ', $result['format']); ?></p>
                <p><strong>Style:</strong> <?php echo implode(', ', $result['style']); ?></p>
                <?php if (isset($result['thumb'])): ?>
                    <img src="<?php echo $result['thumb']; ?>" alt="Album Cover" class="album-thumb">
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <?php if ($record['stock'] > 0): ?>
            <form action="cart.php" method="post">
                <input type="hidden" name="record_id" value="<?php echo $record['recordID']; ?>">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $record['stock']; ?>">
                <input type="submit" value="Add to Cart" class="add-to-cart">
            </form>
        <?php else: ?>
            <p class="out-of-stock">Out of Stock</p>
        <?php endif; ?>

        <p><a href="vinyl_list.php">Back to Records List</a></p>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Vinyl Shop. All rights reserved.</p>
    </footer>
</body>
</html>
