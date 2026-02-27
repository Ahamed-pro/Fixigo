<?php


session_start();
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email    = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        header("Location: ../auth.php?error=Please+fill+in+all+fields.");
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../auth.php?error=Please+enter+a+valid+email+address.");
        exit;
    }

    $adm = $conn->prepare("SELECT id, name, password FROM admins WHERE email = ? LIMIT 1");
    $adm->bind_param("s", $email);
    $adm->execute();
    $admin = $adm->get_result()->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION["user_id"]      = $admin["id"];
        $_SESSION["user_name"]    = $admin["name"];
        $_SESSION["account_type"] = "admin";
        header("Location: ../admin/admin_index_dashboard.php");
        exit;
    }


    $stmt = $conn->prepare("SELECT id, full_name, password, account_type FROM users WHERE email = ?");
    if (!$stmt) {
        header("Location: ../auth.php?error=Database+error.+Please+try+again.");
        exit;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        header("Location: ../auth.php?error=No+account+found+with+this+email.");
        exit;
    }
    if (!password_verify($password, $user["password"])) {
        header("Location: ../auth.php?error=Incorrect+password.");
        exit;
    }


    $_SESSION["user_id"]      = $user["id"];
    $_SESSION["user_name"]    = $user["full_name"];
    $_SESSION["account_type"] = $user["account_type"];

    $welcome = urlencode("Welcome back, " . $user["full_name"] . "!");

    if ($user["account_type"] === "workshop") {
        header("Location: ../workshop/workshop_index_dashboard.php?success=" . $welcome);
    } else {
        header("Location: ../user/user_index_dashboard.php?success=" . $welcome);
    }
    exit;
}
?>