// JavaScript Document

function test()//Used for hide the delete button while click the check  box
				{
					var a=0;
					var table = document.getElementById('datatable1');
					var addbutton = document.getElementById('addbutton');
					var checkall = document.getElementById('checkall');
					 var rowCount = table.rows.length;
					  
					 if(rowCount >1)
					 {
						
						 for(var i=1;i < rowCount;i++)
						 {
						 if(document.getElementById('datatable1').rows[i].getElementsByTagName("INPUT")[0].checked==true)
							{
								 // delbutton.style.visibility = 'visible'; 
								 a++;
								 
							}
						 }
							
					 }
					 if(a>0)
					 {
						
						//delbutton.style.visibility = 'visible';
						//addbutton.style.visibility='hidden';
						addbutton.value="Delete";
						addbutton.name="Delete";
						
						 
					 }
					 else
					 {
						// addbutton.style.visibility='visible';
						 // delbutton.style.visibility='hidden';
						 	addbutton.value="Save";
							addbutton.name="Save";
						  checkall.checked= false; 
						 location.reload();
					 }
		}
checked=false;
function checkedAll (frm1) {
	//var aa= document.getElementById('frm1');
	var addbutton = document.getElementById('addbutton');
	var table = document.getElementById('datatable1');
	var rowCount = table.rows.length;
	 if (rowCount >1 && checked == false)
          {
           checked = true
		   // delbutton.style.visibility = 'visible';
			//addbutton.style.visibility='hidden';
			addbutton.value="Delete";
			addbutton.name="Delete";
			// addbutton.style.width='1';
			//addbutton.style.height='1';
		   
          }
        else
          {
          checked = false
		// delbutton.style.visibility = 'hidden';
		  // addbutton.style.visibility='visible'; 
			//addbutton.add();
			addbutton.value="Save";
			addbutton.name="Save";
			location.reload();
			//document.location='productgroupmaster.php';
		 
          }
	for (var i =0; i < frm1.elements.length; i++) 
	{
	 frm1.elements[i].checked = checked;
	 //delbutton.style.visibility = 'visible'; 
	}
      }
