var lastUrl = null;
var onUpdatedUrl;
var storage = chrome.storage.local;
var listen = false;
var question = 1;
var firstUrl;
var adress = "http://163.172.59.102";
var userEmail = null;

// Fires whenever the user opens a new tab and reloads a tab.
chrome.tabs.onUpdated.addListener(function(tabId, changeInfo, tab) {
  // Get information about the active tab
  chrome.tabs.query({'active': true, 'lastFocusedWindow': true}, function (tabs) {
    // If the tab is still loading
    if(changeInfo.status == "loading"){
      // We just take the url
      onUpdatedUrl = tabs[0].url;
    }
  });
  // If the tab has finished loading
  if (changeInfo.status == "complete") {
    saveTime();
    getCurrentTabUrl(onUpdatedUrl);
  }  
}); 

/**
 * Store the current tab’s url in the storage and get all tab’s datas to generate an object
 * that we will store in the storage and save in the database in the end.
 *
 * @param string - The tab’s url where the user is located
 *
 * @return void
 */
function getCurrentTabUrl(eventUrl) {
  var urlRes
  var queryInfo = {
    active: true,
    currentWindow: true
  };
  // Get information about the active tab
  chrome.tabs.query(queryInfo, (tabs) => {
    var tab = tabs.find(val => val.url == eventUrl);
    var url;
    var title;
    var hostName;

    if(tab != undefined){
      url = tab.url;
    }
    else{
      url = eventUrl;
    }

    // Get
    storage.get('firstUrl', function(result) {
      if(result.firstUrl != undefined && result.firstUrl != url){
        var keywords;
        var dateBegin = new Date();
        hostName = (new URL(url).hostname).toString();
        dateBegin = dateBegin.toJSON();
        chrome.tabs.executeScript({
          file:
          "./scripts/requestMeta.js", runAt: "document_end"
        }, function(results){
          if (results[0].keywords != null) {
            keywords = results[0].keywords;
          }
          else{
            keywords = ["no keywords"];
          }

          if (title = results[0].title != null) {
            title = results[0].title;
          }
          else{
            title = "no title"
          }
          
          var values = {'url':url, 'title':title, 'keywords':keywords, 'firstTime':dateBegin, 'dateBegin': dateBegin, 'timeOnPage': {'hours': 0, 'minutes': 0, 'secondes': 0}, 'views':1, 'hostName': hostName, 'question': [{question: question, date:dateBegin}] }
          saveList(values);
        });
      }
    });
  });
}

// Fires when the active tab in a window changes
chrome.tabs.onActivated.addListener(function(tabId, removeInfo) {
  var currentUrl;
  // Get information about the active tab
  chrome.tabs.query({'active': true, 'lastFocusedWindow': true}, function (tabs) {
    currentUrl = tabs[0].url;
    getCurrentTabUrl(currentUrl);
    saveTime();
  });
});

/**
 * Update the time spent on a tab each time it changes tab.
 *
 * @param void
 *
 * @return void
 */
function saveTime(){
  storage = chrome.storage.local;
  var dateEnd = new Date();
  storage.get('data', function(result) {
    if (find == undefined) {
      chrome.tabs.query({'active': true, 'lastFocusedWindow': true}, function (tabs) {
        lastUrl = tabs[0].url;
        var find = result.data.find(val => val.url == lastUrl);
        compareDate(result.data[result.data.indexOf(find)].dateBegin, dateEnd.toJSON(), result.data[result.data.indexOf(find)]);
        result.data[result.data.indexOf(find)].dateBegin = new Date().toJSON();
        storage.set({'data':result.data});
      });
    }
    var find = result.data.find(val => val.url == lastUrl);
    if (find != undefined) {
      compareDate(result.data[result.data.indexOf(find)].dateBegin, dateEnd.toJSON(), result.data[result.data.indexOf(find)]);
      storage.set({'data':result.data});
    }
    chrome.tabs.query({'active': true, 'lastFocusedWindow': true}, function (tabs) {
      lastUrl = tabs[0].url;
    });
  });
}

/**
 * Store values in the storage
 *
 * @param object - The object generate in the function “getCurrentTabUrl”
 *
 * @return void
 */
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

          if ( result.data[result.data.indexOf(find)].question.find(val => val.question == question) == undefined ) {
            result.data[result.data.indexOf(find)].question.push({question: question, date:dateBegin});
          }
        }
        else {
          result.data.push(values);
        }
        storage.set({'data':result.data});
      } 
    }
  })  
}

/**
 *
 *
 *
 *
 * @param string - 
 * @param string - 
 * @param object - 
 *
 * @return void
 */
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

// Fired when a message is sent from a content script (by tabs.sendMessage).
// This event manages all internal communications of the extension.
chrome.runtime.onMessage.addListener(function(message, sender, sendResponse){
  if (message.type === 'start') {
    createUniqId();
    listen = true;

    sendResponse({url: sendFirstUrl(), result: listen, email:userEmail});
  }
  else if (message.type === 'stop') {
    listen = false;
    saveTime();
    sendResponse({result: listen});
  }
  else if (message.type === 'get') {
    if(firstUrl === undefined){
      sendResponse({url: sendFirstUrl(), result: listen});
    }
    else{      
      sendResponse({url: sendSecondUrl(), result: listen});
    }
  }
  else if (message.type === 'reset'){
    firstUrl = undefined;
  }
  else if (message.type === 'getEmail') {
    sendResponse({email: userEmail});
  }
});

// Fired when a message is sent from our website (by runtime.sendMessage).
// Cannot be used in a content script. This event manages all communications between the extension and the website.
chrome.runtime.onMessageExternal.addListener(
  function(request, sender, sendResponse) {
    if (request.action == 'stop') {
      userEmail = null;
      autoStop(request.email);       
    }
    else if (request.action == 'start') {
      userEmail = request.email; 
      autoStart(request.url);
    }
    else if (request.action == 'change_question') {
      question += 1;
      sendResponse({result: question});
    }
  });

/**
 *
 *
 *
 * @param void
 *
 * @return string - 
 */
function sendSecondUrl(){
  storage.get('firstUrl', function(result) {
    var obj = {}
    obj['firstUrl'] = firstUrl;
    storage.set(obj);
    result.firstUrl = firstUrl;
    return result.firstUrl;
  });
}

/**
 * Generate a unique id for the user and store the id in the storage
 *
 * @param void
 *
 * @return void
 */
function createUniqId(){
  storage.get('uniqId', function(result) {
    var id = (new Date().getTime().toString() + Math.floor((Math.random()*10000)+1).toString(16));
    if (result.uniqId == undefined) {
      var obj = {}
      obj['uniqId'] = id;
      storage.set(obj);
      result.uniqId = id;
    }
    else{
      var obj = {}
      obj['uniqId'] = id;
      storage.set(obj);
      result.uniqId = id;
    }
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

/**
 * This function starts recording web browsing data for a user. 
 * This function is called when the user starts to make a questionnaire.
 *
 * @param string - The starting url of the search
 *
 * @return void
 */
function autoStart(url){
  if (!listen) {
    sendFirstUrl();
    listen = true;
    firstUrl = url;

    var post = $.post(adress+'/dataBase.php', { url:firstUrl, key:"first" });
    post.done(function(data){});
  }
}

/**
 * This function stops recording web browsing data for a user.
 * This function is called when the user sends his answers.
 *
 * @param string - it is the email of the user who answered the questionnaire
 *
 * @return void
 */
function autoStop(email){
  if (listen) {
    storage.get('data', function(result){
      var post = $.post(adress+'/dataBase.php', { d:result, url:{firstUrl:firstUrl}, key:'add', email:email });
      post.done(function(data){
        listen = false;
        question = 1;
        firstUrl = undefined;
        storage.clear();
      });
    });
  }
}