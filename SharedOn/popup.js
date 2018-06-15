var adress = "http://163.172.59.102"
// var adress = "http://localhost/chromeExtension"

function generateList(data) {
  if (data != undefined) {
    while (document.getElementById('nbSite').firstChild) {
      document.getElementById('nbSite').removeChild(document.getElementById('nbSite').firstChild);
    }
    while (document.getElementById('timeSpend').firstChild) {
      document.getElementById('timeSpend').removeChild(document.getElementById('timeSpend').firstChild);
    }
    var pNbSite = document.createElement("p");
    var imgNbSite = document.createElement("img");
    imgNbSite.style.float = "left"
    imgNbSite.setAttribute("src", " count.png");
    imgNbSite.setAttribute("alt", "counter");
    pNbSite.textContent =" You visited "+data.length+" websites ";

    pNbSite.appendChild(imgNbSite);
    document.getElementById('nbSite').appendChild(pNbSite);

    var dateNow = new Date(data[data.length-1]['firstTime']);
    var firstDate = new Date(data[0]['firstTime'])
    durationMilliSec = (dateNow.getTime() - firstDate.getTime())/1000;
    console.log(durationMilliSec);

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

    var pTimeSpend = document.createElement("p");
    var imgTimeSpend = document.createElement("img");
    imgTimeSpend.style.float = "left"
    imgTimeSpend.setAttribute("src", "time.png");
    imgTimeSpend.setAttribute("alt", "timer");

    pTimeSpend.textContent =" You search since "+hours+"h"+minutes+"min"+secondes+"sec";
    
    pTimeSpend.appendChild(imgTimeSpend);
    document.getElementById('timeSpend').appendChild(pTimeSpend);
  }
}

function loadList() {
  var storage = chrome.storage.local;
  storage.get('data', function(result) {
    //console.log(result);
    generateList(result.data);
  })
}

function resetList() {
  chrome.storage.local.clear(function() {
    var error = chrome.runtime.lastError;
    if (error) {
      console.error(error);
    }
  });
}

function sendData(key) {
  var storage = chrome.storage.local;
  storage.get('data', function(result){
    if (key == "load") {
      var post = $.post(adress+'/dataBase.php', { d:example, key:"add" });
    }
    else{
      storage.get('uniqId', function(resultId){
        storage.get('firstUrl', function(resultUrl){
          console.log(result);
          var post = $.post(adress+'/dataBase.php', { d:result, url:resultUrl, uniqId: resultId.uniqId, key:key, type:"type"});
          post.done(function(data) {
            // var test = $.parseJSON(data);
            // $.each($.parseJSON(test[0]['timer']), function(i, element){
              console.log(data);
            // });
          });
        });
      });
    }
  });
}

/*var obj = {};
obj['data'] = [{'url':'http://google.fr'}]
chrome.storage.local.set(obj)
storage.get('k1',function(result){
  console.log(result.k1);
  result.k1.push({test:'test'})
  obj['k1'] = result.k1
  storage.set(obj)
  //console output = {k1:'s1'}
});
storage.set(obj)
chrome.storage.local.get(function(result){
  console.log(result);
})*/


loadList();

document.addEventListener('DOMContentLoaded', function () {

    // console.log(document.getElementsByClassName("overlay"));
    document.getElementsByClassName("overlay")[0].addEventListener("click",function(e) {
      document.getElementById('start').style.visibility = 'visible';
      document.getElementById('stop').style.visibility = 'visible';
    });

    document.getElementsByClassName("overlay")[1].addEventListener("click",function(e) {
      document.getElementById('start').style.visibility = 'hidden';
      document.getElementById('stop').style.visibility = 'hidden';
    });

  // document.getElementById('suppr').addEventListener('click', resetList);
  //document.getElementById('add').addEventListener('click', function(){ sendData("add") });
  // document.getElementById('load').addEventListener('click', function(){ sendData("load") });
  // document.getElementById('delete').addEventListener('click', function(){ sendData("delete") });

  chrome.runtime.sendMessage({type: 'get'}, function get(response){
    console.log(response);
    if (response.result) {
      document.getElementById('start').disabled = response.result;   
      document.getElementById('stop').disabled = !response.result;
    }
    else{
      document.getElementById('start').disabled = response.result;   
      document.getElementById('stop').disabled = !response.result; 
    }
  });

  chrome.runtime.sendMessage({type: 'getEmail'}, function getEmail(response){
    console.log(response.email);
    while (document.getElementById('connected').firstChild) {
      document.getElementById('connected').removeChild(document.getElementById('connected').firstChild);
    }
    var p = document.createElement("p");
    var pUser = document.createElement("p");
    var img = document.createElement("img");
    img.style.float = "left"
    if (response.email != null) {
      img.setAttribute("src", "check.png");
      img.setAttribute("alt", "connected");
      p.textContent =" We listen your navigation for ";
      pUser.textContent = response.email;
    }
    else{
      img.setAttribute("src", "cross.png");
      img.setAttribute("alt", "disconnected");
      p.textContent =" We don't listen your navigation";
    }
    p.appendChild(img);
    document.getElementById('connected').appendChild(p);
    document.getElementById('connected').appendChild(pUser);
  });

  document.getElementById('start').addEventListener('click', function(){
    document.getElementById('start').disabled = true; 
    document.getElementById('stop').disabled = false;
    chrome.runtime.sendMessage({type: 'start'}, function start(response){
      if(response.result){
        var storage = chrome.storage.local;
        storage.get('firstUrl', function(result) {
          var post = $.post(adress+'/dataBase.php', { url:result.firstUrl, key:"first" });
        });
      }
    });
  });

  document.getElementById('stop').addEventListener('click', function(){

    document.getElementById('start').disabled = false;
    document.getElementById('stop').disabled = true;
    chrome.runtime.sendMessage({type: 'stop'}, function stop(response){ 
      if (!response.result) {
        sendData("add");
        var storage = chrome.storage.local;
        storage.get('firstUrl', function(result) {
          var post = $.post(adress+'/dataBase.php', { url:result.firstUrl, key:"get_id_firstUrl" });
          post.done(function(data){
            dataParse = JSON.parse(data);
            var a = document.createElement('a');
            a.target = "_blank";
            if (dataParse.length == 0) {
              a.href = adress+"/webSite/graph.php";
              a.textContent = adress+"/webSite/graph.php";
              // a.href = "http://localhost/chromeExtension/webSite/graph.php";
              // a.textContent = "http://localhost/chromeExtension/webSite/graph.php";

            }
            else {
              a.href = adress+"/webSite/graph.php?id="+dataParse[0].id;
              a.textContent = adress+"/webSite/graph.php?id="+dataParse[0].id;
              // a.href = "http://localhost/chromeExtension/webSite/graph.php?id="+dataParse[0].id;
              // a.textContent = "http://localhost/chromeExtension/webSite/graph.php?id="+dataParse[0].id;

            }
            // document.getElementById('data-overlay').appendChild(a);
            resetList();
          });
        });
      }
    });
    chrome.runtime.sendMessage({type: 'reset'}, function get(response){});
  });

  chrome.storage.local.get('event', function(result) {
    console.log(result.event);
  })
});