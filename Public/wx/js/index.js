$(function () {
    'use strict';

    $(document).on("pageInit", "", function(e, id, page) {
        $(".qidai").die("click").live('click', function () {
    		$.alert('该功能即将开放!', '提示');
    	});
    });

    //会员首页
    $(document).on("pageInit", "#page-vip-index", function (e, id, page) {
        
    });

    $.init();
});