(function($) {
$(document).ready(function() {
	
	/////////////////////////////////////////////////////////////////////////TASKS///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//check/uncheck all
	$("#wpsxp_check_all").click(function() {
		if($(this).is(":checked")) {
			$('.wpsxp_row_ch').attr('checked',true);
		}
		else {
			$('.wpsxp_row_ch').attr('checked',false);
		}
		
		wpsxp_check_the_selection();
	});
	
	//unpublish task
	$(".wpsxp_unpublish").click(function() {
		var id = $(this).attr("wpsxp_id");
		$("#wpsxp_def_id").val(id);
		$("#wpsxp_task").val('unpublish');
		$("#wpsxp_form").submit();
		return false;
	});
	//publish task
	$(".wpsxp_publish").click(function() {
		var id = $(this).attr("wpsxp_id");
		$("#wpsxp_def_id").val(id);
		$("#wpsxp_task").val('publish');
		$("#wpsxp_form").submit();
		return false;
	});
	//publish list task
	$("#wpsxp_publish_list").click(function(e) {
		e.preventDefault();
		var l = parseInt($('.wpsxp_row_ch:checked').length);
		if(l > 0) {
			$("#wpsxp_task").val('publish');
			$("#wpsxp_form").submit();
			return false;
		}
		else {
			alert('Please first make a selection from the list');
			return false;
		}
	});
	//unpublish list task
	$("#wpsxp_unpublish_list").click(function(e) {
		e.preventDefault();
		var l = parseInt($('.wpsxp_row_ch:checked').length);
		if(l > 0) {
			$("#wpsxp_task").val('unpublish');
			$("#wpsxp_form").submit();
			return false;
		}
		else {
			alert('Please first make a selection from the list');
			return false;
		}
	});
	//edit list task
	$("#wpsxp_edit").click(function(e) {
		e.preventDefault();
		var l = parseInt($('.wpsxp_row_ch:checked').length);
		if(l > 0) {
			var id = $('.wpsxp_row_ch:checked').first().val();
			var url_part1 =$("#wpsxp_form").attr("action");
			var url = url_part1 + '&act=edit&id=' + id;
			window.location.replace(url);
			return false;
		}
		else {
			alert('Please first make a selection from the list');
			return false;
		}
	});
	//delete task
	$("#wpsxp_delete").click(function(e) {
		e.preventDefault();
		var l = parseInt($('.wpsxp_row_ch:checked').length);
		if(l > 0) {
			if(confirm('Delete selected items?')) {
				$("#wpsxp_task").val('delete');
				$("#wpsxp_form").submit();
			}
			return false;
		}
		else {
			alert('Please first make a selection from the list');
			return false;
		}
	});
	
	
	//filter select
	$(".wpsxp_select").change(function() {
		$("#wpsxp_form").submit();
	});
	//filter search
	$("#wpsxp_filter_search_submit").click(function() {
		$("#wpsxp_form").submit();
	});
	
	//list of checkbox
	$('.wpsxp_row_ch').click(function() {
		if(!($(this).is(':checked'))) {
			$("#wpsxp_check_all").attr('checked',false);
		}
		wpsxp_check_the_selection();
	});
	
	function wpsxp_check_the_selection() {
		var l = parseInt($('.wpsxp_row_ch:checked').length);
		if(l == 0) {
			$('.wpsxp_disabled').addClass('button-disabled');
			$('.wpsxp_disabled').attr('title','Please make a selection from the list, to activate this button');
		}
		else {
			$('.wpsxp_disabled').removeClass('button-disabled');
			$('.wpsxp_disabled').attr('title','');
		}
	};
	
	/////////////////////////////////////////////////////Add form//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$("#wpsxp_form_save").click(function() {
		if(!wpsxp_validate_form())
			return false;
		$("#wpsxp_task").val('save');
		$("#wpsxp_form").submit();
		return false;
	});
	$("#wpsxp_form_save_close").click(function() {
		if(!wpsxp_validate_form())
			return false;
		$("#wpsxp_task").val('save_close');
		$("#wpsxp_form").submit();
		return false;
	});
	$("#wpsxp_form_save_new").click(function() {
		if(!wpsxp_validate_form())
			return false;
		$("#wpsxp_task").val('save_new');
		$("#wpsxp_form").submit();
		return false;
	});
	
	//function to validate forms form
	function wpsxp_validate_form() {
		var tested = true;
		$("#wpsxp_form").find('.required').each(function() {
			var val = $.trim($(this).val());
			if(val == '') {
				$(this).addClass('wpsxp_error');
				tested = false;
			}
			else
				$(this).removeClass('wpsxp_error');
		});
		if(tested)
			return true;
		else
			return false;
	};
	
	//////////////////////////////////////////////////Table list sortable///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	var wpsxp_selected_tr_id = 0;
	function wpsxp_make_sortable() {
		var table_name = $("#wpsxp_sortable").attr("table_name");
		var reorder_type = $("#wpsxp_sortable").attr("reorder_type");
		
		//sortable
		$("#wpsxp_sortable").sortable();
		$("#wpsxp_sortable").disableSelection();
		$("#wpsxp_sortable").sortable( "option", "disabled", true );
		$("#wpsxp_sortable .wpsxp_reorder").mousedown(function()
		{
			wpsxp_selected_tr_id = $(this).parents('tr').attr("id");
			$( "#wpsxp_sortable" ).sortable( "option", "disabled", false );
		});
		$( "#wpsxp_sortable" ).sortable(
		{
			update: function(event, ui) 
			{
				var order = $("#wpsxp_sortable").sortable('toArray').toString();
				$.post
				(
						"admin.php?page=sexypolling&act=wpsxp_submit_data&holder=sexyajax",
						{order: order,type: reorder_type,table_name: table_name},
						function(data)
						{
							//window.location.reload();
							return false;
						}
				);
			}
		});
		$( "#wpsxp_sortable" ).sortable(
		{
			stop: function(event, ui) 
			{
				$( "#wpsxp_sortable" ).sortable( "option", "disabled", true );
			}
		});
	}
	wpsxp_make_sortable();
	
	function wpsxp_generate_td_width() {
		$('.ui-state-default').each(function() {
			$(this).find('td').each(function(i) {
				if(i == $(this).find('td').length)
					var w = $(this).width()-2;
				else
					var w = $(this).width();
				$(this).attr("w",w);
				$(this).css('width',w);
			});
		})
	};
	wpsxp_generate_td_width();
	
					
});
})(jQuery);