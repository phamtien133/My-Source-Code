<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>

<link href="<c:url value="/assests/css/bootstrap.min.css" />" rel="stylesheet">
<link href="<c:url value="/assests/css/bootstrap-theme.min.css" />" rel="stylesheet">
<script src="../assests/js/jquery-3.2.1.min.js"></script>
<script  src="../assests/js/bootstrap.min.js"></script>
<%@page contentType="text/html" pageEncoding="UTF-8"%>
<%@ page session="false" %>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>iShop - Bán hàng online</title>
        <style>
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }

            td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }
            th {
                text-align: center;
                color: white;
            }

            thead {
                text-align: center;
                background-color: #2F6FA7;
            }
            tr:nth-child(even) {
                background-color: #D9EDF7;
            }
            /* Dropdown Button */
            .dropbtn {
                background-color: #2F70A9;
                color: white;
                padding: 16px;
                font-size: 16px;
                border: none;
                cursor: pointer;
            }

            /* Dropdown button on hover & focus */
            .dropbtn:hover, .dropbtn:focus {
                background-color: #2F70A9;
            }

            /* The container <div> - needed to position the dropdown content */
            .dropdown {
                position: relative;
                display: inline-block;
            }

            /* Dropdown Content (Hidden by Default) */
            .dropdown-content {
                display: none;
                position: absolute;
                background-color: #f9f9f9;
                min-width: 160px;
                box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                z-index: 1;
            }

            /* Links inside the dropdown */
            .dropdown-content a {
                color: black;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
            }

            /* Change color of dropdown links on hover */
            .dropdown-content a:hover {background-color: #f1f1f1}

            /* Show the dropdown menu (use JS to add this class to the .dropdown-content container when the user clicks on the dropdown button) */
            .show {display:block;}
            .btn {
                background-color: #2E6DA5; /* blue */
                border: none;
                color: white;
                padding: 10px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                cursor: pointer;
            }

            .btn-rad-12 {border-radius: 12px;}
            .remove-product{
                display: none;
            }
        </style>
    </head>

    <body>
        <div class="col-md-10"></div>
        <div class="col-md-2">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Xin chào, Nhân viên
                    <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="${pageContext.request.contextPath}/info">Xem thông tin cá nhân</a></li>
                    <li><a href="${pageContext.request.contextPath}/logout">Đăng xuất</a></li>
                </ul>
            </div>        
        </div>
        <div class="col-md-12">
            <hr>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        DANH SÁCH CHỨC NĂNG
                    </a>
                    <a href="${pageContext.request.contextPath}/home/index_staff" class="list-group-item list-group-item-info">Xem danh sách sản phẩm</a>                   
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã số</th>
                        <th>Tên</th>
                        <th>Số điện thoại</th>
                        <th>Chứng minh nhân dân</th>
                        <th>Địa chỉ</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;"><c:out value="${listInfo.userId}"/></td>
                        <td style="text-align: center;"><c:out value="${listInfo.userName}"/></td>
                        <td style="text-align: center;"><c:out value="${listInfo.userPhone}"/></td>
                        <td style="text-align: center;"><c:out value="${listInfo.userIdentifyCard}"/></td>
                        <td style="text-align: center;"><c:out value="${listInfo.userAddress}"/></td>
                    </tr>                       
                </tbody>
            </table>
        </div>
        <script>
            function deleteProduct(id)
            {
                // confirm alert
                var _continue = true;
                if (!confirm('Bạn có chắc chắn muốn xóa?')) {
                    _continue = false;
                }
                if (_continue) {
                    sParams = id;
                    $.ajax({
                        type: "GET",
                        url: "${pageContext.request.contextPath}/ajax/delete/" + id + ".html",
                        success: function (result) {
                            alert(result);
                        }
                    });
                }
                return false;
            }
            $(function () {
                $('.js-delete').each(function () {
                    $(this).unbind('click').click(function () {
                        $(this).unbind('click');
                        var id = $(this).data('id');
                        var obj = $('#js-object-' + id);
                        if (parseInt(id) > 0) {
                            deleteProduct(id);
                            obj.addClass('remove-product');
                        }
                    });
                });
                $('.js-delete-more').each(function () {
                    $(this).unbind('click').click(function () {
                        $(this).unbind('click');
                        alert($("input:checked").attr('data-id'));
                        var obj = $('#js-object-' + $("input:checked").attr('data-id'));
                        obj.addClass('remove-product');
                    });
                });
            });
        </script>
    </body>
</html>
