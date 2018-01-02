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
							RetailerName.options.add(new Option(lstval[2],lstval[1]));
						}
					}
			}
		}
		 
   RetailerName.options.add(new Option('.All Branches.','0'));
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
	  RetailerName.options.add(new Option('.All Distributor.','0'));
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
	var RetailerCodelist = document.getElementById('productlist');
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
     alert(e.options[j].selected);
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
	var e = document.getElementById("franchise");
	var RetailerCodelist = document.getElementById('rclist');	
	var RetailerName = document.getElementById('RetailerName');
	var RetailerCategory = document.getElementById('RetailerCategory');
	/* var rc1 = document.getElementById('rc1');
	var rc2 = document.getElementById('rc2');
	var rc3 = document.getElementById('rc3');
	var rclass = document.getElementById('rclass');
	var franchiseeme = document.getElementById('franchiseeme');
	var geographical = document.getElementById('geographical'); */
	RetailerName.options.length = 0;
	RetailerCategory.options.length = 0;
	/* rc1.options.length = 0;
	rc2.options.length = 0;
	rc3.options.length = 0;
	rclass.options.length = 0;
	franchiseeme.options.length = 0;
	geographical.options.length = 0; */
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
							RetailerCategory.options.add(new Option(lstval[2],lstval[2]));
							/* rc1.options.add(new Option(lstval[6],lstval[6]));
							rc2.options.add(new Option(lstval[7],lstval[7]));
							rc3.options.add(new Option(lstval[8],lstval[8]));
							rclass.options.add(new Option(lstval[4],lstval[4]));
							franchiseeme.options.add(new Option(lstval[3],lstval[3]));
							geographical.options.add(new Option(lstval[5],lstval[5])); */
						}
					}
			}
		}
		 
   RetailerName.options.add(new Option('.All Retailer Name.','0'));
	var usedNames = {};
	$("select[id='RetailerName'] > option").each(function () {
	if(usedNames[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNames[this.text] = this.value;
	}
	});
SortOptions("RetailerName");

  RetailerCategory.options.add(new Option('.All Retailer Category.','0'));
	var usedNamescat = {};
	$("select[id='RetailerCategory'] > option").each(function () {
	if(usedNamescat[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNamescat[this.text] = this.value;
	}
	});
SortOptions("RetailerCategory");
/* 
   rc1.options.add(new Option('.All Retailer Category 1.','0'));
	var usedNamesrc1 = {};
	$("select[id='rc1'] > option").each(function () {
	if(usedNamesrc1[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNamesrc1[this.text] = this.value;
	}
	});
SortOptions("rc1");

   rc2.options.add(new Option('.All Retailer Category 2.','0'));
	var usedNamesrc2 = {};
	$("select[id='rc2'] > option").each(function () {
	if(usedNamesrc2[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNamesrc2[this.text] = this.value;
	}
	});
SortOptions("rc2");

rc3.options.add(new Option('.All Retailer Category 3.','0'));
	var usedNamesrc3 = {};
	$("select[id='rc3'] > option").each(function () {
	if(usedNamesrc3[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNamesrc3[this.text] = this.value;
	}
	});
SortOptions("rc3");

rclass.options.add(new Option('.All Retailer Classification.','0'));
	var usedNamesrclass= {};
	$("select[id='rclass'] > option").each(function () {
	if(usedNamesrclass[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNamesrclass[this.text] = this.value;
	}
	});
SortOptions("rclass");

franchiseeme.options.add(new Option('.All Franchisee M.E.','0'));
	var ufranchiseeme= {};
	$("select[id='franchiseeme'] > option").each(function () {
	if(ufranchiseeme[this.text] || this.text=='') {
	$(this).remove();
	} else {
	ufranchiseeme[this.text] = this.value;
	}
	});
SortOptions("franchiseeme");

geographical.options.add(new Option('.All Geography.','0'));
	var ugeographical= {};
	$("select[id='geographical'] > option").each(function () {
	if(ugeographical[this.text] || this.text=='') {
	$(this).remove();
	} else {
	ugeographical[this.text] = this.value;
	}
	});
SortOptions("geographical"); */
}

//To Select All the State
function allstate()
{
	
	var e = document.getElementById("state");
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

//To Select All the Region
function allregion()
{
	
	var e = document.getElementById("region");
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

//To Select All the Producttype
function allProducttype()
{
	var e = document.getElementById("Producttype");
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

//To Select All Branches
 function allbranch(){
	var e = document.getElementById("branch");
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
 
 //To Select All Retailer Name
 function allretailername()
{
	var e = document.getElementById("RetailerName");
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

 //To Select All Retailer Category
 function allretailercategory()
{
	var e = document.getElementById("RetailerCategory");
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
 
//To Select all Primary Franchisee
function allprimaryfranchise()
{
	var e = document.getElementById("primaryfranchise");
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

// To Select All Primary Franchise Based on the branch selected
function drpprimary()
{
	var e = document.getElementById("branch");
	var RetailerCodelist = document.getElementById('psdiscode');
	var RetailerName = document.getElementById('primaryfranchise');
		
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
	  RetailerName.options.add(new Option('.All Distributor.','0'));
	var usedNames = {};
	$("select[id='primaryfranchise'] > option").each(function () {
	if(usedNames[this.text] || this.text=='') {
	$(this).remove();
	} else {
	usedNames[this.text] = this.value;
	}
	});
	SortOptions("primaryfranchise");
}

// To Select All Franchise Based on the Primary Franchise selected
function drpfranchise()
{
	var e = document.getElementById("primaryfranchise");
	var RetailerCodelist = document.getElementById('psdiscode');
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
			
			if(lstval[1]==franchisecode && lstval[1]!='')
			{
				RetailerName.options.add(new Option(lstval[2],lstval[2]));
				
			}
		}
		}
		  // str = str + i + " ";
    }
	  RetailerName.options.add(new Option('.All Distributor.','0'));
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

//To Select All the franchise and their selected values based on branch
function drpfuncbrn()
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
		/* if(franchisecode==0)
				{
					for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
		
				} */
				
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

//To Select All the branch and their selected values based on region
function drpfuncreg()
{
	var e = document.getElementById("region");
	var RetailerCodelist = document.getElementById('emplist');	
	var RetailerName = document.getElementById('branch');	
	RetailerName.options.length = 0;
		for (j=0;j<e.options.length;j++) {
			if (e.options[j].selected) {
			var franchisecode = e.options[j].value;
				/* if(franchisecode==0)
				{
						for (i = 0; i < e.options.length; i++) {
    					e.options[i].selected=true;
						} 
					
				} */
				for (i = 0; i < RetailerCodelist.options.length; i++) 
					{
						optvalue = RetailerCodelist.options[i].value;
						var lstval=optvalue.split("~");
						if(lstval[0]==franchisecode)
						{
							RetailerName.options.add(new Option(lstval[2],lstval[1]));
						}
					}
			}
		}
		 
   RetailerName.options.add(new Option('.Select.','0'));
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