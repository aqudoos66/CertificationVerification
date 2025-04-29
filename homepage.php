<?php
session_start();

include('file/config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$participants_result = $conn->query("SELECT COUNT(*) AS total FROM candidates");
$participants_data = $participants_result->fetch_assoc();
$total_participants = $participants_data['total'];

$course_result = $conn->query("SELECT COUNT(*) AS total FROM course");
$course_data = $course_result->fetch_assoc();
$total_courses = $course_data['total'];

$current_date = date("F j, Y"); // "April 29, 2025" format
$current_time = date("h:i A"); // 12-hour format with AM/PM
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>COK - Admin Dashboard</title>
  <link rel="stylesheet" href="assets/css/app.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/custom.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel='shortcut icon' type='image/x-icon' href='assets/img/cok/logo.webp' />
  
  <style>
    :root {
      --participants: #3498db;
      --course: #27ae60;
      --date: #9b59b6;
      --time: #e67e22;
    }
    
    .dashboard-card {
      border: none;
      border-radius: 15px;
      transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
      overflow: hidden;
      position: relative;
      background: white;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
      margin-bottom: 1.5rem;
      height: 100%;
    }
    
    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .card-icon {
      position: absolute;
      right: 20px;
      top: 20px;
      font-size: 3.5rem;
      opacity: 0.15;
      transition: all 0.3s ease;
    }

    .dashboard-card:hover .card-icon {
      opacity: 0.2;
      transform: scale(1.1);
    }

    .card-content {
      padding: 25px;
      position: relative;
      z-index: 2;
    }

    .card-title {
      font-size: 1rem;
      color: #6c757d;
      margin-bottom: 8px;
      font-weight: 600;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .card-value {
      font-size: 1.8rem; /* Slightly reduced from 2.2rem */
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 0;
      line-height: 1.2;
      white-space: nowrap;
      overflow: hidden;
    }

    /* Specific style for date card */
    .card-date .card-value {
      font-size: 1.8rem;
      letter-spacing: normal;
      white-space: nowrap;
      overflow: visible;
      width: 100%;
      text-align: left;
      padding-right: 0;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .card-date .card-value {
        font-size: 1.6rem;
      }
    }
    
    @media (max-width: 576px) {
      .card-date .card-value {
        font-size: 1.4rem;
      }
    }

    .card-divider {
      width: 40px;
      height: 4px;
      margin: 15px 0;
      border-radius: 2px;
      transition: all 0.3s ease;
    }

    .dashboard-card:hover .card-divider {
      width: 60px;
    }

    /* Card specific styles */
    .card-participants .card-divider { background-color: var(--participants); }
    .card-course .card-divider { background-color: var(--course); }
    .card-date .card-divider { background-color: var(--date); }
    .card-time .card-divider { background-color: var(--time); }

    .card-participants .card-icon { color: var(--participants); }
    .card-course .card-icon { color: var(--course); }
    .card-date .card-icon { color: var(--date); }
    .card-time .card-icon { color: var(--time); }

    @media (max-width: 768px) {
      .card-value {
        font-size: 1.6rem;
      }
      .card-date .card-value {
        font-size: 1.5rem;
      }
      .card-icon {
        font-size: 2.5rem;
      }
    }
  </style>
</head>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

      <?php include('file/navbar.php'); ?>
      <?php include('file/sidebar.php'); ?>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
                   <!-- Additional Dashboard Content -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h4>Welcome to City of Knowledge Admin Panel</h4>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Participants Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="dashboard-card card-participants">
                <div class="card-content">
                  <i class="fas fa-users card-icon"></i>
                  <h5 class="card-title">Participants</h5>
                  <div class="card-divider"></div>
                  <h2 class="card-value"><?= number_format($total_participants) ?></h2>
                </div>
              </div>
            </div>

            <!-- Course Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="dashboard-card card-course">
                <div class="card-content">
                  <i class="fas fa-book-open card-icon"></i>
                  <h5 class="card-title">Courses</h5>
                  <div class="card-divider"></div>
                  <h2 class="card-value"><?= number_format($total_courses) ?></h2>
                </div>
              </div>
            </div>

            <!-- Date Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="dashboard-card card-date">
                <div class="card-content">
                  <i class="fas fa-calendar-alt card-icon"></i>
                  <h5 class="card-title">Today's Date</h5>
                  <div class="card-divider"></div>
                  <h2 class="card-value"><?= $current_date ?></h2>
                </div>
              </div>
            </div>

            <!-- Time Card -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="dashboard-card card-time">
                <div class="card-content">
                  <i class="fas fa-clock card-icon"></i>
                  <h5 class="card-title">Current Time</h5>
                  <div class="card-divider"></div>
                  <h2 class="card-value" id="liveTime"><?= $current_time ?></h2>
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
  <script src="assets/bundles/apexcharts/apexcharts.min.js"></script>
  <script src="assets/js/page/index.js"></script>
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>
  
  <script>
    // Live time update
    function updateLiveTime() {
      const now = new Date();
      const options = { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true 
      };
      document.getElementById('liveTime').textContent = now.toLocaleTimeString('en-US', options);
    }
    
    updateLiveTime();
    setInterval(updateLiveTime, 1000);
  </script>
</body>
</html>