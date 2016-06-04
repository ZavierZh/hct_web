 $(function() {

 	if(init_opt.dialog){
	  	init_opt.dialog.obj = $(init_opt.dialog.id);
	  	init_opt.dialog.obj.dialog({
	  		autoOpen: false,
	  		modal: true,
	  		height: init_opt.dialog.height ? init_opt.dialog.height : 500,
	  		width: init_opt.dialog.width ? init_opt.dialog.width : 600,
	  	});

	  	init_opt.dialog.iframe_obj = $(init_opt.dialog.iframe_id);
	}
	
	//添加按钮

	if(init_opt.btn_add){
		$(init_opt.btn_add.id).click(function() {
			$("#message").text("正在加载..");
			$("#loading").show();
			init_opt.dialog.iframe_obj.attr('src',init_opt.btn_add.url);
			init_opt.dialog.iframe_obj.load(function(){
				$("#message").text("");
				$("#loading").hide();
				init_opt.dialog.obj.dialog("open");
			});
		});
	}
	//删除按钮
	if(init_opt.btn_del && init_opt.btn_del.id){
		$(init_opt.btn_del.id).click(function(){
			var valArr = new Array;
			$(init_opt.table.id+" :checked").each(function(i){ 
				valArr[i] = this.value; 
			});
			if (!valArr.length){
				zz.tip.toptip('没有选中任何数据',2);
				return;
			}
			var vals = valArr.join(',');
			jConfirm('确定要删除'+valArr.length+'条内容吗？\n','警告',function(r) {
				if(r == false) return;
				zz.sendto({
					url: init_opt.btn_del.url,
					data:  {id:""+vals},
				});
			});
		});
	}
	if(init_opt.table){
		$(init_opt.table.checkbox_id).click(function(){
		    if(this.checked){
		        $(init_opt.table.id+" input[type='checkbox']").prop("checked", true);
		    }else{
		        $(init_opt.table.id+" input[type='checkbox']").prop("checked", false);
		    }
		});
	}

});

//编辑
function edit_data(id) {
	if(isNaN(id)) return;

	$("#message").text("正在加载..");
				$("#loading").show();

	init_opt.dialog.iframe_obj.attr('src',init_opt.edit_url+"/id/"+id);
  	init_opt.dialog.iframe_obj.load(function(){
  		$("#message").text("");
  		$("#loading").hide();
  		init_opt.dialog.obj.dialog("open");
  });
}
function del_data_url(urll,id) {
	if(isNaN(id)) return;
	if(urll == '') return;
	jConfirm('你确定这么做吗?\n数据将被删除', '警告', function(r) {
		if(r == false) return;
		zz.sendto({
			url: urll,
			data: {id:""+id},
		});
	});
	return;
}
function del_data(id) {
	del_data_url(init_opt.btn_del.url,id);
}
//添加或删除数据成功后,用该函数刷新数据表
function flush_data(type){
	var obj = $(".pagination");
	if(obj.length == 0){
		location.reload();
	}else{
		obj.jqPaginator('option');
	}
}

function page_list(){
	$(".pagination").jqPaginator('option',{
		currentPage:1,
		onChangePage:zz.page_normal,
	});
}

function search_prj(id){
	var arr = {};
	$(id+" [name]").each(function(){
		arr[this.name] = $(this).val();
	});
	//去除表单中的空值
	for (var i in arr){
		if (!arr[i]) delete arr[i];
	}
	if(jQuery.isEmptyObject(arr)){
		zz.tip.toptip('没有选中任何条件',2);
		return;
	}
	zz.pageopt.opts = arr;
	$(".pagination").jqPaginator('option',{
		currentPage:1,
		onChangePage:zz.page_search,
	});
}
function open_dialog(url){
 	init_opt.dialog.iframe_obj.empty();			
	    init_opt.dialog.iframe_obj.attr('src',url);
	    init_opt.dialog.iframe_obj.load(function(){
	        init_opt.dialog.obj.dialog("open");
	    });	
}
function btn_open_dialog(id,url){
	$(id).button().click(function() {
		open_dialog(url);
	});
}