<?php
session_start();
include 'connect.php';

// Only allow admins to access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied. Admins only.";
    exit();
}

// Handle product addition
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $image = mysqli_real_escape_string($conn, $_FILES['image']['name']);

    // File upload handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES['image']['name']);
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Insert product into the database
        $sql = "INSERT INTO products (name, description, price, stock, image) VALUES ('$name', '$description', '$price', '$stock', '$image')";

        if (mysqli_query($conn, $sql)) {
            $message = "Product added successfully!";
        } else {
            $message = "Error adding product: " . mysqli_error($conn);
        }
    } else {
        $message = "Error uploading image.";
    }
}

// Display all products
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">
    <style>
        :root {
            --main-bg-gradient: linear-gradient(135deg, #a1c4fd, #c2e9fb);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --font-color: #333;
            --accent-color: #007bff;
            --accent-hover-color: #0056b3;
            --input-bg: rgba(255, 255, 255, 0.2);
            --input-focus-color: #007bff;
            --shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--main-bg-gradient);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            backdrop-filter: blur(10px);
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
            padding: 40px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 1000px;
            text-align: center;
            color: var(--font-color);
            overflow: auto;
        }

        h2, h3 {
            margin-bottom: 20px;
            color: var(--font-color);
        }

        .product-form input, 
        .product-form textarea, 
        .product-form button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            font-size: 16px;
            background-color: var(--input-bg);
            color: var(--font-color);
            transition: all 0.3s ease;
        }

        .product-form input:focus, 
        .product-form textarea:focus {
            border-color: var(--input-focus-color);
            outline: none;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }

        .product-form button {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-hover-color));
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background 0.3s ease;
        }

        .product-form button:hover {
            background: linear-gradient(135deg, var(--accent-hover-color), var(--accent-color));
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: var(--glass-bg);
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        table th {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        table td img {
            width: 50px;
            height: auto;
        }

        .action-links a {
            color: var(--accent-color);
            text-decoration: none;
        }

        .action-links a:hover {
            text-decoration: underline;
        }

        .links {
            margin-top: 20px;
        }

        .links a {
            margin-right: 15px;
            color: var(--accent-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            table th, table td {
                padding: 8px;
            }

            .product-form input, 
            .product-form textarea, 
            .product-form button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Panel</h2>

    <!-- Form to add a new product -->
    <div class="product-form">
        <h3>Add New Product</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Product Name" required>
            <textarea name="description" placeholder="Product Description" required></textarea>
            <input type="number" name="price" placeholder="Price" step="0.01" required>
            <input type="number" name="stock" placeholder="Stock" required>
            <input type="file" name="image" required>
            <button type="submit" name="add_product">Add Product</button>
        </form>
    </div>

    <!-- Display existing products -->
    <h3>Existing Products</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($product = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo $product['name']; ?></td>
                <td><?php echo $product['description']; ?></td>
                <td><?php echo '$' . $product['price']; ?></td>
                <td><?php echo $product['stock']; ?></td>
                <td><img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>"></td>
                <td class="action-links">
                    <a href="edit.php?id=<?php echo $product['id']; ?>">Edit</a> | 
                    <a href="delete.php?id=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <div class="links">
        <a href="index.php">Back to Home</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
