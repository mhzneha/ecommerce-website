<?php

@include 'db.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_wishlist'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_wishlist_numbers) > 0){
        $message[] = 'already added to wishlist';
    }elseif(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'already added to cart';
    }else{
        mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
        $message[] = 'product added to wishlist';
    }

}

if(isset($_POST['add_to_cart'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'already added to cart';
    }else{

        $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

        if(mysqli_num_rows($check_wishlist_numbers) > 0){
            mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        }

        mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'product added to cart';
    }

}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>search</title>
        <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="css/search.css">
        <link rel="stylesheet" href="css/products.css">
    </head>
    <body>
        <?php @include 'navbar.php'; ?>
        <!-- <hr>
        <section class="heading">
            <h3>Search page</h3>
            <p> <a href="homepage.php"><i class='bx bx-home-alt'></i> &nbsp;Home</a> &nbsp;  &nbsp;
            <i class='bx bxs-cart' ></i> &nbsp; Search
            </p>
        </section>
        <hr> -->
        <section class="search-form">
            <form action="" method="POST">
                <input type="text" class="box" placeholder="search products..." name="search_box">
                <button type="submit" class="search-icon" name="search_btn">
                <i class='bx bx-search nav-icon'></i>
            </button>
            </form>
        </section>

        <section class="products" style="padding-top: 0;">

        <div class="prod-container">

            <?php
                if(isset($_POST['search_btn'])){
                $search_box = mysqli_real_escape_string($conn, $_POST['search_box']);
                $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_box}%'") or die('query failed');
                if(mysqli_num_rows($select_products) > 0){
                    while($fetch_products = mysqli_fetch_assoc($select_products)){
            ?>
            <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>">
            <form action="" method="POST" class="box">
                <div class="top-info">
                    <div class="name"><?php echo $fetch_products['name']; ?></div>
                    <div class="price">Rs.<?php echo $fetch_products['price']; ?>/-</div>
                </div>
                <img src="images/<?php echo $fetch_products['image']; ?>" alt="" class="image">
                
                <input type="number" name="product_quantity" value="1" min="0" class="qty">
                <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                <div class="top-info"></div>
            </a>
                <!-- <input type="submit" value="add to wishlist" name="add_to_wishlist" class="option-btn"> -->
                <input type="submit" value="add to cart" name="add_to_cart" class="btn">
            </form>
            <?php
                }
                    }else{
                        echo '<p class="empty">No result found</p>';
                    }
                }
            ?>

        </div>

        </section>
    </body>
</html>