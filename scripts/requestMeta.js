var tabElem = {keywords: [], title: ""} ;
var metaElem = document.getElementsByName("keywords");
var titleElem = document.title;
var splitElem;
if(metaElem[0] != undefined){
	splitElem = metaElem[0].getAttribute('content').split(',');
	splitElem.forEach(function(element) {
		tabElem.keywords.push(element);
	});
}
else{
	tabElem.keywords.push("no keywords");
}
if(titleElem != null){
	tabElem.title = titleElem;
}

tabElem;