<?php
require_once('database.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    
    try {
        $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':email', $email);
        $statement->execute();
        header("Location: login.php");
        exit();
    } catch (PDOException $e) {
        $error = "Username or email already exists";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Vinyl Shop</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <header>
        <h1>Vinyl Shop</h1>
        <nav class="main-nav">
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="login.php">Login</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="form-section">
            <h2>Register</h2>
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <form action="register.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <button type="submit" class="submit-button">Register</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Vinyl Shop. All rights reserved.</p>
    </footer>
</body>
</html>
