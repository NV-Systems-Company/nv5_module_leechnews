/**
 * @Project FEEDNEWS ON NUKEVIET 4.x
 * @Author KENNYNGUYEN (nguyentiendat713@gmail.com)
 */

 $(document).ready(function() {
	$("body").delegate( ".ninfo", "click", function(){
		$(".ninfo").each(function() {
			$(this).show()
		});
		$(".wttooltip").each(function() {
			$(this).hide()
		});
		$(this).hide().next(".wttooltip").show();
		return false
	});
	$("body").delegate( ".wttooltip", "click", function(){
		$(this).hide().prev(".ninfo").show();
	});
});

function nv_change_source_cat(catid, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + catid, 5000);
	var new_vid = $('#id_' + mod + '_' + catid).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_source_cat&nocache=' + new Date().getTime(), 'catid=' + catid + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split('_');
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_list_cat();
		return;
	});
	return;
}
 
function nv_setup_source_cat(filename, mod) {
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_source_cat&nocache=' + new Date().getTime(), 'mod=' + mod + '&filename=' + filename, function(res) {
		alert(res);
		window.location.href = window.location.href;
	});
	return false;
 }

function nv_download_source_cat(catid, mod) {
	window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_source_cat&catid=' + catid + '&mod=' + mod + '&nocache=' + new Date().getTime();
	return false;
 }
 
function nv_download_template(filename, mod) {
	window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_source_cat&filename=' + filename + '&mod=' + mod + '&nocache=' + new Date().getTime();
	return false;
 }

function nv_show_list_cat() {
	if (document.getElementById('module_show_list')) {
		$('#module_show_list').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_source_cat' + '&page=' + curr_page + '&nocache=' + new Date().getTime());
	}
	return;
}

function nv_show_list_proxy() {
	if (document.getElementById('module_show_proxy')) {
		$('#module_show_proxy').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_proxy' + '&page=' + curr_page + '&nocache=' + new Date().getTime());
	}
	return;
}

function nv_del_source_cat(catid, delallcheckss) {
	if (confirm(nv_is_del_confirm[0])) {
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_source_cat' + '&nocache=' + new Date().getTime(), 'catid=' + catid + '&checkss=' + delallcheckss, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				nv_show_list_cat();
			} else if (r_split[0] == 'ERR') {
				alert(r_split[1]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_change_proxy(proxy_id, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + proxy_id, 5000);
	var new_vid = $('#id_' + mod + '_' + proxy_id).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_proxy&nocache=' + new Date().getTime(), 'proxy_id=' + proxy_id + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split('_');
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_list_proxy();
		return;
	});
	return;
}

function leechnews_cronfile( ) {
	if (confirm(nv_is_change_act_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_source&nocache=' + new Date().getTime(), 'id=1&mod=cron_create', function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				alert(r_split[1]);
				window.location.href = window.location.href;
			} else if (r_split[0] == 'ERR') {
				alert(r_split[1]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function leechnews_cronsql( ) {
	if (confirm(nv_is_change_act_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_source&nocache=' + new Date().getTime(), 'id=1&mod=cron_setup',function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				alert(r_split[1]);
				window.location.href = window.location.href;
			} else if (r_split[0] == 'ERR') {
				alert(r_split[1]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_del_proxy(proxy_id, delallcheckss) {
	if (confirm(nv_is_del_confirm[0])) {
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_proxy' + '&nocache=' + new Date().getTime(), 'proxy_id=' + proxy_id + '&checkss=' + delallcheckss, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				nv_show_list_proxy();
			} else if (r_split[0] == 'ERR') {
				alert(r_split[1]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_change_source(id, mod) {
	var nv_timer = nv_settimeout_disable('id_' + mod + '_' + id, 5000);
	var new_vid = $('#id_' + mod + '_' + id).val();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_source&nocache=' + new Date().getTime(), 'id=' + id + '&mod=' + mod + '&new_vid=' + new_vid, function(res) {
		var r_split = res.split('_');
		if (r_split[0] != 'OK') {
			alert(nv_is_change_act_confirm[2]);
		}
		clearTimeout(nv_timer);
		nv_show_list_source(source_cat_id);
		return;
	});
	return;
}

function nv_show_list_source(catid) {
	if (document.getElementById('module_show_source')) {
		$('#module_show_source').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_source' + '&catid=' + catid  + '&page=' + curr_page + '&nocache=' + new Date().getTime());
	}
	return;
}

function nv_show_reports() {
	if (document.getElementById('show_reports')) {
		$('#show_reports').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_reports' + '&page=' + curr_page + '&nocache=' + new Date().getTime());
	}
	return;
}

function nv_show_logs() {
	if (document.getElementById('show_logs')) {
		$('#show_logs').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=list_logs' + '&page=' + curr_page + '&nocache=' + new Date().getTime());
	}
	return;
}

function nv_del_source(id, delallcheckss) {
	if (confirm(nv_is_del_confirm[0])) {
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_source' + '&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + delallcheckss, function(res) {
			var r_split = res.split('_');
			if (r_split[0] == 'OK') {
				nv_show_list_source(source_cat_id);
			} else if (r_split[0] == 'ERR') {
				alert(r_split[1]);
			} else {
				alert(nv_is_del_confirm[2]);
			}
		});
	}
	return false;
}

function nv_feednews_change_mod(id) {
	var mod_data = $('#target_module').val();
	if (document.getElementById('target_cat_select')) {
		$('#target_cat_select').load(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=source_mod&nocache=' + new Date().getTime(), 'mod_data=' + mod_data + '&id=' + id );
	}
	return;
}

$(document).ready(function(){
	$("#select-img-cat").click(function() {
		var area = "logo";
		var path = CFG.upload_path;
		var currentpath = CFG.upload_current;
		var type = "image";
		nv_open_browse(script_name + "?" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
});

function nv_admin_leechnews(id, checkss) {
	task_running();
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=get&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + checkss, function(res) {
		alert(res);
		task_finish();
		nv_show_list_source(source_cat_id);
	});
	return false;
}

function nv_sourcelist_action(oForm, checkss, msgnocheck) {
	var fa = oForm['idcheck[]'];
	var listid = '';
	if (fa.length) {
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				listid = listid + fa[i].value + ',';
			}
		}
	} else {
		if (fa.checked) {
			listid = listid + fa.value + ',';
		}
	}

	if (listid != '') {
		var action = document.getElementById('action').value;
		if (action == 'delete') {
			if (confirm(nv_is_del_confirm[0])) {
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_source&nocache=' + new Date().getTime(), 'listid=' + listid + '&checkss=' + checkss, function(res) {
					var r_split = res.split('_');
					if (r_split[0] == 'OK') {
						nv_show_list_source(source_cat_id);
					} else if (r_split[0] == 'ERR') {
						alert(r_split[1]);
					} else {
						alert(nv_is_del_confirm[2]);
					}
				});
			}
		} else if (action == 'admin_leechnews'){
			task_running();
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=get&nocache=' + new Date().getTime(), 'listid=' + listid + '&checkss=' + checkss, function(res) {
				alert(res);
				task_finish();
				nv_show_list_source(source_cat_id);
			});
		}
	} else {
		alert(msgnocheck);
	}
}

function nv_sourcecat_action(oForm, checkss, msgnocheck) {
	var fa = oForm['catidcheck[]'];
	var listcatid = '';
	if (fa.length) {
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				listcatid = listcatid + fa[i].value + ',';
			}
		}
	} else {
		if (fa.checked) {
			listcatid = listcatid + fa.value + ',';
		}
	}

	if (listcatid != '') {
		var action = document.getElementById('action').value;
		if (action == 'delete') {
			if (confirm(nv_is_del_confirm[0])) {
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_source_cat' + '&nocache=' + new Date().getTime(), 'listcatid=' + listcatid + '&checkss=' + checkss, function(res) {
					var r_split = res.split('_');
					if (r_split[0] == 'OK') {
						nv_show_list_cat();
					} else if (r_split[0] == 'ERR') {
						alert(r_split[1]);
					} else {
						alert(nv_is_del_confirm[2]);
					}
				});
			}
		}else{
			return false;
		}
	} else {
		alert(msgnocheck);
	}
}

function nv_proxy_action(oForm, checkss, msgnocheck) {
	var fa = oForm['proxy_idcheck[]'];
	var listproxy_id = '';
	if (fa.length) {
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				listproxy_id = listproxy_id + fa[i].value + ',';
			}
		}
	} else {
		if (fa.checked) {
			listproxy_id = listproxy_id + fa.value + ',';
		}
	}

	if (listproxy_id != '') {
		var action = document.getElementById('action').value;
		if (action == 'delete') {
			if (confirm(nv_is_del_confirm[0])) {
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_proxy' + '&nocache=' + new Date().getTime(), 'listproxy_id=' + listproxy_id + '&checkss=' + checkss, function(res) {
					var r_split = res.split('_');
					if (r_split[0] == 'OK') {
						nv_show_list_proxy();
					} else if (r_split[0] == 'ERR') {
						alert(r_split[1]);
					} else {
						alert(nv_is_del_confirm[2]);
					}
				});
			}
		}else if (action == 'active') {
			if (confirm(nv_moderate_confirm)) {
				task_running();
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_proxy' + '&nocache=' + new Date().getTime(), 'listproxy_id=' + listproxy_id + '&checkss=' + checkss + '&mod=active', function(res) {
					var r_split = res.split('_');
					if (r_split[0] == 'OK') {
						window.location.href = window.location.href;
					} else if (r_split[0] == 'ERR') {
						alert(r_split[1]);
						nv_show_list_proxy();
						task_finish();
					}
				});
			}
		}else if (action == 'check_alive') {
			if (confirm(nv_moderate_confirm)) {
				task_running();
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_proxy' + '&nocache=' + new Date().getTime(), 'listproxy_id=' + listproxy_id + '&checkss=' + checkss + '&mod=deny', function(res) {
					var r_split = res.split('_');
					if (r_split[0] == 'OK') {
						alert(r_split[1]);
						nv_show_list_proxy();
					} else if (r_split[0] == 'ERR') {
						alert(r_split[1]);
						nv_show_list_proxy();
					}
				});
				task_finish();
			}
		}else{
			return false;
		}
	} else {
		alert(msgnocheck);
	}
}

function nv_reports_action(oForm, checkss, msgnocheck) {
	var fa = oForm['idcheck[]'];
	var listid = '';
	if (fa.length) {
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				listid = listid + fa[i].value + ',';
			}
		}
	} else {
		if (fa.checked) {
			listid = listid + fa.value + ',';
		}
	}

	if (listid != '') {
		var action = document.getElementById('action').value;
		if (action == 'delete') {
			if (confirm(nv_is_del_confirm[0])) {
				task_running();
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_report' + '&nocache=' + new Date().getTime(), 'listid=' + listid + '&checkss=' + checkss, function(res) {
					var r_split = res.split('_');
					if (r_split[0] == 'OK') {
						window.location.href = window.location.href;
						return false;
					} else if (r_split[0] == 'ERR') {
						alert(r_split[1]);
					} else {
						alert(nv_is_del_confirm[2]);
					}
				});
			}
		}else if (action == 'approve') {
			if (confirm(nv_moderate_confirm)) {
				task_running();
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=moderate_news' + '&nocache=' + new Date().getTime(), 'listid=' + listid + '&checkss=' + checkss + '&mod=approve', function(res) {
					var r_split = res.split('_');
					if (r_split[0] == 'OK') {
						window.location.href = window.location.href;
					} else if (r_split[0] == 'ERR') {
						alert(r_split[1]);
						nv_show_reports();
						task_finish();
					}
				});
			}
		}else if (action == 'deny') {
			if (confirm(nv_moderate_confirm)) {
				task_running();
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=moderate_news' + '&nocache=' + new Date().getTime(), 'listid=' + listid + '&checkss=' + checkss + '&mod=deny', function(res) {
					var r_split = res.split('_');
					if (r_split[0] == 'OK') {
						alert(r_split[1]);
						nv_show_reports();
					} else if (r_split[0] == 'ERR') {
						alert(r_split[1]);
						nv_show_reports();
					}
				});
				task_finish();
			}
		}else{
			return false;
		}
	} else {
		alert(msgnocheck);
	}
}

function nv_logs_action(oForm, checkss, msgnocheck) {
	var fa = oForm['logidcheck[]'];
	var listid = '';
	if (fa.length) {
		for (var i = 0; i < fa.length; i++) {
			if (fa[i].checked) {
				listid = listid + fa[i].value + ',';
			}
		}
	} else {
		if (fa.checked) {
			listid = listid + fa.value + ',';
		}
	}

	if (listid != '') {
		var action = document.getElementById('action').value;
		if (action == 'delete') {
			if (confirm(nv_is_del_confirm[0])) {
				task_running();
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_log' + '&nocache=' + new Date().getTime(), 'listid=' + listid + '&checkss=' + checkss, function(res) {
					var r_split = res.split('_');
					if (r_split[0] == 'OK') {
						window.location.href = window.location.href;
						return false;
					} else if (r_split[0] == 'ERR') {
						alert(r_split[1]);
					} else {
						alert(nv_is_del_confirm[2]);
					}
				});
				task_finish();
			}
		}else{
			return false;
		}
	} else {
		alert(msgnocheck);
	}
}

function task_running (){
	$('#running').fadeIn(500, 'linear');     
}

function task_finish (){
	$('#running').fadeOut(200, "linear");
}
