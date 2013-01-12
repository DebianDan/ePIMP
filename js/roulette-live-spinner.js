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
	var bettinglist = $('<ul/>',{
		class:"winner-list"
	});
	$.each(results_block,function() {
		
	});
}

function update_active_betters(active_betters) {
	console.log(active_betters);
	// $('#active-bets').empty();
	// var bettinglist = $('<tbody/>',{
	// 	class:"betting-list"
	// });
	var bettinglist= $("#bet-list-container");
	bettinglist.empty();
	$.each(active_betters,function() {
		var row = $('<tr/>');
		row.append($('<td/>').text(this.first_name));
		row.append($('<td/>').text(this.last_name));
		row.append($('<td/>').text(-1 * parseInt(this.award)));
		var color = (this.color == "0") ? "Black" : "Red";
		row.append($('<td/>').text(color));
		bettinglist.append(row);
	});
	// $("#bet-list-container").empty();
	// $("#bet-list-container").append(bettinglist);
}

function update_clock(remaining) {
	$('#clock').text(Math.max(remaining,0));
}