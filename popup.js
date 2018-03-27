function generateList(data) {
  if (data != undefined) {

    data.sort(function(a,b){
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
    }


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
    console.log(result);
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
    var post = $.post('http://localhost/chromeExtension/dataBase.php', { d:result, key:key });

    post.done(function(data) {
      var test = $.parseJSON(data);
      $.each($.parseJSON(test[0]['timer']), function(i, element){
        console.log(element)
      });
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
  document.getElementById('suppr').addEventListener('click', resetList);
  document.getElementById('add').addEventListener('click', function(){ sendData("add") });
  document.getElementById('load').addEventListener('click', function(){ sendData("load") });
  document.getElementById('delete').addEventListener('click', function(){ sendData("delete") });
});

chrome.storage.local.get('event', function(result) {
  console.log(result.event);
})