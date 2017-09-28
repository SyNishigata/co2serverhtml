/**
 * Carbon Neutral Challenge
 * Aterkia, aterkia [dot] com
 */
function EmissionsFood(vegetarian, num) {
	var vegetarian_value = 0.9;
	if (vegetarian== 1) {
		document.getElementById('vegetarianNo').style.display="none";
		document.getElementById('lamb').value="";
		document.getElementById('beef').value="";
		document.getElementById('pork').value="";
		document.getElementById('fish').value="";
		document.getElementById('poultry').value="";
		document.getElementById('emissions_food_tones').innerHTML=vegetarian_value.toFixed(5);
		document.getElementById('emissions_food_tones_var').value=vegetarian_value.toFixed(5);
	} else {
		document.getElementById('vegetarianNo').style.display="block";
		document.getElementById('emissions_food_tones').innerHTML=vegetarian_value.toFixed(5);
		if (!isNaN(num)) {
			emissions_food_lamb=document.getElementById('lamb').value;
			emissions_food_beef=document.getElementById('beef').value;
			emissions_food_pork=document.getElementById('pork').value;
			emissions_food_fish=document.getElementById('fish').value;
			emissions_food_poultry=document.getElementById('poultry').value;
			emissions_food_tones_var=Number(vegetarian_value)+(emissions_food_lamb*3.92*0.001)+(emissions_food_beef*27*0.001)+(emissions_food_pork*12.1*0.001)+(emissions_food_fish*11.9*0.001)+(emissions_food_poultry*6.9*0.001);
			document.getElementById('emissions_food_tones').innerHTML=emissions_food_tones_var.toFixed(5);
			document.getElementById('emissions_food_tones_var').value=emissions_food_tones_var.toFixed(5);
		}
	}
}
function EmissionsHomeWater() {
	people=document.getElementById('people').value;
	if (people=='' || isNaN(people)) {
		document.getElementById('people').value=1;
		people=1;
	}
	emissions_home_water_gallons=document.getElementById('emissions_home_water_gallons').value;
	emissions_home_water_lowest=document.getElementById('emissions_home_water_lowest').value;
	emissions_home_water_highest=document.getElementById('emissions_home_water_highest').value;
	if (document.getElementById('emissions_home_water_gallons').value!='' && document.getElementById('gals').checked!=true && document.getElementById('tgals').checked!=true) {
		metric='gals';
		document.getElementById('gals').checked=true;
	}
	if (document.getElementById('tgals').checked==true) { metric='tgals'; }
	if (emissions_home_water_lowest!='' || emissions_home_water_highest!='') {
		emissions_home_water_gallons=(Number(emissions_home_water_lowest) + Number(emissions_home_water_highest)) * 6;
		document.getElementById('emissions_home_water_gallons').value=emissions_home_water_gallons;
	}
	if (metric=='gals') {
		emissions_home_water_gallons = emissions_home_water_gallons * 4.082 * 0.000001;
	} else {
		emissions_home_water_gallons = emissions_home_water_gallons * 1000 * 4.082 * 0.000001;
	}
	emissions_home_water_tones_var = emissions_home_water_gallons / people;
	document.getElementById('emissions_water_tones').innerHTML=emissions_home_water_tones_var.toFixed(5);
	document.getElementById('emissions_home_water_tones_var').value=emissions_home_water_tones_var.toFixed(5);
}
function EmissionsHomeGas() {
	people=document.getElementById('people').value;
	if (people=='' || isNaN(people)) {
		document.getElementById('people').value=1;
		people=1;
	}
	emissions_home_gas_usage=document.getElementById('emissions_home_gas_usage').value;
	emissions_home_gas_units=document.getElementById('emissions_home_gas_metric').value;
	emissions_home_gas_lowest=document.getElementById('emissions_home_gas_lowest').value;
	emissions_home_gas_highest=document.getElementById('emissions_home_gas_highest').value;
	if (emissions_home_gas_lowest!='' || emissions_home_gas_highest!='') {
		emissions_home_gas_usage=(Number(emissions_home_gas_lowest) + Number(emissions_home_gas_highest)) * 6;
		document.getElementById('emissions_home_gas_usage').value=emissions_home_gas_usage;
	}
	if (emissions_home_gas_units=='1') {
		emissions_home_gas_usage = emissions_home_gas_usage;
	} else if (emissions_home_gas_units=='2') {
		emissions_home_gas_usage = emissions_home_gas_usage * 0.1;
	} else if (emissions_home_gas_units=='3') {
		emissions_home_gas_usage = emissions_home_gas_usage * 102800;
	} else if (emissions_home_gas_units=='4') {
		emissions_home_gas_usage = emissions_home_gas_usage * 1.028;
	} else if (emissions_home_gas_units=='5') {
		emissions_home_gas_usage = emissions_home_gas_usage * 29.31;
	}	
	if (emissions_home_gas_units=='-1') {
		document.getElementById('emissions_home_gas_metric').style.background="red";
	} else {
		document.getElementById('emissions_home_gas_metric').style.background="#ddd";
	}
	emissions_home_gas_tones_var = (emissions_home_gas_usage / 100) * 54.7 * 1.14 * 0.000001;
	document.getElementById('emissions_gas_tones').innerHTML=emissions_home_gas_tones_var.toFixed(5);
	document.getElementById('emissions_home_gas_tones_var').value=emissions_home_gas_tones_var.toFixed(5);
}
function EmissionsHomeFuel() {
	people=document.getElementById('people').value;
	if (people=='' || isNaN(people)) {
		document.getElementById('people').value=1;
		people=1;
	}
	emissions_home_fuel_usage=document.getElementById('emissions_home_fuel_usage').value;	
	fuel_cooking_var=document.getElementById('fuel_cooking_var').value;
	if (document.getElementById('fuel_cooking').checked && document.getElementById('fuel_cooking_var').value==0) {
		emissions_home_fuel_usage = Number(emissions_home_fuel_usage)+50;
		document.getElementById('fuel_cooking_var').value=1;
	} else if(!document.getElementById('fuel_cooking').checked && document.getElementById('fuel_cooking_var').value==1) {
		emissions_home_fuel_usage = Number(emissions_home_fuel_usage)-50;
		document.getElementById('fuel_cooking_var').value=0;
	}
	if (document.getElementById('fuel_drying').checked && document.getElementById('fuel_drying_var').value==0) {
		emissions_home_fuel_usage = Number(emissions_home_fuel_usage)+100;
		document.getElementById('fuel_drying_var').value=1;
	} else if(!document.getElementById('fuel_drying').checked && document.getElementById('fuel_drying_var').value==1) {
		emissions_home_fuel_usage = Number(emissions_home_fuel_usage)-100;
		document.getElementById('fuel_drying_var').value=0;
	}
	if (document.getElementById('fuel_water_heating').checked && document.getElementById('fuel_water_heating_var').value==0) {
		emissions_home_fuel_usage = Number(emissions_home_fuel_usage)+350;
		document.getElementById('fuel_water_heating_var').value=1;
	} else if(!document.getElementById('fuel_water_heating').checked && document.getElementById('fuel_water_heating_var').value==1) {
		emissions_home_fuel_usage = Number(emissions_home_fuel_usage)-350;
		document.getElementById('fuel_water_heating_var').value=0;
	}
	document.getElementById('emissions_home_fuel_usage').value=emissions_home_fuel_usage;
	emissions_home_fuel_tones_var = emissions_home_fuel_usage * 8362 * 0.000001 / people;
	document.getElementById('emissions_fuel_tones').innerHTML=emissions_home_fuel_tones_var.toFixed(5);
	document.getElementById('emissions_home_fuel_tones_var').value=emissions_home_fuel_tones_var.toFixed(5);
}
function EmissionsHomeElectric() {
	people=document.getElementById('people').value;
	if (people=='' || isNaN(people)) {
		document.getElementById('people').value=1;
		people=1;
	}
	emissions_home_electric_usage=document.getElementById('emissions_home_electric_usage').value;
	emissions_home_electric_lowest=document.getElementById('emissions_home_electric_lowest').value;
	emissions_home_electric_highest=document.getElementById('emissions_home_electric_highest').value;
	if (emissions_home_electric_lowest!='' || emissions_home_electric_highest!='') {
		emissions_home_electric_usage=(Number(emissions_home_electric_lowest) + Number(emissions_home_electric_highest)) * 6;
		document.getElementById('emissions_home_electric_usage').value=emissions_home_electric_usage;
	}
	emissions_home_electric_tones_var = emissions_home_electric_usage * 835 * 1.09 * 0.000001 / people;
	document.getElementById('emissions_electric_tones').innerHTML=emissions_home_electric_tones_var.toFixed(5);
	document.getElementById('emissions_home_electric_tones_var').value=emissions_home_electric_tones_var.toFixed(5);
}
function EmissionsTravelMotorcycle() {
	alert('PENDING');
}
// ----- ----- ----- THE END ----- ----- ----- //