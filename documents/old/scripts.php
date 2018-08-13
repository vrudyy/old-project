<!-- /container -->
<script src="js/bootstrap-transition.js"></script>
<script src="js/bootstrap-carousel.js"></script>
<script src="js/bootstrap-alert.js"></script>
<script src="js/bootstrap-modal.js"></script>
<script src="js/bootstrap-dropdown.js"></script>
<script src="js/bootstrap-scrollspy.js"></script>
<script src="js/bootstrap-tab.js"></script>
<script src="js/bootstrap-tooltip.js"></script>
<script src="js/bootstrap-popover.js"></script>
<script src="js/bootstrap-button.js"></script>
<script src="js/bootstrap-collapse.js"></script>
<script src="js/bootstrap-typeahead.js"></script>
<script src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script src="js/jquery.smooth-scroll.min.js"></script>
<script src="js/lightbox.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script> 
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.pt-BR.js"></script>   
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/bootstrap-table.js"></script>
<script src="js/jquery.placeholder.min.js"></script>

</script>

<script>
function hyperlinkSorter(a, b) {
    var pos;
    pos = a.indexOf(">");
    a = a.substring(pos+1);
    a = a.replace("</a>","");
    a = a.replace(" ","");

    pos = b.indexOf(">");
    b = b.substring(pos+1);
    b = b.replace("</a>","");
    b = b.replace(" ","");

    if (a < b ) return -1;
    if (a > b) return 1;
    return 0;
}
</script>

<script>
function moneySorter(a, b) {
	pos = a.indexOf("-");
	var signa = 1;
	if(pos>0)
	{
		signa = -1;
	}
	pos = b.indexOf("-");
	var signb = 1;
	if(pos>0)
	{
		signb = -1;
	}
	var a = Number(a.replace(/[^0-9\.]+/g,""))*signa;
	var b = Number(b.replace(/[^0-9\.]+/g,""))*signb;

    if (a < b ) return -1;
    if (a > b) return 1;
    return 0;
}
</script>

<script>
function perCentSorter(a, b) {
	pos = a.indexOf(">");
    a = a.substring(pos+1);
    a = a.replace("</font>","");

    pos = b.indexOf(">");
    b= b.substring(pos+1);
    b = b.replace("</font>","");

	pos = a.indexOf("-");
	var signa = 1;
	if(pos>0)
	{
		signa = -1
	}

	pos = b.indexOf("-");
	var signb = 1;
	if(pos>0)
	{
		signb = -1
	}

	var a = Number(a.replace(/%/g,""))*signa;
	var b = Number(b.replace(/%/g,""))*signb;

    if (a < b ) return -1;
    if (a > b) return 1;
    return 0;
}
</script>

<script>
function dateFromStringSorter(a, b) {

	pos = a.indexOf(">");
    a = a.substring(pos+1);
    a = a.replace("</font>","");
    a = a.split(" ");

    a[1] = a[1].replace("th","");
    a[1] = a[1].replace("st","");
    a[1] = a[1].replace("nd","");

	var string1 =a[2]+" "+a[1]+", "+a[3];
    var date1 = new Date(string1);
	
	pos = b.indexOf(">");
    b = b.substring(pos+1);
    b = b.replace("</font>","");
    b = b.split(" ");

    b[1] = b[1].replace("th","");
    b[1] = b[1].replace("st","");
    b[1] = b[1].replace("nd","");

	var string2 =b[2]+" "+b[1]+", "+b[3];
    var date2 = new Date(string2);
	

    if (date1 < date2 ) return -1;
    if (date1 > date2) return 1;
    return 0;
}
</script>

<script type="text/javascript">
$('#datetimepicker').datetimepicker({
pickTime: false
});
</script>

<script type="text/javascript">
$('#tasksTable').bootstrapTable({
});
</script>

<script type="text/javascript">
$('#studentsTable').bootstrapTable({
});
</script>

<script type="text/javascript">
$('#classesProfitabilityTable').bootstrapTable({
});
</script>

<script type="text/javascript">
$('#prospectsTable').bootstrapTable({
});
</script>

<script type="text/javascript">
$('#classesTable').bootstrapTable({
});
</script>

<script type="text/javascript">
$('#coursesTable').bootstrapTable({
});
</script>

<script type="text/javascript">
$('#qualificationsTable').bootstrapTable({
});
</script>

<script type="text/javascript">
$('#bankStatementTable').bootstrapTable({
});
</script>

<script type="text/javascript">
$('#contactsTable').bootstrapTable({
});
</script>

<script type="text/javascript">
// When the document is ready
	$(document).ready(function ()
	{
		$('#datepicker1').datepicker({
			format: "dd MM yyyy"
		}); 
		$('#datepicker2').datepicker({
			format: "dd MM yyyy"
		}); 
		$('#datepicker3').datepicker({
			format: "dd MM yyyy"
		}); 
		$('#datepicker4').datepicker({
			format: "dd MM yyyy"
		}); 
		$('#datepickerATS').datepicker({
			format: "yyyy-mm-dd"
		}); 
		$('#datepickerATF').datepicker({
			format: "yyyy-mm-dd"
		}); 
		$('#datepickerWTS').datepicker({
			format: "yyyy-mm-dd"
		});   
		$('#datepickerWTF').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#datepickerSTS').datepicker({
			format: "yyyy-mm-dd"
		}); 
		$('#datepickerSTF').datepicker({
			format: "yyyy-mm-dd"
		});

		$('#datepickerSMTS').datepicker({
			format: "yyyy-mm-dd"
		}); 
		$('#datepickerSMTF').datepicker({
			format: "yyyy-mm-dd"
		});

		$('#datepickerCHS').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#datepickerCHF').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#datepickerOHTS').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#datepickerOHTF').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#datepickerDHTS').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#datepickerDHTF').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#datepickerFHTS').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#datepickerFHTF').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#datepickerEHS').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#datepickerEHF').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#datepickerMHTS').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#datepickerMHTF').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#examsDate').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#testDate').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#absenceDate').datepicker({
			format: "yyyy-mm-dd"
		});
		$('#classDate').datepicker({
			format: "dd-mm-yyyy"
		});
	    $('#birthday').datepicker({
			format: "dd MM yyyy"
		});	
		$('.ScheduledClassDate').datepicker({
			format: "dd-mm-yyyy"
		});
		$('#taskDeadline').datepicker({
			format: "dd-mm-yyyy"
		});
	});
</script>