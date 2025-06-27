<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <link rel="stylesheet" href="inc/menu_bar.css" />
  <link rel="stylesheet" href="inc/loading.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <?php 
  $page = basename($_SERVER['PHP_SELF']);
  $index_active                  = $page == "index.php" ? "active" : "";
  $plan_active                   = $page == "upload_plan.php" ? "active" : "";
  $hazard_active                 = $page == "hazard.php" ? "active" : "";
  $boundary_active               = $page == "boundary.php" ? "active" : "";
  $device_active                 = $page == "devices_entry.php" ? "active" : "";
  $online_active                 = $page == "online_devices.php" ? "active" : "";
  $database_management_active    = $page == "database_management.php" ? "active" : "";
  ?>
  <div class="container">
   <h2 align="center">Automated Construction Safety Tracker Panel</h2>
   <br />
   <div align="center">
  <ul>
  <li><a class="<?php echo $index_active; ?>" href="index.php">Tracker Panel</a></li>
  <li><a class="<?php echo $plan_active; ?>" href="upload_plan.php">Upload Plan</a></li>
  <li><a class="<?php echo $boundary_active; ?>" href="boundary.php">Insert Image Boundary</a></li>
  <li><a class="<?php echo $hazard_active; ?>" href="hazard.php">Insert Hazard Area</a></li>
  <li><a class="<?php echo $device_active; ?>" href="devices_entry.php">Add Devices</a></li>
  <li><a class="<?php echo $online_active; ?>" href="online_devices.php">View Online Devices</a></li>
  <li><a class="<?php echo $database_management_active; ?>" href="database_management.php">Database Management</a></li>
  <li><a href="logout.php">Logout</a></li>
  </ul>
   </div>
   <br />

