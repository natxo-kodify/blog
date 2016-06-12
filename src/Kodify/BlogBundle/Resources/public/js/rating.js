$(document).ready(function() {
	$('#js_rating_change').change( function(){
		var rate = $(this).val();
		if (parseInt(rate,10) == rate) {
			$.post($(this).data('ajax'), {'rating':rate}, function(dt){
				console.log(dt);
				if	(typeof dt.new_rating !== 'undefined') {
					$('#js_rating_score').text(dt.new_rating);
				}
			});			
		}
		
	});
});
