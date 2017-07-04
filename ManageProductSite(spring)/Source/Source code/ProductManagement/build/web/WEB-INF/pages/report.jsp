<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>

<link href="<c:url value="../assests/css/bootstrap.min.css" />" rel="stylesheet">
<link href="<c:url value="../assests/css/bootstrap-theme.min.css" />" rel="stylesheet">
<script src="../assests/js/jquery-3.2.1.min.js"></script>
<script  src="../assests/js/bootstrap.min.js"></script>
<%@page import="service.ProductService"%>
<%@page import="dao.ProductDao"%>
<%@page import="pojos.TbProduct"%>
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
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Xin chào, nhân viên
                    <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="#">Xem thông tin cá nhân</a></li>
                    <li><a href="#">Đổi mật khẩu</a></li>
                    <li><a href="#">Đăng xuất</a></li>
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
                    <a href="${pageContext.request.contextPath}/home/index" class="list-group-item list-group-item-info">Xem danh sách sản phẩm</a>
                    <a href="${pageContext.request.contextPath}/product/add" class="list-group-item">Thêm mới sản phẩm</a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <form:form action="${pageContext.request.contextPath}/ajax/index" modelAttribute="formAddProduct" method="post">
                <div>
                    <p style="color:blue;">Xóa thành công, Nhấn Next để tiếp tục</p>
                    <a href="${pageContext.request.contextPath}/home/index"">Next</a>
                    <div style="margin-left: 600px;">
                        <input type="submit" class="btn btn-rad-12" value="Lưu" />
                    </div>
                </div>
            </form:form>

        </div>
    </body>
</html>
