<?php include('menu.php');?>


<script type="text/javascript">
function validateForm() {

	var x = document.forms["newSchoolYearForm"]["SchoolYear"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the name for the School Year");
        return false;
    }
    
    x = document.forms["newSchoolYearForm"]["datepickerSMTS"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the Summer Term Start");
        return false;
    }

    x = document.forms["newSchoolYearForm"]["datepickerSMTF"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the Summer Term Finish");
        return false;
    }


    x = document.forms["newSchoolYearForm"]["datepickerATS"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the Autumn Term Start");
        return false;
    }

    x = document.forms["newSchoolYearForm"]["datepickerCHS"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the Christmas Holidays Start");
        return false;
    }

    x = document.forms["newSchoolYearForm"]["datepickerCHF"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the Christmas Holidays Finish");
        return false;
    }

     x = document.forms["newSchoolYearForm"]["datepickerEHS"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the Easter Holidays Start");
        return false;
    }

    x = document.forms["newSchoolYearForm"]["datepickerEHF"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the Easter Holidays Finish");
        return false;
    }

 	x = document.forms["newSchoolYearForm"]["datepickerATF"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the Autumn Term Finish");
        return false;
    }

	x = document.forms["newSchoolYearForm"]["datepickerWTS"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the Winter Term Start");
        return false;
    }
   
    x = document.forms["newSchoolYearForm"]["datepickerWTF"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the Winter Term Finish");
        return false;
    }

    x = document.forms["newSchoolYearForm"]["datepickerSTS"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the Spring Term Start");
        return false;
    }

    x = document.forms["newSchoolYearForm"]["datepickerSTF"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the Spring Term Finish");
        return false;
    }

    x = document.forms["newSchoolYearForm"]["datepickerOHTS"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the October Half Term Start");
        return false;
    }

    x = document.forms["newSchoolYearForm"]["datepickerOHTF"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the October Half Term Finish");
        return false;
    }

     x = document.forms["newSchoolYearForm"]["datepickerDHTS"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the December Half Term Start");
        return false;
    }

    x = document.forms["newSchoolYearForm"]["datepickerDHTF"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the December Half Term Finish");
        return false;
    }

     x = document.forms["newSchoolYearForm"]["datepickerFHTS"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the February Half Term Start");
        return false;
    }

    x = document.forms["newSchoolYearForm"]["datepickerFHTF"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the February Half Term Finish");
        return false;
    }

     x = document.forms["newSchoolYearForm"]["datepickerMHTS"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the May Half Term Start");
        return false;
    }

    x = document.forms["newSchoolYearForm"]["datepickerMHTF"].value;
    if ( x==null || x=="" ) {
        alert("Please provide the date for the May Half Term Finish");
        return false;
    }
}
</script>

<body style="margin: 20px; padding: 20px"> 
<h1>Add a School Year</h1>

<form name="newSchoolYearForm" method="post"  onsubmit="return validateForm()" action="do_addSchoolYear.php" enctype="multipart/form-data">   

<table border="0"  class="table table-striped table-condensed" >
<caption><h3>School Year Dates (All fields are required)</h3></caption>
<tr>
  <td><input type="text" class="form-control" name="SchoolYear" style="width:100px;" placeholder="School Year (eg 2014-2015)"></td>
  <td></td>
</tr>
<tr>
  <td><input  type="text" placeholder="Summer Term Start"  id="datepickerSMTS" name="datepickerSMTS"></td> 
  <td><input  type="text" placeholder="Summer Term Finish"  id="datepickerSMTF" name="datepickerSMTF"></td>
</tr>
<tr>
  <td><input  type="text" placeholder="Autumn Term Start"  id="datepickerATS" name="datepickerATS"></td> 
  <td><input  type="text" placeholder="Autumn Term Finish"  id="datepickerATF" name="datepickerATF"></td>
</tr>
<tr>
 <td><input  type="text" placeholder="Winter Term Start"  id="datepickerWTS" name="datepickerWTS"></td> 
 <td><input  type="text" placeholder="Winter Term Finish"  id="datepickerWTF" name="datepickerWTF"></td>
</tr>
<tr>
  <td><input  type="text" placeholder="Spring Term Start"  id="datepickerSTS" name="datepickerSTS"></td> 
  <td><input  type="text" placeholder="Spring Term Finish"  id="datepickerSTF" name="datepickerSTF"></td>
</tr>  
<tr>
  <td><input  type="text" placeholder="Christmas Holidays Start"  id="datepickerCHS" name="datepickerCHS"></td> 
  <td><input  type="text" placeholder="Christmas Holidays Finish"  id="datepickerCHF" name="datepickerCHF"></td>
</tr>  
<tr>
  <td><input  type="text" placeholder="Easter Holidays Start"  id="datepickerEHS" name="datepickerEHS"></td> 
  <td><input  type="text" placeholder="Easter Holidays Finish"  id="datepickerEHF" name="datepickerEHF"></td>
</tr>  

</table>


<table border="0"  class="table table-striped table-condensed" >
<caption><h3>Half Terms (All fields are required)</h3></caption>
<tr>
  <td><input  type="text" placeholder="October Half Term Start"  id="datepickerOHTS" name="datepickerOHTS"></td> 
  <td><input  type="text" placeholder="October Half Term Finish"  id="datepickerOHTF" name="datepickerOHTF"></td>
</tr>
<tr>
  <td><input  type="text" placeholder="December Half Term Start"  id="datepickerDHTS" name="datepickerDHTS"></td> 
  <td><input  type="text" placeholder="December Half Term Finish"  id="datepickerDHTF" name="datepickerDHTF"></td>
</tr>    

<tr>
  <td><input  type="text" placeholder="February Half Term Start"  id="datepickerFHTS" name="datepickerFHTS"></td> 
  <td><input  type="text" placeholder="February Half Term Finish"  id="datepickerFHTF" name="datepickerFHTF"></td>
</tr>
<tr>
  <td><input  type="text" placeholder="May Half Term Start"  id="datepickerMHTS" name="datepickerMHTS"></td> 
  <td><input  type="text" placeholder="May Half Term Finish"  id="datepickerMHTF" name="datepickerMHTF"></td>
</tr>   

</table>

<button type="submit" class="btn btn-primary">Add School Year</button>
<button type="reset" class="btn btn-mini">Cancel</button>

<br>
<?php include('footer.php');?>
</form>