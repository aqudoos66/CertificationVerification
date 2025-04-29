<?php
session_start();
include('file/config.php'); // Include the database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form inputs and sanitize them
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    // Check if passwords match
    if ($password && $password !== $confirm_password) {
        $_SESSION['error'] = "Passwords must match!";
        header("Location: profile.php");
        exit();
    }

    // Profile Image Handling
    $profile_image = $_FILES['profile_image'];
    $image_error = $profile_image['error'];

    if ($image_error == 0) {
        $image_name = $profile_image['name'];
        $image_tmp_name = $profile_image['tmp_name'];
        $image_size = $profile_image['size'];
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png'];

        // Check if the image is valid
        if (!in_array($image_ext, $allowed_extensions) || $image_size > 2097152) {
            $_SESSION['error'] = "Invalid image format or size. Max 2MB and only JPEG/PNG allowed!";
            header("Location: profile.php");
            exit();
        }

        // Create a unique filename for the image
        $new_image_name = uniqid("profile_", true) . "." . $image_ext;
        $upload_dir = "assets/img/profiles/";
        $image_path = $upload_dir . $new_image_name;

        // Move the uploaded file to the server
        if (!move_uploaded_file($image_tmp_name, $image_path)) {
            $_SESSION['error'] = "Error uploading profile image!";
            header("Location: profile.php");
            exit();
        }
    } else {
        // If no image uploaded, use the current profile image (if any)
        $image_path = isset($user['profile_image']) ? $user['profile_image'] : '';
    }

    // Update the user profile information in the database
    $update_query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, profile_image = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone, $image_path, $user_id);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // If password is provided, update it as well
        if ($password) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $update_password_query = "UPDATE users SET password = ? WHERE id = ?";
            $password_stmt = $conn->prepare($update_password_query);
            $password_stmt->bind_param("si", $hashed_password, $user_id);
            $password_stmt->execute();
        }

        $_SESSION['success'] = "Profile updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating profile!";
    }

    header("Location: profile.php");
    exit();
}
?>
