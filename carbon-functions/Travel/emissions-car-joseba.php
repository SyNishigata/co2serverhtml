<?php
require('../../joseba/includes/db_config.php');
$car_make_array = $database->get_results( "SELECT DISTINCT make FROM ".TABLE_DB_LIBRARY_CAR_MODELS." " );
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<div class="form-group">
		<div class="input-group">
			<div class="row">
				<div class="small-4 large-4 columns">
					<select id="car_make" name="car_make" onchange="get_car_models()">
						<option>Select a Make</option>
						<?php
                        foreach( $car_make_array as $car_make ) {
							echo '<option value="' . $car_make["make"] . '">' . $car_make["make"] . '</option>';
						}
						?>
					</select>
				</div>
				<div class="small-4 large-4 columns">
                	<div id="car_models"></div>
				</div>
				<div class="small-4 large-4 columns">
                	<div id="car_years"></div>
				</div>
				<div class="small-4 large-4 columns">
                	<div id="car_emissions"></div>
				</div>
			</div>
		</div>
        <div id="summary" style="padding:3em;border:0.5em solid red; display:none;">
        Summary<br />
        <input type="text" id="hidden_car_make" name="hidden_car_make" value="" /><br />
        <input type="text" id="hidden_car_model" name="hidden_car_model" value="" /><br />
        <input type="text" id="hidden_car_year" name="hidden_car_year" value="" /><br />
        <input type="text" id="hidden_car_emission" name="hidden_car_emission" value="" />
        </div>
		<input type="submit" id="formSubmit" name="formSubmit" value="Submit" />
	</div>
</form>
<script type="text/javascript">
	function get_car_models(){
		var car_make = document.getElementById('car_make').value;
		jQuery.ajax({
		    url: "emissions-car-joseba-model.php",
		    type: 'post',
		    data: { make : car_make },
		    dataType: 'text',
		    success: function (data) {
				 $('#car_models').html(data);
		    },
		    error: function (e) {
		        alert("Server Error : " + e.state());
		    }
		});
		document.getElementById('hidden_car_make').value=car_make;
	}
	function get_car_years(){
		var car_model = document.getElementById('car_model').value;
		var car_make = document.getElementById('car_make').value;
		jQuery.ajax({
		    url: "emissions-car-joseba-year.php",
		    type: 'post',
		    data: { model : car_model, make : car_make },
		    dataType: 'text',
		    success: function (data) {
				 $('#car_years').html(data);
		    },
		    error: function (e) {
		        alert("Server Error : " + e.state());
		    }
		});
		document.getElementById('hidden_car_model').value=car_model;
	}
	function get_car_efficiency(){
		var car_year = document.getElementById('car_year').value;
		var car_model = document.getElementById('car_model').value;
		var car_make = document.getElementById('car_make').value;
		jQuery.ajax({
		    url: "emissions-car-joseba-emission.php",
		    type: 'post',
		    data: { model : car_model, make : car_make, year : car_year },
		    dataType: 'text',
		    success: function (data) {
				 $('#car_emissions').html(data);
		    },
		    error: function (e) {
		        alert("Server Error : " + e.state());
		    }
		});
		document.getElementById('hidden_car_year').value=car_year;
		document.getElementById('summary').style.display="block";
	}
</script>