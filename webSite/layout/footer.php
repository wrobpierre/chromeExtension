  <script>
// Used to toggle the menu on small screens when clicking on the menu button
function myFunction() {
	var x = document.getElementById("navDemo");
	if (x.className.indexOf("w3-show") == -1) {
		x.className += " w3-show";
	} else { 
		x.className = x.className.replace(" w3-show", "");
	}
}
</script>
<footer class="w3-container w3-padding-64 w3-center w3-opacity">  
	<div class="w3-xlarge w3-padding-32">
	</div>
	<p>Our gitHub <a href="https://github.com/wrobpierre/chromeExtension/" target="_blank">Github</a></p>
	<p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
</footer>