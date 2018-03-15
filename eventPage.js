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
    var keywords = [];
    var date = new Date();
    var dateBegin =createDate();


    chrome.tabs.executeScript({
      file:
        "./scripts/requestMeta.js", runAt: "document_end"
      }, function(results){
        
        var values = {'url':url, 'title':title, 'keywords':results, 'dateBegin': dateBegin, 'timeOnPage': dateBegin}
        saveList(values);
      }
    );
  });
}

chrome.tabs.onRemoved.addListener(function(tabId, removeInfo) {
  var storage = chrome.storage.local;
  storage.get('data', function(result) {
    var dateEnd = createDate();
    result.data.forEach(function(element) {
      // element['timeOnPage'] = dateEnd - element['dateBegin']; 
      alert(element.dateBegin);
    })
  });
});

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

function createDate(){
  var date = new Date();
  var result = date.getDate()+"/"+date.getMonth()+"/"+date.getFullYear()+" "+
      date.getHours()+":"+date.getMinutes()+":"+date.getSeconds();
  return result;
}
