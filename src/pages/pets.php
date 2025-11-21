<?php
require_once "db.php";

//Deletes
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM Pets WHERE PetID = $id");
    header("Location: pets.php");
    exit;
}

//Handles create and update
$isEdit = false;
$editPet = [
    "PetID" => "",
    "Name" => "",
    "Age" => "",
    "Street" => "",
    "City" => "",
    "ZipCode" => "",
    "State" => "",
    "TypeofPet" => ""
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $petID  = (int)($_POST["PetID"] ?? 0);
    $name   = $conn->real_escape_string($_POST["Name"]);
    $age    = (int)$_POST["Age"];
    $street = $conn->real_escape_string($_POST["Street"]);
    $city   = $conn->real_escape_string($_POST["City"]);
    $zip    = $conn->real_escape_string($_POST["ZipCode"]);
    $state  = $conn->real_escape_string($_POST["State"]);
    $type   = $conn->real_escape_string($_POST["TypeofPet"]);

    if ($petID > 0) {
        // UPDATE
        $sql = "UPDATE Pets SET
                    Name='$name',
                    Age=$age,
                    Street='$street',
                    City='$city',
                    ZipCode='$zip',
                    State='$state',
                    TypeofPet='$type'
                WHERE PetID=$petID";
        $conn->query($sql);
    } else {
        // CREATE
        $sql = "INSERT INTO Pets (Name, Age, Street, City, ZipCode, State, TypeofPet)
                VALUES ('$name', $age, '$street', '$city', '$zip', '$state', '$type')";
        $conn->query($sql);
    }

    header("Location: pets.php");
    exit;
}

//Loads edit target
if (isset($_GET["edit"])) {
    $isEdit = true;
    $id = (int)$_GET["edit"];
    $res = $conn->query("SELECT * FROM Pets WHERE PetID=$id");
    if ($res && $res->num_rows > 0) {
        $editPet = $res->fetch_assoc();
    }
}

//Reads all pets
$pets = $conn->query("SELECT * FROM Pets ORDER BY PetID DESC");
?>









<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Star Admin2 </title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
<link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
<link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
<link rel="stylesheet" href="../assets/vendors/typicons/typicons.css">
<link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
<link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
<!-- endinject -->
<!-- Plugin css for this page -->
<link rel="stylesheet" href="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
<link rel="stylesheet" type="text/css" href="../assets/js/select.dataTables.min.css">
<!-- End plugin css for this page -->
<!-- inject:css -->
<link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
<!-- endinject -->
<link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body> 
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
  <div id="settings-trigger"><i class="ti-settings"></i></div>
  <div id="theme-settings" class="settings-panel">
    <i class="settings-close ti-close"></i>
    <p class="settings-heading">SIDEBAR SKINS</p>
    <div class="sidebar-bg-options selected" id="sidebar-light-theme">
      <div class="img-ss rounded-circle bg-light border me-3"></div>Light
    </div>
    <div class="sidebar-bg-options" id="sidebar-dark-theme">
      <div class="img-ss rounded-circle bg-dark border me-3"></div>Dark
    </div>
    <p class="settings-heading mt-2">HEADER SKINS</p>
    <div class="color-tiles mx-0 px-4">
      <div class="tiles success"></div>
      <div class="tiles warning"></div>
      <div class="tiles danger"></div>
      <div class="tiles info"></div>
      <div class="tiles dark"></div>
      <div class="tiles default"></div>
    </div>
  </div>
</div>
<div id="right-sidebar" class="settings-panel">
  <i class="settings-close ti-close"></i>
  <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="todo-tab" data-bs-toggle="tab" href="#todo-section" role="tab"
        aria-controls="todo-section" aria-expanded="true">TO DO LIST</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="chats-tab" data-bs-toggle="tab" href="#chats-section" role="tab"
        aria-controls="chats-section">CHATS</a>
    </li>
  </ul>
  <div class="tab-content" id="setting-content">
    <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel"
      aria-labelledby="todo-section">
      <div class="add-items d-flex px-3 mb-0">
        <form class="form w-100">
          <div class="form-group d-flex">
            <input type="text" class="form-control todo-list-input" placeholder="Add To-do">
            <button type="submit" class="add btn btn-primary todo-list-add-btn" id="add-task">Add</button>
          </div>
        </form>
      </div>
      <div class="list-wrapper px-3">
        <ul class="d-flex flex-column-reverse todo-list">
          <li>
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox">
                Team review meeting at 3.00 PM
              </label>
            </div>
            <i class="remove ti-close"></i>
          </li>
          <li>
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox">
                Prepare for presentation
              </label>
            </div>
            <i class="remove ti-close"></i>
          </li>
          <li>
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox">
                Resolve all the low priority tickets due today
              </label>
            </div>
            <i class="remove ti-close"></i>
          </li>
          <li class="completed">
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox" checked>
                Schedule meeting for next week
              </label>
            </div>
            <i class="remove ti-close"></i>
          </li>
          <li class="completed">
            <div class="form-check">
              <label class="form-check-label">
                <input class="checkbox" type="checkbox" checked>
                Project review
              </label>
            </div>
            <i class="remove ti-close"></i>
          </li>
        </ul>
      </div>
      <h4 class="px-3 text-muted mt-5 fw-light mb-0">Events</h4>
      <div class="events pt-4 px-3">
        <div class="wrapper d-flex mb-2">
          <i class="ti-control-record text-primary me-2"></i>
          <span>Feb 11 2018</span>
        </div>
        <p class="mb-0 font-weight-thin text-gray">Creating component page build a js</p>
        <p class="text-gray mb-0">The total number of sessions</p>
      </div>
      <div class="events pt-4 px-3">
        <div class="wrapper d-flex mb-2">
          <i class="ti-control-record text-primary me-2"></i>
          <span>Feb 7 2018</span>
        </div>
        <p class="mb-0 font-weight-thin text-gray">Meeting with Alisa</p>
        <p class="text-gray mb-0 ">Call Sarah Graves</p>
      </div>
    </div>
  </div>
</div>
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
  <nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
    <li class="nav-item">
      <a class="nav-link" href="../index.html">
        <i class="mdi mdi-grid-large menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    <li class="nav-item nav-category">UI Elements</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
        <i class="menu-icon mdi mdi-floor-plan"></i>
        <span class="menu-title">UI Elements</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="ui-basic">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="../pages/ui-features/buttons.html">Buttons</a></li>
          <li class="nav-item"> <a class="nav-link" href="../pages/ui-features/dropdowns.html">Dropdowns</a></li>
          <li class="nav-item"> <a class="nav-link" href="../pages/ui-features/typography.html">Typography</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item nav-category">Forms and Datas</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false"
        aria-controls="form-elements">
        <i class="menu-icon mdi mdi-card-text-outline"></i>
        <span class="menu-title">Form elements</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="form-elements">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"><a class="nav-link" href="../pages/forms/basic_elements.html">Basic Elements</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
        <i class="menu-icon mdi mdi-chart-line"></i>
        <span class="menu-title">Charts</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="charts">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="../pages/charts/chartjs.html">ChartJs</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
        <i class="menu-icon mdi mdi-table"></i>
        <span class="menu-title">Tables</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="tables">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="../pages/tables/basic-table.html">Basic table</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false" aria-controls="icons">
        <i class="menu-icon mdi mdi-layers-outline"></i>
        <span class="menu-title">Icons</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="icons">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="../pages/icons/mdi.html">Mdi icons</a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item nav-category">pages</li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
        <i class="menu-icon mdi mdi-account-circle-outline"></i>
        <span class="menu-title">User Pages</span>
        <i class="menu-arrow"></i>
      </a>
      <div class="collapse" id="auth">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> <a class="nav-link" href="../pages/samples/login.html"> Login </a></li>
        </ul>
      </div>
    </li>
    <li class="nav-item nav-category">help</li>
    <li class="nav-item">
      <a class="nav-link" href="http://bootstrapdash.com/demo/star-admin2-free/docs/documentation.html">
        <i class="menu-icon mdi mdi-file-document"></i>
        <span class="menu-title">Documentation</span>
      </a>
    </li>
  </ul>
</nav>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <ul class="nav nav-tabs" role="tablist">
                         <li class="nav-item">
                      <a class="nav-link" href="../index.html">Home</a>
                    <li class="nav-item">
                      <a class="nav-link" href="pets.php">Pets</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="owners.php">Owners</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="foods.php">Foods</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="owns.php">Owns</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link border-0" href="Purchases.php">Purchases</a>
                    </li>
                  </ul>
                </div>
                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="statistics-details d-flex align-items-center justify-content-between">
                        </div>
                      </div>
                    </div>
                        <div class="row flex-grow">
                          <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <div class="table-responsive">
                                  <table class="table table-hover">
                                    <thead>
                                      <tr>
                                        <th> PetID </th>
                                        <th> Name </th>
                                        <th> Age </th>
                                        <th> Street </th>
                                        <th> City </th>
                                        <th> ZipCode </th>
                                        <th> State </th>
                                        <th> TypeofPet </th>
                                        <th> Actions </th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php while ($pet = $pets->fetch_assoc()): ?>
                                      <tr>
                                        <td> <?= htmlspecialchars($pet['PetID']) ?> </td>
                                        <td> <?= htmlspecialchars($pet['Name']) ?> </td>
                                        <td> <?= htmlspecialchars($pet['Age']) ?> </td>
                                        <td> <?= htmlspecialchars($pet['Street']) ?> </td>
                                        <td> <?= htmlspecialchars($pet['City']) ?> </td>
                                        <td> <?= htmlspecialchars($pet['ZipCode']) ?> </td>
                                        <td> <?= htmlspecialchars($pet['State']) ?> </td>
                                        <td> <?= htmlspecialchars($pet['TypeofPet']) ?> </td>
                                        <td>
                                          <a href="pets.php?edit=<?= $pet['PetID'] ?>">Edit</a> |
                                          <a href="pets.php?delete=<?= $pet['PetID'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                      </tr>
                                      <?php endwhile; ?>
                                    </tbody>
                                  
                                  </table>
                                  <div class="row">
                                    <div class="col-12 grid-margin stretch-card">
                                    <div class="card card-rounded">
                                      <div class="card-body">
                                        <h4 class="card-title"><?= $isEdit ? 'Edit Pet' : 'Add New Pet' ?></h4>
                                        <form method="POST" class="forms-sample">
                                          <input type="hidden" name="PetID" value="<?= htmlspecialchars($editPet['PetID']) ?>">
                                          
                                          <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" name="Name" value="<?= htmlspecialchars($editPet['Name']) ?>" required>
                                          </div>
                                          
                                          <div class="form-group">
                                            <label>Age</label>
                                            <input type="number" class="form-control" name="Age" value="<?= htmlspecialchars($editPet['Age']) ?>" required>
                                          </div>
                                          
                                          <div class="form-group">
                                            <label>Street</label>
                                            <input type="text" class="form-control" name="Street" value="<?= htmlspecialchars($editPet['Street']) ?>">
                                          </div>
                                          
                                          <div class="form-group">
                                            <label>City</label>
                                            <input type="text" class="form-control" name="City" value="<?= htmlspecialchars($editPet['City']) ?>">
                                          </div>
                                          
                                          <div class="form-group">
                                            <label>Zip Code</label>
                                            <input type="text" class="form-control" name="ZipCode" value="<?= htmlspecialchars($editPet['ZipCode']) ?>">
                                          </div>
                                          
                                          <div class="form-group">
                                            <label>State</label>
                                            <input type="text" class="form-control" name="State" value="<?= htmlspecialchars($editPet['State']) ?>">
                                          </div>
                                          
                                          <div class="form-group">
                                            <label>Type of Pet</label>
                                            <input type="text" class="form-control" name="TypeofPet" value="<?= htmlspecialchars($editPet['TypeofPet']) ?>">
                                          </div>
                                          
                                          <button type="submit" class="btn btn-primary me-2"><?= $isEdit ? 'Update' : 'Create' ?></button>
                                          <?php if ($isEdit): ?>
                                            <a href="pets.php" class="btn btn-light">Cancel</a>
                                          <?php endif; ?>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                      </div>
                
                          </div> 
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
</footer>
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
</div>
  <!-- plugins:js -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="assets/vendors/chart.js/Chart.min.js"></script>
  <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
  <script src="assets/js/dashboard.js"></script>
  <script src="assets/js/proBanner.js"></script>
  <!-- <script src="../../assets/js/Chart.roundedBarCharts.js"></script> -->
  <!-- End custom js for this page-->
</body>

</html>