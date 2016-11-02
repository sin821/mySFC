<?php
include('../php/db_conn.php');
?>
<div class="modal-header">
	<h4 class="modal-title block-head" id="myModalLabel">Sortie Completion: <?php echo $_GET['cadet']; ?></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-3">
            Signed CCT:
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        	<ul>
        	<?php
        	$cadet = $_GET['cadet'];

        	$query = "SELECT syllabus_code FROM tbl_cadets JOIN tbl_syllabus ON cadet_syllabus=syllabus_id WHERE cadet_name='$cadet'";
        	$result = mysqli_query($link, $query);
        	while($row = mysqli_fetch_array($result)) {
        		$syllabus_code = $row['syllabus_code'];
        	}

        	$query = "SELECT sortie_code FROM tbl_sorties WHERE sortie_syllabus='$syllabus_code'";
        	$result = mysqli_query($link, $query);
        	while($row = mysqli_fetch_array($result)) {
        		if($syllabus_code=='MPL-M2') {
        			preg_match("/\d{3}[AS]/sm", $row['sortie_code'], $output_array);
        		}
        		elseif($syllabus_code=='CPL-A-A'||$syllabus_code=='CPL-G-B') {
        			preg_match("/(IPT)?S?\d{3}/sm", $row['sortie_code'], $output_array);
        		}
        		$code = $output_array[0];

        		?>
        		<li><?php echo $row['sortie_code'] ?> - 
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