function getCurrentTabUrl() {
  var urlRes
  var queryInfo = {
    active: true,
    currentWindow: true
  };
  chrome.tabs.query(queryInfo, (tabs) => {
    var tab = tabs[0];
    var url = tab.url;
    generateList(url);
    console.log(url);
  });
}

function generateList(url) {
  var section = document.querySelector('body>section');
  var ol = document.createElement('ol');
  var li, p, em, code, text;
  var i;
  li = document.createElement('li');
  p = document.createElement('p');
  em = document.createElement('em');
  em.textContent = 0;
  code = document.createElement('code');
  code.textContent = url;
  console.log(code.textContent);

  p.appendChild(em);
  p.appendChild(code);
  li.appendChild(p);
  ol.appendChild(li);
  section.innerHTML = '';
  section.appendChild(ol);
}
getCurrentTabUrl();
