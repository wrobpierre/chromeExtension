/**
* Principal function of this page, it create the pie chart, give it the datas and draw it.
* @param : object dataset - une object that contain all the  percent of success for questions.
* @param : object div - The DOM element we will use to display the chart.
* @return : void
*/
function generatePieCharts(dataset, div){

	//create the donut of the chart
	var pie=d3.layout.pie()
	.value(function(d){return d.percent})
	.sort(null)
	.padAngle(.03);

	var w=300,h=300;

	var outerRadius=w/2;
	var innerRadius=100;

	var color = d3.scale.category10();

	var arc=d3.svg.arc()
	.outerRadius(outerRadius)
	.innerRadius(innerRadius);

	var svg=d3.select(div)
	.append("svg")
	.attr({
		width:w,
		height:h,
		class:'shadow'
	}).append('g')
	.attr({
		transform:'translate('+w/2+','+h/2+')'
	});
	var path=svg.selectAll('path')
	.data(pie(dataset))
	.enter()
	.append('path')
	.attr({
		d:arc,
		fill:function(d,i){
			// color of the donut blue or red
			if(i%2){
				return '#f44336';
			}
			else{
				return '#1f77b4';
			}
		}
	});

	path.transition()
	.duration(1000)
	.attrTween('d', function(d) {
		var interpolate = d3.interpolate({startAngle: 0, endAngle: 0}, d);
		return function(t) {
			return arc(interpolate(t));
		};
	});

	// Create the legend of the chart
	var restOfTheData=function(){
		var text=svg.selectAll('text')
		.data(pie(dataset))
		.enter()
		.append("text")
		.transition()
		.duration(200)
		.attr("transform", function (d) {
			return "translate(" + arc.centroid(d) + ")";
		})
		.attr("dy", ".4em")
		.attr("text-anchor", "middle")
		.text(function(d){
			return d.data.percent+"%";
		})
		.style({
			fill:'#fff',
			'font-size':'10px'
		});

		var legendRectSize=20;
		var legendSpacing=7;
		var legendHeight=legendRectSize+legendSpacing;
		console.log(legendRectSize+legendSpacing);
		var legend=svg.selectAll('.legend')
		.data([dataset[0].name, dataset[1].name])
		.enter()
		.append('g')
		.attr({
			class:'legend',
			transform:function(d,i){
                //Just a calculation for x & y position
                return 'translate(-65,' + ((i*legendHeight)-35) + ')';
            }
        });
		legend.append('rect')
		.attr({
			width:legendRectSize,
			height:legendRectSize,
			rx:20,
			ry:20
		})
		.style({
			fill:function(d,i){
				// color of the legend blue or red
				if(i%2){
					return '#f44336';
				}
				else{
					return '#1f77b4';
				}
			},
			stroke:color
		});

		legend.append('text')
		.attr({
			x:30,
			y:15
		})
		.text(function(d){
			return d;
		}).style({
			fill:'#929DAF',
			'font-size':'14px'
		});
	};

	setTimeout(restOfTheData,1000);

}