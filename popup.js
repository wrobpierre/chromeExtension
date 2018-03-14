function getCurrentTabUrl() {
  var urlRes
  var queryInfo = {
    active: true,
    currentWindow: true
  };
  chrome.tabs.query(queryInfo, (tabs) => {
    var tab = tabs[0];
    var url = tab.url;
    saveList(url);
    //console.log(listUrl);
    //generateList(url);
    //console.log(url);
  });
}

function generateList(data) {
  var section = document.querySelector('body>section');
  var ol = document.createElement('ol');
  var li, p, em, code, text;
  var i;
  for (var i = 0; i < data.length; i++) {
    li = document.createElement('li');
    p = document.createElement('p');
    em = document.createElement('em');
    em.textContent = i+1;
    code = document.createElement('code');
    code.textContent = data[i]['url'];
    //console.log(code.textContent);

    p.appendChild(em);
    p.appendChild(code);
    li.appendChild(p);
    ol.appendChild(li);
  }
  section.innerHTML = '';
  section.appendChild(ol);
}

function loadList() {
  var storage = chrome.storage.local;
  storage.get('data', function(result) {
    generateList(result.data);
  })
}

function saveList(url) {
  var storage = chrome.storage.local;
  storage.get('data', function(result) {
    if (result.data == undefined) {
      var obj = {}
      obj['data'] = [{'url':url}]
      storage.set(obj)
    }
    else {
      result.data.push({'url':url});
      storage.set({'data':result.data})
    }
    loadList();
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

getCurrentTabUrl();

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('suppr').addEventListener('click', resetList);
});