// JavaScript Document
//初始化地域联动
var area_url = 'get_area.php';
template.compile("areaTemplate",areaTemplate);
createAreaSelect('province',0,'',area_url);
$(function(){


	//将表单内容写入父窗口form，进行提交
	$('#sub').on('click',function(){
		if(!check_order())return false;

		var formObj = $('#dingdanqueren');

		var formParent = $('#order_box', window.parent.document);
		formParent.html('');
		formParent.append(formObj);
		formParent.submit();
	})

	//发票信息切换
	jQuery.jqtab = function(tabtit,tab_conbox,shijian) {
		$(tab_conbox).find("li").hide();
		$(tabtit).find("li:first").addClass("thistab").show();
		$(tab_conbox).find("li:first").show();

		$(tabtit).find("li").bind(shijian,function(){
			if($(this).attr('id')=='common'){
				$('input[name=invo_type]').val(0);
			}
			else $('input[name=invo_type]').val(1);

			$(this).addClass("thistab").siblings("li").removeClass("thistab");
			var activeindex = $(tabtit).find("li").index(this);
			$(tab_conbox).children().eq(activeindex).show().siblings().hide();
			return false;
		});

	};
	/*调用方法如下：*/
	$.jqtab("#tabs","#tab_conbox","click");

	$.jqtab("#tabs2","#tab_conbox2","mouseenter");

	//数量input焦点离开时计算总金额
	$('[name=product_num]').on('blur',function(){
		var product_num = $.trim($('input[name=product_num]').val());
		if(!product_num.match(/^\d{1,9}$/)){
			alert('购买数量必须是整数');
			$('input[name=product_num]').focus();
			return false;
		}
		var price = parseFloat($.trim($('#price').text()));
		var total_price = price * product_num;
		total_price = total_price.toFixed(2);
		$('#total_fee').text(total_price);
	})
	//订单内容验证
	function check_order(){
		var product_num = $.trim($('input[name=product_num]').val());
		if(!product_num.match(/^\d{1,9}$/)){
			alert('购买数量必须是整数');
			$('input[name=product_num]').focus();
			return false;
		}

		var area = $('select[name=area]').val();
		if(area==null){
			alert('请选择地区');
			$('[name=area]').focus();
			return false;
		}
		var address = $.trim($('input[name=buyer_address]').val());
		if(address==''){
			alert('请填写详细地址');
			$('[name=buyer_address]').focus();
			return false;
		}
		var buyer_name = $.trim($('input[name=buyer_name]').val());
		if(buyer_name==''){
			alert('请填写姓名');
			$('[name=buyer_name]').focus();
			return false;
		}

		var phone = $.trim($('input[name=buyer_phone]').val());
		if(!phone.match(/^1\d{10}$/)){
			alert('请正确填写手机号码');
			$('[name=buyer_phone]').focus();
			return false;
		}
		return true;
	}
})