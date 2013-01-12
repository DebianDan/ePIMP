$(function () {
	$('#defaultCountdown').countdown({until: +10, format: 'MS'});
	update_time();
});

function update_time() {
	$.ajax({
    	url:'/roulette/wheel_results.json',
	    type:'POST',
    }).done(function(results){
        console.log(results.timestamp - Math.round($.now()/1000)); 
        console.log(); 
    });
}