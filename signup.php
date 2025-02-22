<?php
include 'connect.php';

$message = "";

if (isset($_POST['signup'])) {
    // Sanitize and validate input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the `users` table
    $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hashed_password', '$role')";

    if (mysqli_query($conn, $query)) {
        $message = "Signup successful! Redirecting to login page...";
        header("refresh:3;url=login.php");  // Redirect to login.php after 3 seconds
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap">
    <style>
        /* CSS Variables for consistent design */
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
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .signup-container {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: 15px;
            padding: 40px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .signup-container h2 {
            font-size: 26px;
            color: var(--font-color);
            margin-bottom: 25px;
        }

        .signup-container input, 
        .signup-container select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid var(--glass-border);
            border-radius: 10px;
            font-size: 16px;
            background-color: var(--input-bg);
            color: var(--font-color);
            transition: all 0.3s ease;
        }

        .signup-container input:focus, 
        .signup-container select:focus {
            border-color: var(--input-focus-color);
            outline: none;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }

        .signup-container button {
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

        .signup-container button:hover {
            background: linear-gradient(135deg, var(--accent-hover-color), var(--accent-color));
        }

        .signup-container a {
            color: var(--accent-color);
            text-decoration: none;
            font-size: 14px;
            margin-top: 15px;
            display: inline-block;
        }

        .signup-container a:hover {
            text-decoration: underline;
        }

        .message {
            color: green;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .message.error {
            color: red;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .signup-container {
                padding: 20px;
            }

            .signup-container h2 {
                font-size: 22px;
            }

            .signup-container input, 
            .signup-container select, 
            .signup-container button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="signup-container">
    <h2>Create Your Account</h2>
    <?php if ($message != "") { ?>
        <p class="message"><?php echo $message; ?></p>
    <?php } ?>
    <form method="POST">
        <input type="text" name="name" placeholder="Enter your name" required>
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Create a password" required>
        <select name="role" required>
            <option value="" disabled selected>Select Role</option>
            <option value="customer">Customer</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit" name="signup">Sign Up</button>
    </form>
    <a href="login.php">Already have an account? Log in here</a>
</div>

</body>
</html>
