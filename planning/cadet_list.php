<?php
session_start([
    'cookie_lifetime' => 86400,
    'read_and_close'  => false,
]);
if(!isset($_SESSION['cadet'])) header('location: /index.php?status=failed&msg=You need to log in.');
include('../php/db_conn.php');
?>

<!DOCTYPE html>
<html lang="en">
  <?php include('../modules/head.php'); ?>

  <body>

    <?php include('../modules/navbar.php'); ?>

    <div class="container">

    <?php include('../modules/alerts.php'); ?>

      <div class="starter-template">
        <h1>Cadet List</h1>

        <div class="row">
          <div class="col-lg-offset-1 col-lg-10 col-md-12">
            <table id="table" class="table table-hover table=condensed text-left">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Course</th>
                  <th>Instructor</th>
                  <th>Syllabus</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $query = "SELECT cadet_id, cadet_name, cadet_course, cadet_instructor, instructor_initials, cadet_syllabus, syllabus_code FROM tbl_cadets JOIN tbl_instructors ON cadet_instructor=instructor_id JOIN tbl_syllabus ON cadet_syllabus=syllabus_id WHERE 1";
                $result = mysqli_query($link, $query);
                while($row = mysqli_fetch_array($result)) {
                  ?>
                  <tr>
                    <td><?php echo $row['cadet_name']; ?></td>
                    <td><?php echo $row['cadet_course']; ?></td>
                    <td>
                      <select id='instructorSelector' class="form-control" onchange="updateInstructor('<?php echo $row['cadet_id']; ?>')">
                      <?php
                      $query2 = "SELECT instructor_id, instructor_initials FROM tbl_instructors WHERE 1";
                      $result2 = mysqli_query($link, $query2);
                      while($row2 = mysqli_fetch_array($result2)) {
                        ?>
                        <option value="<?php echo $row2['instructor_id']; ?>" <?php if($row['cadet_instructor']==$row2['instructor_id']) echo 'selected'; ?>><?php echo $row2['instructor_initials']; ?></option>
                        <?php
                      }
                      ?>
                      </select>
                    </td>
                    <td>
                      <select id='syllabusSelector' class="form-control" onchange="updateSyllabus('<?php echo $row['cadet_id']; ?>')">
                      <?php
                      $query2 = "SELECT syllabus_id, syllabus_code FROM tbl_syllabus WHERE 1";
                      $result2 = mysqli_query($link, $query2);
                      while($row2 = mysqli_fetch_array($result2)) {
                        ?>
                        <option value="<?php echo $row2['syllabus_id']; ?>" <?php if($row['cadet_syllabus']==$row2['syllabus_id']) echo 'selected'; ?>><?php echo $row2['syllabus_code']; ?></option>
                        <?php
                      }
                      ?>
                      </select>
                    </td>
                  </tr>
                  <?php
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div><!-- /.container -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" id="ajax"></div>
      </div>
    </div>


    <?php include('../modules/scripts.php') ?>
    

    <!-- Page-dependent custom scripts -->
    <script>
    $(document).ready( function () {
        //initialise datatable
        $('#table').DataTable({
            "paging": false,
            "responsive": true
        });
    } );
    </script>
  </body>
</html>
