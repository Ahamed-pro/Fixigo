<?php
require_once "db.php"; 

$admins = [
    ["AhamedAdmin", "ahamedadmin@gmail.com", "Ahamedadmin123$$"],
    ["AnasAdmin", "anasadmin@gmail.com", "Anasadmin123$$"],
    ["ZumairAdmin", "zumairadmin@gmail.com", "Zumairadmin123$$"],
    ["NadhaAdmin", "nadhaadmin@gmail.com", "Nadhaadmin123$$"],
    ["KiwiyashiniAdmin", "kiwiyashiniadmin@gmail.com", "Kiwiyashiniadmin123$$"]
];

$stmt = $conn->prepare("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)");

foreach ($admins as $admin) {
    $name = $admin[0];
    $email = $admin[1];
    $password = password_hash($admin[2], PASSWORD_DEFAULT);

    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();
}

echo "All admins inserted successfully!";