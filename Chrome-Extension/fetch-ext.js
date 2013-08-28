var linksearchURL = "https://mod2.jsc.nasa.gov/wiki/fetch/lib/linksearch.php";

/*****************************************************************/
/*           No changes required below this line                 */
/*****************************************************************/

var fetchLinks = {};

// This event is fired each time the user updates the text in the omnibox,
// as long as the extension's keyword mode is still active.
chrome.omnibox.onInputChanged.addListener(
	function(text, suggest) {
		
		/*
		suggest([
			{content: "test", description: "this is test"},
			{content: "moretest", description: "this is more test"}
		]);*/
	
		//console.log('pre-callback');
		$.getJSON(
			linksearchURL,
			{ term : text },
			function(data){
				console.log("callback");

				var suggestions = [];
				fetchLinks = {};
				for(var i=0; i<data.length; i++) {
					var cont='', desc='';
					
					if ( data[i].source ) {
						cont += data[i].source + ": "
						desc += "<dim>" + data[i].source + ":</dim> "
					}
					cont += data[i].label;
					desc += "<match>" + data[i].label + "</match>";
					
					
					suggestions[i] = {
						content : cont,
						description : desc
					};
					fetchLinks[data[i].label] = data[i].url;
					
				}
				suggest(suggestions);
			}
		);
		
	});

// This event is fired with the user accepts the input in the omnibox.
chrome.omnibox.onInputEntered.addListener(
	function(text) {

		if(fetchLinks[text])
		{
			console.log("Attempting to navigate to URL...")
			console.log("URL = " + fetchLinks[text]);
			var myURL = fetchLinks[text];
		}
		else
		{
			console.log("Input doesn't match fetchLinks: " + text);
			console.log("Using top item");
			for (var key in fetchLinks) {
				var myURL = fetchLinks[key];
				break;
			}
			
		}

		chrome.tabs.getSelected(null, function(tab) {
			chrome.tabs.update(tab.id, {url: myURL });
		});
	});