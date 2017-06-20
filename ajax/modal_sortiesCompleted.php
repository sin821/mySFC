<?php
include('../php/db_conn.php');
?>
<div class="modal-header">
	<h4 class="modal-title block-head" id="myModalLabel">Track Progress: <?php echo $_GET['cadet']; ?></h4>
</div>
<div class="modal-body">
    <?php
    $cadet_opsname = $_GET['cadet'];

    $query = "SELECT cadet_name, cadet_crosswind, cadet_signedCCT, cadet_signedGH, cadet_signedNav FROM tbl_cadets WHERE cadet_opsname='$cadet_opsname'";
    $result = mysqli_query($link, $query);
    while($row = mysqli_fetch_array($result)) {
        $signedCCT = $row['cadet_signedCCT'];
        $signedGH = $row['cadet_signedGH'];
        $signedNav = $row['cadet_signedNav'];
        $crosswind = $row['cadet_crosswind'];
        $cadet = addslashes($row['cadet_name']);
    }
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-3 text-center well well-sm">
                <p><b>Signed CCT:</b></p>
                <p><?php echo $signedCCT; ?></p>
            </div>
            <div class="col-md-3 text-center well well-sm">
                <p><b>Signed GH:</b></p>
                <p><?php echo $signedGH; ?></p>
            </div>
            <div class="col-md-3 text-center well well-sm">
                <p><b>Signed Nav:</b></p>
                <p><?php echo $signedNav; ?></p>
            </div>
            <div class="col-md-3 text-center well well-sm">
                <p><b>Crosswind:</b></p>
                <p><?php echo $crosswind; ?></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        	<ul class="list-group">
        	<?php

        	$query = "SELECT syllabus_code FROM tbl_cadets JOIN tbl_syllabus ON cadet_syllabus=syllabus_id WHERE cadet_name='$cadet'";
        	$result = mysqli_query($link, $query);
        	while($row = mysqli_fetch_array($result)) {
        		$syllabus_code = $row['syllabus_code'];
        	}

        	$query = "SELECT sortie_code, sortie_type, sortie_area, sortie_duration, sortie_nature FROM tbl_sorties WHERE sortie_syllabus='$syllabus_code'";
        	$result = mysqli_query($link, $query);
        	while($row = mysqli_fetch_array($result)) {
        		if($syllabus_code=='MPL-M2') {
        			preg_match("/\d{3}[AS]/sm", $row['sortie_code'], $output_array);
        		}
        		else {
        			preg_match("/(IPT)?S?\d{3}/sm", $row['sortie_code'], $output_array);
        		}
        		$code = $output_array[0];

                $duration = $row['sortie_duration']/60/60;

                if($duration<1) {
                    //express in minutes
                    $duration = $duration*60;
                    $duration = $duration.'min';
                }
                else {
                    $duration = $duration.'hr';
                }

        		?>
        		<li class="list-group-item">
                <p style="margin-bottom:0px;"><small><b><?php echo $row['sortie_code'] ?></b> - 
        		<?php

        		$query2 = "SELECT flightlist_sortie, flightlist_status, flightlist_date FROM tbl_flightlist WHERE (flightlist_pilot1='$cadet' OR flightlist_pilot2='$cadet') AND flightlist_sortie RLIKE '$code'";
				$result2 = mysqli_query($link, $query2);
				while($row2 = mysqli_fetch_array($result2)) {
					$class = 'text-muted';
					if($row2['flightlist_status']=='PostFlight') $class = 'text-success';
					if($row2['flightlist_status']=='Cancelled') $class = 'text-danger';
					?>
					<span class="<?php echo $class; ?>"><?php echo $row2['flightlist_date'] ?></span>
					<?php
				}
				?>
                <?php

                $query2 = "SELECT flightlist_sortie, flightlist_status, flightlist_date FROM tbl_tms2currentplan WHERE (flightlist_pilot1='$cadet' OR flightlist_pilot2='$cadet') AND flightlist_sortie RLIKE '$code'";
                $result2 = mysqli_query($link, $query2);
                while($row2 = mysqli_fetch_array($result2)) {
                    $class = 'text-muted';
                    if($row2['flightlist_status']=='PostFlight') $class = 'text-success';
                    if($row2['flightlist_status']=='Cancelled') $class = 'text-danger';
                    ?>
                    <span class="<?php echo $class; ?>"><?php echo $row2['flightlist_date'] ?></span>
                    <?php
                }
                ?>
                </small></p>
                <p class="text-muted" style="margin-bottom:0px;"><small><?php echo $duration.' '.$row['sortie_area'].' '.$row['sortie_type'].' '.$row['sortie_nature'] ?></small></p>
				</li>
				<?php

        	}
        	?>
        	</ul>
        </div>
    </div>
</div>
<div class="modal-footer">
	<div class="form-input pull-right">
        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
    </div>
</div>

<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({
    	container: '.row',
    	html: true,
    	trigger: 'hover',
    	animation: true
    });

    	window.setTimeout(function () {
			$('#timeline-container').animate({scrollLeft: $('#timeline-container').width()/2}, 1500, 'swing');
	    }, 500);
});
</script>