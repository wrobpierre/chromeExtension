chrome.webNavigation.onDOMContentLoaded.addListener(function() {
	chrome.storage.local.get('event', function(result){
		var obj = {}
		if (result.event == undefined) {
			obj['event'] = 1
			chrome.storage.local.set(obj)
		}
		else {
			obj['event'] = result.event+1
			chrome.storage.local.set(obj)
		}
	})
})


chrome.tabs.onUpdated.addListener(function(tabId, changeInfo, tab) {
  if (changeInfo.status == "complete") {
   getCurrentTabUrl();
 }
}); 

function getCurrentTabUrl() {
  var urlRes
  var queryInfo = {
    active: true,
    currentWindow: true
  };
  chrome.tabs.query(queryInfo, (tabs) => {
    var tab = tabs[0];
    var url = tab.url;
    var title = tab.title;
    var keywords;

    chrome.tabs.executeScript( null, {
      code: 
        "document.getElementsByTagName('meta');"
      }, function(results){
        keywords = results;
        
        var values = {'url':url, 'title':title, 'keywords':results}
        saveList(values);
      }
    );

  });
}

function saveList(values) {
  var storage = chrome.storage.local;
  storage.get('data', function(result) {
    if (result.data == undefined) {
      var obj = {}
      obj['data'] = [values]
      storage.set(obj)
    }
    else {
      result.data.push(values);
      storage.set({'data':result.data})
    }
  })  
}