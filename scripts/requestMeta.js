var tabElem = [] ;
var metaElem = document.getElementsByName('keywords');
var titleElem = document.title;
var splitElem;
metaElem.forEach(function(elements) {
	splitElem = elements.getAttribute('content').split(',');
	splitElem.forEach(function(element) {
		tabElem.push(element);
	});
});
tabElem.push(titleElem);

tabElem;