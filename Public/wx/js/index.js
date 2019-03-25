$(function () {
    'use strict';

    $(document).on("pageInit", "", function(e, id, page) {
        $(".qidai").die("click").live('click', function () {
    		$.alert('该功能即将开放!', '提示');
        });
        $('textarea, input, select').on('blur', function () {
            setTimeout(function () {
                window.scrollTo(0, 0)
            }, 100)
        })

        $("textarea").bind("input propertychange change", function (event) {
            console.log(3333)
            var offsetTop = $(this).offset().top;
            setTimeout(function () {
                window.scrollTo(0, 111);
            }, 100)
        });

        

    });

    $(document).on("pageInit", "#page-login", function (e, id, page) {
        $("#login").click(function(){
            var username = $("[name='username']").val();
            var password = $("[name='password']").val();
            if (username == '') {
                $.alert('请输入账号', '提示');
                return false;
            }
            if (password == '') {
                $.alert('请输入密码', '提示');
                return false;
            }
            $.showIndicator()
            $.ajax({
                type: 'POST',
                data: { username: username, password: password },
                url: 'index.php?m=Home&c=Login&a=login',
                success: function (res) {
                    if (res.code == 0) {
                        $.router.load('index.php', true);
                    } else {
                        $.alert(res.msg, '提示');
                    }
                    $.hideIndicator();
                }
            });
        });
    });

    $(document).on("pageInit", "#page-search", function (e, id, page) {
        $("#search").click(function(){
            var keyword = $("[name='keyword']").val();
            if (keyword) {
                $.showIndicator()
                $.ajax({
                    type: 'POST',
                    data: { keyword: keyword},
                    url: 'index.php?a=searchOrder',
                    success: function (res) {
                        if (res.code == 0) {
                            var html = '';
                            res.data.forEach(function(item, index){
                                html += '<div class="order-list">';
                                    html += '<div class="left">';
                                        html += '<p class="time">'+item.date.substring(item.date.length - 5)+'</p>';
                                        html += '<p class="village">'+item.village+'</p>';
                                    html += '</div>';
                                    html += '<div class="right">';
                                        html += '<p class="name">姓名：<span>'+item.name+'</span></p>';
                                        html += '<p class="phone">电话：<span>'+item.phone+'</span></p>';
                                        html += '<p class="address">地址：<span>'+item.address+'</span></p>';
                                        html += '<a class="detail" data-no-cache="true" href="index.php?a=detail&id='+item.id+'">点击详情</a>';
                                    html += '</div>';
                                    html += '<div class="both"></div>';
                                html += '</div>';
                            });
                            $(".data-list").html(html);
                            if (html == '') {
                                $(".empty").show();
                            } else {
                                $(".empty").hide();
                            }
                        } else {
                            $.alert(res.msg, '提示');
                        }
                        $.hideIndicator();
                    }
                });
            }
        });
    });

    $(document).on("pageInit", "#page-order", function (e, id, page) {
        $("[name='date']").datetimePicker({});

        $("#submit").click(function () {
            var data = {
                name: $("[name='name']").val(),
                phone: $("[name='phone']").val(),
                village: $("[name='village']").val(),
                address: $("[name='address']").val(),
                amount: $("[name='amount']").val(),
                date: $("[name='date']").val(),
                content: $("[name='content']").val(),
            }
            if (data.name == '') {
                $.alert('请输入客户姓名', '提示');
                $("[name='name']").focus();
                return false;
            }
            if (data.phone == '') {
                $.alert('请输入客户电话', '提示');
                $("[name='phone']").focus();
                return false;
            }
            if (data.village == '') {
                $.alert('请输入所在小区', '提示');
                $("[name='village']").focus();
                return false;
            }
            if (data.address == '') {
                $.alert('请输入客户地址', '提示');
                $("[name='address']").focus();
                return false;
            }
            if (data.amount == '') {
                $.alert('请输入定金', '提示');
                $("[name='amount']").focus();
                return false;
            }
            if (data.date == '') {
                $.alert('请选择测量日期', '提示');
                $("[name='date']").focus();
                return false;
            }
            if (data.content == '') {
                $.alert('请输入测量内容', '提示');
                $("[name='content']").focus();
                return false;
            }
            $.confirm('确定要提交吗?', '提示', function () {
                $.showIndicator()
                $.ajax({
                    type: 'POST',
                    data: data,
                    url: 'index.php?a=addOrder',
                    success: function (res) {
                        if (res.code == 0) {
                            $.alert('保存成功', '提示', function(){
                                history.go(-1);
                            });
                        } else {
                            $.alert(res.msg, '提示');
                        }
                        $.hideIndicator();
                    }
                });
            });
        });
    });

    $.init();
});