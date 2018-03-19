chrome.webNavigation.onTabReplaced.addListener(function() {
  alert('coucou');
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
    alert(tab.url)
   getCurrentTabUrl();
 }
}); 

chrome.tabs.onCreated.addListener(function(tab) {

})

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
    var keywords = [];

    chrome.tabs.executeScript({
      file:
        "./scripts/requestMeta.js", runAt: "document_end"
      }, function(results){

        var values = {'url':url, 'title':title, 'keywords':results, 'numRequests':1}

        saveList(values);
      }
    );
  });
}

function saveList(values) {
  var storage = chrome.storage.local;
  storage.get('data', function(result) {
    if (result.data == undefined) {
      var obj = {};
      obj['data'] = [values];
      storage.set(obj);
    }
    else {
      var find = result.data.find(val => val.url == values.url);
      if (find != undefined) {
        result.data[result.data.indexOf(find)].numRequests += 1;
      }
      else {
        result.data.push(values);
      }
      storage.set({'data':result.data});
    }
  })  
}