
<footer>
    <?php echo $this->_sFooter; ?>
</footer>
<? if (Core::getParam('core.main_server') == 'cms.' && Core::getLib('module')->getModuleName() != 'print'): ?>
<div class="none">
    <div class="row-fluid">
            <label style="width: 100%;" align="center" id="txtRegStatus"></label>
                    <label style="height: 100%">Disable Video: <input style="float:left;padding-left:10px" type='checkbox' id='cbVideoDisable' checked disabled /></label>
            <input type="button" class="btn btn-success" id="btnRegister" value="Kết nối" disabled onclick='sipRegister();' />
                        &nbsp;
                        <input type="button" class="btn btn-danger" id="btnUnRegister" value="LogOut" disabled onclick='sipUnRegister();' />

        <div id="divCallCtrl" style='display:table-cell; vertical-align:middle'>
            <label style="width: 100%;" align="center" id="txtCallStatus"></label>
            <table style='width: 100%;'>
                <tr>
                    <td style="white-space:nowrap;">
                        <input type="text" style="width: 100%; height:100%;" id="txtPhoneNumber" value="" placeholder="Enter phone number to call" />
                    </td>
                </tr>
                <tr>
                    <td colspan="1" align="right">
                        <div class="btn-toolbar" style="margin: 0; vertical-align:middle">
                            <!--div class="btn-group">
                                <input type="button" id="btnBFCP" style="margin: 0; vertical-align:middle; height: 100%;" class="btn btn-primary" value="BFCP" onclick='sipShareScreen();' disabled />
                            </div-->
                            <div id="divBtnCallGroup" class="btn-group">
                                <button id="btnCall" disabled class="btn btn-primary" data-toggle="dropdown">Call</button>
                            </div>&nbsp;&nbsp;
                            <div class="btn-group">
                                <input type="button" id="btnHangUp" style="margin: 0; vertical-align:middle; height: 100%;" class="btn btn-primary" value="HangUp" onclick='sipHangUp();' disabled />
                            </div>
                         </div>
                    </td>
                </tr>
                <tr>
                    <td id="tdVideo" class='tab-video'>
                        <div id="divVideo" class='div-video'>
                            <div id="divVideoRemote" style='position:relative; border:1px solid #009; height:100%; width:100%; z-index: auto; opacity: 0'>
                                <video class="video" width="100%" height="100%" id="video_remote" autoplay style="opacity: 0; background-color: #000000; -webkit-transition-property: opacity; -webkit-transition-duration: 2s;">
                                </video>
                            </div>

                            <div id="divVideoLocalWrapper" style="margin-left: 0px; border:0px solid #009; z-index: 1000">
                                <iframe class="previewvideo" style="border:0px solid #009; z-index: 1000"> </iframe>
                                <div id="divVideoLocal" class="previewvideo" style=' border:0px solid #009; z-index: 1000'>
                                    <video class="video" width="100%" height="100%" id="video_local" autoplay muted style="opacity: 0; background-color: #000000; -webkit-transition-property: opacity; -webkit-transition-duration: 2s;">
                                    </video>
                                </div>
                            </div>
                            <div id="divScreencastLocalWrapper" style="margin-left: 90px; border:0px solid #009; z-index: 1000">
                                <iframe class="previewvideo" style="border:0px solid #009; z-index: 1000"> </iframe>
                                <div id="divScreencastLocal" class="previewvideo" style=' border:0px solid #009; z-index: 1000'>
                                </div>
                            </div>
                            <!--div id="div1" style="margin-left: 300px; border:0px solid #009; z-index: 1000">
                                <iframe class="previewvideo" style="border:0px solid #009; z-index: 1000"> </iframe>
                                <div id="div2" class="previewvideo" style='border:0px solid #009; z-index: 1000'>
                                  <input type="button" class="btn" style="" id="Button1" value="Button1" /> &nbsp;
                                  <input type="button" class="btn" style="" id="Button2" value="Button2" /> &nbsp;
                                </div>
                            </div-->
                        </div>
                    </td>
                </tr>
                <tr>
                   <td align='center'>
                        <div id='divCallOptions' class='call-options' style='opacity: 0; margin-top: 0px'>
                            <input type="button" class="btn" style="" id="btnFullScreen" value="FullScreen" disabled onclick='toggleFullScreen();' /> &nbsp;
                            <input type="button" class="btn" style="" id="btnMute" value="Mute" onclick='sipToggleMute();' /> &nbsp;
                            <input type="button" class="btn" style="" id="btnHoldResume" value="Hold" onclick='sipToggleHoldResume();' /> &nbsp;
                            <input type="button" class="btn" style="" id="btnTransfer" value="Transfer" onclick='sipTransfer();' /> &nbsp;
                            <input type="button" class="btn" style="" id="btnKeyPad" value="KeyPad" onclick='openKeyPad();' />
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

        <object id="fakePluginInstance" classid="clsid:69E4A9D1-824C-40DA-9680-C7424A27B6A0" style="visibility:hidden;"> </object>
        <div id='divGlassPanel' class='glass-panel' style='visibility:hidden'></div>
    <!-- KeyPad Div -->
    <div id='divKeyPad' class='span2 well div-keypad' style="left:0px; top:0px; width:250; height:240; visibility:hidden">
        <table style="width: 100%; height: 100%">
            <tr><td><input type="button" style="width: 33%" class="btn" value="1" onclick="sipSendDTMF('1');"/><input type="button" style="width: 33%" class="btn" value="2" onclick="sipSendDTMF('2');"/><input type="button" style="width: 33%" class="btn" value="3" onclick="sipSendDTMF('3');"/></td></tr>
            <tr><td><input type="button" style="width: 33%" class="btn" value="4" onclick="sipSendDTMF('4');"/><input type="button" style="width: 33%" class="btn" value="5" onclick="sipSendDTMF('5');"/><input type="button" style="width: 33%" class="btn" value="6" onclick="sipSendDTMF('6');"/></td></tr>
            <tr><td><input type="button" style="width: 33%" class="btn" value="7" onclick="sipSendDTMF('7');"/><input type="button" style="width: 33%" class="btn" value="8" onclick="sipSendDTMF('8');"/><input type="button" style="width: 33%" class="btn" value="9" onclick="sipSendDTMF('9');"/></td></tr>
            <tr><td><input type="button" style="width: 33%" class="btn" value="*" onclick="sipSendDTMF('*');"/><input type="button" style="width: 33%" class="btn" value="0" onclick="sipSendDTMF('0');"/><input type="button" style="width: 33%" class="btn" value="#" onclick="sipSendDTMF('#');"/></td></tr>
            <tr><td colspan=3><input type="button" style="width: 100%" class="btn btn-medium btn-danger" value="close" onclick="closeKeyPad();" /></td></tr>
        </table>
    </div>
    <!-- Call button options -->
    <ul id="ulCallOptions" class="dropdown-menu" style="visibility:hidden">
        <li><a href="#" onclick='sipCall("call-audio");'>Audio</a></li>
        <li><a href="#" onclick='sipCall("call-audiovideo");'>Video</a></li>
        <li id='liScreenShare' ><a href="#" onclick='sipShareScreen();'>Screen Share</a></li>
        <li class="divider"></li>
        <li><a href="#" onclick='uiDisableCallOptions();'><b>Disable these options</b></a></li>
    </ul>
</div>
<? endif;?>
<script type="text/javascript">
    $(function(){
    function detect_bowser()
    {
        var browser = navigator.appName;
        var b_version = navigator.appVersion;
        var version = parseFloat(b_version);
        var useragent = navigator.userAgent;
        switch (browser){
            case 'Microsoft Internet Explorer':
                browser = "msie";
                version = useragent.substr(useragent.lastIndexOf('MSIE') + 5, 3);
                break;
            case 'Netscape':
                if (useragent.lastIndexOf('Chrome/') > 0) {
                    browser = "chrome";
                    version = useragent.substr(useragent.lastIndexOf('Chrome/') + 7, 10);
                }
                else if (useragent.lastIndexOf('Firefox/') > 0) {
                    browser = "firefox";
                    version = useragent.substr(useragent.lastIndexOf('Firefox/') + 8, 5);
                }
                else if (useragent.lastIndexOf('Safari/') > 0) {
                    browser = "safari";
                    version = useragent.substr(useragent.lastIndexOf('Safari/') + 7, 7);
                }
                else {
                }
                break;
            case 'Opera':
                version = useragent.substr(useragent.lastIndexOf('Version/') + 8, 5);
                break;

        }
        return {browser: browser, version: version};
    }
    var tmps = detect_bowser();
    if(tmps.browser == 'chrome' && tmps.version < 34)
    {
        if(confirm("Vui lòng cập nhật trình duyệt.\nBạn có muốn chuyển đến trang cài đặt phiên bản mới")) window.location = 'https://www.google.com/intl/vi/chrome/browser/';
    }
    else if(tmps.browser == 'firefox' && tmps.version < 29)
    {
        if(confirm("Vui lòng cập nhật trình duyệt.\nBạn có muốn chuyển đến trang cài đặt phiên bản mới")) window.location = 'https://www.mozilla.org/vi/firefox/new/';
    }
    /*
    else if(tmps.browser == 'msie' && tmps.version < 29)
    {
        if(confirm("Vui lòng cập nhật trình duyệt.\nBạn có muốn chuyển đến trang cài đặt phiên bản mới")) window.location = 'https://www.mozilla.org/vi/firefox/new/';
    }
    */
});
</script>