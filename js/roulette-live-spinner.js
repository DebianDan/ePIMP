$(function () {
	update_time();

});

var remaining = 1;
var timestamp = -1;

function update_time() {
	$.ajax({
    	url:'/roulette/wheel_results.json',
	    type:'POST',
    }).done(function(results){

        remaining = results.timestamp - Math.round($.now()/1000); 
		$('#defaultCountdown').countdown({until: +remaining, format: 'MS'});
		$(this).timer({
			delay:remaining*1000,
			callback: function() { 
				$.ajax({
			    	url:'/roulette/wheel_results.json',
				    type:'POST',
			    }).done(function(results){	
					if (results.timestamp != timestamp) {
						timestamp = results.timestamp;
						spin (results);
					}else {
						update_time();
					}
				})
				.fail(function(){
					update_time();
				});
			}});
   		})
    .fail(function() {
		$(this).timer({
			delay:30000,
			callback: function() { 
				update_time();
			}
		});
    });
}

function spin (results_block) {
	console.log(results_block);
}