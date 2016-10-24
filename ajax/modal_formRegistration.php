<?php
include('../php/db_conn.php');
?>
<div class="modal-header">
	<h4 class="modal-title block-head" id="myModalLabel">Sign up for a new account</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">

        	<form id="registrationForm" method="POST" action="../php/registerNewAccount.php">
        		<div class="form-group">
        			<label>Name: </label>
        			<select class="form-control" name="cadet">
        				<?php
        				echo $query = "SELECT DISTINCT(cadet_id), cadet_name FROM tbl_cadets LEFT JOIN tbl_login ON login_cadet=cadet_id WHERE login_cadet IS NULL ORDER BY cadet_name ASC";
        				$result = mysqli_query($link, $query);
        				while($row = mysqli_fetch_array($result)) {
        					?>
        					<option value="<?php echo $row['cadet_id']; ?>"><?php echo $row['cadet_name']; ?></option>
        					<?php
        				}
        				?>
        			</select>
        		</div>
        		<div class="form-group">
        			<label>E-mail: </label>
        			<input class="form-control" name="email" id="email" type="email" placeholder="Email" value="<?php if(isset($_GET['email'])) echo $_GET['email']; ?>" required/>
        		</div>
        		<div class="form-group">
        			<label>Password: </label>
        			<input class="form-control" name="password" type="password" placeholder="Password" required/>
        		</div>
        		<div class="form-group">
        			<label>Weight: </label>
        			<input class="form-control" name="weight" type="number" placeholder="Weight" step="1" required/>
        		</div>
        		<div class="form-group">
        			<label>Course: </label>
        			<select class="form-control" name="course">
        				<?php
        				echo $query = "SELECT DISTINCT(cadet_course) FROM tbl_cadets WHERE cadet_course IS NOT NULL AND cadet_course!='' ORDER BY cadet_course ASC";
        				$result = mysqli_query($link, $query);
        				while($row = mysqli_fetch_array($result)) {
        					?>
        					<option value="<?php echo $row['cadet_course']; ?>"><?php echo $row['cadet_course']; ?></option>
        					<?php
        				}
        				?>
        			</select>
        		</div>
        		<div>
        			<span id="errorSpan"></span>
        		</div>
        	</form>

        </div>
    </div>
</div>
<div class="modal-footer">
	<div class="form-input pull-right">
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" onclick="submitRegistration()">Register</button>
    </div>
</div>

<script>
function submitRegistration() {
	var error = 0;
	var email = document.getElementById('email').value;
  	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  	if(!re.test(email)){
    	document.getElementById('errorSpan').innerHTML = "Not a valid email address.";
    	error = 1;
  	}
  	if(error==0) {
  		document.getElementById('registrationForm').submit();
  	}
}
</script>