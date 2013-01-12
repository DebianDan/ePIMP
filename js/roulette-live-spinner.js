$(function () {
	$(this).timer({
		delay: 1000,
		callback: update_time,
		repeat: true
	});
});

var timestamp = -1;
var activebets = {};

function update_time() {
	$.ajax({
    	url:'/roulette/wheel_results.json',
	    type:'POST',
    }).done(function(results){
        var remaining = results.timestamp - Math.round($.now()/1000);
		update_clock(remaining);
		if (results.timestamp != timestamp) {
			timestamp = results.timestamp;
			spin(results);
		}
	});

	$.ajax({
    	url:'/roulette/current_betters.php',
	    type:'POST',
	}).done(function(active_betters) {
		update_active_betters(jQuery.parseJSON(active_betters));
	});
}

function spin (results_block) {
	// alert(results_block);
	// alert("Spin");
	// location.reload();
}

function update_active_betters(active_betters) {
	// $('#active-bets').empty();
	var bettinglist = $('<ul/>',{
		class:"betting-list"
	});
	$.each(active_betters,function() {
		bettinglist.append($('<li/>').text(this.first_name+' '+this.last_name));
	});
	$("#bet-list-container").empty();
	$("#bet-list-container").append(bettinglist);
}

function update_clock(remaining) {
	$('#clock').text(Math.max(remaining,0));
}