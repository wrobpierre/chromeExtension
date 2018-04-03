var example = {
  data: [
  {'url':'https://www.lci.fr/', 'title':'lci', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 0, 'secondes': 0}, 'views':10, 'scrollPercent': 0},
  {'url':'https://fr.wikipedia.org/', 'title':'wikipedia', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 10, 'secondes': 0}, 'views':30, 'scrollPercent': 0},
  {'url':'https://www.youtube.com/', 'title':'youtube', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 2, 'secondes': 0}, 'views':3, 'scrollPercent': 0},
  {'url':'http://www.le-dictionnaire.com/', 'title':'dictionnaire', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 6, 'secondes': 0}, 'views':1000, 'scrollPercent': 0},
  {'url':'https://stackoverflow.com/', 'title':'stackoverflow', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 0, 'secondes': 15}, 'views':1235, 'scrollPercent': 0},
  {'url':'http://www.meteofrance.com/', 'title':'meteo france', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 15, 'secondes': 1}, 'views':46, 'scrollPercent': 0},
  {'url':'http://www.jeuxvideo.com/', 'title':'jeuxvideo.com', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 3, 'secondes': 30}, 'views':5464, 'scrollPercent': 0},
  {'url':'http://www.lemonde.fr/', 'title':'lemonde', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 10, 'secondes': 1}, 'views':158, 'scrollPercent': 0},
  {'url':'https://www.google.fr/maps', 'title':'google maps', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 4, 'secondes': 0}, 'views':10546, 'scrollPercent': 0},
  {'url':'https://translate.google.fr/?hl=fr', 'title':'translate', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 14, 'secondes': 0}, 'views':7896, 'scrollPercent': 0},
  {'url':'https://www.youtube.com/user/joueurdugrenier', 'title':'JDG', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 0, 'secondes': 30}, 'views':12, 'scrollPercent': 0},
  {'url':'http://www.larousse.fr/', 'title':'larousse', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 0, 'secondes': 40}, 'views':751, 'scrollPercent': 0},
  {'url':'https://openclassrooms.com/', 'title':'openclassrooms', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 2, 'secondes': 0}, 'views':2135, 'scrollPercent': 0},
  {'url':'https://conjugaison.lemonde.fr/', 'title':'lemonde conjugaison', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 12, 'secondes': 0}, 'views':7, 'scrollPercent': 0},
  {'url':'http://www.lcp.fr/', 'title':'lcp', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 16, 'secondes': 0}, 'views':654, 'scrollPercent': 0},
  {'url':'https://www.tf1.fr/', 'title':'TF1', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 2, 'secondes': 0}, 'views':1865, 'scrollPercent': 0},
  {'url':'https://doc.ubuntu-fr.org', 'title':'doc ubuntu', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 0, 'secondes': 25}, 'views':9874, 'scrollPercent': 0},
  {'url':'http://www.quebecscience.qc.ca/', 'title':'quebecscience', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 6, 'secondes': 0}, 'views':46, 'scrollPercent': 0},
  {'url':'http://philosophie.philisto.fr/', 'title':'philosophie.philisto', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 9, 'secondes': 10}, 'views':1235, 'scrollPercent': 0},
  {'url':'https://fr.wikipedia.org/wiki/Temps', 'title':'wikipedia', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 7, 'secondes': 0}, 'views':964, 'scrollPercent': 0},
  {'url':'https://www.mondedemain.org/', 'title':'mondedemain', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 10, 'secondes': 6}, 'views':264, 'scrollPercent': 0},
  {'url':'http://www.linternaute.com/', 'title':'linternaute', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 11, 'secondes': 0}, 'views':12358, 'scrollPercent': 0},
  {'url':'http://forums.futura-sciences.com/', 'title':'futura-sciences', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 5, 'secondes': 0}, 'views':1235, 'scrollPercent': 0},
  {'url':'https://www.journaldunet.com/', 'title':'journaldunet', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 14, 'secondes': 1}, 'views':6874, 'scrollPercent': 0},
  {'url':'https://www.pourlascience.fr/', 'title':'pourlascience', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 15, 'secondes': 23}, 'views':3, 'scrollPercent': 0},
  {'url':'http://www.hatem.com/temps.htm', 'title':'hatem', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 13, 'secondes': 0}, 'views':658, 'scrollPercent': 0},
  {'url':'https://www.franceinter.fr/', 'title':'franceinter', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 2, 'secondes': 0}, 'views':102, 'scrollPercent': 0},
  {'url':'https://www.kaizen-magazine.com/', 'title':'kaizen-magazine', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 6, 'secondes': 0}, 'views':7892, 'scrollPercent': 0},
  {'url':'http://etienneklein.fr/quest-ce-que-le-temps/', 'title':'etienneklein', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 10, 'secondes': 0}, 'views':2365, 'scrollPercent': 0},
  {'url':'https://www.lelivrescolaire.fr/', 'title':'lelivrescolaire', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 0, 'secondes': 23}, 'views':86, 'scrollPercent': 0},
  {'url':'https://usbeketrica.com', 'title':'usbeketrica', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 4, 'secondes': 0}, 'views':4862, 'scrollPercent': 0},
  {'url':'http://www.vie-publique.fr/', 'title':'vie-publique', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 12, 'secondes': 0}, 'views':123, 'scrollPercent': 0},
  {'url':'http://dicocitations.lemonde.fr/citation-temps.php', 'title':'dicocitations.lemonde', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 2, 'secondes': 0}, 'views':1234, 'scrollPercent': 0},
  {'url':'http://www.assistant-juridique.fr/', 'title':'assistant-juridique', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 0, 'secondes': 15}, 'views':10236, 'scrollPercent': 0},
  {'url':'https://la-philosophie.com/', 'title':'philosophie', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 0, 'secondes': 17}, 'views':913, 'scrollPercent': 0},
  {'url':'https://www.assistancescolaire.com/', 'title':'assistancescolaire', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 12, 'secondes': 0}, 'views':78, 'scrollPercent': 0},
  {'url':'https://www.cafephilosophia.fr/', 'title':'cafephilosophia', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 8, 'secondes': 0}, 'views':5236, 'scrollPercent': 0},
  {'url':'http://olivierabel.fr', 'title':'olivierabel', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 1, 'secondes': 0}, 'views':785, 'scrollPercent': 0},
  {'url':'http://www.maphilo.net/', 'title':'maphilo', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 7, 'secondes': 12}, 'views':569, 'scrollPercent': 0},
  {'url':'http://www.philocours.com/', 'title':'philocours', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 3, 'secondes': 50}, 'views':1235, 'scrollPercent': 0},
  {'url':'https://dicophilo.fr/', 'title':'dicophilo', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 4, 'secondes': 0}, 'views':10356, 'scrollPercent': 0},
  {'url':'http://www.philopratique.com/', 'title':'philopratique', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 15, 'secondes': 0}, 'views':986, 'scrollPercent': 0},
  {'url':'https://www.franceculture.fr/', 'title':'franceculture', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 14, 'secondes': 0}, 'views':5648, 'scrollPercent': 0},
  {'url':'http://www.philolog.fr/', 'title':'philolog', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 13, 'secondes': 0}, 'views':1298, 'scrollPercent': 0},
  {'url':'http://www.philosophiecontresuperstition.com/', 'title':'philosophiecontresuperstition', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 3, 'secondes': 0}, 'views':654, 'scrollPercent': 0},
  {'url':'https://www.scienceshumaines.com/', 'title':'scienceshumaines', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 0, 'secondes': 10}, 'views':130, 'scrollPercent': 0},
  {'url':'http://www.letudiant.fr/', 'title':'letudiant', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 17, 'secondes': 0}, 'views':9, 'scrollPercent': 0},
  {'url':'https://www.bac-l.net/', 'title':'bac-l', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 25, 'secondes': 0}, 'views':491, 'scrollPercent': 0},
  {'url':'http://www.lesclefsdelecole.com/', 'title':'lesclefsdelecole', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 6, 'secondes': 0}, 'views':1236, 'scrollPercent': 0},
  {'url':'http://www.studyrama.com/', 'title':'studyrama', 'keywords':['LCI', 'information', 'en direct'], 'dateBegin': "", 'timeOnPage': {'hours': 0, 'minutes': 12, 'secondes': 12}, 'views':9865, 'scrollPercent': 0}
  
  ]
}

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
      var post = $.post('http://163.172.59.102/dataBase.php', { d:example, key:"add" });
    }
    else{
      var post = $.post('http://163.172.59.102/dataBase.php', { d:result, key:key });
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
    chrome.runtime.sendMessage({type: 'start'}, function start(response){ console.log(response.result) })

  });

  document.getElementById('stop').addEventListener('click', function(){

    document.getElementById('start').disabled = false;
    document.getElementById('stop').disabled = true;
    chrome.runtime.sendMessage({type: 'stop'}, function stop(response){ if (!response.result) { sendData("add") } })
  });
});

chrome.storage.local.get('event', function(result) {
  console.log(result.event);
})