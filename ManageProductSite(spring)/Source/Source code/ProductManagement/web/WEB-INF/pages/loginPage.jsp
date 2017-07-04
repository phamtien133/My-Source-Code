
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>

<link href="<c:url value="../assests/css/bootstrap.min.css" />" rel="stylesheet">
<link href="<c:url value="../assests/css/bootstrap-theme.min.css" />" rel="stylesheet">
<script src="../assests/js/jquery-3.2.1.min.js"></script>
<script  src="../assests/js/bootstrap.min.js"></script>

<%@page contentType="text/html" pageEncoding="UTF-8"%>
<%@ taglib prefix="spring" uri="http://www.springframework.org/tags"%>
<%@ taglib prefix="form" uri="http://www.springframework.org/tags/form"%>

<html>
    <head>
        <title>iShop - Đăng nhập</title>
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
        <div  style="margin-left: 365px; margin-top: 254px;">

            <!-- /login?error=true -->
            <c:if test="${param.error == 'true'}">
                <div style="color:red;margin:10px 0px;">

                    Login Failed!!!<br />
                    Reason :  ${sessionScope["SPRING_SECURITY_LAST_EXCEPTION"].message}

                </div>
            </c:if>

            <form name='f' action="${pageContext.request.contextPath}/j_spring_security_check" method='POST'>
                <table>
                    <tr>
                        <td width="14%" height="20">Tên đăng nhập<font color="#FF0000">*</font></td>
                        <td><input type='text' name='username' style="width:500px; height:35px;"  placeholder="Tên đăng nhập" value=''></td>
                    </tr>
                    <tr>
                        <td width="14%" height="20">Mật khẩu<font color="#FF0000">*</font></td>
                        <td><input type='password' style="width:500px; height:35px;"  placeholder="******" name='password' /></td>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <input class="btn btn-rad-12" name="submit" type="submit" value="Đăng nhập" />
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>