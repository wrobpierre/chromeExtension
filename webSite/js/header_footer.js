document.addEventListener("DOMContentLoaded",function(){
	var beginHeader = 0;
	var beginFooter = 0;
	var textHeader = document.getElementsByClassName('textHeader');
	var header = document.getElementById('header');
	var imgHeader = document.getElementById('imgHeader');
	var footer = document.getElementById('footer');
	var info = document.getElementById('info');

	header.style.position = "fixed";
	info.style.position = "fixed";
	footer.style.position = "fixed";


	header.addEventListener("click" , function(){
		if(beginHeader%2 == 0){
			var pos = 0;
			var width = 150;
			var height =  150;
			var count = 0;
			var id = setInterval(frame, 1);
			function frame() {
				if (pos == 350) {
					clearInterval(id);
				} else {
					pos += 5; 
					header.style.top = pos + 'px';
					imgHeader.style.display = "none";
					textHeader[0].style.display = "block";
					textHeader[1].style.display = "block";					

					if(count%2 == 0 ){
						width += 5;
						height += 5;
						header.style.width = width + 'px';
						header.style.height = height + 'px';
					}
					count++;
				}
			}
		}
		else{
			var pos = 350;
			var width = 325;
			var height =  325;
			var count = 0;
			var id = setInterval(frame, 1);
			function frame() {
				if (pos == 0) {
					clearInterval(id);
				} else {
					pos -= 5; 
					header.style.top = pos + 'px'; 
					imgHeader.style.display = "block";
					textHeader[0].style.display = "none";
					textHeader[1].style.display = "none";
					if(count%2 == 0 ){
						width -= 5;
						height -= 5;
						header.style.width = width + 'px';
						header.style.height = height + 'px';
					}
					count++;
				}
			}
			header.style.position = "fixed";
		}
		beginHeader++;
	});

	info.addEventListener("click" , function(){
		if(beginFooter%2 == 0){
			var height = 0;
			var infoPosBottom = 0;
			var pos = 0;
			var id = setInterval(frame, 1);
			function frame() {
				if (pos == 50) {
					clearInterval(id);
				} else {

					info.style.bottom  = infoPosBottom + 'px';
					footer.style.height  = height + 'px';

					height += 5;
					infoPosBottom += 5;
					pos++;
				}
			}
		}
		else{

			var height = 100;
			var pos = 0;
			var infoPosBottom = 100;
			var id = setInterval(frame, 1);
			function frame() {
				if (pos == 21) {
					clearInterval(id);
				} else {
					info.style.bottom  = infoPosBottom + 'px';
					footer.style.height  = height + 'px';

					height -= 5;
					infoPosBottom -= 5;
					pos++;
				}
			}
		}
		beginFooter++;
	});

});