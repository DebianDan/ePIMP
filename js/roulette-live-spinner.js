$(function () {
	$(this).timer({
		delay: 1000,
		callback: update_time,
		repeat: true
	});
	present();
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

	// $('#myModal').modal('show');
	// $('div').timer({
	// 	callback: function(){
			// $('#myModal').modal('hide');
	// 	},
	// 	delay: 30000,
	// });
	var color = parseInt(results_block.color);
	$('div').timer({
		callback: function(){
			color = (color + 1) % 3;
			if(color == 0) {
				$('#prev-round').text('Black');
				document.getElementById("prev-round").style.color = "black";
				// $('.winner-type').attr('color','black');
			} else if(color == 1) {
				$('#prev-round').text('Red');
				document.getElementById("prev-round").style.color = "red";
				// $('.winner-type').attr('color','red');
			} else {
				$('#prev-round').text('Green');
				document.getElementById("prev-round").style.color = "green";
				// $('.winner-type').attr('color','green');
			}
		},
		delay: 10,
		repeat: 501
	});
	// $(this).timer({
	// 	callback: function() {
	// 		// $('#myModal').modal('hide');
	// 		var text_color = "Green";
	// 		if (results_block.color == "0") {
	// 			text_color = "Black";
	// 		} else if(results_block.color == "1"){
	// 			text_color = "Red";
	// 		};
	// 		$('#prev-round').text(text_color);
	// 	},
	// 	delay: 5010,
	// });
	var winnerlist = $("#winner-list");
	winnerlist.empty();
	$.each(results_block.winners,function() {
		var row = make_row(this);
		winnerlist.append(row);
	});
}

function update_active_betters(active_betters) {
	var bettinglist= $("#bet-list-container");
	bettinglist.empty();
	$.each(active_betters,function() {
		var row = make_row(this);
		bettinglist.append(row);
	});
}

function update_clock(remaining) {
	$('#clock').text(Math.max(remaining,0));
}

function make_row(row_object){
	var row = $('<tr/>');
	row.append($('<td/>').text(row_object.first_name));
	row.append($('<td/>').text(row_object.last_name));
	row.append($('<td/>').text(Math.abs(parseInt(row_object.award))));
	var color = "";
	if(row_object.color == "0") {
		color = "Black";	
	} else {
		color = "Red";
		row.addClass("error");	
	} 
	row.append($('<td/>').text(color));
	return row;
}

function present() {
}