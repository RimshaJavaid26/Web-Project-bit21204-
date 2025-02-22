<?php
session_start();
include 'connect.php'; // Ensure you have a working connection to your database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bag Bliss - Stylish Bags</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Glassmorphism Navbar */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        nav {
            position: sticky;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ff6347;
            transition: color 0.3s ease;
        }

        .logo:hover {
            color: #e5533c;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #ff6347;
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            background: url('uploads/img1.jpg') center center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            width: 100%;
        }

        .hero-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            max-width: 90vw;
        }

        .hero h1 {
            font-size: 48px;
            color: white;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
        }

        .hero p {
            font-size: 20px;
            color: white;
            margin-top: 20px;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
        }

        .hero .btn {
            margin-top: 30px;
            padding: 12px 30px;
            background-color: #ff6347;
            color: white;
            border-radius: 30px;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        .hero .btn:hover {
            background-color: #e5533c;
        }

        /* Featured Products Section */
        .featured-products {
            padding: 50px 20px;
            text-align: center;
            background-color: #f9f9f9;
        }

        .featured-products h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #333;
        }

        .products-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 250px;
            margin: 15px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-info {
            padding: 15px;
        }

        .product-info h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .product-info p {
            font-size: 16px;
            margin-bottom: 10px;
            color: #666;
        }

        .product-info span {
            font-size: 18px;
            font-weight: bold;
            color: #ff6347;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        /* Add to Cart Button */
        .add-to-cart-btn {
            display: block;
            width: 100%;
            padding: 10px 0;
            background-color: #ff6347;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        /* Hover effect for the Add to Cart button */
        .add-to-cart-btn:hover {
            background-color: #e5533c; /* Slightly darker shade */
            transform: scale(1.05); /* Slightly enlarge */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Add shadow effect */
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 36px;
            }

            .hero p {
                font-size: 16px;
            }

            .products-grid {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav>
    <div class="logo">Bag Bliss</div>
    <div class="nav-links">
        <a href="index.php">Home</a>

        <!-- Check if user is logged in -->
        <?php if (isset($_SESSION['email'])): ?>
            <!-- If logged in as admin -->
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="admin.php">Admin Panel</a>
            <?php else: ?>
                <!-- For customer, show Cart link -->
                <a href="cart.php">Cart</a>
            <?php endif; ?>
            <!-- Show logout button if logged in -->
            <a href="logout.php">Logout</a>

        <?php else: ?>
            <!-- Show Signup/Login options if not logged in -->
            <a href="signup.php">Signup</a>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</nav>

<!-- Hero Section -->
<div class="hero">
    <div class="hero-content">
        <h1>Discover the Latest Stylish Bags</h1>
        <p>Explore our collection of trendy and elegant bags tailored for the modern woman.</p>

        <!-- Button changes based on login status -->
        <?php if (isset($_SESSION['email'])): ?>
            <a href="logout.php" class="btn">Logout</a>
        <?php else: ?>
            <a href="signup.php" class="btn">Shop Now</a>
        <?php endif; ?>
    </div>
</div>

<!-- Featured Products Section -->
<?php if (isset($_SESSION['email'])): ?>
<div class="featured-products">
    <h2>Featured Products</h2>
    <div class="products-grid">
        <?php
        // Query to fetch featured products from the database
        $sql = "SELECT * FROM products LIMIT 6"; // Adjust the query as per your requirements
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="product-card">
                    <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                    <div class="product-info">
                        <h3><?php echo $row['name']; ?></h3>
                        <p><?php echo $row['description']; ?></p>
                        <span>$<?php echo $row['price']; ?></span>
                        <!-- Add to Cart Button -->
                        <button class="add-to-cart-btn">Add to Cart</button>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>No products found</p>";
        }
        ?>
    </div>
</div>
<?php endif; ?>

</body>
</html>
