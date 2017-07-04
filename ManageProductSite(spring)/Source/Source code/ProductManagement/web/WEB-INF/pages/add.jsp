<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>

<link href="<c:url value="../assests/css/bootstrap.min.css" />" rel="stylesheet">
<link href="<c:url value="../assests/css/bootstrap-theme.min.css" />" rel="stylesheet">
<script src="../assests/js/jquery-3.2.1.min.js"></script>
<script  src="../assests/js/bootstrap.min.js"></script>

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<%@ taglib prefix="spring" uri="http://www.springframework.org/tags"%>
<%@ taglib prefix="form" uri="http://www.springframework.org/tags/form"%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>iShop - Bán hàng online</title>
        <style>
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
        </style>
    </head>

    <body>
        <div class="col-md-10"></div>
        <div class="col-md-2">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Xin chào, Quản lý
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
                    <a href="${pageContext.request.contextPath}/home/index" class="list-group-item">Xem danh sách sản phẩm</a>
                    <a href="${pageContext.request.contextPath}/product/add" class="list-group-item list-group-item-info">Thêm mới sản phẩm</a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <p style="color:red;">${msg}</p>
            <form:form action="${pageContext.request.contextPath}/product/add" modelAttribute="formAddProduct" method="post">
                    <table class="table">
                        <tr>
                            <td width="14%" height="20">Tên sản phẩm<font color="#FF0000">*</font></td>
                            <td width="75%"><form:input path="productName" type="text" style="width:500px; height:35px;"  placeholder="Tên sản phẩm" value=""/></td>
                        </tr>
                        <tr>
                            <td width="14%" height="20">Mô tả<font color="#FF0000">*</font></td>
                            <td width="75%"><form:input path="productDetail" type="text" style="width:500px; height:35px;" placeholder="Mô tả sản phẩm" value=""/></td>
                        </tr>
                        <tr>
                            <td width="14%" height="20">Giá<font color="#FF0000">*</font></td>
                            <td width="75%"><form:input path="productPrice" type="number" style="width:500px; height:35px;" placeholder="Giá sản phẩm" value=""/></td>
                        </tr>
                        <tr>
                            <td><font color="#FF0000"></font></td>
                            <td>
                                <div style="margin-left: 600px;">
                                    <input type="submit" class="btn btn-rad-12" value="Lưu" />
                                </div>
                            </td>
                        </tr>
                    </table>
            </form:form>
        </div>
        <script>
            /* When the user clicks on the button, 
             toggle between hiding and showing the dropdown content */
            function myFunction() {
                document.getElementById("myDropdown").classList.toggle("show");
            }

            // Close the dropdown if the user clicks outside of it
            window.onclick = function (event) {
                if (!event.target.matches('.dropbtn')) {

                    var dropdowns = document.getElementsByClassName("dropdown-content");
                    var i;
                    for (i = 0; i < dropdowns.length; i++) {
                        var openDropdown = dropdowns[i];
                        if (openDropdown.classList.contains('show')) {
                            openDropdown.classList.remove('show');
                        }
                    }
                }
            }
        </script>
    </body>
</html>
