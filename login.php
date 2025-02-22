<?php
session_start();
include 'connect.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the user exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $row['role'];

            // Redirect to home page (index.php)
            header("Location: index.php");
            exit();
        } else {
            $message = "Invalid email or password.";
        }
    } else {
        $message = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

        .login-container {
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

        .login-container h1 {
            font-size: 26px;
            color: var(--font-color);
            margin-bottom: 25px;
        }

        .login-container input {
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

        .login-container input:focus {
            border-color: var(--input-focus-color);
            outline: none;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
        }

        .login-btn {
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

        .login-btn:hover {
            background: linear-gradient(135deg, var(--accent-hover-color), var(--accent-color));
        }

        .error {
            color: red;
            margin-top: 15px;
            font-size: 14px;
        }

        .links {
            margin-top: 20px;
        }

        .links a {
            color: var(--accent-color);
            text-decoration: none;
            font-size: 14px;
        }

        .links a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .login-container {
                padding: 20px;
            }

            .login-container h1 {
                font-size: 22px;
            }

            .login-container input, 
            .login-btn {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <h1>Login</h1>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login" class="login-btn">Login</button>
    </form>

    <?php if ($message != "") { ?>
        <p class="error"><?php echo $message; ?></p>
    <?php } ?>

    <div class="links">
        <a href="signup.php">Don't have an account? Sign up</a>
    </div>
</div>

</body>
</html>
