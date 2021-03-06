<?php
include('../php/db_conn.php');
$query = "SELECT cadet_course AS last_course FROM tbl_cadets WHERE cadet_id=(SELECT MAX(cadet_id) FROM tbl_cadets)";
$result = mysqli_query($link, $query);
while($row = mysqli_fetch_array($result)) {
    $cadet_course = $row['last_course'];
}
?>
<div class="modal-header">
	<h4 class="modal-title block-head" id="myModalLabel">Add New Cadet</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">

        	<form id="addForm" method="POST" action="../php/createCadetDetails.php">
        		<div class="form-group">
        			<label for="name" >Name: </label>
        			<input class="form-control" name="cadetName" id="cadetName" type="text" value="" required />
        		</div>
        		<div class="form-group">
                    <label for="opsname" >Ops Name: </label>
                    <input class="form-control" name="cadetOpsname" id="cadetOpsname" type="text" value="" required />
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
                            <option value="<?php echo $row['instructor_id']; ?>"><?php echo $row['instructor_initials']; ?></option>
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
                            <option value="<?php echo $row['syllabus_id']; ?>"><?php echo $row['syllabus_code']; ?></option>
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
    document.getElementById('addForm').submit();
}
</script>