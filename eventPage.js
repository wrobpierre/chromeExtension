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
   getCurrentTabUrl();
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
    saveList(url);
  });
}

function saveList(url) {
  var storage = chrome.storage.local;
  storage.get('data', function(result) {
    if (result.data == undefined) {
      var obj = {}
      obj['data'] = [{'url':url}]
      storage.set(obj)
    }
    else {
      result.data.push({'url':url});
      storage.set({'data':result.data})
    }
  })  
}