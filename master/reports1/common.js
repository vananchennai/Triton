$(document).ready(function() { 
	$(document).bind('keydown', 'alt+s', function() {
		$('#save_btn').click(); return false;
	});
	$('input, select, textarea').bind('keydown', 'alt+s', function() {
		$('#save_btn').click(); return false;
	});
	$(document).bind('keydown', 'alt+u', function() {
		$('#update_btn').click(); return false;
	});
	$('input, select, textarea').bind('keydown', 'alt+u', function() {
		$('#update_btn').click(); return false;
	});
	$(document).bind('keydown', 'alt+d', function() {
		$('#delete_btn').click(); return false;
	});
	$('input, select, textarea').bind('keydown', 'alt+d', function() {
		$('#delete_btn').click(); return false;
	});
	$(document).bind('keydown', 'alt+c', function() {
		$('#cancel_btn').click(); return false;
	});
	$('input, select, textarea').bind('keydown', 'alt+c', function() {
		$('#cancel_btn').click(); return false;
	});
	$(document).bind('keydown', 'alt+e', function() {
		$('#search_btn').click(); return false;
	});
	$('input, select, textarea').bind('keydown', 'alt+e', function() {
		$('#search_btn').click(); return false;
	});
	$(document).bind('keydown', 'alt+x', function() {
		$('#export_btn').click(); return false;
	});
	$('input, select, textarea').bind('keydown', 'alt+x', function() {
		$('#export_btn').click(); return false;
	});
	$(document).bind('keydown', 'alt+i', function() {
		$('#import_btn').click(); return false;
	});
	$('input, select, textarea').bind('keydown', 'alt+i', function() {
		$('#import_btn').click(); return false;
	});
	
	$(document).bind('keydown', 'alt+r', function() {
		$('#get_report_btn').click(); return false;
	});
	$('input, select, textarea').bind('keydown', 'alt+r', function() {
		$('#get_report_btn').click(); return false;
	});
	
	$(document).bind('keydown', 'alt+g', function() {
		$('#change_btn').click(); return false;
	});
	$('input, select, textarea').bind('keydown', 'alt+g', function() {
		$('#change_btn').click(); return false;
	});
	
	
	$('#dataTable .table_last_field:last').live('keydown', function (e) {
		if (e.keyCode == 9) {
			addRow('dataTable');
			return false;
		} 
	}); 
	
	$('#delete_btn').click(function () {
		var checkedBox = false;
		$('.grid table.sortable tbody input').each(function() {
			if($(this).attr('checked') == 'checked') {
				checkedBox = true;
			}
		}); 
		if(checkedBox) {
			var checkedConfirm = confirm("Are you sure want to delete record!");
			if(checkedConfirm) { return true; } else { return false; }
		}
		
	});
        
        /* Added for Report */
        $('#region').change(function (){
            var region = $('#region').val();
            $.ajax({
                type: 'post',
                url: 'report_filter.php',
                data: 'action=branch&region='+region,
                 success: function (res){
                     eval("var obj=" + res);
                     $("select#branch option").each( function(index) {
                        if($(this).val() != 0) {
                            $(this).remove();
                        }                        
                     });
                     if(obj['branch']) {
                        $.each( obj['branch'], function( val, text ) {                         
                           $('select#branch').append( new Option(text,val) );
                        });
                     }
                     $("select#franchise option").each( function(index) {
                        if($(this).val() != 0) {
                            $(this).remove();
                        }                        
                     });
                     if(obj['franchise']) {
                        $.each( obj['franchise'], function( val, text ) {                         
                           $('select#franchise').append( new Option(text,val) );
                        });
                     }
                 }
            });
        });
        
        $('#branch').change(function (){
            var region = $('#region').val();
            var branch = $('#branch').val();
            $.ajax({
                type: 'post',
                url: 'report_filter.php',
                data:  'action=franchise&region='+region+'&branch='+branch,
                 success: function (res){
                     eval("var obj=" + res);
                     $("select#franchise option").each( function(index) {
                        if($(this).val() != 0) {
                            $(this).remove();
                        }                        
                     });
                     $.each( obj['franchise'], function( val, text ) {                         
                        $('select#franchise').append( new Option(text,val) );
                     });
                 }
            });
        });
                
        
       /* Added for Report */
        
        
        
});    
function alert(msg, redirect) {	
	custom_alert(msg, function (){
		// Write code, If click 'yes'
		if(redirect)
			document.location = redirect;
	});
}    
/**/    
setTimeout(function() { $('#confirm .yes').trigger('click'); $.modal.close(); }, 3000); 

function ci(obj){
	console.info(obj);
}

function custom_alert(message, callback) {
	$('#confirm').modal({
		closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
		position: ["20%",],
		overlayId: 'confirm-overlay',
		containerId: 'confirm-container', 
		onShow: function (dialog) {
			var modal = this;

			$('.message', dialog.data[0]).append(message);
                        //$('.yes').hide();
						$('.no').hide();
			// if the user clicks "yes"
			$('.yes', dialog.data[0]).click(function () {
				// call the callback
				if ($.isFunction(callback)) {
					callback.apply();
				}
				// close the dialog
				modal.close(); // or $.modal.close();
			});
		}
	});
}

function addOption(selectbox,text,value )
{
    var optn = document.createElement("OPTION");
    optn.text = text;
    optn.value = value;
    selectbox.options.add(optn);
}