<?php
session_start();
include 'connect.php';

// Check if the user is admin, restrict access if not
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied. Admins only.";
    exit();
}

// Get the product ID from the query string
$product_id = $_GET['id'];

// Fetch product details from the database
$query = "SELECT * FROM products WHERE id = '$product_id'";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "Product not found!";
    exit();
}

// Handle product update
if (isset($_POST['update_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];  // Stock field added

    // Check if an image was uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

        // Update with new image
        $sql = "UPDATE products SET name = '$name', description = '$description', price = '$price', stock = '$stock', image = '$image' WHERE id = '$product_id'";
    } else {
        // Update without changing the image
        $sql = "UPDATE products SET name = '$name', description = '$description', price = '$price', stock = '$stock' WHERE id = '$product_id'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "Product updated successfully!";
        header("Location: admin.php");
        exit();
    } else {
        echo "Error updating product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        /* Enhanced Gradient Background */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, rgb(154, 169, 255) 0%, rgb(137, 172, 248) 50%, rgb(104, 182, 245) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Centered Glassmorphism Container */
        .edit-container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: fadeIn 1s ease-in-out;
            box-sizing: border-box;
        }

        /* Align elements within the container */
        .edit-container h2 {
            text-align: center;
            color: #fff;
            font-size: 26px;
            margin-bottom: 30px;
            font-weight: 600;
        }

        /* Form Group Styling */
        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .form-group label {
            font-size: 14px;
            margin-bottom: 8px;
            color: #e0e0e0;
            text-align: left;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 12px;
            font-size: 14px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            color: #333;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #007bff;
            outline: none;
            background: rgba(255, 255, 255, 0.6);
        }

        .form-group input[type="file"] {
            padding: 5px;
            background-color: rgba(255, 255, 255, 0.4);
            border: 1px solid #e1e1e1;
        }

        .form-group button {
            padding: 14px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .form-group button:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            text-decoration: none;
            color: #007bff;
            font-size: 14px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        /* Image preview styling */
        .image-preview {
            width: 150px;
            height: 150px;
            margin: 15px auto;
            display: block;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        .form-group textarea {
            resize: vertical;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <div class="edit-container">
        <h2>Edit Product</h2>

        <!-- Display current image preview -->
        <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="image-preview">

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" step="0.01" id="price" name="price" value="<?php echo $product['price']; ?>" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" value="<?php echo $product['stock']; ?>" required>
            </div>
            <div class="form-group">
                <label for="image">Change Image</label>
                <input type="file" id="image" name="image">
            </div>
            <div class="form-group">
                <button type="submit" name="update_product">Update Product</button>
            </div>
        </form>

        <div class="back-link">
            <a href="admin.php">Back to Admin Panel</a>
        </div>
    </div>

</body>
</html>
