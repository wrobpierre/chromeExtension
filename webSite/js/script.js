var minTime = null;
var maxTime = null;
var nb_question = null;
var nb_site = null;
var bestNote;
var errorImg = false;
var adress = "http://163.172.59.102"
var tabData = [];
var maxAvg = 0;
var minAvg;
// var adress = "http://localhost/chromeExtension"

function median(values) {
  values.sort(function(a, b){ return a.total - b.total; });
  var half = Math.floor(values.length/2);
  if(values.length % 2)
    return values[half];
  else
    return (values[half-1] + values[half]) / 2.0;
}

var id = document.URL.split('-')[1];
//var user = getUrlParameter('user');

/*if (id !== undefined && user !== undefined) {
  var post = $.post(adress+'/dataBase.php', { key:"load", id:id});
}*/
if (id !== undefined) {
  var post = $.post(adress+'/dataBase.php', { key:"load", id:id });
}
else {
  var post = $.post(adress+'/dataBase.php', { key:"load" });
}


post.done(function(data) {
  var nytg = nytg || {}; 
  var time = 0;
  var alreadySave = false;
  var maxQuestion = 0;

  nytg.array_webSites = [];
  nytg.array_best_median = [];

  best_users = [];
  best_users['view'] = [];
  best_users['time'] = [];
  
  dataParse = JSON.parse(data);
  console.log(dataParse);
  bestNote = dataParse[0]['note'];
  dataParse.forEach(function(element){
    timer = JSON.parse(element['timer']);

    if (element['note'] > bestNote) {
      bestNote = element['note'];
    }

    if ( best_users['view'][element['key_user']] == undefined ) {  
      best_users['view'][element['key_user']] = [];
      best_users['view'][element['key_user']]['total'] = JSON.parse(element['question']).length;

      time = parseInt(timer.hours)*3600 + parseInt(timer.minutes)*60 + parseInt(timer.secondes);
      best_users['time'][element['key_user']] = [];
      best_users['time'][element['key_user']]['total'] = time;
      
      best_users['view'][element['key_user']].push(element);
      best_users['time'][element['key_user']].push(element);
    } 
    else {
      best_users['view'][element['key_user']].push(element);
      best_users['view'][element['key_user']]['total'] += JSON.parse(element['question']).length;

      time = parseInt(timer.hours)*3600 + parseInt(timer.minutes)*60 + parseInt(timer.secondes);
      best_users['time'][element['key_user']].push(element);
      best_users['time'][element['key_user']]['total'] += time;
    }

    nb_question = element['nb_question'];
    if (!tabData.find(function(elem){ return elem.url === element.url; })) {
      tmp = dataParse.filter(function(obj){ return obj.url == element.url; });
      note = 0;
      hours = 0;
      minutes = 0;
      secondes = 0;
      views = 0;
      var tabMedianeTime = [];
      var tabMedianeView = [];
      var tabMedianeFirstTime = [];
      var questionNum = JSON.parse(element['question']);
      if (maxQuestion < questionNum[0]['question']) {
        maxQuestion = questionNum[0]['question'];
      }

      tmp.forEach(function(elem){
        views += parseInt(elem['view']);
        note += parseInt(elem['note']);
        timer = JSON.parse(elem['timer']);
        tabMedianeView.push(parseInt(elem['view']));
        tabMedianeTime.push(parseInt(timer.hours)*3600+parseInt(timer.minutes)*60+parseInt(timer.secondes));
        tabMedianeFirstTime.push(new Date(elem['first_time']).getTime());
        //console.log("elem : "+parseInt(elem['note']));
      });
      tabMedianeView = tabMedianeView.sort(function compareNombres(a, b) {return a - b;});
      element['view'] = tabMedianeView[Math.ceil(parseInt(tabMedianeView.length/2))];
      element['avg'] = note/tmp.length;

      tabMedianeTime = tabMedianeTime.sort(function compareNombres(a, b) {return a - b;});
      element['timer'] = JSON.parse(element['timer']);

      if((tabMedianeTime[Math.ceil(parseInt(tabMedianeTime.length/2))]/3600) >= 1){
        hours = tabMedianeTime[Math.ceil(parseInt(tabMedianeTime.length/2))]/3600;
        tabMedianeTime[Math.ceil(parseInt(tabMedianeTime.length/2))] -= Math.floor(hours)*3600;
        hours = Math.floor(hours);
      }
      if((tabMedianeTime[Math.ceil(parseInt(tabMedianeTime.length/2))]/60)>= 1){
        minutes = tabMedianeTime[Math.ceil(parseInt(tabMedianeTime.length/2))]/60
        tabMedianeTime[Math.ceil(parseInt(tabMedianeTime.length/2))] -= Math.floor(minutes)*60;
        minutes = Math.floor(minutes);
      }
      secondes = Math.floor(tabMedianeTime[Math.ceil(parseInt(tabMedianeTime.length/2))]);

      element['timer']['hours'] = hours;
      element['timer']['minutes'] = minutes;
      element['timer']['secondes'] = secondes;
      
      tabMedianeFirstTime.sort(function compareNombres(a, b) {return a - b;});
      element['first_time'] = tabMedianeFirstTime[Math.ceil(parseInt(tabMedianeTime.length/2))];

      if ( element['host_name'].indexOf('www.google.') == -1 && element['host_name'].indexOf('163.172.59.102') == -1 ) {
        tabData.push(element);
      }
    }
  });

  var tmp_best_users_view = best_users['view'].filter(function(element){ return element[0]['note'] == bestNote; });
  var tmp_best_users_time = best_users['time'].filter(function(element){ return element[0]['note'] == bestNote; });
  
  median_view = median(tmp_best_users_view);
  median_time = median(tmp_best_users_time);

  median_view.forEach( function(element, index) {
    var question = JSON.parse(element['question']);
    question.forEach( function(el) {
      var tmpEl = JSON.parse(JSON.stringify(element));
      tmpEl['first_time'] = new Date(el['date']).getTime();
      tmpEl['type'] = 'view';
      nytg.array_best_median.push(tmpEl);
    });
  });

  var nb_site_view = nytg.array_best_median.length;

  median_time.forEach( function(element, index) {
    var question = JSON.parse(element['question']);
    question.forEach( function(el) {
      var tmpEl = JSON.parse(JSON.stringify(element));
      tmpEl['first_time'] = new Date(el['date']).getTime();
      tmpEl['type'] = 'time ';
      nytg.array_best_median.push(tmpEl);
    });
  });
  
  var nb_site_time = nytg.array_best_median.length - nb_site_view;

  nytg.array_best_median.sort(function(a,b) {
    return a.first_time - b.first_time;
  });

  var order_view = 0;
  var order_time = 0;
  nytg.array_best_median.forEach( function(element, index) {
    element["positions"] = {"total":{"x": Math.random()*600 - 300, "y": Math.random()*600 - 300 }};
    if (element['type'] == 'view') {
      element['order'] = order_view++;
    }
    else {
      element['order'] = order_time++;
    }
  });

  nb_site = tabData.length;

  tabData.sort(function(a,b) {
    return a.first_time - b.first_time;
  });
  minAvg = tabData[0]['avg'];
  tabData.forEach(function(element){
    element["positions"] = {"total":{"x": Math.random()*600 - 300, "y": Math.random()*600 - 300 }};
    if(minTime == null || minTime.hours >= parseInt(element.timer.hours)) {
      if (minTime == null || minTime.hours > parseInt(element.timer.hours) || minTime.minutes >= parseInt(element.timer.minutes)) {
        if (minTime == null || minTime.hours > parseInt(element.timer.hours) || minTime.minutes > parseInt(element.timer.minutes) || minTime.secondes >= parseInt(element.timer.secondes)) {
          minTime = element.timer;
        }
      }
    }
    if(maxTime == null || maxTime.hours <= parseInt(element.timer.hours)) {
      if (maxTime == null || maxTime.hours < parseInt(element.timer.hours) || maxTime.minutes <= parseInt(element.timer.minutes)) {
        if (maxTime == null || maxTime.hours < parseInt(element.timer.hours) || maxTime.minutes < parseInt(element.timer.minutes) || maxTime.secondes <= parseInt(element.timer.secondes)) {
          maxTime = element.timer;
        }
      }
    }

    nytg.array_webSites.push(element);

    if (minAvg > element['avg'] ) {
      minAvg = element['avg'];
    }
    if (maxAvg < element['avg']) {
      maxAvg = element['avg'];
    }
  })

  for (var i = minAvg; i <= maxAvg; i++) {
    var notesFilter = document.getElementById('notes');
    var input = document.createElement("input");
    var label = document.createElement("label");
    input.setAttribute("class", "sorts notesFilter w3-radio");
    input.setAttribute("type", "checkbox");
    input.style.float = "left";
    label.style.textAlign = "left";
    label.innerHTML = "note : "+i+"/"+maxAvg;
    notesFilter.appendChild(input);
    notesFilter.appendChild(label);
  }

  //nytg.category_data = [{"label":"Health and Human Services","total":921605000,"num_children":26,"short_label":"Health and Human Services"},{"label":"State","total":31608000,"num_children":8,"short_label":"State"},{"label":"Judicial Branch","total":7502000,"num_children":13,"short_label":"Judicial Branch"},{"label":"International Assistance Programs","total":37399000,"num_children":16,"short_label":"International"},{"label":"Agriculture","total":154667000,"num_children":45,"short_label":"Agriculture"},{"label":"Treasury","total":519490000,"num_children":15,"short_label":"Treasury"},{"label":"Other Defense Civil Programs","total":57416000,"num_children":9,"short_label":"Defense Civil Programs"},{"label":"Appalachian Regional Commission","total":64000,"num_children":2,"short_label":"Appalachian Commission"},{"label":"Legislative Branch","total":4789000,"num_children":20,"short_label":"Legislative Branch"},{"label":"Veterans Affairs","total":137381000,"num_children":9,"short_label":"Veterans Affairs"},{"label":"Justice","total":30023000,"num_children":18,"short_label":"Justice"},{"label":"Interior","total":11357000,"num_children":31,"short_label":"Interior"},{"label":"Commerce","total":9239000,"num_children":21,"short_label":"Commerce"},{"label":"Labor","total":88993000,"num_children":15,"short_label":"Labor"},{"label":"Homeland Security","total":45109000,"num_children":23,"short_label":"Homeland Security"},{"label":"Housing and Urban Development","total":44010000,"num_children":14,"short_label":"Housing"},{"label":"Corps of Engineers--Civil Works","total":4668000,"num_children":3,"short_label":"Corps of Engineers"},{"label":"Executive Office of the President","total":392000,"num_children":12,"short_label":"Office of the President"},{"label":"Energy","total":32300000,"num_children":10,"short_label":"Energy"},{"label":"Transportation","total":74280000,"num_children":22,"short_label":"Transportation"},{"label":"Education","total":55685000,"num_children":15,"short_label":"Education"},{"label":"Federal Deposit Insurance Corporation","total":1515000,"num_children":5,"short_label":"F.D.I.C."},{"label":"District of Columbia","total":902000,"num_children":5,"short_label":"District of Columbia"},{"label":"Environmental Protection Agency","total":8138000,"num_children":2,"short_label":"E.P.A."},{"label":"Defense - Military","total":620259000,"num_children":13,"short_label":"Defense"},{"label":"Institute of Museum and Library Services","total":231000,"num_children":2,"short_label":"Museum and Library Services"},{"label":"National Aeronautics and Space Administration","total":17693000,"num_children":2,"short_label":"NASA"},{"label":"National Archives and Records Administration","total":370000,"num_children":3,"short_label":"National Archives"},{"label":"National Science Foundation","total":7470000,"num_children":2,"short_label":"N.S.F."},{"label":"Nuclear Regulatory Commission","total":127000,"num_children":2,"short_label":"Nuclear Regulation"},{"label":"Office of Personnel Management","total":94857000,"num_children":3,"short_label":"Personnel Management"},{"label":"Postal Service","total":78000,"num_children":2,"short_label":"Postal Service"},{"label":"Public Company Accounting Oversight Board","total":237000,"num_children":2,"short_label":"Accounting Oversight"},{"label":"Railroad Retirement Board","total":7202000,"num_children":3,"short_label":"Railroad Retirement"},{"label":"Small Business Administration","total":1111000,"num_children":2,"short_label":"Small Business"},{"label":"Social Security Administration","total":885315000,"num_children":2,"short_label":"Social Security"},{"label":"Federal Communications Commission","total":9633000,"num_children":2,"short_label":"F.C.C."},{"label":"Securities Investor Protection Corporation","total":259000,"num_children":2,"short_label":"S.I.P.C."},{"label":"Other","total":-512596000,"num_children":97,"short_label":"Other"}];

  var question = $.post(adress+'/webSite/questionnaires/management_questionnaire.php', { action:'get_data_question', id:id });
  question.done(function(data){
    if (data != "") {

      var dataParse = JSON.parse(data);
      console.log(dataParse);

      for (var i = 0; i < maxQuestion; i++) {
        var questionFilter = document.getElementById('questionFilter');
        var input = document.createElement("input");
        var label = document.createElement("label");
        input.setAttribute("class", "sorts questionFilter w3-radio");
        input.setAttribute("type", "checkbox");
        input.setAttribute("checked", "checked");
        input.style.float = "left";
        label.style.textAlign = "left";
        label.style.paddingLeft = "25px";
        label.innerHTML = dataParse[i][2];
        // label.innerHTML = "question "+[i];
        questionFilter.appendChild(input);
        questionFilter.appendChild(label);
        questionFilter.appendChild(document.createElement("br"));
      }

      document.getElementById('question_title').textContent = dataParse[0]['title'];
      var li = document.createElement("li");
      var a = document.createElement("a");
      a.style.textDecoration = "none";
      a.style.color = "#999";
      a.setAttribute("href" ,  "questionnaires/questionnaire-"+id);
      a.textContent = "Answer this questionnaire"
      li.appendChild(a);
      document.getElementById('navBarGraph').appendChild(li);
      // document.getElementById('navBarGraph').appendChild() += "<li style='display: none;'><a href='' style='text-decoration: none; color: #999;'>Answer this questionnaire</a></li>";
      document.querySelector('.nytg-overview > p').textContent = dataParse[0]['statement'];
    }
  });

// BEGIN nytg Additions
jQuery.noConflict();
var $j = jQuery;
// END nytg Additions

var nytg = nytg || {};

nytg.formatNumber = function(n) {
  var s, suffix;
  suffix = ""

  if (n >= 1000000000000) {
    suffix = " trillion"
    n = n / 1000000000000
    //decimals = 2
  } else if (n >= 1000000000) {
    suffix = " billion"
    n = n / 1000000000
    //decimals = 1
  } else if (n >= 1000000) {
    suffix = " million"
    n = n / 1000
  } 

  s = String(Math.round(n));
  s = s.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
  return  s + suffix;
};

nytg.changeTickValues = [];
for (var i = 0; i < 2; i++) {
  nytg.changeTickValues.push(i+1);
}

nytg.changeScale = [0,2];

/********************************
 ** FILE: Chart.js
 ********************************/

 var nytg = nytg || {};

 nytg.Chart = function(){
  return {
    $j : jQuery,
    //defaults
    width           : 970,
    height          : 850,
    groupPadding    : 10,
    totalValue      : 3700000000000,
    deficitValue    : 901000,
    // CONST
    MANDATORY       : "Mandatory",
    DISCRETIONARY   : "Discretionary",
    NET_INTEREST    : "Net interest",
    
    //will be calculated later
    boundingRadius  : null,
    maxRadius       : null,
    centerX         : null,
    centerY         : null,
    scatterPlotY    : null,

    //d3 settings
    defaultGravity  : 0.1,
    defaultCharge   : function(d){
      if (d.value < 0) {
        return 0
      } else {
        return -Math.pow(d.radius,2.0)/8 
      };
    },
    links           : [],
    nodes           : [],
    positiveNodes   : [],
    force           : {},
    svg             : {},
    circle          : {},
    gravity         : null,
    charge          : null,
    changeTickValues: nytg.changeTickValues,
    categorizeChange: function(c){
      var time = parseInt(c['hours'])*3600 + parseInt(c['minutes'])*60 + parseInt(c['secondes']);
      var nbSecondesMax = parseInt(maxTime['hours'])*3600 + parseInt(maxTime['minutes'])*60 + parseInt(maxTime['secondes']);
      var nbSecondesMin = parseInt(minTime['hours'])*3600 + parseInt(minTime['minutes'])*60 + parseInt(minTime['secondes']);
      var divisionTime = nbSecondesMax/6;

      var multiplyTime = 1;
      var hours =0;
      var minutes =0;
      var secondes =0;
      for (var i = 0; i < $j(".nytg-colorLabels")[0].children.length; i++) {
        var convertTime = Math.round(divisionTime*multiplyTime);
        var print = "";
        if (hours > 0) {
          print += "Between "+ hours+"h "+minutes+"min "+secondes+"sec and ";
        }
        else if(minutes > 0) {
          print += "Between "+minutes+"min "+secondes+"sec and ";
        }
        else {
          print += "Between "+secondes+"sec and ";
        }
        
        if(convertTime > 3600){
          hours = Math.floor(convertTime / 3600);
          convertTime -= hours*3600
          print += hours+"h "
        }
        if(convertTime > 60){
          minutes = Math.floor(convertTime / 60);
          convertTime -= minutes*60
          print += minutes+"min "
        }
        secondes = Math.floor(convertTime)
        print += secondes+"sec "


        $j(".nytg-colorLabels")[0].children[i].textContent = print;
        multiplyTime++;
      }

      if (time < divisionTime) { return -2;
      } else if ( time < divisionTime*2) { return -1;
      } else if ( time < divisionTime*3){ return 0;
      } else if ( time < divisionTime*4){ return 1;
      } else if ( time < divisionTime*5){ return 2;
      } else { return 3; }
    },
    fillColor       : d3.scale.ordinal().domain([-2,-1,0,1,2,3]).range(["#d84b2a", "#ee9586", "#e4b7b2", "#BECCAE", "#9caf84", "#7aa25c"]),
    strokeColor     : d3.scale.ordinal().domain([-2,-1,0,1,2,3]).range(["#c72d0a", "#e67761", "#d9a097", "#b5c1a6", "#7e965d", "#5a8731"]),
    getFillColor    : null,
    getStrokeColor  : null,

    bigFormat       : function(n){return nytg.formatNumber(n)},
    nameFormat      : function(n){return n},
    
    rScale          : d3.scale.pow().exponent(0.15).domain([0,10000000000]).range([1,100]),
    radiusScale     : null,
    changeScale     : d3.scale.linear().domain(nytg.changeScale).range([620,260]).clamp(true),
    sizeScale       : d3.scale.linear().domain([0,110]).range([0,1]),
    //groupScale      : {},
    
    //data settings
    data                    : nytg.array_webSites,
    categoryPositionLookup  : {},
    categoriesList          : [],
    
    init: function() {
      var that = this;
      
      this.scatterPlotY = this.changeScale(0);
      
      this.radiusScale = function(n){ return that.rScale(Math.abs(n)); };
      this.getStrokeColor = function(d){
        return that.strokeColor(d.changeCategory);
      };
      this.getFillColor = function(d){
        if (d.isNegative) {
          return "#fff"
        }
        return that.fillColor(d.changeCategory);
      };
      
      this.boundingRadius = this.radiusScale(this.totalValue);
      this.centerX = this.width / 2;
      this.centerY = 300;
      
      /*nytg.category_data.sort(function(a, b){  
        return b['total'] - a['total'];  
      });

      //calculates positions of the category clumps
      //it is probably overly complicated
      var columns = [4, 7, 9, 9]
      rowPadding = [150, 100, 90, 80, 70],
      rowPosition = [220, 450, 600, 720, 817],
      rowOffsets = [130, 80, 60, 45, 48]
      currentX = 0,
      currentY = 0;
      for (var i=0; i < nytg.category_data.length; i++) {
        var t = 0, 
        w,
        numInRow = -1,
        positionInRow = -1,
        currentRow = -1,
        cat = nytg.category_data[i]['label'];
        // calc num in this row
        for (var j=0; j < columns.length; j++) {
          if (i < (t + columns[j])) {
            numInRow = columns[j];
            positionInRow = i - t;
            currentRow = j;
            break;
          };
          t += columns[j];
        };
        if (numInRow === -1) {
          numInRow = nytg.category_data.length - d3.sum(columns);
          currentRow = columns.length;
          positionInRow = i  - d3.sum(columns)
        };
        nytg.category_data[i].row = currentRow;
        nytg.category_data[i].column = positionInRow;
        w = (this.width - 2*rowPadding[currentRow]) / (numInRow-1)
        currentX = w * positionInRow + rowPadding[currentRow];
        currentY = rowPosition[currentRow];
        this.categoriesList.push(cat);
        this.categoryPositionLookup[cat] = {
          x:currentX, 
          y:currentY,
          w: w*0.9,
          offsetY: rowOffsets[currentRow],
          numInRow:numInRow,
          positionInRow:positionInRow
        }        
      };

      this.groupScale = d3.scale.ordinal().domain(this.categoriesList).rangePoints([0,1]);*/

      var maxView = null;
      var minView = null;
      var percent = 0;

      for (var i=0; i < this.data.length; i++) {
        if (maxView == null || maxView < parseInt(this.data[i].view)) {
          maxView = this.data[i].view;
        }
        if (minView == null || minView > parseInt(this.data[i].view)) {
          minView = this.data[i].view;
        }
      }

      percent = maxView/100;
      // Builds the nodes data array from the original data
      for (var i=0; i < this.data.length; i++) {
        var n = this.data[i];

        var res = Math.exp((this.data[i].view/5)/percent);
        var out = {
          sid: n['id'],
          radius: this.radiusScale(res),
          group: n['host_name'],
          change: n['timer'],
          changeCategory: this.categorizeChange(n['timer']),
          value: n['view'],
          url: n['url'],
          avg: n['avg'],
          positions: n.positions,
          x:Math.random() * 1000,
          y:Math.random() * 1000
        }
        if (n.positions.total) {
          out.x = n.positions.total.x + (n.positions.total.x - (that.width / 2)) * 0.5;
          out.y = n.positions.total.y + (n.positions.total.y - (150)) * 0.5;
        };
        this.nodes.push(out)
      };

      this.nodes.sort(function(a, b){  
        return Math.abs(b.value) - Math.abs(a.value);  
      });
      
      for (var i=0; i < this.nodes.length; i++) {
        this.positiveNodes.push(this.nodes[i])
      };
      
      this.svg = d3.select("#nytg-chartCanvas").append("svg:svg")
      .attr("width", this.width);
      
      for (var i=0; i < this.changeTickValues.length; i++) {
        d3.select("#nytg-discretionaryOverlay").append("div")
        .html("<p>"+this.changeTickValues[i]+"/"+this.changeTickValues.length+"</p>")
        .style("top", this.changeScale(this.changeTickValues[i])+'px')
        .classed('nytg-discretionaryTick', true)
        .classed('nytg-discretionaryZeroTick', (this.changeTickValues[i] === 0) )
      };
      d3.select("#nytg-discretionaryOverlay").append("div")
      .html("<p></p>")
      .style("top", this.changeScale(0)+'px')
      .classed('nytg-discretionaryTick', true)
      .classed('nytg-discretionaryZeroTick', true)
      /*d3.select("#nytg-discretionaryOverlay").append("div")
      .html("<p>+26% or higher</p>")
      .style("top", this.changeScale(100)+'px')
      .classed('nytg-discretionaryTickLabel', true)
      d3.select("#nytg-discretionaryOverlay").append("div")
      .html("<p>&minus;26% or lower</p>")
      .style("top", this.changeScale(-100)+'px')
      .classed('nytg-discretionaryTickLabel', true)*/

      // deficit circle
      d3.select("#nytg-deficitCircle").append("circle")
      .attr('r', this.radiusScale(this.deficitValue))
      .attr('class',"nytg-deficitCircle")
      .attr('cx', 125)
      .attr('cy', 125);

      // $ 100 billion
      d3.select("#nytg-scaleKey").append("circle")
      .attr('r', this.radiusScale(10000000))
      .attr('class',"nytg-scaleKeyCircle")
      .attr('cx', 30)
      .attr('cy', 40);

      // $ 10 billion
      d3.select("#nytg-scaleKey").append("circle")
      .attr('r', this.radiusScale(1000000))
      .attr('class',"nytg-scaleKeyCircle")
      .attr('cx', 30)
      .attr('cy', 50);

      // $ 1 billion
      d3.select("#nytg-scaleKey").append("circle")
      .attr('r', this.radiusScale(100000))
      .attr('class',"nytg-scaleKeyCircle")
      .attr('cx', 30)
      .attr('cy', 55);
      
      // This is the every circle
      this.circle = this.svg.selectAll("circle")
      .data(this.nodes, function(d) { return d.sid; });

      this.circle.enter().append("svg:circle")
      .attr("r", function(d) { return 0; } )
      .style("fill", function(d) { return that.getFillColor(d); } )
      .style("stroke-width", 1)
      .attr('id',function(d){ return 'nytg-circle'+d.sid })
      .style("stroke", function(d){ return that.getStrokeColor(d); })
      .on("mouseover",function(d,i) { 
        var el = d3.select(this)
        var xpos = Number(el.attr('cx'))
        var ypos = (el.attr('cy') - d.radius - 10)
        el.style("stroke","#000").style("stroke-width",3);
        d3.select("#nytg-tooltip").style('top',ypos+"px").style('left',xpos+"px").style('display','block')
        .classed('nytg-plus', (d.changeCategory > 0))
        .classed('nytg-minus', (d.changeCategory < 0));
        
        var $j = jQuery;

        d3.select("#nytg-tooltip .nytg-url").html(that.nameFormat(d.url.substr(0, 35)+"..."))
        d3.select("#nytg-tooltip .nytg-domain").text(d.group)
        var url = new URL(d.url)

        $j("#nytg-tooltip .nytg-logo").html('<img class="icon" src="'+url.protocol+"//"+url.hostname+"/favicon.ico"+'" alt="icon site" style="width: 40px;" onerror = "this.remove()" />')
        if (d.value == 1) {
          d3.select("#nytg-tooltip .nytg-value").html(that.bigFormat(d.value)+' view') 
        }
        else{
          d3.select("#nytg-tooltip .nytg-value").html(that.bigFormat(d.value)+' views')
        }
      })

      .on("mouseout",function(d,i) { 
        d3.select(this)
        .style("stroke-width",1)
        .style("stroke", function(d){ return that.getStrokeColor(d); })
        d3.select("#nytg-tooltip").style('display','none')})

      .on("click", function(d) {
        var win = window.open(d.url, '_blank');
        win.focus();
      });

      this.circle.transition().duration(2000).attr("r", function(d){return d.radius})
    },

    update: function(array_data){
      var that = this;
      nb_site = array_data.length;

      this.data = array_data;
      this.nodes = [];
      this.svg = {};
      this.circle = {};

      var maxView = null;
      var minView = null;
      var percent = 0;

      for (var i=0; i < this.data.length; i++) {
        if (maxView == null || maxView < parseInt(this.data[i].view)) {
          maxView = this.data[i].view;
        }
        if (minView == null || minView > parseInt(this.data[i].view)) {
          minView = this.data[i].view;
        }
      }

      percent = maxView/100;
      // Builds the nodes data array from the original data
      for (var i=0; i < this.data.length; i++) {
        var n = this.data[i];
        var res = Math.exp((this.data[i].view/5)/percent);
        var out = {
          sid: n['id'],
          radius: this.radiusScale(res),
          group: n['host_name'],
          change: n['timer'],
          changeCategory: this.categorizeChange(n['timer']),
          value: n['view'],
          url: n['url'],
          avg: n['avg'],
          positions: n.positions,
          x:Math.random() * 1000,
          y:Math.random() * 1000
        }
        if (n.positions.total) {
          out.x = n.positions.total.x + (n.positions.total.x - (that.width / 2)) * 0.5;
          out.y = n.positions.total.y + (n.positions.total.y - (150)) * 0.5;
        };
        if (n.order) {
          out['order'] = n.order;
        };
        if (n['type']) {
          out['type'] = n['type'];
        };
        this.nodes.push(out)
      };

      this.nodes.sort(function(a, b){  
        return Math.abs(b.value) - Math.abs(a.value);  
      });
      
      this.svg = d3.select("#nytg-chartCanvas").append("svg:svg")
      .attr("width", this.width);
      
      for (var i=0; i < this.changeTickValues.length; i++) {
        d3.select("#nytg-discretionaryOverlay").append("div")
        .html("<p>"+this.changeTickValues[i]+"/"+this.changeTickValues.length+"</p>")
        .style("top", this.changeScale(this.changeTickValues[i])+'px')
        .classed('nytg-discretionaryTick', true)
        .classed('nytg-discretionaryZeroTick', (this.changeTickValues[i] === 0) )
      };

      d3.select("#nytg-discretionaryOverlay").append("div")
      .html("<p></p>")
      .style("top", this.changeScale(0)+'px')
      .classed('nytg-discretionaryTick', true)
      .classed('nytg-discretionaryZeroTick', true)
      
      // $ 100 billion
      d3.select("#nytg-scaleKey").append("circle")
      .attr('r', this.radiusScale(10000000))
      .attr('class',"nytg-scaleKeyCircle")
      .attr('cx', 30)
      .attr('cy', 40);

      // $ 10 billion
      d3.select("#nytg-scaleKey").append("circle")
      .attr('r', this.radiusScale(1000000))
      .attr('class',"nytg-scaleKeyCircle")
      .attr('cx', 30)
      .attr('cy', 50);

      // $ 1 billion
      d3.select("#nytg-scaleKey").append("circle")
      .attr('r', this.radiusScale(100000))
      .attr('class',"nytg-scaleKeyCircle")
      .attr('cx', 30)
      .attr('cy', 55);

      // This is the every circle
      this.circle = this.svg.selectAll("circle")
      .data(this.nodes, function(d) { return d.sid; });

      this.circle.enter().append("svg:circle")
      .attr("r", function(d) { return 0; } )
      .style("fill", function(d) { return that.getFillColor(d); } )
      .style("stroke-width", 1)
      .attr('id',function(d){ return 'nytg-circle'+d.sid })
      .style("stroke", function(d){ return that.getStrokeColor(d); })
      .on("mouseover",function(d,i) { 
        var el = d3.select(this)
        var xpos = Number(el.attr('cx'))
        var ypos = (el.attr('cy') - d.radius - 10)
        el.style("stroke","#000").style("stroke-width",3);
        d3.select("#nytg-tooltip").style('top',ypos+"px").style('left',xpos+"px").style('display','block')
        .classed('nytg-plus', (d.changeCategory > 0))
        .classed('nytg-minus', (d.changeCategory < 0));
        
        var $j = jQuery;

        d3.select("#nytg-tooltip .nytg-url").html(that.nameFormat(d.url.substr(0, 35)+"..."))
        d3.select("#nytg-tooltip .nytg-domain").text(d.group)
        var url = new URL(d.url)

        $j("#nytg-tooltip .nytg-logo").html('<img class="icon" src="'+url.protocol+"//"+url.hostname+"/favicon.ico"+'" alt="icon site" style="width: 40px;" onerror="this.remove()" />')
        if (d.value == 1) {
          d3.select("#nytg-tooltip .nytg-value").html(that.bigFormat(d.value)+' view') 
        }
        else{
          d3.select("#nytg-tooltip .nytg-value").html(that.bigFormat(d.value)+' views')
        }
      })

      .on("mouseout",function(d,i) { 
        d3.select(this)
        .style("stroke-width",1)
        .style("stroke", function(d){ return that.getStrokeColor(d); })
        d3.select("#nytg-tooltip").style('display','none')})

      .on("click", function(d) {
        var win = window.open(d.url, '_blank');
        win.focus();
      });

      this.circle.transition().duration(2000).attr("r", function(d){return d.radius})
    },

    start: function() {
      var that = this;

      this.force = d3.layout.force()
      .nodes(this.nodes)
      .size([this.width, this.height])

      // this.circle.call(this.force.drag)
    },
    
    totalLayout: function() {
      var that = this;
      this.force
      .gravity(-0.01)
      .charge(that.defaultCharge)
      .friction(0.9)
      .on("tick", function(e){
        that.circle
        .each(that.totalSort(e.alpha))
        .each(that.buoyancy(e.alpha))
        .attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
      })
      .start();     
    },

    /*mandatoryLayout: function() {
      var that = this;
      this.force
      .gravity(0)
      .friction(0.9)
      .charge(that.defaultCharge)
      .on("tick", function(e){
        that.circle
        .each(that.mandatorySort(e.alpha))
        .each(that.buoyancy(e.alpha))
        .attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
      })
      .start();   
    },*/

    discretionaryLayout: function() {
      $j('#navBarGraph')

      var that = this;
      this.force
      .gravity(0)
      .charge(0)
      .friction(0.2)
      .on("tick", function(e){
        that.circle
        .each(that.discretionarySort(e.alpha))
        .attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
      })
      .start();
    },
    
    // ----------------------------------------------------------------------------------------
    // FORCES
    // ----------------------------------------------------------------------------------------

    totalSort: function(alpha) {
      var that = this;
      return function(d){
        var targetY = that.centerY;
        var targetX = that.width / 2;
        
        d.y = d.y + (targetY - d.y) * (that.defaultGravity + 0.02) * alpha
        d.x = d.x + (targetX - d.x) * (that.defaultGravity + 0.02) * alpha
        
      };
    },

    buoyancy: function(alpha) {
      var that = this;
      return function(d){        
        var targetY = that.centerY - (d.changeCategory / 3) * that.boundingRadius
        d.y = d.y + (targetY - d.y) * (that.defaultGravity) * alpha * alpha * alpha * 100
      };
    },

    mandatorySort: function(alpha) {
      var that = this;
      return function(d){
        var targetY = that.centerY;
        var targetX = 0;

        if (d.changeCategory > 0) {
          d.x = - 200
        } else {
          d.x =  1100
        }
        return;

        if (d.discretion === that.DISCRETIONARY) {
          targetX = 550
        } else if ((d.discretion === that.MANDATORY)||(d.discretion === that.NET_INTEREST)) {
          targetX = 400
        } else {
          targetX = 900
        };

        d.y = d.y + (targetY - d.y) * (that.defaultGravity) * alpha * 1.1
        d.x = d.x + (targetX - d.x) * (that.defaultGravity) * alpha * 1.1
      };
    },

    discretionarySort: function(alpha) {
      var that = this;
      return function(d){
        if (d['type'] == 'view') {
          if (d.order == undefined) {
            lastX = 0*(870/nb_site_view)+(60+d.radius);  
          }
          else {
            lastX = d.order*(870/nb_site_time)+(60+d.radius);
          }
          lastY = 260;
        }
        else {
          if (d.order == undefined) {
            lastX = 0*(870/nb_site_view)+(60+d.radius);  
          }
          else {
            lastX = d.order*(870/nb_site_time)+(60+d.radius);
          }
          lastY = 440;
        }

        var speedX = (lastX - d.x)/10;
        var speedY = (lastY - d.y)/10;

        if (lastX >= d.x && lastX - d.x <= 0.5) {
          speedX = 0;
        }
        else if (lastX <= d.x && lastX - d.x >= 0.5) {
          speedX = 0; 
        }

        if (lastY >= d.y && lastY - d.y <= 0.5) {
          speedY = 0;
        }
        else if (lastY <= d.y && lastY - d.y >= 0.5) {
          speedY = 0; 
        }

        d.x = d.x + speedX;
        d.y = d.y + speedY;
      };
    },
  }
};

/********************************
 ** FILE: ChooseList.js
 ********************************/

 var nytg = nytg || {};
 var $j = jQuery;

 nytg.ChooseList = function(node, changeCallback) {
  this.container = $j(node);
  this.selectedNode = null;
  this.currentIndex = null;
  this.onChange = changeCallback;
  this.elements = this.container.find('li');
  this.container.find('li').on('click',$j.proxy(this.onClickHandler, this));
  this.selectByIndex(0);
};

nytg.ChooseList.prototype.onClickHandler = function(evt) {
  evt.preventDefault();
  this.selectByElement(evt.currentTarget);
};


nytg.ChooseList.prototype.selectByIndex = function(i) {
  this.selectByElement(this.elements[i])
};


nytg.ChooseList.prototype.selectByElement = function(el) {
  if (this.selectedNode) {
    $j(this.selectedNode).removeClass("selected");
  };
  $j(el).addClass("selected");
  for (var i=0; i < this.elements.length; i++) {
    if (this.elements[i] === el) {
      this.currentIndex = i;
    }
  };
  this.selectedNode = el;
  this.onChange(this);
};

/********************************
 ** FILE: base.js
 ********************************/

 /*var $j = jQuery;

 nytg.filename = function(index){
  var tabs = [
  "total",
  "mandatory",
  "discretionary",
  "department"];
  return tabs[index];
}
$j("#save").click(function(){
  $j.ajax({ 
    type: "POST", 
    url: "/save", 
    data: {
      'filename':nytg.filename(nytg.mainNav.currentIndex),
      'contents':nytg.c.getCirclePositions()
    }
  });
  
})*/

nytg.ready = function() {
  var that = this;    
  nytg.c = new nytg.Chart();
  nytg.c.init();
  //nytg.c.start();

  this.highlightedItems = [];
  
  // nytg.s = new nytg.SearchBox("nytg-search", nytg.array_webSites, "name", "department", "budget_2012", "id");
  // nytg.s.findCallback = function(evt){
  //   var foundId = evt.id;    
  //   
  //   for (var i=0; i < that.highlightedItems.length; i++) {
  //     $j("#nytg-circle"+that.highlightedItems[i]).css({'stroke-width':1});
  //   };
  // 
  //   that.highlightedItems = [evt.id];
  //   for (var i=0; i < that.highlightedItems.length; i++) {
  //     $j("#nytg-circle"+that.highlightedItems[i]).css({'stroke-width':20});
  //   };
  //   
  // }

  var currentOverlay = undefined;
  nytg.mainNav = new nytg.ChooseList($j(".nytg-navigation"), onMainChange);
  function onMainChange(evt) {
    //console.log(this)
    var tabIndex = evt.currentIndex
    if (this.currentOverlay !== undefined) {
      this.currentOverlay.hide();
    };
    if (tabIndex === 0) {
      $j('svg').remove();
      nytg.c.update(nytg.array_webSites);
      nytg.c.start();
      nytg.c.totalLayout();
      //eventFire($j("#nytg-totalOverlay"), 'click');
      //console.log('totalLayout');
      this.currentOverlay = $j("#nytg-totalOverlay");
      this.currentOverlay.delay(300).fadeIn(500);
      $j("#nytg-chartFrame").css({'height':550});
    } /*else if (tabIndex === 1){
      $j('svg').remove();
      nytg.c.update(nytg.array_webSites);
      nytg.c.start();
      nytg.c.mandatoryLayout();
      //console.log('mandatoryLayout');
      this.currentOverlay = $j("#nytg-mandatoryOverlay");
      this.currentOverlay.delay(300).fadeIn(500);
      $j("#nytg-chartFrame").css({'height':550});
    }*/ else if (tabIndex === 1){
      $j('svg').remove();
      nytg.c.update(nytg.array_best_median);
      nytg.c.start();
      nytg.c.discretionaryLayout();
      //console.log('discretionaryLayout');
      this.currentOverlay = $j("#nytg-discretionaryOverlay");
      this.currentOverlay.delay(300).fadeIn(500);
      $j("#nytg-chartFrame").css({'height':650});
    }
  }
}

if (!!document.createElementNS && !!document.createElementNS('http://www.w3.org/2000/svg', "svg").createSVGRect){
  $j(document).ready($j.proxy(nytg.ready, this));
} else {
  $j("#nytg-chartFrame").hide();
  // $j("#nytg-error").show();
}

$j('.sorts').click(function() {
  jQuery.noConflict();
  var $j = jQuery;
  //console.log(nytg.array_webSites);
  nytg.array_webSites = [];
  //console.log(nytg.array_webSites);
  var checkedQuestions = [];
  var checkedNotes = [];

  $j('.questionFilter').each(function(index) {
    if (this.checked) {
      checkedQuestions.push(index);
    }
  });
  $j('.notesFilter').each(function(index) {
    if (this.checked) {
      checkedNotes.push(index);
    }
  });

  $j.each(tabData, function(indexData) {
    var check = false;
    var questionNum = JSON.parse(tabData[indexData]['question']);
    $j.each(questionNum, function(indexQuestNum) {
      if (checkedQuestions.length > 0) {
        //console.log(checkedNotes);
        if (checkedNotes.length > 0) {
          $j.each(checkedQuestions, function(indexQuestions) {
            if(JSON.parse(questionNum[indexQuestNum]['question']) == (checkedQuestions[indexQuestions]+1)){
              $j.each(checkedNotes, function(indexNotes) {
                //console.log(tabData[indexData]['avg']);
                if((tabData[indexData]['avg'] >= checkedNotes[indexNotes]+minAvg && tabData[indexData]['avg'] < (checkedNotes[indexNotes]+1+minAvg ))){
                  check = true;
                }
              });
            }
          });
        }
        else{
          $j.each(checkedQuestions, function(indexQuestions) {
            if(JSON.parse(questionNum[indexQuestNum]['question']) == (checkedQuestions[indexQuestions]+1)){
              check = true;
            }
          });
        }
      }
      else{
        $j.each(checkedNotes, function(indexNotes) {
          //console.log(tabData[indexData]['avg']);
          if((tabData[indexData]['avg'] >= checkedNotes[indexNotes]+minAvg && tabData[indexData]['avg'] < (checkedNotes[indexNotes]+1+minAvg ))){
            check = true;
          }
        });
      }
    });

    if (check) {
      nytg.array_webSites.push(tabData[indexData]);
    }
  });

  if (!!document.createElementNS && !!document.createElementNS('http://www.w3.org/2000/svg', "svg").createSVGRect){
    if ($j("#navBarGraph").find('.selected')[0].id == 'nytg-nav-all') {
      $j('svg').remove()
      nytg.c.update(nytg.array_webSites);
      nytg.c.start();
      nytg.c.totalLayout();
    };
  } else {
    $j("#nytg-chartFrame").hide();
  // $j("#nytg-error").show();
}

});
});