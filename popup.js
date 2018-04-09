function generateList(data) {
  if (data != undefined) {

    /*data.sort(function(a,b){
      return b.views - a.views;
    });

    var table = document.querySelector('table>tbody');
    for (var i = 0; i < data.length; i++) {
      var tr = document.createElement('tr');
      var rank = document.createElement('td');
      rank.textContent = i+1;
      
      var url = document.createElement('td');
      url.textContent = data[i]['url'];
      
      var title = document.createElement('td');
      title.textContent = data[i]['title'];

      var keywords = document.createElement('td');
      if (data[i]['keywords'] != null) {
        keywords.textContent = data[i]['keywords'].join(', '); 
      }
      else {
        keywords.textContent = "nothing..."; 
      }

      var views = document.createElement('td');
      views.textContent = data[i]['views'];

      var time = document.createElement('td');
      time.textContent = data[i]['timeOnPage']['hours']+":"+data[i]['timeOnPage']['minutes']+":"+data[i]['timeOnPage']['secondes'];

      var scroll = document.createElement('td');
      scroll.textContent = data[i]['scrollPourcent'];

      tr.appendChild(rank);
      tr.appendChild(url);
      tr.appendChild(title);
      tr.appendChild(keywords);
      tr.appendChild(views);
      tr.appendChild(time);
      tr.appendChild(scroll);
      table.appendChild(tr);
    }*/


    // var liste = document.getElementById('dropdown');
    // var ol = document.createElement('ol');
    // var li, p, em, code, title, keywords, numReq;
    // for (var i = 0; i < data.length; i++) {
    //   li = document.createElement('li');
    //   p = document.createElement('p');

    //   em = document.createElement('em');
    //   em.textContent = i+1;
    //   p.appendChild(em);

    //   code = document.createElement('code');
    //   code.textContent = data[i]['url'];
    //   p.appendChild(code);

    //   numReq = document.createElement('p');
    //   numReq.textContent = "Views: "+data[i]['views'];
    //   p.appendChild(numReq);

    //   title = document.createElement('p');
    //   title.textContent = "Title: "+data[i]['title'];
    //   p.appendChild(title);

    //   keywords = document.createElement('p');
    //   keywords.textContent = "Keywords: "; 

    //   if (data[i]['keywords'] != null) {
    //     data[i]['keywords'].forEach( function(element) {
    //       keywords.textContent += element+","; 
    //     });   
    //   }
    //   else {
    //     keywords.textContent += "nothing"; 
    //   }
    //   p.appendChild(keywords);

    //   li.appendChild(p);
    //   ol.appendChild(li);
    // }
    //liste.innerHTML = '';
    //liste.appendChild(ol);
  }
  else {
    console.log('fail data')
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
      // var post = $.post('http://163.172.59.102/dataBase.php', { d:example, key:"add" });
      var post = $.post('http://localhost/chromeExtension/dataBase.php', { d:example, key:"add" });

    }
    else{
      // var post = $.post('http://163.172.59.102/dataBase.php', { d:result, key:key });
      storage.get('firstUrl', function(resultUrl){
        var post = $.post('http://localhost/chromeExtension/dataBase.php', { d:result, url:resultUrl, key:key });
      });
    }
    post.done(function(data) {
      // var test = $.parseJSON(data);
      // $.each($.parseJSON(test[0]['timer']), function(i, element){
        console.log(data);
      // });
    });
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

  document.getElementsByClassName("nav")[0].addEventListener("click",function(e) {
    console.log(e.target)
    var overlays = document.getElementById('overlays').children;
    for (var i = 0; i < overlays.length; i++) {
      overlays[i].style.display = 'none';
      if (e.target.id == i+1) {
        overlays[i].style.display = 'block';
      }
    }
  });


  document.getElementById('suppr').addEventListener('click', resetList);
  //document.getElementById('add').addEventListener('click', function(){ sendData("add") });
  document.getElementById('load').addEventListener('click', function(){ sendData("load") });
  document.getElementById('delete').addEventListener('click', function(){ sendData("delete") });

  chrome.runtime.sendMessage({type: 'get'}, function get(response){
    console.log(response.result);
    if (response.result) {
      console.log("1");
      document.getElementById('start').disabled = response.result;   
      document.getElementById('stop').disabled = !response.result;
    }
    else{
      console.log("2");
      document.getElementById('start').disabled = response.result;   
      document.getElementById('stop').disabled = !response.result; 
    }
  });

  document.getElementById('start').addEventListener('click', function(){
    document.getElementById('start').disabled = true; 
    document.getElementById('stop').disabled = false;
    chrome.runtime.sendMessage({type: 'start'}, function start(response){
      resetList();
      //alert(response.result);
      if(response.result){
        //alert("1");
        var storage = chrome.storage.local;
        storage.get('firstUrl', function(result) {
          //alert("2")
          //alert(result.firstUrl)
          //var post = $.post('http://163.172.59.102/dataBase.php', { url:result.firstUrl, key:"first" });
          var post = $.post('http://localhost/chromeExtension/dataBase.php', { url:result.firstUrl, key:"first" });
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
          //var post = $.post('http://163.172.59.102/dataBase.php', { d:example, key:"add" });
          //alert(result.firstUrl);
          var post = $.post('http://localhost/chromeExtension/dataBase.php', { url:result.firstUrl, key:"get_id_firstUrl" });
          post.done(function(data){
            console.log('envoie');
            dataParse = JSON.parse(data);
            //alert(dataParse[0].id);
            var a = document.createElement('a');
            a.target = "_blank";
            if (dataParse.length == 0) {
              //a.href = "http://163.172.59.102/webSite/index.php";
              //a.textContent = "http://163.172.59.102/webSite/index.php";
              a.href = "http://localhost/chromeExtension/webSite/index.php";
              a.textContent = "http://localhost/chromeExtension/webSite/index.php";

            }
            else {
              //a.href = "http://163.172.59.102/webSite/index.php?=";
              //a.textContent = "http://163.172.59.102/webSite/index.php?=";
              a.href = "http://localhost/chromeExtension/webSite/index.php?="+dataParse[0].id;
              a.textContent = "http://localhost/chromeExtension/webSite/index.php?="+dataParse[0].id;
            }
            document.getElementById('data-overlay').appendChild(a);
          });
        });
      }
    });
  });

  chrome.storage.local.get('event', function(result) {
    console.log(result.event);
  })
});