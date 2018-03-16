function generateList(data) {
  if (data != undefined) {

    data.sort(function(a,b){
      return b.numRequests - a.numRequests;
    });

    var table = document.querySelector('table>tbody');
    for (var i = 0; i < data.length; i++) {
      var tr = document.createElement('tr');
      var rank = document.createElement('td');
      rank.textContent = i;
      
      var url = document.createElement('td');
      url.textContent = data[i]['url'];
      
      var title = document.createElement('td');
      title.textContent = data[i]['title'];

      var keywords = document.createElement('td');
      if (data[i]['keywords'] != null) {
        data[i]['keywords'].forEach( function(element) {
          keywords.textContent += element+","; 
        });   
      }
      else {
        keywords.textContent += "nothing"; 
      }

      var views = document.createElement('td');
      views.textContent = data[i]['numRequests'];

      tr.appendChild(rank);
      tr.appendChild(url);
      tr.appendChild(title);
      tr.appendChild(keywords);
      tr.appendChild(views);
      table.appendChild(tr);
    }


    var liste = document.getElementById('dropdown');
    var ol = document.createElement('ol');
    var li, p, em, code, title, keywords, numReq;
    for (var i = 0; i < data.length; i++) {
      li = document.createElement('li');
      p = document.createElement('p');
      
      em = document.createElement('em');
      em.textContent = i+1;
      p.appendChild(em);
      
      code = document.createElement('code');
      code.textContent = data[i]['url'];
      p.appendChild(code);
      
      numReq = document.createElement('p');
      numReq.textContent = "Views: "+data[i]['numRequests'];
      p.appendChild(numReq);

      title = document.createElement('p');
      title.textContent = "Title: "+data[i]['title'];
      p.appendChild(title);
      
      keywords = document.createElement('p');
      keywords.textContent = "Keywords: "; 
      
      if (data[i]['keywords'] != null) {
        data[i]['keywords'].forEach( function(element) {
          keywords.textContent += element+","; 
        });   
      }
      else {
        keywords.textContent += "nothing"; 
      }
      p.appendChild(keywords);

      li.appendChild(p);
      ol.appendChild(li);
    }
    liste.innerHTML = '';
    liste.appendChild(ol);
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
});

chrome.storage.local.get('event', function(result) {
  console.log(result.event);
})