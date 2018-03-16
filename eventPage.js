var currentUrl;

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
  chrome.tabs.query({'active': true, 'lastFocusedWindow': true}, function (tabs) {
    currentUrl = tabs[0].url;
  });
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
    var dateBegin = new Date();
    dateBegin = dateBegin.toJSON();

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
  var dateEnd = new Date();
  
    storage.get('data', function(result) {
      result.data.forEach(function(element) {
        alert("current / "+currentUrl);
        alert("to see / "+element.url);
        compareDate(element.dateBegin, dateEnd.toJSON());
        
      });
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

function compareDate(dateBegin, dateEnd){
  var timeBegin = new Date(dateBegin).getTime();
  var timeEnd = new Date(dateEnd).getTime();
  var durationMilliSec = (timeEnd - timeBegin)/1000;
  var hours = 0;
  var minutes = 0;
  
  
  
  if((durationMilliSec/3600) >= 1){
    hours = durationMilliSec/3600;
    durationMilliSec -= Math.round(hours)*3600;
    hours = Math.round(hours);
  }
  if((durationMilliSec/60)>= 1){
    minutes = durationMilliSec/60
    durationMilliSec -= Math.round(minutes)*60;
    minutes = Math.round(minutes);
  }
  var secondes = Math.round(durationMilliSec);

  var duration = [hours, minutes, secondes];
  alert(hours+" / "+minutes+" / "+secondes);
}
