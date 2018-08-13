
/* Adds the todays marker on the graph */
var date_to_str = gantt.date.date_to_str(gantt.config.task_date);
	var today = new Date();
	gantt.addMarker({
		start_date: today,
		css: "today",
		text: "Today",
		title: "Today: " + date_to_str(today)
	});



function parseArrayOfJSON(string){
    data = [];
    while(string.indexOf("{") !== -1){
        start = string.indexOf("{");
        end = string.indexOf("}")+1;
        object = JSON.parse(string.slice(start, end));
        data.push(object);
        string = string.slice(end, string.length);
    }
    return data;
}

schoolYearId = document.getElementById("schoolYearId").getAttribute("value");


/* ADDED BY VOLODYMYR*/
if (window.XMLHttpRequest) {
    // code for modern browsers
    xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", "php/javascript/get-gantt-data.php?schoolYearId="+schoolYearId, true);
    xmlhttp.send();
 } else {
    // code for old IE browsers
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.open("GET", "php/javascript/get-gantt-data.php?schoolYearId="+schoolYearId, true);
    xmlhttp.send();
}

var data = "test";
xmlhttp.onreadystatechange = function() {
    if (this.readyState === 4 && this.status === 200) {
        var data = parseArrayOfJSON(this.responseText);
    

        tasks = {
            "data": data
        };

        
        

	gantt.config.open_tree_initially = true;
	gantt.config.readonly = true;


	gantt.config.columns =  [
    {name:"text",       label:"Period",   tree:true, min_width: 200 },
    {name:"start_date", label:"Start time", align:"center", min_width: 100 },
    {name:"end_date",   label:"End date",   align:"center" , min_width: 100 },
    {name:"duration",   label:"Duration",   align:"center" , min_width: 60 }
	];
 
 	gantt.config.date_grid = "%d-%M-%Y";

 	setScaleConfig('4');

    gantt.init("gantt_here");
    gantt.parse(tasks);

gantt.templates.tooltip_date_format=function (date){
    var formatFunc = gantt.date.date_to_str("%d-%M-%Y");
    return formatFunc(date);
};
	gantt.templates.tooltip_text = function(start,end,task){
	    return task.text+
	    "<br/><b>Start date:</b> " + gantt.templates.tooltip_date_format(start) +
	    "<br/><b>End date:</b> " + gantt.templates.tooltip_date_format(end) +
	    "<br/><b>Duration:</b> " + task.duration + " days";
	};



}
};
    var func = function (e) {
		e = e || window.event;
		var el = e.target || e.srcElement;
		var value = el.value;
		setScaleConfig(value);
		gantt.render();
	};

	var els = document.getElementsByName("scale");
	for (var i = 0; i < els.length; i++) {
		els[i].onclick = func;
	}

   function toggleMode(toggle) {
		toggle.enabled = !toggle.enabled;
		if (toggle.enabled) {
			toggle.innerHTML = "Set default Scale";
			//Saving previous scale state for future restore
			saveConfig();
			zoomToFit();
		} else {

			toggle.innerHTML = "Zoom to Fit";
			//Restore previous scale state
			restoreConfig();
			gantt.render();
		}
	}

	var cachedSettings = {};

	function saveConfig() {
		var config = gantt.config;
		cachedSettings = {};
		cachedSettings.scale_unit = config.scale_unit;
		cachedSettings.date_scale = config.date_scale;
		cachedSettings.step = config.step;
		cachedSettings.subscales = config.subscales;
		cachedSettings.template = gantt.templates.date_scale;
		cachedSettings.start_date = config.start_date;
		cachedSettings.end_date = config.end_date;
	}

	function restoreConfig() {
		applyConfig(cachedSettings);
	}

	function applyConfig(config, dates) {
		gantt.config.scale_unit = config.scale_unit;
		if (config.date_scale) {
			gantt.config.date_scale = config.date_scale;
			gantt.templates.date_scale = null;
		}
		else {
			gantt.templates.date_scale = config.template;
		}

		gantt.config.step = config.step;
		gantt.config.subscales = config.subscales;

		if (dates) {
			gantt.config.start_date = gantt.date.add(dates.start_date, -1, config.unit);
			gantt.config.end_date = gantt.date.add(gantt.date[config.unit + "_start"](dates.end_date), 2, config.unit);
		} else {
			gantt.config.start_date = gantt.config.end_date = null;
		}
	}


	function zoomToFit() {
		var project = gantt.getSubtaskDates(),
			areaWidth = gantt.$task.offsetWidth;

		for (var i = 0; i < scaleConfigs.length; i++) {
			var columnCount = getUnitsBetween(project.start_date, project.end_date, scaleConfigs[i].unit, scaleConfigs[i].step);
			if ((columnCount + 2) * gantt.config.min_column_width <= areaWidth) {
				break;
			}
		}

		if (i == scaleConfigs.length) {
			i--;
		}

		applyConfig(scaleConfigs[i], project);
		gantt.render();
	}

	// get number of columns in timeline
	function getUnitsBetween(from, to, unit, step) {
		var start = new Date(from),
			end = new Date(to);
		var units = 0;
		while (start.valueOf() < end.valueOf()) {
			units++;
			start = gantt.date.add(start, step, unit);
		}
		return units;
	}

	//Setting available scales
	var scaleConfigs = [
		// minutes
		{
			unit: "minute", step: 1, scale_unit: "hour", date_scale: "%H", subscales: [
				{unit: "minute", step: 1, date: "%H:%i"}
			]
		},
		// hours
		{
			unit: "hour", step: 1, scale_unit: "day", date_scale: "%j %M",
			subscales: [
				{unit: "hour", step: 1, date: "%H:%i"}
			]
		},
		// days
		{
			unit: "day", step: 1, scale_unit: "month", date_scale: "%F",
			subscales: [
				{unit: "day", step: 1, date: "%j"}
			]
		},
		// weeks
		{
			unit: "week", step: 1, scale_unit: "month", date_scale: "%F",
			subscales: [
				{
					unit: "week", step: 1, template: function (date) {
						var dateToStr = gantt.date.date_to_str("%d %M");
						var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
						return dateToStr(date) + " - " + dateToStr(endDate);
					}
				}
			]
		},
		// months
		{
			unit: "month", step: 1, scale_unit: "year", date_scale: "%Y",
			subscales: [
				{unit: "month", step: 1, date: "%M"}
			]
		},
		// quarters
		{
			unit: "month", step: 3, scale_unit: "year", date_scale: "%Y",
			subscales: [
				{
					unit: "month", step: 3, template: function (date) {
						var dateToStr = gantt.date.date_to_str("%M");
						var endDate = gantt.date.add(gantt.date.add(date, 3, "month"), -1, "day");
						return dateToStr(date) + " - " + dateToStr(endDate);
					}
				}
			]
		},
		// years
		{
			unit: "year", step: 1, scale_unit: "year", date_scale: "%Y",
			subscales: [
				{
					unit: "year", step: 5, template: function (date) {
						var dateToStr = gantt.date.date_to_str("%Y");
						var endDate = gantt.date.add(gantt.date.add(date, 5, "year"), -1, "day");
						return dateToStr(date) + " - " + dateToStr(endDate);
					}
				}
			]
		},
		// decades
		{
			unit: "year", step: 10, scale_unit: "year", template: function (date) {
				var dateToStr = gantt.date.date_to_str("%Y");
				var endDate = gantt.date.add(gantt.date.add(date, 10, "year"), -1, "day");
				return dateToStr(date) + " - " + dateToStr(endDate);
			},
			subscales: [
				{
					unit: "year", step: 100, template: function (date) {
						var dateToStr = gantt.date.date_to_str("%Y");
						var endDate = gantt.date.add(gantt.date.add(date, 100, "year"), -1, "day");
						return dateToStr(date) + " - " + dateToStr(endDate);
					}
				}
			]
		}
	];
 
 	function setScaleConfig(value) {
		switch (value) {
			case "1":
				gantt.config.scale_unit = "day";
				gantt.config.step = 1;
				gantt.config.date_scale = "%d %M";
				gantt.config.subscales = [];
				gantt.config.scale_height = 27;
				gantt.templates.date_scale = null;
				break;
			case "2":
				var weekScaleTemplate = function (date) {
					var dateToStr = gantt.date.date_to_str("%d %M");
					var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
					return dateToStr(date) + " - " + dateToStr(endDate);
				};

				gantt.config.scale_unit = "week";
				gantt.config.step = 1;
				gantt.templates.date_scale = weekScaleTemplate;
				gantt.config.subscales = [
					{unit: "day", step: 1, date: "%D"}
				];
				gantt.config.scale_height = 50;
				break;
			case "3":
				gantt.config.scale_unit = "month";
				gantt.config.date_scale = "%F, %Y";
				gantt.config.subscales = [
					{unit: "day", step: 1, date: "%j, %D"}
				];
				gantt.config.scale_height = 50;
				gantt.templates.date_scale = null;
				break;
			case "4":
				gantt.config.scale_unit = "year";
				gantt.config.step = 1;
				gantt.config.date_scale = "%Y";
				gantt.config.min_column_width = 50;

				gantt.config.scale_height = 90;
				gantt.templates.date_scale = null;


				gantt.config.subscales = [
					{unit: "month", step: 1, date: "%M"}
				];
				break;
		}
	}