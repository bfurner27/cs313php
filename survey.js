function load() {
	
	$(function() {
		$("#fruitQs").hide();
		$("#vegQs").hide();
		$("#meatQs").hide();
		$("#dairyQs").hide();
		$("#otherQs").hide();
		$("#frustrated").hide();

	});

}

function fruitOptions() {
	$(function() {
		$("#fruitQs").show();
		$("#vegQs").hide();
		$("#meatQs").hide();
		$("#dairyQs").hide();
		$("#otherQs").hide();
		$("#frustrated").hide();
	});
}

function vegOptions() {
		$("#fruitQs").hide();		
		$("#vegQs").show();
		$("#meatQs").hide();
		$("#dairyQs").hide();
		$("#otherQs").hide();
		$("#frustrated").hide();
}

function meatOptions() {
		$("#fruitQs").hide();		
		$("#vegQs").hide();
		$("#meatQs").show();
		$("#dairyQs").hide();
		$("#otherQs").hide();
		$("#frustrated").hide();
}

function dairyOptions() {
		$("#fruitQs").hide();		
		$("#vegQs").hide();
		$("#meatQs").hide();
		$("#dairyQs").show();
		$("#otherQs").hide();
		$("#frustrated").hide();
}

function otherOptions() {
		$("#fruitQs").hide();		
		$("#vegQs").hide();
		$("#meatQs").hide();
		$("#dairyQs").hide();
		$("#otherQs").show();
		$("#frustrated").hide();
}

function revealFrustrationQ() {
	$(function() {
		$("#frustrated").show();
	});
}