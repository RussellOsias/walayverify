<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['auth'])) {
    $_SESSION['auth_status'] = "You need to be logged in to access this page";
    header('Location: loginform.php');
    exit();
}

// Include necessary files
include('config/db_conn.php');
include('includes/header.php');
include('includes/topbar.php');
include('includes/sidebar.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Student</h1>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="code.php" method="post">
      <div class="modal-body">
        <div class="form-group">
          <label for="student_id">Student ID</label>
          <input type="text" id="student_id" name="student_id" class="form-control" placeholder="Student ID">
        </div>
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" class="form-control" placeholder="Name">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Email">
        </div>
        <div class="form-group">
          <label for="course">Course</label>
          <input type="text" id="course" name="course" class="form-control" placeholder="Course">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="addUser" class="btn btn-primary">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Student</h1>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="editStudent.php" method="post">
      <div class="modal-body">
        <input type="hidden" id="edit_student_id" name="edit_student_id">
        <div class="form-group">
          <label for="edit_name">Name</label>
          <input type="text" id="edit_name" name="edit_name" class="form-control" placeholder="Name">
        </div>
        <div class="form-group">
          <label for="edit_email">Email</label>
          <input type="email" id="edit_email" name="edit_email" class="form-control" placeholder="Email">
        </div>
        <div class="form-group">
          <label for="edit_course">Course</label>
          <input type="text" id="edit_course" name="edit_course" class="form-control" placeholder="Course">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="editUserbtn" class="btn btn-primary">Save Changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Student Modal -->
<div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Student</h1>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="code.php" method="post">
      <div class="modal-body">
        <input type="hidden" id="delete_student_id" name="delete_student_id">
        <p>Are you sure you want to delete this student?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="deleteUserbtn" class="btn btn-primary">Yes, Delete!</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Registered Students</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <?php   
        if (isset($_SESSION['status'])) {
          echo "<h4>".$_SESSION['status']."</h4>";
          unset($_SESSION['status']);
        }
        ?>
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Registered Students</h3>
            <!-- Add Student Button -->
            <button type="button" class="btn btn-primary bt-sm float-right" data-toggle="modal" data-target="#addStudentModal">Add Student</button>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Course</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $query = "SELECT * FROM student_regis";
                $run_query = mysqli_query($conn, $query);
                
                if (!$run_query) {
                  die('Query Error: ' . mysqli_error($conn));
                }
                
                if(mysqli_num_rows($run_query) > 0) {
                  while ($row = mysqli_fetch_assoc($run_query)) {
                    ?>
                    <tr>
                      <td><?php echo $row['student_id'] ?></td>
                      <td><?php echo $row['full_name'] ?></td>
                      <td><?php echo $row['email'] ?></td>
                      <td><?php echo $row['course'] ?></td>
                      <td>
                        <!-- Edit Student Button -->
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#editStudentModal" onclick="editStudent('<?php echo $row['student_id']; ?>', '<?php echo $row['full_name']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['course']; ?>')">Edit</button>
                        <!-- Delete Student Button -->
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteStudentModal" onclick="deleteStudent('<?php echo $row['student_id']; ?>')">Delete</button>
                      </td>
                    </tr>
                    <?php
                  }
                } else {
                  echo "<tr><td colspan='5'>No records found</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

</div>

<?php 
include("includes/script.php");
?>

<script>
function editStudent(student_id, full_name, email, course) {
  document.getElementById("edit_student_id").value = student_id;
  document.getElementById("edit_name").value = full_name;
  document.getElementById("edit_email").value = email;
  document.getElementById("edit_course").value = course;
}

function deleteStudent(student_id) {
  document.getElementById("delete_student_id").value = student_id;
}
</script>

<?php 
include("includes/footer.php");
?>