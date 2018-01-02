// JavaScript Document
//sort function
function SortOptions(id) {
    var prePrepend = "#";
    if (id.match("^#") == "#") prePrepend = "";
    $(prePrepend + id).html($(prePrepend + id + " option").sort(
        function (a, b) { return a.text == b.text ? 0 : a.text < b.text ? -1 : 1 })
    );
}
//To Select All the retailer details  based on  their selected values based on franchisee


//To Select All the branch and their selected values based on region
function drpfunc()
{
	var e = document.getElementById("region");
	var RetailerCodelist = document.getElementById('emplist');	
	var RetailerName = document.getElementById('branch');
	
	RetailerName.options.length = 0;
		for (j=0;j<e.options.length;j++) {
			if (e.options[j].selected) {
			var franchisecode = e.options[j].value;

				if(franchisecode==0)
				{
						for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
					
				}
				for (i = 0; i < RetailerCodelist.options.length; i++) 
					{
						optvalue = RetailerCodelist.options[i].value;
						var lstval=optvalue.split("~");
						if(lstval[0]==franchisecode)
						{
							RetailerName.options.add(new Option(lstval[1],lstval[1]));
						}
					}
			}
		}
   RetailerName.options.add(new Option('----Select----','0'));
	var usedNames = {};
	$("select[id='branch'] > option").each(function () {
	if(usedNames[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNames[this.text] = this.value;
	}
	});
SortOptions("branch");
}
//To Select All the franchise and their selected values based on branch
function drpfunc12()
{
	var e = document.getElementById("branch");
	var RetailerCodelist = document.getElementById('forlist');
	var RetailerName = document.getElementById('franchise');
		
	//var str="",j;
RetailerName.options.length = 0;
for (j=0;j<e.options.length;j++) {
    if (e.options[j].selected) {
     
	//strUser = e.selectedIndex;
	var franchisecode = e.options[j].value;
		if(franchisecode==0)
				{
					for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
		
				}
				
				for (i = 0; i < RetailerCodelist.options.length; i++) 
		{
			optvalue = RetailerCodelist.options[i].value;
			
			var lstval=optvalue.split("~");
			
			if(lstval[0]==franchisecode && lstval[1]!='')
			{
				RetailerName.options.add(new Option(lstval[2],lstval[1]));
				
			}
		}
		}
		  // str = str + i + " ";
    }
	  RetailerName.options.add(new Option('----Select----','0'));
	var usedNames = {};
	$("select[id='franchise'] > option").each(function () {
	if(usedNames[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNames[this.text] = this.value;
	}
	});
	SortOptions("franchise");
	
}

function drpfunc1()
{
	var e = document.getElementById("branch");
	var RetailerCodelist = document.getElementById('forlist');
	var RetailerName = document.getElementById('franchise');
		
	//var str="",j;
RetailerName.options.length = 0;
for (j=0;j<e.options.length;j++) {
    if (e.options[j].selected) {
     
	//strUser = e.selectedIndex;
	var franchisecode = e.options[j].value;
		if(franchisecode==0)
				{
					for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
		
				}
				
				for (i = 0; i < RetailerCodelist.options.length; i++) 
		{
			optvalue = RetailerCodelist.options[i].value;
			
			var lstval=optvalue.split("~");
			
			if(lstval[0]==franchisecode && lstval[1]!='')
			{
				RetailerName.options.add(new Option(lstval[1],lstval[1]));
				
			}
		}
		}
		  // str = str + i + " ";
    }
	  RetailerName.options.add(new Option('.All Franchisees.','0'));
	var usedNames = {};
	$("select[id='franchise'] > option").each(function () {
	if(usedNames[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNames[this.text] = this.value;
	}
	});
	SortOptions("franchise");
}

//To Select All the Productsegment and their selected values based on Productgroup
function drpfunc2()
{
		var e = document.getElementById("Productgroup");
	var RetailerCodelist = document.getElementById('productslist1');
	var RetailerName = document.getElementById('Productsegment');	
	//var str="",j;
RetailerName.options.length = 0;
for (j=0;j<e.options.length;j++) {
    if (e.options[j].selected) {
     
	//strUser = e.selectedIndex;
	var franchisecode = e.options[j].value;
		if(franchisecode==0)
		{
			for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
		}
				for (i = 0; i < RetailerCodelist.options.length; i++) 
				{
					optvalue = RetailerCodelist.options[i].value;
					
					var lstval=optvalue.split("~");
					
					if(lstval[0]==franchisecode && lstval[1]!='')
					{
						RetailerName.options.add(new Option(lstval[1],lstval[1]));
						
					}
				}
				
		}
		  // str = str + i + " ";
    }
	 RetailerName.options.add(new Option('.All Productsegment.','0'));
	var usedNames = {};
	$("select[id='Productsegment'] > option").each(function () {
	if(usedNames[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNames[this.text] = this.value;
	}
	});
	SortOptions("Productsegment");
}
//To Select All the Producttype and their selected values based on Productsegment
function drpfunc3()
{
	var e = document.getElementById("Productsegment");
	var RetailerCodelist = document.getElementById('productsseglist1');
	var RetailerName = document.getElementById('Producttype');	
	//var str="",j;
	RetailerName.options.length = 0;
	for (j=0;j<e.options.length;j++) {
		if (e.options[j].selected) {
     
	//strUser = e.selectedIndex;
	var franchisecode = e.options[j].value;
		if(franchisecode==0)
		{
			for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
		}
				for (i = 0; i < RetailerCodelist.options.length; i++) 
				{
					optvalue = RetailerCodelist.options[i].value;
					
					var lstval=optvalue.split("~");
					
					if(lstval[0]==franchisecode && lstval[1]!='')
					{
						RetailerName.options.add(new Option(lstval[1],lstval[1]));
						
					}
				}
				
		}
		  // str = str + i + " ";
    }
	 RetailerName.options.add(new Option('.All Producttype.','0'));
	var usedNames = {};
	$("select[id='Producttype'] > option").each(function () {
	if(usedNames[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNames[this.text] = this.value;
	}
	});
	SortOptions("Producttype");
}
//To Select All the product code and their selected values based on product type
function drpfunc4()
{
	
	var e = document.getElementById("Producttype");
	var RetailerCodelist = document.getElementById('productscodlist');
	var RetailerName = document.getElementById('productcode');	
	
	//var str="",j;
	RetailerName.options.length = 0;
	for (j=0;j<e.options.length;j++) {
	if (e.options[j].selected) {
     
	//strUser = e.selectedIndex;
	var franchisecode = e.options[j].value;
		if(franchisecode==0)
		{
			for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
		}
			for (i = 0; i < RetailerCodelist.options.length; i++) 
			{
				optvalue = RetailerCodelist.options[i].value;
				var lstval=optvalue.split("~");
				if(lstval[0]==franchisecode && lstval[1]!='')
				{
					RetailerName.options.add(new Option(lstval[1],lstval[1]));
				}
			}
		}
		  // str = str + i + " ";
    }
	 RetailerName.options.add(new Option('.All Products.','0'));
	var usedNames = {};
	$("select[id='productcode'] > option").each(function () {
	if(usedNames[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNames[this.text] = this.value;
	}
	});
	SortOptions("productcode");
}

//To Select All the franchisee
function allfranchisee()
{
	
	var e = document.getElementById("franchise");
	for (j=0;j<e.options.length;j++) {
	if (e.options[j].selected) {
	var franchisecode = e.options[j].value;
		if(franchisecode==0)
		{
			for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
		}
	}
	}
}

//To Select All the productcode
function allproductcode()
{
	
	var e = document.getElementById("productcode");
	for (j=0;j<e.options.length;j++) {
	if (e.options[j].selected) {
	var franchisecode = e.options[j].value;
		if(franchisecode==0)
		{
			for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
		}
	}
	}
}
//To Select All the Voucher
function allVoucher()
{
	
	var e = document.getElementById("Voucher");
	for (j=0;j<e.options.length;j++) {
	if (e.options[j].selected) {
	var franchisecode = e.options[j].value;
		if(franchisecode==0)
		{
			for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
		}
	}
	}
}
//To Select All the Vechile Model
function allmodelname()
{
	var e = document.getElementById("modelname");
	for (j=0;j<e.options.length;j++) {
	if (e.options[j].selected) {
	var franchisecode = e.options[j].value;
		if(franchisecode==0)
		{
			for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
		}
	}
	}
}
//To Select All the Decision
function allDecision()
{
	
	var e = document.getElementById("Decision");
	for (j=0;j<e.options.length;j++) {
	if (e.options[j].selected) {
	var franchisecode = e.options[j].value;
		if(franchisecode==0)
		{
			for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
		}
	}
	}
}


//To Select All the Failure mode
function allfailuremodedescription()
{
	
	var e = document.getElementById("failuremodedescription");
	for (j=0;j<e.options.length;j++) {
	if (e.options[j].selected) {
	var franchisecode = e.options[j].value;
		if(franchisecode==0)
		{
			for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
		}
	}
	}
}


//To Select All the branch and their selected values based on region

function drpfuncretailer()
{
	
	var e = $("#franchise").val();
	if( e != 0){
	var RetailerCodelist = document.getElementById('RetailerSelect');	
	//console.log("'RetailerName"+idcount.toString()+"'");
	var RetailerName = document.getElementById("RetailerName");
	/* var searchstring = hello;
	var searchlength = hello.length;
	searchstring = searchstring.toLowerCase() */;
	RetailerName.options.length = 0;
		//	console.log(searchstring);
			//if(searchlength >= 3){
				for (i = 0; i < RetailerCodelist.options.length; i++) 
					{
						optvalue = RetailerCodelist.options[i].value;
						
						var lstval=optvalue.split("~");
						var rename = lstval[0].toLowerCase();
						if(rename.search(searchstring) != -1)
						{
							
							RetailerName.options.add(new Option(lstval[0],lstval[0]));
						
							/* RetailerCategory.options.add(new Option(lstval[2],lstval[2]));
							rc1.options.add(new Option(lstval[6],lstval[6]));
							rc2.options.add(new Option(lstval[7],lstval[7]));
							rc3.options.add(new Option(lstval[8],lstval[8]));
							rclass.options.add(new Option(lstval[4],lstval[4]));
							franchiseeme.options.add(new Option(lstval[3],lstval[3]));
							geographical.options.add(new Option(lstval[5],lstval[5])); */
						}
					}
	RetailerName.options.add(new Option('----Select----','0'));
	var usedNames = {};
	 $("select[id='RetailerName"+idcount.toString()+"'] > option").each(function () {
	if(usedNames[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNames[this.text] = this.value;
	}
	});
//SortOptions("'RetailerName"+idcount.toString()+"'");
$("'RetailerName"+idcount.toString()+"'").show();
//}
}
}

function drpfuncretailerselect()
{
	var e = document.getElementById("franchise");
	var RetailerCodelist = document.getElementById('rclist');
	var RetailerName = document.getElementById('RetailerName');
     //var table = document.getElementById('dataTable');
    // var rowCount = table.rows.length;
	//var idcount = rowCount-2;
   // console.log("'RetailerName"+idcount.toString()+"'");
   // var srcElem = window.event.srcElement;
		// rowNum = srcElem.parentNode.parentNode.rowIndex ;
 
 //var RetailerName1 = document.getElementById('dataTable').rows[idcount].getElementsByTagName("select")[0];
// RetailerName1.options.length = 0;
 // var chosen = document.getElementById('dataTable').rows[idcount].getElementsByTagName("ul")[0];


			for (j=0;j<e.options.length;j++) {
			if (e.options[j].selected) {
			var franchisecode = e.options[j].value;
				if(franchisecode==0)
				{
						for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
					
				}
				for (i = 0; i < RetailerCodelist.options.length; i++) 
					{
						optvalue = RetailerCodelist.options[i].value;
						
						var lstval=optvalue.split("~");
						if(lstval[0]==franchisecode)
						{
							RetailerName.options.add(new Option(lstval[1],lstval[1]+'~'+lstval[2]));
						}
					}
			}
		}
		RetailerName.options.add(new Option('----Select----','0'));
	var usedNames = {};
	$("select[id='RetailerName'] > option").each(function () {
	//$("select[id='RetailerName"+idcount.toString()+"'] > option").each(function () {
	if(usedNames[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNames[this.text] = this.value;
	}
	});
SortOptions("RetailerName");
 var len = RetailerName.options.length;
 if(len > 1){
	document.getElementById("franchise").disabled=true;
	document.getElementById("branch").disabled=true;
	document.getElementById("region").disabled=true;
 }
//$("#RetailerName"+idcount.toString()+"").trigger("chosen:updated");

//SortOptions("RetailerName"+idcount.toString()+"");

}
function changedisable(){
	document.getElementById("franchise").disabled=false;
	document.getElementById("branch").disabled=false;
	document.getElementById("region").disabled=false;
}

//To Select All the franchise and their selected values based on branch--FOR MSCReport


function drpfuncmsc()
{
	var e = document.getElementById("branch");
	var RetailerCodelist = document.getElementById('forlist');
	var RetailerName = document.getElementById('franchise');
		
	//var str="",j;
RetailerName.options.length = 0;
for (j=0;j<e.options.length;j++) {
    if (e.options[j].selected) {
     
	//strUser = e.selectedIndex;
	var franchisecode = e.options[j].value;
		if(franchisecode==0)
				{
					for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
		
				}
				
				for (i = 0; i < RetailerCodelist.options.length; i++) 
		{
			optvalue = RetailerCodelist.options[i].value;
			
			var lstval=optvalue.split("~");
			
			if(lstval[0]==franchisecode && lstval[1]!='')
			{
				RetailerName.options.add(new Option(lstval[1],lstval[1]));
				
			}
		}
		}
		  // str = str + i + " ";
    }
	  RetailerName.options.add(new Option('.Select.','0'));
	var usedNames = {};
	$("select[id='franchise'] > option").each(function () {
	if(usedNames[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNames[this.text] = this.value;
	}
	});
	SortOptions("franchise");
}


