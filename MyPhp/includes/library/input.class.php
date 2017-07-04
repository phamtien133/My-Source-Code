<?php
class Input
{
    public function __construct()
    {
        
    }
    public function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
		$sort_col = array();
		foreach ($arr as $key=> $row) {
			$sort_col[$key] = $row[$col];
		}
	
		array_multisort($sort_col, $dir, $arr);
	}
	
	// function order by value of key in array
	public function aasort (&$array, $key) {
		$this->array_sort_by_column($array, $key);
	}
	
    public function removeXSS($sStr)
    {
        // remove hidden character - alt character
        $sStr = preg_replace('/[^\P{C}\n]+/u', '', $sStr);
        $sStr = stripslashes($sStr);
        return $sStr;
    }
    
    public function removeBreakLine($aParam)
    {
        if(!isset($aParam['text']))
            return '';
        $sText = $aParam['text'];
        $sReplace = '';
        if(isset($aParam['replace'])){
            $sReplace = $aParam['replace'];
        }
        $sText = str_replace(array("\t", "\r\n", "\n\r", "\n", "\r"), $sReplace, $sText);
        return $sText;
    }
    
    public function formatBbcode($sStr)
    {
        // To jump a line
        $sStr = str_replace(">
        <", '><', $sStr);
        $sStr = str_replace(">\n<", '><', $sStr);

        $sLowerStr = mb_strtolower($sStr);
        // quote
        if (preg_match('#\[/quote]#is', $sLowerStr)){
            $iEndLocation = 0;
            while(1){
                $sInput = '';
                $iStartKey = '[quote';
                $iEndKey = '[/quote]';
                $iFirstLocation = mb_strpos($sLowerStr, $iStartKey, 0, 'UTF8');
                if($iFirstLocation !== false){
                    $iEndLocation = mb_strpos($sLowerStr, $iEndKey, $iFirstLocation, 'UTF8');
                    if($iEndLocation !== false){
                        $sSearch = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation + mb_strlen($iEndKey, 'UTF8'), 'UTF8');
                        $iHeaderLocation = mb_strpos($sSearch, "]", 0, "UTF8") + 1;
                        $sHeader = mb_substr($sSearch, mb_strlen($iStartKey, 'UTF8') + 1, $iHeaderLocation - mb_strlen($iStartKey, 'UTF8') - 1, "UTF8");
                        $aTmp = explode(";", $sHeader, 2);
                        if($aTmp[0] && $aTmp[1]){
                            if(mb_substr($aTmp[0],0,1, "UTF8")=='"' || mb_substr($aTmp[0],0,1, "UTF8")=="'") 
                                $aTmp[0] = mb_substr($aTmp[0].'1',1,-1, "UTF8");
                            if(mb_substr($aTmp[1],-1,1, "UTF8")=='"' || mb_substr($aTmp[1],-1,1, "UTF8")=="'")
                                $aTmp[1] = mb_substr($aTmp[1],0,-1, "UTF8");
                            $sInput ='<div class="bbcode_container"><div class="bbcode_quote"><div class="quote_container"><div class="bbcode_postedby"><img title="Trích" src="/styles/global/images/misc/quote_icon.png" alt="Trích"> Nguyên văn bởi <strong>'.$aTmp[0].'</strong> <a href="/loi_binh/'.$aTmp[1].'/xem-loi-binh.html"><img title="Xem Lời Bình" class="inlineimg" src="/styles/global/images/lastpost-right.png" alt="View Post"></a></div><div class="message">';
                        }else{
                            preg_match('#\[quote=(.*?)]#is', $sSearch, $aTmp);
                            if($aTmp[1]){
                                if(mb_substr($aTmp[1],0,1, "UTF8")=='"' || mb_substr($aTmp[1],0,1, "UTF8")=="'")
                                    $aTmp[1] = mb_substr($aTmp[1].'1',1,-1, "UTF8");
                                if(mb_substr($aTmp[1],-1,1, "UTF8")=='"' || mb_substr($aTmp[1],-1,1, "UTF8")=="'")
                                    $aTmp[1] = mb_substr($aTmp[1],0,-1, "UTF8");
                                $sInput = '<div class="bbcode_container"><div class="bbcode_quote"><div class="quote_container"><div class="bbcode_postedby"><img title="Trích" src="/styles/global/images/misc/quote_icon.png" alt="Trích"> Nguyên văn bởi <strong>'.$aTmp[1].'</strong></div><div class="message">';
                            }
                        }
                        
                        if(!$sInput){
                            $sInput = '<div class="bbcode_container"><div class="bbcode_quote"><div class="quote_container"><div class="message">';
                            $iFirstLocation += mb_strlen($iStartKey, 'UTF8')+1;
                        }else 
                            $iFirstLocation += $iHeaderLocation;
                            
                        $sReplace = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation, 'UTF8');
                        
                        while(1){
                            if(substr($sReplace, 0, 2) == '</'){
                                $iCloseTagLocation = mb_strpos($sReplace, '>', 0, "UTF8");
                                $sReplace = mb_substr($sReplace."-", $iCloseTagLocation + 1, -1, "UTF8");
                            }
                            else 
                                break;
                        }
                        
                        if(substr($sReplace."-", -1, 1) == '>'){
                            $iCloseTagLocation = mb_strrpos($sReplace, '</', 0, "UTF8");
                            if($iCloseTagLocation !== false){
                                $iEndCloseTagLocation = mb_strpos($sReplace, '>', $iCloseTagLocation, "UTF8");
                                if($iEndCloseTagLocation !== false)
                                    $sReplace = mb_substr($sReplace."-", 0, $iEndCloseTagLocation + 1, "UTF8");
                            }
                        }
                        $sStr = str_replace($sSearch, $sInput.$sReplace.'</div></div></div></div>', $sStr);
                        $sLowerStr = mb_strtolower($sStr,'UTF8');
                    }
                    else break;
                }
                else break;
            }
        }
        // gmap
        if(preg_match('#\[/gmap]#is', $sLowerStr)){
            $iEndLocation = 0;
            while(1){
                $iStartKey = '[gmap]';
                $iEndKey = '[/gmap]';
                $iFirstLocation = mb_strpos($sLowerStr, $iStartKey, 0, 'UTF8');
                if($iFirstLocation !== false){
                    $iEndLocation = mb_strpos($sLowerStr, $iEndKey, $iFirstLocation, 'UTF8');
                    if($iEndLocation!==false){
                        $sSearch = mb_substr($sStr, $iFirstLocation, $iEndLocation-$iFirstLocation + mb_strlen($iEndKey, 'UTF8'), 'UTF8');
                        $iFirstLocation += mb_strlen($iStartKey, 'UTF8');
                        $sReplace = mb_substr($sStr, $iFirstLocation,$iEndLocation - $iFirstLocation, 'UTF8');
                        
                        if(strpos($sReplace, '|') !== false){
                            $aTmp = explode('|', $sReplace);
                            foreach($aTmp as $sValue){
                                $aTmps = explode(':', $sValue, 2);
                                $aTmps[1] = $this->removeBreakLine(array(
                                    'text' => $aTmps[1]
                                ));
                                
                                if($aTmps[0] == 'width' || $aTmps[0] == 'height' || $aTmps[0] == 'type'){
                                    $aObject[$aTmps[0]] = $aTmps[1];
                                    continue;
                                }
                                $aObject[$aTmps[0]][] = $aTmps[1];
                            }
                        }else{
                            $aTmp[0] = $sReplace;
                            $aTmp[1] = '';
                            $aTmp[2] = 'auto';
                            $aTmp[3] = 'auto';
                            $aObject['width'] = '100%';
                            $aObject['height'] = '250px';
                        }
                        $sReplace = time();

                        $sTmpContent = '';
                        $sTmpContent2 = '';
                        for($iCnt = 0; $iCnt < count($aObject['name']); $iCnt++){
                            if($aObject['type'] != 'one'){
                                $sTmpContent2 .= '<div><h3><a>'.$aObject['name'][$iCnt].'</a></h3></div><div>'.$aObject['addr'][$iCnt].'<br>'.$aObject['phone'][$iCnt].'</div>';
                            }
                            $sTmpContent .= '["'.$aObject['name'][$iCnt].'", '.$aObject['lat'][$iCnt].', '.$aObject['lng'][$iCnt].', "<div class=\"gmap_content\"><h3><a>'.$aObject['name'][$iCnt].'</a></h3></div><div>'.$aObject['addr'][$iCnt].'<br>'.$aObject['phone'][$iCnt].'</div>"],';
                        }
                        $sTmpContent = mb_substr($sTmpContent, 0, -1);
                        if($aObject['type'] == 'one'){
                            $sReplace = '<div class="gmap" id="gmap_'.$sReplace.'" style="width:'.$aObject['width'].';height:'.$aObject['height'].'"></div><script>$(function(){cai_dat_ban_do("gmap_'.$sReplace.'", ['.$sTmpContent.']);});</script>';
                        }else{
                            $sReplace = '<div class="gmap"><div class="flet gmap_left">'.$sTmpContent2.'</div><div class="gmap_right" id="gmap_'.$sReplace.'" style="width:'.$aObject['width'].';height:'.$aObject['height'].'"></div></div><script>$(function(){cai_dat_ban_do("gmap_'.$sReplace.'", ['.$sTmpContent.']);});</script>';
                        }
                        $sStr = str_replace($sSearch, $sReplace, $sStr);
                        $sLowerStr = mb_strtolower($sStr,'UTF8');
                    }
                    else break;
                }
                else break;
            }
        }
        // code
        if (preg_match('#\[/code]#is', $sLowerStr)){
            $iEndLocation = 0;
            while(1){
                $iStartKey = '[code]';
                $iEndKey = '[/code]';
                $iFirstLocation = mb_strpos($sLowerStr, $iStartKey, 0, 'UTF8');
                if($iFirstLocation !== false){
                    $iEndLocation = mb_strpos($sLowerStr, $iEndKey, $iFirstLocation, 'UTF8');
                    if($iEndLocation !== false){
                        $sSearch = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation + mb_strlen($iEndKey, 'UTF8'), 'UTF8');
                        $iFirstLocation += mb_strlen($iStartKey, 'UTF8');
                        $sReplace = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation, 'UTF8');
                        $sReplace = str_replace("<p>", "\n", $sReplace);
                        $sReplace = str_replace("</p>", "", $sReplace);
                        $sStr = str_replace($sSearch, '<pre name="code" class="brush: CSharp;">'.$sReplace.'</pre>', $sStr);
                        $sLowerStr = mb_strtolower($sStr,'UTF8');
                    }
                    else break;
                }
                else break;
            }
        }
        // php
        if (preg_match('#\[/php]#is', $sLowerStr)){
            $iEndLocation = 0;
            while(1){
                $iStartKey = '[php]';
                $iEndKey = '[/php]';
                $iFirstLocation = mb_strpos($sLowerStr, $iStartKey, 0, 'UTF8');
                if($iFirstLocation !== false){
                    $iEndLocation = mb_strpos($sLowerStr, $iEndKey, $iFirstLocation, 'UTF8');
                    if($iEndLocation !== false){
                        $sSearch = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation + mb_strlen($iEndKey, 'UTF8'), 'UTF8');
                        $iFirstLocation += mb_strlen($iStartKey, 'UTF8');
                        $sReplace = mb_substr($sStr, $iFirstLocation,$iEndLocation - $iFirstLocation, 'UTF8');
                        $sReplace = str_replace("<p>", "\n", $sReplace);
                        $sReplace = str_replace("</p>", "", $sReplace);
                        $sStr = str_replace($sSearch, '<pre name="code" class="brush: php;">'.$sReplace.'</pre>', $sStr);
                        $sLowerStr = mb_strtolower($sStr,'UTF8');
                    }else break;
                }
                else break;
            }
        }
        // java
        if (preg_match('#\[/java]#is', $sLowerStr)){
            $iEndLocation = 0;
            while(1){
                $iStartKey = '[java]';
                $iEndKey = '[/java]';
                $iFirstLocation = mb_strpos($sLowerStr, $iStartKey, 0, 'UTF8');
                if($iFirstLocation !== false){
                    $iEndLocation = mb_strpos($sLowerStr, $iEndKey, $iFirstLocation, 'UTF8');
                    if($iEndLocation !== false){
                        $sSearch = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation + mb_strlen($iEndKey, 'UTF8'), 'UTF8');
                        $iFirstLocation += mb_strlen($iStartKey, 'UTF8');
                        $sReplace = mb_substr($sStr, $iFirstLocation,$iEndLocation - $iFirstLocation, 'UTF8');
                        $sReplace = str_replace("<p>", "\n", $sReplace);
                        $sReplace = str_replace("</p>", "", $sReplace);
                        $sStr = str_replace($sSearch, '<pre name="code" class="brush: java;">'.$sReplace.'</pre>', $sStr);
                        $sLowerStr = mb_strtolower($sStr,'UTF8');
                    }
                    else break;
                }
                else break;
            }
        }
        // html
        if (preg_match('#\[/html]#is', $sLowerStr)){
            $iEndLocation = 0;
            while(1){
                $iStartKey = '[html]';
                $iEndKey = '[/html]';
                $iFirstLocation = mb_strpos($sLowerStr, $iStartKey, 0, 'UTF8');
                if($iFirstLocation !== false){
                    $iEndLocation = mb_strpos($sLowerStr, $iEndKey, $iFirstLocation, 'UTF8');
                    if($iEndLocation !== false){
                        $sSearch = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation + mb_strlen($iEndKey, 'UTF8'), 'UTF8');
                        $iFirstLocation += mb_strlen($iStartKey, 'UTF8');
                        $sReplace = mb_substr($sStr, $iFirstLocation,$iEndLocation - $iFirstLocation, 'UTF8');
                        $sReplace = str_replace("<p>", "\n", $sReplace);
                        $sReplace = str_replace("</p>", "", $sReplace);
                        $sStr = str_replace($sSearch, '<pre name="code" class="brush: xhtml;">'.$sReplace.'</pre>', $sStr);
                        $sLowerStr = mb_strtolower($sStr,'UTF8');
                    }
                    else break;
                }
                else break;
            }
        }
        // spl
        if (preg_match('#\[/spl]#is', $sLowerStr)){
                $iEndLocation = 0;
                while(1){
                    $iStartKey = '[spl=';
                    $iEndKey = '[/spl]';
                    $iFirstLocation = mb_strpos($sLowerStr, $iStartKey, 0, 'UTF8');
                    if($iFirstLocation !== false){
                        $iEndLocation = mb_strpos($sLowerStr, $iEndKey, $iFirstLocation, 'UTF8');
                        if($iEndLocation !== false){
                            $sSearch = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation + mb_strlen($iEndKey, 'UTF8'), 'UTF8');
                            $iMiddleLocation += mb_strpos($sSearch, ']', 1, 'UTF8') + 1;
                            $iFirstLocation += mb_strlen($iStartKey, 'UTF8');
                            $sReplace = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation, 'UTF8');
                            $iMiddleLocation = mb_strpos($sReplace, ']', 1, 'UTF8');
                            if($iMiddleLocation !== false){
                                $count = 1;
                                $sTmp = mb_substr($sReplace, 0, $iMiddleLocation, 'UTF8');
                                if(mb_substr($sTmp, 0, 1, "UTF8") == '"' || mb_substr($sTmp, 0, 1, "UTF8") == "'")
                                    $sTmp = mb_substr($sTmp.'1', 1, -1, "UTF8");
                                if(mb_substr($sTmp, -1, 1, "UTF8") == '"' || mb_substr($sTmp, -1, 1, "UTF8") == "'")
                                    $sTmp = mb_substr($sTmp, 0, -1, "UTF8");
                                
                                $sReplace = mb_substr($sReplace.'1', $iMiddleLocation + 1, -1, 'UTF8');
                                $sStr = str_replace($sSearch, '<input class="button" size="15" onclick="if(this.nextSibling.style.display!=\'\'){this.nextSibling.style.display=\'\';this.innerText = \'\'; this.value = \'Ẩn: '.$sTmp.'\'; }else{this.nextSibling.style.display=\'none\';this.innerText = \'\'; this.value=\''.$sTmp.'\';}" value="'.$sTmp.'" type="button"><div class="alt2" style="margin-top: 5px; border: 1px inset; padding: 2px; display: none;">'.$sReplace.'</div>', $sStr);
                                $sLowerStr = mb_strtolower($sStr,'UTF8');
                            }
                            else break;
                        }
                        else break;
                    }
                    else break;
                }
            }
        // video
        if (preg_match('#\[/video]#is', $sLowerStr)){
            $iEndLocation = 0;
            while(1){
                $iStartKey = '[video]';
                $iEndKey = '[/video]';
                $iFirstLocation = @mb_strpos($sLowerStr, $iStartKey, $iEndLocation, 'UTF8');
                if($iFirstLocation !== false){
                    $iEndLocation = mb_strpos($sLowerStr, $iEndKey, $iFirstLocation, 'UTF8');
                    if($iEndLocation!==false){
                        $sSearch = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation + mb_strlen($iEndKey, 'UTF8'), 'UTF8');
                        $iFirstLocation += mb_strlen($iStartKey, 'UTF8');
                        $sReplace = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation, 'UTF8');
                        
                        /**
                        * change to string
                        * format: (width:100;height:100;type:11)
                        * [video]width=100&height=100&type=11-http://abc.vn[/video]
                        */
                        $aTmp = explode('-', $sReplace, 2);
                        if(strlen($aTmp[0]) != 2){
                            parse_str($aTmp[0], $aOutput);
                            if(!empty($aOutput['width']))
                                $sWidth = $aOutput['width'];
                            else
                                $sWidth = '100%';
                            
                            if(!empty($aOutput['height']))
                                $sHeight = $aOutput['height'];
                            else
                                $sHeight = '100%';
                            
                            if(!empty($aOutput['type']))
                                $iType = $aOutput['type'];
                            else continue;
                        }else{
                            $iType = $aTmp[0];
                        }
                        
                        if($iType == '11'){
                            $sReplace = substr($sReplace, 3);
                            $sStr = str_replace($sSearch, '<div class="embedyoutube"><object height="100%" width="100%" align="center"><param name="movie" value="http://www.youtube.com/v/'.$sReplace.'&modestbranding=1&showinfo=0&hl=en&fs=1&rel=0&showsearch=0"></param><param name="wmode" value="transparent"><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$sReplace.'&modestbranding=1&showinfo=0&hl=en&fs=1&rel=0&showsearch=0" wmode="transparent" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" height="100%" width="100%" align="center"></embed></object></div>', $sStr);
                        }elseif($iType == '12'){
                            $sReplace = substr($sReplace, 3);
                            $sStr = str_replace($sSearch, '<div align="center"><object type="application/x-shockwave-flash" width="100%" data="http://www.vimeo.com/moogaloop.swf?clip_id='.$sReplace.'"><param name="quality" value="best" /><param name="allowfullscreen" value="true" /><param name="scale" value="showAll" /><param name="movie" value="http://www.vimeo.com/moogaloop.swf?clip_id='.$sReplace.'" /></object></div>', $sStr);
                        }elseif($iType == '13'){
                            $sReplace = substr($sReplace, 3);
                            $sStr = str_replace($sSearch, '<div align="center"><object width="400" height="326" type="application/x-shockwave-flash" data="http://video.google.com/googleplayer.swf?docId='.$sReplace.'"><param name="movie" value="http://video.google.com/googleplayer.swf?docId='.$sReplace.'" /><param name="allowScriptAccess" value="sameDomain" /><param name="quality" value="best" /><param name="scale" value="noScale" /><param name="wmode" value="transparent" /><param name="salign" value="TL" /><param name="FlashVars" value="playerMode=embedded" /><em><strong>ERROR:</strong> If you can see this, then <a href="http://video.google.com/">Google Video</a> is down or you don\'t have Flash installed.</em></object></div>', $sStr);
                        }elseif($iType == '14'){
                            $sReplace = substr($sReplace, 3);
                            $sStr = str_replace($sSearch, '<div align="center"><object width="300" height="300">  <param name="movie" value="http://www.nhaccuatui.com/m/'.$sReplace.'" />  <param name="quality" value="high" />  <param name="wmode" value="transparent" />  <embed src="http://www.nhaccuatui.com/m/'.$sReplace.'" quality="high" wmode="transparent" type="application/x-shockwave-flash" width="300" height="300"></embed></object></div>', $sStr);
                        }elseif($iType == '15'){
                            $sReplace = substr($sReplace, 3);
                            $sStr = str_replace($sSearch, '<div align="center"><object width="480" height="385"><param name="movie" value="http://clip.vn/w/'.$sReplace.'"/><param name="allowFullScreen" value="true"/><param name="allowScriptAccess" value="always"/><embed type="application/x-shockwave-flash" allowFullScreen="true" allowScriptAccess="always" width="480" height="385" src="http://clip.vn/w/'.$sReplace.'"></embed></object></div>', $sStr);
                        }elseif($iType == '16'){
                            $sReplace = substr($sReplace, 3);
                            $sStr = str_replace($sSearch, '<div align="center"><object width="480" height="269"><param name="movie" value="http://www.dailymotion.com/swf/video/'.$sReplace.'?theme=none"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><param name="wmode" value="transparent"></param><embed type="application/x-shockwave-flash" src="http://www.dailymotion.com/swf/video/'.$sReplace.'?theme=none" width="480" height="269" wmode="transparent" allowfullscreen="true" allowscriptaccess="always"></embed></object></div>', $sStr);
                        }elseif($iType == '10'){
                            $iTmp = substr($sReplace, 3, 1);
                            $sReplace = substr($sReplace, 5);
                            if($iTmp == 1){
                                $sStr = str_replace($sSearch, '<div align="center"><OBJECT ID="NSPlay" classid="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95"codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701"standby="Loading Microsoft Windows Media Player components..."type="application/x-oleobject" width="304" height="279"><param name="AudioStream" value="-1"><param name="AutoSize" value="0"><param name="AutoStart" value="0"><param name="AnimationAtStart" value="-1"><param name="AllowScan" value="-1"><param name="AllowChangeDisplaySize" value="-1"><param name="AutoRewind" value="1"><param name="Balance" value="0"><param name="BaseURL" value><param name="BufferingTime" value="5"><param name="CaptioningID" value><param name="ClickToPlay" value="-1"><param name="CursorType" value="0"><param name="CurrentPosition" value="-1"><param name="CurrentMarker" value="0"><param name="DefaultFrame" value><param name="DisplayBackColor" value="0"><param name="DisplayForeColor" value="16777215"><param name="DisplayMode" value="0"><param name="DisplaySize" value="4"><param name="Enabled" value="-1"><param name="EnableContextMenu" value="0"><param name="EnablePositionControls" value="-1"><param name="EnableFullScreenControls" value="0"><param name="EnableTracker" value="-1"><param name="Filename" value=\''.$sReplace.'\'><param name="InvokeURLs" value="-1"><param name="Language" value="-1"><param name="Mute" value="0"><param name="PlayCount" value="1"><param name="PreviewMode" value="0"><param name="Rate" value="1"><param name="SAMILang" value><param name="SAMIStyle" value><param name="SAMIFileName" value><param name="SelectionStart" value="-1"><param name="SelectionEnd" value="-1"><param name="SendOpenStateChangeEvents" value="-1"><param name="SendWarningEvents" value="-1"><param name="SendErrorEvents" value="-1"><param name="SendKeyboardEvents" value="0"><param name="SendMouseClickEvents" value="0"><param name="SendMouseMoveEvents" value="0"><param name="SendPlayStateChangeEvents" value="-1"><param name="ShowCaptioning" value="0"><param name="ShowControls" value="-1"><param name="ShowAudioControls" value="-1"><param name="ShowDisplay" value="0"><param name="ShowGotoBar" value="0"><param name="ShowPositionControls" value="-1"><param name="ShowStatusBar" value="0"><param name="ShowTracker" value="-1"><param name="TransparentAtStart" value="0"><param name="VideoBorderWidth" value="0"><param name="VideoBorderColor" value="0"><param name="VideoBorder3D" value="0"><param name="Volume" value="0"><param name="WindowlessVideo" value="0"><embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/Downloads/Contents/Products/MediaPlayer/" file=\''.$sReplace.'\' src=\''.$sReplace.'\' ShowControls="1" ShowDisplay="0" ShowStatusBar="1" width="320" height="320"></OBJECT></div>', $sStr);
                            }else{
                                $sStr = str_replace($sSearch, '<div align="center"><embed type="application/x-shockwave-flash" src="/styles/global/images/player.swf" style="" id="playlist" name="playlist" quality="high" allowscriptaccess="always" allowfullscreen="true" wmode="transparent" flashvars="&amp;extjs=Poly_ShowAdsVideoLarge&amp;shuffle=false&amp;repeat=list&amp;volume=60&amp;smoothing=true&amp;screencolor=000000&amp;lightcolor=000000&amp;frontcolor=000000&amp;start=0&amp;provider=http&amp;file='.$sReplace.'&amp;autostart=false" height="380" width="480"></div>', $sStr);
                            }
                        }
                        $sLowerStr = mb_strtolower($sStr,'UTF8');
                    }
                    else break;
                }
                else break;
            }
        }
        // flash
        if (preg_match('/\[\/flash]/i', $sLowerStr)){
            $aBeginTag = explode('[flash]',$sLowerStr);
            $aEndTag = explode('[/flash]', $sLowerStr);
            if (count($aBeginTag) == count($aEndTag)) {
                $sInput = '<embed wmode="transparent" src="';
                $sTmp = '" type="application/x-shockwave-flash" width="425" height="340">';
                $sStr = str_replace("[flash]", $sInput, $sStr);
                $sStr = str_replace("[FLASH]", $sInput, $sStr);
                $sStr = str_replace("[/flash]", $sTmp, $sStr);
                $sStr = str_replace("[/FLASH]", $sTmp, $sStr);
                $sLowerStr = mb_strtolower($sStr,'UTF8');
            }
        }
        // PDF
        if (preg_match('#\[/pdf]#is', $sLowerStr)){
            $iEndLocation = 0;
            while(1){
                $iStartKey = '[pdf]';
                $iEndKey = '[/pdf]';
                $iFirstLocation = mb_strpos($sLowerStr, $iStartKey, 0, 'UTF8');
                if($iFirstLocation !== false){
                    $iEndLocation = mb_strpos($sLowerStr, $iEndKey, $iFirstLocation, 'UTF8');
                    if($iEndLocation !== false){
                        $sSearch = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation + mb_strlen($iEndKey, 'UTF8'), 'UTF8');
                        $iFirstLocation += mb_strlen($iStartKey, 'UTF8');
                        $sReplace = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation, 'UTF8');
                        $sStr = str_replace($sSearch, '<div align="center"><iframe src="http://docs.google.com/gview?url='.$sReplace.'&amp;embedded=true" style="width: 100%; height: 600px;" frameborder="0"></iframe></div>', $sStr);
                        $sLowerStr = mb_strtolower($sStr,'UTF8');
                    }
                    else break;
                }
                else break;
            }
        }
        // PDF SHARE
        if (preg_match('#\[/pdfshare]#is', $sLowerStr)){
            $iEndLocation = 0;
            while(1){
                $iStartKey = '[pdfshare]';
                $iEndKey = '[/pdfshare]';
                $iFirstLocation = mb_strpos($sLowerStr, $iStartKey, 0, 'UTF8');
                if($iFirstLocation !== false){
                    $iEndLocation = mb_strpos($sLowerStr, $iEndKey, $iFirstLocation, 'UTF8');
                    if($iEndLocation !== false){
                        $sSearch = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation + mb_strlen($iEndKey, 'UTF8'), 'UTF8');
                        $iFirstLocation += mb_strlen($iStartKey, 'UTF8');
                        $sReplace = mb_substr($sStr, $iFirstLocation,$iEndLocation - $iFirstLocation, 'UTF8');
                        $sStr = str_replace($sSearch, '<div align="center"> <p align="center"> <embed src="'.$sReplace.'" type="application/pdf" width="700" height="900"></embed> <br /> <a href="'.$sReplace.'">Click here to view or download the file directly.</a> <br /> </p></div>', $sStr);
                        $sLowerStr = mb_strtolower($sStr,'UTF8');
                    }
                    else break;
                }
                else break;
            }
        }
        // likecontent
        if (preg_match('#\[/likecontent]#is', $sLowerStr)){
            $iEndLocation = 0;
            while(1){
                $iStartKey = '[likecontent]';
                $iEndKey = '[/likecontent]';
                $iFirstLocation = mb_strpos($sLowerStr, $iStartKey, 0, 'UTF8');
                if($iFirstLocation !== false){
                    $iEndLocation = mb_strpos($sLowerStr, $iEndKey, $iFirstLocation, 'UTF8');
                    if($iEndLocation !== false){
                        $sSearch = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation + mb_strlen($iEndKey, 'UTF8'), 'UTF8');
                        $iFirstLocation += mb_strlen($iStartKey, 'UTF8');
                        $sReplace = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation, 'UTF8');
                        $sStr = str_replace($sSearch, '<div class="likecontent">'.$sReplace.'</div>', $sStr);
                        $sLowerStr = mb_strtolower($sStr,'UTF8');
                    }
                    else break;
                }
                else break;
            }
        }
        // music
        if (preg_match('#\[/music]#is', $sLowerStr)){
            $iEndLocation = 0;
            while(1){
                $iStartKey = '[music]';
                $iEndKey = '[/music]';
                $iFirstLocation = mb_strpos($sLowerStr, $iStartKey, 0, 'UTF8');
                if($iFirstLocation!==false){
                    $iEndLocation = mb_strpos($sLowerStr, $iEndKey, $iFirstLocation, 'UTF8');
                    if($iEndLocation!==false){
                        $sSearch = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation+mb_strlen($iEndKey, 'UTF8'), 'UTF8');
                        $iFirstLocation += mb_strlen($iStartKey, 'UTF8');
                        $sReplace = mb_substr($sStr, $iFirstLocation, $iEndLocation - $iFirstLocation, 'UTF8');
                        $sStr = str_replace($sSearch, '<div align="center" class="smallfont"><object><embed pluginspage="http://www.microsoft.com/Windows/Downloads/Contents/Products/MediaPlayer/" src="'.$sReplace.'" type="application/x-mplayer2" showstatusbar="1" volume="100" autostart="0" showcontrols="1" width="425" height="340"></object></div>', $sStr);
                        $sLowerStr = mb_strtolower($sStr,'UTF8');
                    }
                    else break;
                }
                else break;
            }
        }
        
        return($sStr);
    }
    
    public function removeDuplicate($aParam)
    {
        if(!isset($aParam['text']))
            return '';
        $sText = $aParam['text'];
        $sKey = '  ';
        $sKeyReplace = ' ';
        if(isset($aParam['key'])){
            $sKey = $aParam['key'];
        }
        if(isset($aParam['replace'])){
            $sKeyReplace = $aParam['replace'];
        }
        if ($sKey == $sKeyReplace)
            return $sText;
        while (1) {
            if (strpos($sText, $sKey) !== false) {
                $sText = str_replace($sKey, $sKeyReplace, $sText);
            }
            else
                break;
        }
        return $sText;
    }
    
    public function filterTagsPHeadLine($sContent)
    {
        $bIsContinue = false;
        if (strpos($sContent, "\n") === false) {
            // đếm sổ thẻ p
            $iPosition = strpos($sContent, '<p>');
            if ($iPosition  !== false) {
                $iPosition = strpos($sContent, '<p>', $iPosition + 1);
                if ($iPosition  === false) {
                    // kiểm tra xem có tồn tại thẻ kết thúc p không
                    $iPosition = strpos($sContent, '</p>', $iPosition + 1);
                    if ($iPosition  !== false)
                        $bIsContinue = true;
                }
            }
            // end
        }
        if ($bIsContinue) {
            $sContent = mb_substr($sContent, 3, -4, 'utf8');
        }
        return $sContent;
    }
    
    public function optimalBbcode($sStr)
    {
        $sStrToLower = mb_strtolower($sStr, 'utf8');
        // video
        if (strpos($sStrToLower, '[/video]') !== false)  {
            $aWeb = array(
                '20',
                '11',
                '11',
                '12',
                '13', '14', '15','16', '17');
            $aWebValue = array(
                'video',
                'youtu',
                'youtube',
                'vimeo',
                'google', 'nhaccuatui', 'clip','dailymotion', 'tamtay');
            $iPositionLast = 0;
            while (1) {
                $sUrl = '';
                $sKeyStart = '[video]';
                $sKeyEnd = '[/video]';
                $iPositionFirst = @mb_strpos($sStrToLower, $sKeyStart, $iPositionLast, 'UTF8');
                if ($iPositionFirst!==false) {
                    $iPositionLast = mb_strpos($sStrToLower, $sKeyEnd, $iPositionFirst, 'UTF8');
                    if ($iPositionLast!==false) {
                        $sVarExtra = mb_substr($sStr, $iPositionFirst, $iPositionLast - $iPositionFirst + mb_strlen($sKeyEnd, 'UTF8'), 'UTF8');
                        $iPositionFirst += mb_strlen($sKeyStart, 'UTF8');
                        $sVarMain = mb_substr($sStr, $iPositionFirst,$iPositionLast-$iPositionFirst, 'UTF8');
                        $sVarMain = $this->removeXSS($sVarMain);
                        $bIsProcess = true;
                        if (strpos($sVarMain, '//') !== false) {
                            $sUrl = substr($sVarMain, strpos($sVarMain, '//') + 2);
                            $sUrl = substr($sUrl, 0, strpos($sUrl, '/'));
                            $sUrl = explode('.', $sUrl);
                            $sUrl = $sUrl[count($sUrl)-2];
                        }
                        
                        if ($sUrl == '') {
                            $sTmp = substr($sVarMain, 0, 2)*1;
                            for ($i = 0;$i < count($aWeb); $i++) {
                                if ($sTmp == $aWeb[$i])
                                    $sUrl = $aWebValue[$i];
                            }
                            if ($sUrl != '') {
                                $bIsProcess = false;
                                $sVarMain = substr($sVarMain, 3);
                            }
                        }
                        if ($bIsProcess && ($sUrl == 'youtube' || $sUrl == 'youtu')) {
                            $bIsExist = -1;
                            $aTmp = array(
                                'v=',
                                '/v/',
                                '/embed/'
                            );
                            for ($i = 0;$i < count($aTmp); $i++) {
                                if ($bIsExist == -1) {
                                    $iPosition = strpos($sVarMain, $aTmp[$i]);
                                    if ($iPosition !== false) {
                                        $bIsExist = $i;
                                        $sVarMain = substr($sVarMain, $iPosition+strlen($aTmp[$i]));
                                    }
                                }
                                else
                                    break;
                            }
                            if ($bIsExist == -1) {
                                $iPosition = strpos($sVarMain, '/', 7);
                                if ($iPosition !== false) {
                                    $sVarMain = substr($sVarMain, $iPosition+1);
                                }
                            }
                            elseif ($bIsExist == 0) {
                                $iTmp = strpos($sVarMain, '&');
                                if ($iTmp !== false) {
                                    $sVarMain = substr($sVarMain, 0, $iTmp);
                                }
                            }
                            elseif ($bIsExist == 1) {
                                $iTmp = strpos($sVarMain, '&');
                                if ($iTmp !== false) {
                                    $sVarMain = substr($sVarMain, 0, $iTmp);
                                }
                            }
                            elseif ($bIsExist == 2) {
                                $iTmp = strpos($sVarMain, '?');
                                if ($iTmp !== false) {
                                    $sVarMain = substr($sVarMain, 0, $iTmp);
                                }
                            }
                            $sStr = str_replace($sVarExtra, '[video]11-'.$sVarMain.'[/video]', $sStr);
                        }
                        elseif ($bIsProcess && $sUrl == 'vimeo') {
                            $iPosition = strpos($sVarMain, '/', 7);
                            if ($iPosition !== false) {
                                $sVarMain = substr($sVarMain, $iPosition + 1);
                            }
                            $sStr = str_replace($sVarExtra, '[video]12-'.$sVarMain.'[/video]', $sStr);
                        }
                        elseif ($bIsProcess && $sUrl == 'google') {
                            $iPosition = strpos($sVarMain, 'd=');
                            if ($iPosition !== false) {
                                $sVarMain = substr($sVarMain, $iPosition + 2);
                                $iPosition = strpos($sVarMain, '&');
                                if ($iPosition !== false) {
                                    $sVarMain = substr($sVarMain, $iPosition+1);
                                }
                            }
                            $sStr = str_replace($sVarExtra, '[video]12-'.$sVarMain.'[/video]', $sStr);
                        }
                        elseif ($bIsProcess && $sUrl == 'nhaccuatui') {
                            $iPosition = strrpos($sVarMain, '=');
                            if ($iPosition !== false) {
                                $sVarMain = substr($sVarMain, $iPosition + 1);
                            }
                            $sStr = str_replace($sVarExtra, '[video]14-'.$sVarMain.'[/video]', $sStr);
                        }
                        elseif ($bIsProcess && $sUrl == 'clip') {
                            $aTmp = array(
                                ',',
                                '/w/'
                            );
                            $bIsExist = -1;
                            for ($i = 0;$i < count($aTmp); $i++) {
                                if ($bIsExist == -1) {
                                    $iPosition = strpos($sVarMain, $aTmp[$i]);
                                    if ($iPosition !== false) {
                                        $bIsExist = $i;
                                        $sVarMain = substr($sVarMain, $iPosition+strlen($aTmp[$i]));
                                    }
                                }
                                else
                                    break;
                            }
                            if ($bIsExist != -1)
                                $sStr = str_replace($sVarExtra, '[video]15-'.$sVarMain.'[/video]', $sStr);
                        }
                        elseif ($bIsProcess && $sUrl == 'dailymotion') {
                            $iPosition = strrpos($sVarMain, 'video/');
                            if ($iPosition !== false) {
                                $iPosition+=6;
                                $iPositionLast = strpos($sVarMain, '_', $iPosition);
                                if ($iPositionLast !== false)
                                    $sVarMain = substr($sVarMain, $iPosition, $iPositionLast-$iPosition);
                                else
                                    $sVarMain = substr($sVarMain, $iPosition);
                            }
                            $sStr = str_replace($sVarExtra, '[video]16-'.$sVarMain.'[/video]', $sStr);
                        }
                        elseif ($bIsProcess) {
                            if (strpos($sVarMain, '.wmv') !== false || strpos($sVarMain, '.mkv') !== false
                                || strpos($sVarMain, '.avi') !== false)
                                $iType = 1;
                            else
                                $iType = 0;
                            $sStr = str_replace($sVarExtra, '[video]10-'.$iType.'-'.$sVarMain.'[/video]', $sStr);
                        }
                        $sStrToLower = mb_strtolower($sStr,'UTF8');
                    }
                    else
                        break;
                }
                else
                    break;
            }
        }
        return $sStr;
    }
    
    public function modifyUrl($sStr)
    {
        $iPositionLast = 0;
        $iCount = 0;
        while (1) {
            $iCount++;
            $sStrToLower = mb_strtolower($sStr, 'UTF8');
            $sUrl = '';
            $iPositionTagsALast = 0;
            $iPositionFirst = mb_strpos($sStrToLower, '<a', $iPositionLast, 'UTF8');
            if ($iPositionFirst === false)
                break;
            
            $iPositionLast = mb_strpos($sStrToLower, '>', $iPositionFirst, 'UTF8');
            if ($iPositionLast === false)
                break;
            else
                $iPositionLast += 1;
            $sContent = mb_substr($sStr, $iPositionFirst, $iPositionLast-$iPositionFirst, 'UTF8');
            $sContentToLower = mb_strtolower($sContent, 'UTF8');
            
            $iPositionTagsAFirst = mb_strpos($sContentToLower, 'href="', $iPositionTagsALast, 'UTF8');
            if ($iPositionTagsAFirst !== false) {
                $iPositionTagsAFirst += 6;
                $iPositionTagsALast = mb_strpos($sContentToLower, '"', $iPositionTagsAFirst, 'UTF8');
                if ($iPositionTagsALast!==false) {
                    $sUrl = mb_substr($sContent, $iPositionTagsAFirst, $iPositionTagsALast-$iPositionTagsAFirst, 'UTF8');
                }
                if (!$this->checkUrl($sUrl)) {
                    $bIsExist = false;
                    $sContentReplace = $sContent;
                    // kiểm tra xem trong nội dung có thẻ rel hay không để tiến hành cộng dồn
                    preg_match_all('/ rel(.*?)=(.*?)"(.*?)"/is', $sContentReplace, $aTmp);
                    if (isset($aTmp[3][0])) {
                        $sReplace = $aTmp[3][0];
                        $sReplace = str_replace(array('nofollow', 'follow'), '', $sReplace);
                        $sReplace = ' rel="nofollow '.$sReplace.'"';
                        for ($i = 0; $i < count($aTmp[0]); $i++) {
                            $sContentReplace = str_replace($aTmp[0][$i], $sReplace, $sContentReplace);
                            $sReplace = '';
                        }
                        $bIsExist = true;
                    }
                    if (!$bIsExist) {
                        // bỏ thẻ rel
                        $sContentReplace = preg_replace('/ rel(.*?)=(.*?)"(.*?)"/is', '', $sContentReplace);
                        // end
                        $sContentReplace = str_replace('<a ', '<a rel="nofollow" ', $sContentReplace);
                    }
                    $aContent[] = $sContent;
                    $aContentReplace[] = $sContentReplace;
                }
            }
        }
        $sStr = str_replace($aContent, $aContentReplace, $sStr);
        return $sStr;
    }
    
    function checkUrl($sUrl)
    {
        if (!empty($sUrl) && strpos($sUrl, '//') !== false && Core::getService('core.tools')->getDomain(array('url' => $sUrl)) != Core::getDomainName())
            return false;
        return true;
    }
    
    //Function Kho
    /**
     * Parse and clean a string. We mainly use this for a title of an item, which
     * does not allow any HTML. It can also be used to shorten a string bassed on 
     * the numerical value passed by the 2nd argument.
     *
     * @param string $sTxt Text to parse.
     * @param int $iShorten (Optional) Define how short you want the string to be.
     * @return string Returns the new parsed string.
     */
    public function clean($sTxt, $iShorten = null)
    {
        $sTxt = Core::getLib('output')->htmlspecialchars($sTxt);
        
        // Parse for language package
        $sTxt = $this->_utf8ToUnicode($sTxt);
       
        $sTxt = str_replace('\\', '&#92;', $sTxt);

        if ($iShorten !== null)
        {            
            $sTxt = $this->_shorten($sTxt, $iShorten);
        }    
        
        return $sTxt;
    }
    
    /**
     * Shortens a string respecting non english characters
     * @param string $sTxt string to shorten
     * @param int $iLetters how many characters must the resulting string have
     * @return string shortened string
     */
    private function _shorten($sTxt, $iLetters)
    {
        if (!preg_match('/(&#[0-9]+;)/', $sTxt))
        {
            return substr($sTxt, 0, $iLetters);
        }
        $sOut = '';
        $iOutLen = 0;
        $iPos = 0; 
        $iTxtLen = strlen($sTxt);
        for ($iPos; $iPos < $iTxtLen && $iOutLen <= $iLetters; $iPos++)
        {
            if ($sTxt[$iPos] == '&')
            {
                $iEnd = strpos($sTxt, ';', $iPos) + 1;
                $sTemp = substr($sTxt, $iPos, $iEnd - $iPos);
                if (preg_match('/(&#[0-9]+;)/', $sTemp))
                {
                    $sTmp = $sOut;
                    $sOut .= $sTemp; // add the entity altogether
                    if (strlen($sOut) > $iLetters)
                    {
                        return $sTmp;
                    }
                    $iOutLen++; // increment the length of the returning string
                    $iPos = $iEnd-1; // move the pointer to skip the entity in the next run
                    continue;
                }
            }
            $sOut .= $sTxt[$iPos];
            $iOutLen++;
        }
        return $sOut;
    }
    
    /**
     * Converts a string with non-latin characters into UNICODE. We convert all strings
     * before we enter them into the database so clients do not have to worry about database
     * collations and website encoding as all common browsers have no problems displaying UNICODE.
     *
     * @see self::_unicodeToEntitiesPreservingAscii()
     * @param string $str String we need to parse.
     * @param bool $bForUrl TRUE for URL strings, FALSE for general usage.
     * @return string Returns string that has been converted.
     */
    private function _utf8ToUnicode($str, $bForUrl = false)
    {
        $unicode = array();
        $values = array();
        $lookingFor = 1;
        
        if(defined('CORE_UNICODE_JSON') && CORE_UNICODE_JSON === true)
        {
            $aUnicodes = preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);
            foreach($aUnicodes as $char)
            {
                $thisValue = ord($char);
                if ($thisValue < 128)
                {
                    $unicode[] = $thisValue;
                }
                else
                {
                    $unicode[] = hexdec(trim(trim(json_encode($char), '"'), '\u'));
                }
            }
        }
        else
        {
            for ($i = 0; $i < strlen( $str ); $i++ )
            {
                $thisValue = ord( $str[ $i ] );

                if ( $thisValue < 128 )
                {
                    $unicode[] = $thisValue;
                }
                else
                {
                    if ( count( $values ) == 0 ) $lookingFor = ( $thisValue < 224 ) ? 2 : 3;

                    $values[] = $thisValue;

                    if ( count( $values ) == $lookingFor ) 
                    {
                        $number = ( $lookingFor == 3 ) ?
                            ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ):
                            ( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64 );

                        $unicode[] = $number;
                        $values = array();
                        $lookingFor = 1;
                    }
                }
            }
        }

        return $this->_unicodeToEntitiesPreservingAscii($unicode, $bForUrl);
    }

    /**
     * Converts a string with non-latin characters into UNICODE. This method is used with the method _utf8ToUnicode().
     *
     * @see self::_utf8ToUnicode()
     * @param array $unicode ARRAY of unicode values.
     * @param bool $bForUrl TRUE for URL strings, FALSE for general usage.
     * @return string Returns string that has been converted.
     */
    private function _unicodeToEntitiesPreservingAscii($unicode, $bForUrl = false)
    {
        $entities = '';
        foreach( $unicode as $value )
        {
            if ($bForUrl === true)
            {
                if ($value == 42 || $value > 127)
                { 
                    $sCacheValue = preg_replace("/&\#(.*?)\;/ise", "'' . \$this->_parse('$1') . ''", '&#' . $value . ';');
                
                    $entities .= (preg_match('/[^a-zA-Z]+/', $sCacheValue) ? '-' . $value : $sCacheValue);               
                }
                else 
                {
                    $entities .= (preg_match('/[^0-9a-zA-Z]+/', chr($value)) ? ' ' : chr($value));
                }                
            }
            else 
            {
                $entities .= ($value == 42 ? '&#' . $value . ';' : ( $value > 127 ) ? '&#' . $value . ';' : chr($value));
            }
        }
        $entities = str_replace("'", '&#039;', $entities);
        return $entities;
    }
}