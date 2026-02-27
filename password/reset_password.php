<?php
include "../config/db.php";
date_default_timezone_set("Asia/Colombo");

if (!isset($_GET["token"])) {
    die("Invalid request.");
}

$token = $_GET["token"];

$stmt = $conn->prepare("SELECT * FROM password_resets WHERE token=? AND expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid or expired token.");
}

$row = $result->fetch_assoc();
$email = $row["email"];

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (empty($_POST["password"])) {
        $error = "Password cannot be empty.";
    } else {

        $new_password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE users SET password=? WHERE email=?");
        $update->bind_param("ss", $new_password, $email);

        if ($update->execute()) {

            $delete = $conn->prepare("DELETE FROM password_resets WHERE token=?");
            $delete->bind_param("s", $token);
            $delete->execute();

            $success = "Password updated successfully!";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - Fixigo</title>
    <link rel="stylesheet" href="reset.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="container">
    <div class="card">
        <h2>Reset Password</h2>
        <p>Enter your new password.</p>

        <form method="POST">
            <input type="password" name="password" placeholder="New Password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</div>

<?php if (!empty($success)) : ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toast = document.createElement("div");
    toast.innerText = "<?php echo $success; ?>";
    toast.className = "toast success";
    document.body.appendChild(toast);

    setTimeout(() => {
        window.location.href = "../auth.php";
    }, 3000);
});
</script>
<?php endif; ?>

<?php if (!empty($error)) : ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const toast = document.createElement("div");
    toast.innerText = "<?php echo $error; ?>";
    toast.className = "toast error";
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
});
</script>
<?php endif; ?>

</body>
</html>