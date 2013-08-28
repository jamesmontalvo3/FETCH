$(document).ready(function(){

	// this checks if $.post is working (does not work on first page load
	// on JSC-MAS domain). This may not be an issue on mod2. Check.
	/*try {
		var testPost = $.post('lib/jscmas_callback.php');                    
	}
	
	// if $.post did not work, reload the page. Works after that for some reason.
	catch(e) {   
		location.reload(true);
	}*/

	$("#fetchbox").focus().autocomplete({
		minLength: 2,
		source: "lib/linksearch.php",
		select: function(event, ui) {
			var success = false;
		
			try {
				//$.post("lib/log_click.php", { linkid: ui.item.id } );
				window.location = ui.item.url;
				success = true;
			}
			catch (e){
			
			}
			
			var fileDownloadHTML = "";
			// if ( ! $.browser.msie) {

				// fileDownloadHTML = "<p>If you are using a Firefox, Chrome or Safari you cannot open files from a local server (i.e. the S-drive) due to your browser's security settings. Please copy the link below and paste it into windows explorer.</p><input type='text' value='" + ui.item.url + "' style='width:100%;' />";

			//}

			setTimeout(
				function(){
					$("<div title='Browser Redirect'>" + 
						"<p>If your browser does not navigate to your page shortly, click the link below.</p>" +
						"<a href='" + ui.item.url + "'>" + ui.item.label + "</a>" +
						"<p>" + fileDownloadHTML + "</p></div>").dialog({width:500});
				}, 2000);
		}
	})
	.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		
		var sourceLabel = "";
		if ( item.source )
			sourceLabel = "<span class='source-label'>" + item.source + "</span> ";

		return $( "<li>" )
			.append( "<a>" + sourceLabel + item.label + "</a>" )
			.appendTo( ul );
    };;

	
	$('#dialog').dialog({
		autoOpen: false,
		width: 500
	});

	$('#opener').click(function() {
		$('#dialog').dialog('open');
		return false;
	});

});

