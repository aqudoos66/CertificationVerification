<?php
include('file/config.php');

if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$id = intval($_GET['id']);
$query = $conn->prepare("SELECT email, role FROM users WHERE id = ?");
$query->bind_param("i", $id);
$query->execute();
$query->bind_result($email, $role);
$query->fetch();
$query->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newEmail = trim($_POST['email']);
    $newRole = trim($_POST['role']);

    // Only update password if user enters one
    if (!empty($_POST['password'])) {
        $newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET email = ?, password = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $newEmail, $newPassword, $newRole, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssi", $newEmail, $newRole, $id);
    }

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: admin.php");
        exit();
    } else {
        echo "<script>alert('Update failed: " . $stmt->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>COK - Admin Dashboard</title>
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/bundles/bootstrap-daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="assets/bundles/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="assets/bundles/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="assets/bundles/jquery-selectric/selectric.css">
  <link rel="stylesheet" href="assets/bundles/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/cok/logo.webp' />
</head>

<body>
<div class="loader"></div>
<div id="app">
  <div class="main-wrapper main-wrapper-1">
    <div class="navbar-bg"></div>

    <?php include('file/navbar.php'); ?>
    <?php include('file/sidebar.php'); ?>

    <div class="main-content">
      <section class="section">
        <div class="section-body">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h4>Edit Admin</h4>
                </div>
                <div class="card-body">
                  <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required
                               value="<?php echo htmlspecialchars($email); ?>">
                      </div>

                      <div class="form-group col-md-6">
                        <label>Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control">
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6 offset-md-3">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                          <option value="">Select Role</option>
                          <option value="admin" <?php echo ($role === 'admin') ? 'selected' : ''; ?>>Admin</option>
                          <option value="user" <?php echo ($role === 'user') ? 'selected' : ''; ?>>User</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group text-center">
                      <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <?php include('file/footer.php'); ?>
  </div>
</div>

<script src="assets/js/app.min.js"></script>
<script src="assets/bundles/cleave-js/dist/cleave.min.js"></script>
<script src="assets/bundles/cleave-js/dist/addons/cleave-phone.us.js"></script>
<script src="assets/bundles/jquery-pwstrength/jquery.pwstrength.min.js"></script>
<script src="assets/bundles/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="assets/bundles/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<script src="assets/bundles/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script src="assets/bundles/select2/dist/js/select2.full.min.js"></script>
<script src="assets/bundles/jquery-selectric/jquery.selectric.min.js"></script>
<script src="assets/js/scripts.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>
