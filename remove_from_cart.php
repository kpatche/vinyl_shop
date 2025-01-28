<?php
session_start();

$record_id = filter_input(INPUT_POST, 'record_id', FILTER_VALIDATE_INT);

if ($record_id && isset($_SESSION['cart'][$record_id])) {
    unset($_SESSION['cart'][$record_id]);
}

header('Location: cart.php');
exit();
?>
