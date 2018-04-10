var lastUrl;
var onUpdatedUrl;
var storage = chrome.storage.local;
var listen = false;
var firstUrl;

chrome.tabs.onUpdated.addListener(function(tabId, changeInfo, tab) {

  chrome.tabs.query({'active': true, 'lastFocusedWindow': true}, function (tabs) {
    if(changeInfo.status == "loading"){
      onUpdatedUrl = tabs[0].url;
    }
  });
  if (changeInfo.status == "complete") {
    saveTime();
    getCurrentTabUrl(onUpdatedUrl);
  }  
}); 

chrome.tabs.onCreated.addListener(function(tab) {

})

function getCurrentTabUrl(eventUrl) {
  var urlRes
  var queryInfo = {
    active: true,
    currentWindow: true
  };
  chrome.tabs.query(queryInfo, (tabs) => {
    var tab = tabs.find(val => val.url == eventUrl);
    var url;
    var title;
    if(tab != undefined){
      url = tab.url;
    }
    else{
      url = eventUrl;
    }
    storage.get('firstUrl', function(result) {
      if(result.firstUrl != undefined && result.firstUrl != url){
        var keywords;
        var dateBegin = new Date();
        dateBegin = dateBegin.toJSON();

        chrome.tabs.executeScript({
          file:
          "./scripts/requestMeta.js", runAt: "document_end"
        }, function(results){
          keywords = results[0];
          title = keywords[keywords.length-1];
          var values = {'url':url, 'title':title, 'keywords':keywords, 'dateBegin': dateBegin, 'timeOnPage': {'hours': 0, 'minutes': 0, 'secondes': 0}, 'views':1, 'scrollPercent': 0}
          saveList(values);
        });
      }
    });
  });
}

chrome.tabs.onActivated.addListener(function(tabId, removeInfo) {
  var currentUrl;
  chrome.tabs.query({'active': true, 'lastFocusedWindow': true}, function (tabs) {
    currentUrl = tabs[0].url;
    getCurrentTabUrl(currentUrl);
    saveTime();
  });
});

function saveTime(){
  storage = chrome.storage.local;
  var dateEnd = new Date();

  storage.get('data', function(result) {
    var find = result.data.find(val => val.url == lastUrl);
    if (find != undefined) {
      compareDate(result.data[result.data.indexOf(find)].dateBegin, dateEnd.toJSON(), result.data[result.data.indexOf(find)]);
      storage.set({'data':result.data}); 
    }
    chrome.tabs.query({'active': true, 'lastFocusedWindow': true}, function (tabs) {
      lastUrl = tabs[0].url;
      find = result.data.find(val => val.url == lastUrl);
      if (find != undefined) {
        result.data[result.data.indexOf(find)].dateBegin = new Date().toJSON();
        storage.set({'data':result.data}); 
      }
    });
  });
}

function saveList(values) {
  storage = chrome.storage.local;
  storage.get('data', function(result) {
    if (listen) {
      if (result.data == undefined) {
        var obj = {}
        obj['data'] = [values]
        storage.set(obj)
      }
      else {
        var find = result.data.find(val => val.url == values.url);
        if (find != undefined) {
          result.data[result.data.indexOf(find)].views += 1;

          var dateBegin = new Date();
          dateBegin = dateBegin.toJSON();

          result.data[result.data.indexOf(find)].dateBegin = dateBegin;
        }
        else {
          result.data.push(values);
        }
        storage.set({'data':result.data});
      } 
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

chrome.runtime.onMessage.addListener(function(message, sender, sendResponse){
  if (message.type === 'start') {
    listen = true;
    sendResponse({url: sendFirstUrl(), result: listen});
  }
  else if (message.type === 'stop') {
    listen = false;
    sendResponse({result: listen});
  }
  else if (message.type === 'get') {
    if(firstUrl === undefined){
      sendResponse({url: sendFirstUrl(), result: listen});
    }
    else{
      //alert(firstUrl); 
      sendResponse({url: sendSecondUrl(), result: listen});
    }
  }
  else if (message.type === 'reset'){
    firstUrl = undefined;
  }
});

function sendSecondUrl(){
  storage.get('firstUrl', function(result) {
    var obj = {}
    obj['firstUrl'] = firstUrl;
    storage.set(obj);
    result.firstUrl = firstUrl;
    return result.firstUrl;
  });
}

function sendFirstUrl(){
  storage.get('firstUrl', function(result) {
    chrome.tabs.query({'active': true, 'currentWindow': true}, function (tabs) {
      firstUrl = tabs[0].url;

      if (result.firstUrl == undefined) {
        var obj = {}
        obj['firstUrl'] = firstUrl;
        storage.set(obj);
        result.firstUrl = firstUrl;

      }
      else{
        var obj = {}
        obj['firstUrl'] = firstUrl;
        storage.set(obj);
        result.firstUrl = firstUrl;
      }
    });
    return result.firstUrl;
  });
}

  /*chrome.webNavigation.onDOMContentLoaded.addListener(function() {
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
})*/