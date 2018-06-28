/**
* Principal function of this page, it create the bar chart, give it the datas and draw it.
* @param : object dataset - une object that contain all the  percent of success for questions.
* @param : object div - The DOM element we will use to display the chart.
* @return : void
*/
function barChart(elem, data , info){
	// If all the datas are find
	if(data.value1 != undefined && data.value2 != undefined && data.value3 != undefined){
		new Chart(elem, {
			type: 'bar',
			data: {
				labels: [data.value1.labels, data.value2.labels, data.value3.labels],
				datasets: [
				{
					label: "Participants number",
					backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f"],
					data: [data.value1.nbData, data.value2.nbData, data.value3.nbData]
				}
				]
			},
			options: {
				legend: { display: false },
				title: {
					display: true,
					text: info
				},
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true
						}
					}]
				}
			}
		});
	}
}
