<?php
// migrate_passwords.php

require_once 'includes/database.php';

$conn = get_db_connection();

// Alter the password column to store hashes
$sql_alter = "ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL";
if (mysqli_query($conn, $sql_alter)) {
    echo "Table 'users' altered successfully.\n";
} else {
    echo "ERROR: Could not alter table 'users'. " . mysqli_error($conn) . "\n";
    exit;
}

// Fetch all users
$sql_select = "SELECT user_id, password FROM users";
$result = mysqli_query($conn, $sql_select);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['user_id'];
        $plain_password = $row['password'];

        // Check if the password is already hashed. This is a simple check, it assumes that passwords that are 60 characters long are already hashed.
        if (strlen($plain_password) === 60 && password_get_info($plain_password)['algo']) {
            echo "Password for user_id $user_id is already hashed. Skipping.\n";
            continue;
        }

        $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

        $sql_update = "UPDATE users SET password = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql_update);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                echo "Password for user_id $user_id has been updated.\n";
            } else {
                echo "ERROR: Could not update password for user_id $user_id. " . mysqli_error($conn) . "\n";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "ERROR: Could not prepare update statement for user_id $user_id. " . mysqli_error($conn) . "\n";
        }
    }
    mysqli_free_result($result);
} else {
    echo "ERROR: Could not fetch users. " . mysqli_error($conn) . "\n";
}

mysqli_close($conn);
?>
