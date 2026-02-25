<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login Page</h2>

<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>

<?php
$conn = new mysqli("localhost", "root", "", "SocialMediaDB");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT password FROM Logins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_hash = $row["password"];

        // Verify hashed password
        if (password_verify($password, $stored_hash)) {
            $message = "Login Successful";
        } else {
            $message = "Login Unsuccessful";
        }
    } else {
        $message = "Login Unsuccessful";
    }

    $stmt->close();
}

$conn->close();
?>

<p style="color:red">
    <?php echo $message; ?>
</p>

</body>
</html>