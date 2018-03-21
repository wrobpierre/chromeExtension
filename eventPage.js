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
    //alert(tab.url)
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
    var dateBegin = new Date();
    dateBegin = dateBegin.toJSON();

    chrome.tabs.executeScript({
      file:
        "./scripts/requestMeta.js", runAt: "document_end"
      }, function(results){
        var values = {'url':url, 'title':title, 'keywords':results, 'dateBegin': dateBegin, 'timeOnPage': {'hours': 0, 'minutes': 0, 'secondes': 0}, 'numRequests':1, 'scrollPercent': 0}
        saveList(values);
      }
    );
  });
}

chrome.tabs.onActivated.addListener(function(tabId, removeInfo) {
  var storage = chrome.storage.local;
  var dateEnd = new Date();

    storage.get('data', function(result) {
      var find = result.data.find(val => val.url == currentUrl);
      if (find != undefined) {
          compareDate(result.data[result.data.indexOf(find)].dateBegin, dateEnd.toJSON(), result.data[result.data.indexOf(find)]);
          storage.set({'data':result.data}); 
        }
          chrome.tabs.query({'active': true, 'lastFocusedWindow': true}, function (tabs) {
            currentUrl = tabs[0].url;
            find = result.data.find(val => val.url == currentUrl);
            if (find != undefined) {
              result.data[result.data.indexOf(find)].dateBegin = new Date().toJSON();
              storage.set({'data':result.data}); 
            }
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
      var find = result.data.find(val => val.url == values.url);
      if (find != undefined) {
        result.data[result.data.indexOf(find)].numRequests += 1;

        var dateBegin = new Date();
        dateBegin = dateBegin.toJSON();

        result.data[result.data.indexOf(find)].dateBegin = dateBegin;
      }
      else {
        result.data.push(values);
      }
      storage.set({'data':result.data});
    }
  })  
}

function compareDate(dateBegin, dateEnd, element){
  var timeBegin = new Date(dateBegin).getTime();
  var timeEnd = new Date(dateEnd).getTime();
  var durationMilliSec = (timeEnd - timeBegin)/1000;
  var hours = 0;
  var minutes = 0;

  if((durationMilliSec/3600) >= 1){
    hours = durationMilliSec/3600;
    durationMilliSec -= Math.floor(hours)*3600;
    hours = Math.floor(hours);
  }
  if((durationMilliSec/60)>= 1){
    minutes = durationMilliSec/60
    durationMilliSec -= Math.floor(minutes)*60;
    minutes = Math.floor(minutes);
  }
  var secondes = Math.floor(durationMilliSec);

  var duration = [hours, minutes, secondes];
  
  element.timeOnPage.secondes += secondes;
  
  if (element.timeOnPage.secondes >= 60) {
    element.timeOnPage.minutes++;
    element.timeOnPage.secondes -= 60 ;
  }

  element.timeOnPage.minutes += minutes;

  if (element.timeOnPage.minutes >= 60) {
    element.timeOnPage.hours++;
    element.timeOnPage.minutes -= 60 ;
  }

  element.timeOnPage.hours += hours;
}
