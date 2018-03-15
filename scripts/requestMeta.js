var tabMeta = [];
var metaElem = document.getElementsByName('keywords');
var splitElem;
metaElem.forEach(function(elements) {
	splitElem = elements.getAttribute('content').split(',');
	splitElem.forEach(function(element) {
		tabMeta.push(element);
	});
});

tabMeta;