<?php
include('../php/db_conn.php');
if(isset($_GET['cadet'])) {
    $cadet_id = $_GET['cadet'];
    $query = "SELECT * FROM tbl_cadets WHERE cadet_id='$cadet_id'";
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_array($result)) {
        $cadet_name = $row['cadet_name'];
        $cadet_opsname = $row['cadet_opsname'];
        $cadet_course = $row['cadet_course'];
        $cadet_instructor = $row['cadet_instructor'];
        $cadet_syllabus = $row['cadet_syllabus'];
    }
}
?>
<div class="modal-header">
	<h4 class="modal-title block-head" id="myModalLabel">Edit Cadet Details for <?php echo $cadet_name; ?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">

        	<form id="editForm" method="POST" action="../php/editCadetDetails.php">
                <input type="hidden" name="cadetId" value="<?php echo $cadet_id; ?>" />
        		<div class="form-group">
        			<label for="name" >Name: </label>
        			<input class="form-control" name="cadetName" id="cadetName" type="text" value="<?php echo $cadet_name; ?>" required />
        		</div>
        		<div class="form-group">
                    <label for="opsname" >Ops Name: </label>
                    <input class="form-control" name="cadetOpsname" id="cadetOpsname" type="text" value="<?php echo $cadet_opsname; ?>" required />
                </div>
                <div class="form-group">
                    <label for="course" >Course: </label>
                    <input class="form-control" name="cadetCourse" id="cadetCourse" type="text" value="<?php echo $cadet_course; ?>" required />
                </div>
                <div class="form-group">
                    <label for="name" >Instructor: </label>
                    <select class="form-control" name="cadetInstructor" id="cadetInstructor">
                        <?php
                        $query = "SELECT * FROM tbl_instructors WHERE 1";
                        $result = mysqli_query($link, $query);
                        while($row = mysqli_fetch_array($result)) {
                            ?>
                            <option value="<?php echo $row['instructor_id']; ?>" <?php if($cadet_instructor==$row['instructor_id']) echo "selected"; ?>><?php echo $row['instructor_initials']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name" >Syllabus: </label>
                    <select class="form-control" name="cadetSyllabus" id="cadetSyllabus">
                        <?php
                        $query = "SELECT * FROM tbl_syllabus WHERE 1";
                        $result = mysqli_query($link, $query);
                        while($row = mysqli_fetch_array($result)) {
                            ?>
                            <option value="<?php echo $row['syllabus_id']; ?>" <?php if($cadet_syllabus==$row['syllabus_id']) echo "selected"; ?>><?php echo $row['syllabus_code']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
        	</form>

        </div>
    </div>
</div>
<div class="modal-footer">
	<div class="form-input pull-right">
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" onclick="submitForm()">Save</button>
    </div>
</div>

<script>
function submitForm() {
    document.getElementById('editForm').submit();
}
</script>