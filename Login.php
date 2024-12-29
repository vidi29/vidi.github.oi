<?php
session_start();
require_once 'Config.php';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Query ke database
    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
    $user = mysqli_fetch_assoc($result);

    // Jika user ditemukan
    if ($user) {
        // Cek password
        if ($user['password'] === $password) {
            // Set sesi user
            $_SESSION['user'] = $user;

            if ($user) {
                echo "<script>
                    alert('Login Success, Sebagai Admin!');
                    window.location.href = 'Index.php';
                </script>";
            } 
        } else {
            // Password salah
            echo "<script>
                alert('Password Salah');
                window.location.href = 'Login.php';
            </script>";
        }
    } else {
        // User tidak ditemukan
        echo "<script>
            alert('User tidak ditemukan');
            window.location.href = 'Login.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modul 6 PAW 2024 -vall</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container mx-auto my-6  ">
        <div class="flex items-center justify-center min-h-screen">
            <div class="w-full max-w-md p-8 space-y-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white">Login to Your Account</h2>
                
                <form action="" method="POST" class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                        <input type="text" name="username" id="username" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"/>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                        <input type="password" name="password" id="password" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"/>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                            <label for="remember" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Remember me</label>
                        </div>
                        <a href="#" class="text-sm text-blue-600 hover:underline dark:text-blue-500">Forgot password?</a>
                    </div>

                    <button type="submit" name="submit"
                            class="w-full px-4 py-2 font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
                        Sign in
                    </button>
                </form>

                <p class="text-sm text-center text-gray-700 dark:text-gray-300">
                    Don't have an account? <a href="register.php" class="text-blue-600 hover:underline dark:text-blue-500">Sign up</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>


</body>
</html>