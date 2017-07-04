<?php

/**
 * Cleans text added to the database and already parsed/cleaned via Parse_Input.
 * This is the last attempt to clean out any invalid tags, usually fix invalid HTML tags. 
 * Anything that needs to be removed should have already been removed with Parse_Input to save 
 * on running such a heavy routine for each item. We also parse emoticons and naughty words here as 
 * this information needs to be checked each time in case new words/emoticons are added by an Admin.
 */
class Output
{
    /**
     * Regex used to convert URL and emails.
     * 
     * @deprecated 2.0.0rc1
     * @var array
     */
    private $_aRegEx = array(
        'url_to_link' => '~(?>[a-z+]{2,}://|www\.)(?:[a-z0-9]+(?:\.[a-z0-9]+)?@)?(?:(?:[a-z](?:[a-z0-9]|(?<!-)-)*[a-z0-9])(?:\.[a-z](?:[a-z0-9]|(?<!-)-)*[a-z0-9])+|(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))(?:/[^\\/:?*"<>|\n]*[a-z0-9])*/?(?:\?[a-z0-9_.%]+(?:=[a-z0-9_.%:/+-]*)?(?:&[a-z0-9_.%]+(?:=[a-z0-9_.%:/+-]*)?)*)?(?:#[a-z0-9_%.]+)?~is',
        'email' => '/[-a-zA-Z0-9._]+@[-a-zA-Z0-9._]+(\.[-a-zA-Z0-9._]+)/is'
    );    
    
    /**
     * Parsing settings for images.
     *
     * @var array
     */
    private $_aImageParams = array();
    
    /**
     * Parsing settings for video embeds.
     *
     * @var array
     */
    private $_aEmbedParams = array();
    
    /**
     * Defines if the string has been shortened or not.
     *
     * @var bool
     */
    private $_bIsShortened = false;
    
    /**
     * Class Constructor.
     *
     */
    public function __construct()
    {        
    }
    
    /**
     * Text we need to parse, usually text added via a <textarea>.
     *
     * @param string $sTxt is the string we need to parse
     * @return string Parsed string
     */
    public function parse($sTxt)
    {        
        if (empty($sTxt))
        {
            return $sTxt;
        }
        $sTxt = ' ' . $sTxt;
        
        if (!Core::getParam('core.allow_html'))
        {
            $oFilterBbcode = Core::getLib('parse.bbcode');
            
            $sTxt = $oFilterBbcode->preParse($sTxt);

            $sAllowedTags = '<p><b><i><u><br><br />';
            $sTxt = strip_tags($sTxt, $sAllowedTags);
            
            // Add breaks without messing up HTML
            $sTxt = str_replace("\n", "[br]", $sTxt);
            $sTxt = preg_replace('/<(.*?)>/ise', "'<'. stripslashes(str_replace('[br]', '', '\\1')) .'>'", $sTxt);
            $sTxt = str_replace("[br]", "<br />", $sTxt);
            
            // Parse BBCode
            $sTxt = $oFilterBbcode->parse($sTxt);    
        }
        $sTxt = $this->parseUrls($sTxt);

        $sTxt = preg_replace_callback('/<object(.*?)>(.*?)<\/object>/is', array($this, '_embedWmode'), $sTxt);
        $sTxt = Core::getService('core.paser')->paser($sTxt);
        if (file_exists(DIR_LIB . 'htmlpurifier/HTMLPurifier.auto.php'))
        {
            static $purifier = null;

            if ($purifier === null)
            {
                require_once DIR_LIB . 'htmlpurifier/HTMLPurifier.auto.php';
                $config = HTMLPurifier_Config::createDefault();
                if (file_exists(DIR_INCLUDE . 'htmlpurifier.php'))
                {
                    require_once(DIR_INCLUDE . 'htmlpurifier.php');
                }
                $config->set('Attr.AllowedRel', array('nofollow'));
                $config->set('Attr.AllowedFrameTargets', array('_blank'));
                $purifier = new HTMLPurifier($config);
            }

            $sTxt = $purifier->purify($sTxt);
        }

        return $sTxt;
    }    

    private function _replaceHashTags($aMatches)
    {
        $sTagSearch = substr_replace(strip_tags($aMatches[0]), '', 0, 1);
        $sTagSearch = preg_replace('/\[UNICODE\]([0-9]+)\[\/UNICODE\]/', '&#\\1;', $sTagSearch);
        $sTagSearch = urlencode($sTagSearch);

        $sTxt = '<a href="' . Core::getLib('url')->makeUrl('hashtag', array($sTagSearch)) . '"' . ((defined('CORE_FEED_HASH_POPUP')) ? '' : ' onclick="tb_show(\'\', $.ajaxBox(\'feed.hashtag\', \'height=300&width=600&hashtagsearch=' . $sTagSearch . '&hashtagpopup=true\')); return false;"') . '>' . strip_tags($aMatches[0]) . '</a>';

        return $sTxt;
    }
    
    private function _replaceHexColor($aMatches)
    {
        if(strlen($aMatches[1]) == 3) {
            $r = hexdec(substr($aMatches[1],0,1).substr($aMatches[1],0,1));
            $g = hexdec(substr($aMatches[1],1,1).substr($aMatches[1],1,1));
            $b = hexdec(substr($aMatches[1],2,1).substr($aMatches[1],2,1));
        }
        else
        {
            $r = hexdec(substr($aMatches[1],0,2));
            $g = hexdec(substr($aMatches[1],2,2));
            $b = hexdec(substr($aMatches[1],4,2));
        }
        $sRGB = "rgb(" . $r . "," . $g . ",". $b . ")";
        
        return "color:" . $sRGB;
    }
    
    public function _replaceUnicode($aMatches)
    {
        return '[UNICODE]' . (int) str_replace(array('&#', ';'), '', $aMatches[0]) . '[/UNICODE]';
    }

    public function replaceHashTags($sTxt)
    {
        $sTxt = preg_replace_callback("/(&#+[0-9+]+;)/", array($this, '_replaceUnicode'), $sTxt);
        $sTxt = preg_replace_callback("/color:\s*#((([0-9a-fA-F]){3}){1,2})/i", array($this, '_replaceHexColor'), $sTxt);
        $sTxt = preg_replace_callback("/(#[\wa-zA-Z0-9\[\]\/]+)/u", array($this, '_replaceHashTags'), $sTxt);
        $sTxt = preg_replace('/\[UNICODE\]([0-9]+)\[\/UNICODE\]/', '&#\\1;', $sTxt);

        return $sTxt;
    }

    public function getHashTags($sTxt)
    {
        $aTags = array();
        $sTxt = str_replace(array('<br >', '<br />', '<p>', '</p>'), ' ', $sTxt);
        
        $sTxt = preg_replace_callback("/(&#+[0-9+]+;)/", array($this, '_replaceUnicode'), $sTxt);
        
        preg_match_all("/(#[^\s]*)/i", $sTxt, $aMatches);
        
        if (is_array($aMatches) && count($aMatches))
        {
            foreach ($aMatches as $aSubTags)
            {
                foreach ($aSubTags as $sTag)
                {
                    $sTag = preg_replace('/\[UNICODE\]([0-9]+)\[\/UNICODE\]/', '&#\\1;', $sTag);
                    $aTags[] = substr_replace($sTag, '', 0, 1);
                }

                break;
            }
        }
        return $aTags;
    }

    public function parseUrls($sTxt)
    {
        if (Core::getParam('core.disable_all_external_emails'))
        {
            $sTxt = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', array(&$this, '_replaceEmails'), $sTxt);    
        }
        
        if (Core::getParam('core.disable_all_external_urls'))
        {
            $sTxt = preg_replace_callback('#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', array(&$this, '_replaceLinks'), $sTxt);
            $sTxt = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', array(&$this, '_replaceLinks'), $sTxt);    
        }

        if (Core::getParam('core.no_follow_on_external_links'))
        {
            $sTxt = preg_replace('/<a\s(.*?)>/is', '<a \\1 rel="nofollow">', $sTxt);
        }
        
        if (Core::getParam('core.replace_url_with_links') || Core::getParam('core.warn_on_external_links') || Core::getParam('core.no_follow_on_external_links'))
        {        
            if (Core::getParam('core.warn_on_external_links'))
            {
                $sTxt = preg_replace_callback('/<a\s(.*?)>/i', array(&$this, '_warnOnExtLink'), $sTxt);
            }
            
            $sTxt = preg_replace_callback('#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', array(&$this, '_urlToLink'), $sTxt);
            $sTxt = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', array(&$this, '_urlToLink'), $sTxt);
        }                
        
        $sTxt = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $sTxt);
        $sTxt = trim($sTxt);

        return $sTxt;
    }
    
    private function _warnOnExtLink($aMatches)
    {
        if (!isset($aMatches[1]))
        {
            return '';
        }
        
        $sHref = '';
        $aParts = explode(' ', $aMatches[1]);
        foreach ($aParts as $sPart)
        {
            if (substr($sPart, 0, 5) == 'href=')
            {
                $sHref = $sPart;
                if (!preg_match('/' . preg_quote(Core::getParam('core.host')) . '/i', $sHref))
                {
                    $sHref = str_replace(array('"', "'"), '', $sHref);
                    $sHref = substr_replace($sHref, '', 0, 5);
                    $sHref = 'href="' . Core::getLib('url')->makeUrl('core.redirect', array('url' => Core::getLib('url')->encode($sHref))) . '"';
                }
                
                return '<a ' . $sHref . '>';
                break;
            }
        }
        
        if (empty($sHref))
        {
            return '<a ' . $aMatches[1] . '>';
        }
    }
    
    public function parseUserTagged($iUser)
    {
        return $this->_parseUserTagged($iUser);
    }
    
    /**
    * Parses users from tags by querying the DB and getting their full name.
    */
    private function _parseUserTagged($iUser)
    {
        static $aCache = array();
        
        if (!isset($aCache[$iUser]))
        {
            $sOut = '';
           
            $aCache[$iUser] = $sOut;
            
            return $sOut;
        }
        else
        {
            return $aCache[$iUser];
        }
    }
    
    public function feedStrip($sStr)
    {
        $sStr = ' ' . $sStr;
        
        if (!Core::getParam('core.allow_html_in_activity_feed'))
        {
            $sId = uniqid();
            $sStr = preg_replace('/<img src="' . preg_quote(Core::getParam('core.path'), '/') . 'file\/pic\/emoticon\/(.*?)"(.*?)\/>/is', '[EMOTICON' . $sId . ']\\1[/EMOTICON]', $sStr);
                        
            $sStr = strip_tags($sStr, '<br ><br>');            
            
            $sStr = preg_replace('/\[EMOTICON' . $sId . '\](.*?)\[\/EMOTICON\]/is', '<img src="' . Core::getParam('core.path') . 'file/pic/emoticon/\\1" class="v_middle" \/>', $sStr);
        }
        
        $sStr = $this->parseUrls($sStr);        
    
        $sStr = $this->replaceUserTag($sStr);
        
        return $sStr;
    }
    
    public function maxLine($sStr)
    {
        if (Core::getParam('core.activity_feed_line_breaks') > 0)
        {
            $aLines = explode("<br />", $sStr);
            if (count($aLines) > Core::getParam('core.activity_feed_line_breaks'))
            {
                $sLines = '<span class="js_read_more_parent_main">';
                $iLineCnt = 0;
                foreach ($aLines as $sLine)
                {
                    $iLineCnt++;
                        
                    if ($iLineCnt > Core::getParam('core.activity_feed_line_breaks'))
                    {
                        $bHasLineBreak = true;
                        $sLines .= '<span class="js_read_more_parent" style="display:none;">';
                        $sLines .= $sLine . '<br />';
                        $sLines .= '</span>';
                    }
                    else
                    {
                        $sLines .= $sLine . '<br />';
                    }
                }
        
                if (isset($bHasLineBreak))
                {
                    $sLines .= '<div><a href="#" onclick="$(this).parents(\'.js_read_more_parent_main:first\').find(\'.js_read_more_parent\').show(); $(this).hide(); return false;">Read More</a></div>';
                }
        
                $sLines .= '</span>';
        
                return $sLines;
            }
        }        
        
        return $sStr;
    }
    
    public function replaceUserTag($sStr)
    {
        $sStr = preg_replace('/\[x=(\d+)\].+?\[\/x\]/ise', "''.stripslashes(\$this->_parseUserTagged('$1')).''", $sStr);
        
        return $sStr;
    }
    
    /**
     * Set image parser settings.
     *
     * @param array $aParams ARRAY of settings.
     */
    public function setImageParser($aParams)
    {
        if (isset($aParams['clear']))
        {
            $this->_aImageParams = array();
        }
        else 
        {
            $this->_aImageParams = $aParams;
        }
    }
    
    /**
     * Set video embed settings.
     *
     * @param array $aParams ARRAY of settings.
     */
    public function setEmbedParser($aParams = null)
    {
        if ($aParams == null)
        {
            $this->_aEmbedParams = array('width' => Core::getParam('feed.width_for_resized_videos'), 'height' => Core::getParam('feed.height_for_resized_videos'));
        }
        elseif (isset($aParams['clear']))
        {
            $this->_aEmbedParams = array();
        }
        else 
        {
            $this->_aEmbedParams = $aParams;
        }
    }    
    
    /**
     * Clean input text, usually used within HTML <input>
     *
     * @param string $sTxt is the string we need to clean
     * @param bool $bHtmlChar TRUE to convert HTML characters or FALSE to not convert.
     * @return string Cleaned string
     */
    public function clean($sTxt, $bHtmlChar = true)
    {
        $sTxt = ($bHtmlChar ? $this->htmlspecialchars($sTxt) : $sTxt);
        
        $sTxt = str_replace('&#160;', '', $sTxt);
        
        return $sTxt;
    }
    
    /**
     * Our method of PHP htmlspecialchars().
     *
     * @see htmlspecialchars()
     * @param string $sTxt String to convert.
     * @return string Converted string.
     */
    public function htmlspecialchars($sTxt)
    {
        $sTxt = preg_replace('/&(?!(#[0-9]+|[a-z]+);)/si', '&amp;', $sTxt);
        $sTxt = str_replace(array(
            '"',
            "'",
            '<',
            '>'
        ),
        array(
            '&quot;',
            '&#039;',
            '&lt;',
            '&gt;'
        ), $sTxt);        
        
        return $sTxt;
    }

    /**
     * Clean text when being sent back via AJAX. 
     * Usually this is used to send back to an HTML <textarea>
     *
     * @param string $sTxt is the text we need to clean
     * @return string Cleaned Text
     */
    public function ajax($sTxt)
    {
        $sTxt = str_replace("\r", "", $sTxt);

        return $sTxt;
    }
    
    /**
     * Shortens a string.
     *
     * @param string $html String to shorten.
     * @param int $maxLength Max length.
     * @param string $sSuffix Optional suffix to add.
     * @param bool $bHide TRUE to hide the shortened string, FALSE to remove it.
     * @return string Returns the new shortened string.
     */
    public function shorten($html, $maxLength, $sSuffix = null, $bHide = false)
    {
        if ($maxLength === 0)
        {
            return $html;
        }        
                
        $printedLength = 0;
        $position = 0;
        $tags = array();
        $html = Core::getLib('parse.bbcode')->removeTagText($html);
        $sNewString = '';
        while ($printedLength < $maxLength && preg_match('{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}', $html, $match, PREG_OFFSET_CAPTURE, $position))
        {
            list($tag, $tagPosition) = $match[0];
    
            // Print text leading up to the tag.
            $str = substr($html, $position, $tagPosition - $position);
            if ($printedLength + strlen($str) > $maxLength)
            {
                $sNewString .= substr($str, 0, $maxLength - $printedLength);
                
                $printedLength = $maxLength;
                
                break;
            }
    
            $sNewString .= $str;
            $printedLength += strlen($str);
    
            if ($tag[0] == '&')
            {
                $sNewString .= $tag;
                
                $printedLength++;
            }
            else
            {
                // Handle the tag.
                $tagName = $match[1][0];
                if ($tag[1] == '/')
                {
                    $openingTag = array_pop($tags);
                    
                    if ($openingTag == $tagName)
                    {
                        $sNewString .= $tag;
                    }
                }
                elseif ($tag[strlen($tag) - 2] == '/')
                {
                    $sNewString .= $tag;
                }
                else
                {
                    $sNewString .= $tag;
                    
                    $tags[] = $tagName;
                }
            }
    
            $position = $tagPosition + strlen($tag);
        }
    
        // Print any remaining text.
        if ($printedLength < $maxLength && $position < strlen($html))
        {
            if(function_exists('mb_substr'))
            {
                $sNewString = mb_substr($html, $position, $maxLength - $printedLength, 'UTF-8'); 
            }
            else
            {
                $sNewString .= substr($html, $position, $maxLength - $printedLength);
            }
        }        
    
        // Close any open tags.
        while (!empty($tags))
        {
            if (array_pop($tags) == 'br')
            {
                continue;
            }
            $sNewString .= sprintf('</%s>', array_pop($tags));
        }       
    
        if ($sSuffix !== null)
        {
            $sCountHtml = strip_tags($html);
            $sCountHtml = preg_replace('/&#?[a-zA-Z0-9]+;/i', 'A', $sCountHtml);            
            
            
            if (strlen($sCountHtml) > $maxLength)
            {
                $this->_bIsShortened = true;
                
                if ($bHide === true)
                {
                    if (defined('CORE_IS_THEATER_MODE'))
                    {
                        $sNewString = '<span class="js_view_more_parent"><span class="js_view_more_part">' . $sNewString . '...<div class="item_view_more"><a href="#" onclick="$(this).parents(\'.js_view_more_parent:first\').find(\'.js_view_more_part\').hide(); $(this).parents(\'.js_view_more_parent:first\').find(\'.js_view_more_full\').show(); return false;">' . $sSuffix . '</a></div></span>';
                        $sNewString .= '<span class="js_view_more_full" style="display:none; position:absolute; z-index:10000; background:#fff; border:1px #dfdfdf solid;">';
                        $sNewString .= '<div style="max-height:200px; overflow:auto; padding:5px;">' . $html . '</div>';
                        $sNewString .= '<div class="item_view_more" style="padding:10px; text-align:center;"><a href="#" onclick="$(this).parents(\'.js_view_more_parent:first\').find(\'.js_view_more_full\').hide(); $(this).parents(\'.js_view_more_parent:first\').find(\'.js_view_more_part\').show(); return false;">view less</a></div>';
                        $sNewString .= '</span>';
                        $sNewString .= '</span>';
                    }
                    else
                    {
                        $sNewString = '<span class="js_view_more_parent"><span class="js_view_more_part">' . $sNewString . '...<div class="item_view_more"><a href="#" onclick="$(this).parents(\'.js_view_more_parent:first\').find(\'.js_view_more_part\').hide(); $(this).parents(\'.js_view_more_parent:first\').find(\'.js_view_more_full\').show(); return false;">' . $sSuffix . '</a></div></span>';
                        $sNewString .= '<span class="js_view_more_full" style="display:none;">';
                        $sNewString .= $html;
                        $sNewString .= '<div class="item_view_more"><a href="#" onclick="$(this).parents(\'.js_view_more_parent:first\').find(\'.js_view_more_full\').hide(); $(this).parents(\'.js_view_more_parent:first\').find(\'.js_view_more_part\').show(); return false;">view less</a></div>';
                        $sNewString .= '</span>';
                        $sNewString .= '</span>';            
                    }
                }
                else 
                {
                    $sNewString .= $sSuffix;
                }
            }
        }
        
        return $sNewString;        
    }   
    
    /**
     * Return if the last string we checked was shortened.
     *
     * @return bool TRUE it was shortened, FALSE if was not.
     */
    public function isShortened()
    {
        $bLastCheck =  $this->_bIsShortened;
        
        $this->_bIsShortened = false;
        
        return $bLastCheck;
    }
    
    /**
     * Split a string at a specified location. This allows for browsers to
     * automatically add breaks or wrap long text strings.
     *
     * @param string $sString Text string you want to split.
     * @param int $iCount How many characters to wait until we need to perform the split.
     * @param bool $bBreak FALSE will just add a space and TRUE will add an HTML <br />.
     * @return string Converted string with splits included.
     */
    public function split($sString, $iCount, $bBreak = false)
    {     
        // $sString = str_replace('0', '&#48;', $sString);
        if ($sString == '0')
        {
            return $sString;
        }
        $sNewString = '';
           $aString = explode('>', $sString);
           $iSizeOf = sizeof($aString);
           $bHasNonAscii = false;
           for ($i=0; $i < $iSizeOf; ++$i) 
           {
            $aChar = explode('<', $aString[$i]);            
                
               if (!empty($aChar[0])) 
               {                       
                   if (preg_match('/&#?[a-zA-Z0-9]+;/', $aChar[0]))
                   {
                        $aChar[0] = str_replace('&lt;', '[CORE_START]', $aChar[0]);
                       $aChar[0] = str_replace('&gt;', '[CORE_END]', $aChar[0]);
                       $aChar[0] = html_entity_decode($aChar[0], null, 'UTF-8');
                               
                       $bHasNonAscii = true;
                   }                   
                   if ($iCount > 9999)
                {
                    $iCount = 9999;
                }
                   $sNewString .= preg_replace('#([^\n\r(?!CORE_) ]{'. $iCount .'})#iu', '\\1 ' . ($bBreak ? '<br />' : ''), $aChar[0]);
               }
           
               if (!empty($aChar[1])) 
               {
                   $sNewString .= '<' . $aChar[1] . '>';
               }
           }   
        
           $sOut = ($bHasNonAscii === true ? str_replace(array('[CORE_START]', '[CORE_END]'), array('&lt;', '&gt;'), Core::getLib('input')->convert($sNewString)) : $sNewString);
        
           return $sOut;
    }    
    
    /**
     * Replace unwanted emails on the site. We also take into account emails
     * that are added into the "white" list.
     *
     * @param array $aMatches ARRAY matches from preg_match.
     * @return string Returns replaced emails.
     */
    private function _replaceEmails($aMatches)
    {
        static $aSites = null;
        
        if ($aSites === null)
        {
            $aSites = explode(',', trim(Core::getParam('core.email_white_list')));
        }
        
        foreach ($aSites as $sSite)
        {
            $sSite = trim($sSite);
            $sSite = str_replace(array('.', '*'), array('\.', '(.*?)'), $sSite);
                    
            if (preg_match('/' . $sSite . '/is', $aMatches[0]))
            {
                return $aMatches[0];
            }
        }
    }
    
    /**
     * Replace unwanted links on the site. We also take into account links
     * that are added into the "white" list.
     *
     * @param array $aMatches ARRAY matches from preg_match.
     * @return string Returns replaced links.
     */
    private function _replaceLinks($aMatches)
    {
        static $aSites = null;
        
        if ($aSites === null)
        {
            $aSites = explode(',', trim(Core::getParam('core.url_spam_white_list')) . ',' . Core::getParam('core.host'));
        }
        
        foreach ($aSites as $sSite)
        {
            $sSite = trim($sSite);
            $sSite = str_replace(array('.', '*'), array('\.', '(.*?)'), $sSite);
                    
            if (preg_match('/' . str_replace('/','\/',$sSite) . '/is', $aMatches[0]))
            {
                return $aMatches[0];
            }
        }
    }
    
    /**
     * Converts a URL into a HTML anchor.
     *
     * @param array $aMatches ARRAY matches from preg_match.
     * @return string Converted URL.
     */
    private function _urlToLink($aMatches)
    {        
        $sHref = '';
        $sName = '';
        
        $aMatches[2] = trim($aMatches[2]);

        if (empty($aMatches[2]))
        {
            return '';
        }
        
        $sHref = $aMatches[2];
        
        if ($sHref == 'ftp.')
        {
            return ' ' . $sHref;    
        }
        
        if (!preg_match("/^(http|https|ftp):\/\/(.*?)$/i", $sHref))
        {
            $sHref = 'http://' . $sHref;
        }        

        $sName = $aMatches[2];        
        if (Core::getParam('core.shorten_parsed_url_links') > 0)
        {
            $sName = substr($sName, 0, Core::getParam('core.shorten_parsed_url_links')) . (strlen($sName) > Core::getParam('core.shorten_parsed_url_links') ? '...' : '');
        }        

        if (Core::getParam('core.warn_on_external_links'))
        {
            if (!preg_match('/' . preg_quote(Core::getParam('core.host')) . '/i', $sHref))
            {
                $sHref = Core::getLib('url')->makeUrl('core.redirect', array('url' => Core::getLib('url')->encode($sHref)));
            }
        }    
        
        return $aMatches[1] . "<a href=\"" . $sHref . "\" target=\"_blank\" rel=\"nofollow\">" . $sName . "</a> ";
    }
    
    /**
     * Converts flash embedded videos to include "wmode".
     *
     * @param array $aMatches ARRAY matchs from preg_match.
     * @return string Fixed flash embed code.
     */
    private function _embedWmode($aMatches)
    {        
        if (Core::getParam('core.resize_embed_video'))
        {               
            $aMatches[1] = ' ' . $aMatches[1] . ' ';
            $aMatches[1] = preg_replace('/ width=([0-9"\' ]+)([ >]+)/ise', "'' . \$this->_fixEmbedWidth('$1', 'width', '$2') . ''", $aMatches[1]);
            $aMatches[1] = trim($aMatches[1]);
            
            $aMatches[1] = ' ' . $aMatches[1] . ' ';
            $aMatches[1] = preg_replace('/ height=([0-9"\' ]+)([ >]+)/ise', "'' . \$this->_fixEmbedWidth('$1', 'height', '$2') . ''", $aMatches[1]);
            $aMatches[1] = trim($aMatches[1]);            
            
            $aMatches[2] = ' ' . $aMatches[2] . ' ';
            $aMatches[2] = preg_replace('/ width=([0-9"\' ]+)([ >]+)/ise', "'' . \$this->_fixEmbedWidth('$1', 'width', '$2') . ''", $aMatches[2]);
            $aMatches[2] = trim($aMatches[2]);            
            
            $aMatches[2] = ' ' . $aMatches[2] . ' ';
            $aMatches[2] = preg_replace('/ height=([0-9"\' ]+)([ >]+)/ise', "'' . \$this->_fixEmbedWidth('$1', 'height', '$2') . ''", $aMatches[2]);
            $aMatches[2] = trim($aMatches[2]);    
        }
        
        return '<object ' . $aMatches[1] . '><param name="wmode" value="transparent"></param>' . str_replace('<embed ', '<embed  wmode="transparent" ', $aMatches[2]) . '</object>';
    }
    
    private function _embedIframe($aMatches)
    {
        if (Core::getParam('core.resize_embed_video'))
        { 
            $aMatches[1] = ' ' . $aMatches[1] . ' ';
            $aMatches[1] = preg_replace('/ width=([0-9"\' ]+)([ >]+)/ise', "'' . \$this->_fixEmbedWidth('$1', 'width', '$2') . ''", $aMatches[1]);
            
            $aMatches[1] = ' ' . $aMatches[1] . ' ';
            $aMatches[1] = preg_replace('/ height=([0-9"\' ]+)([ >]+)/ise', "'' . \$this->_fixEmbedWidth('$1', 'height', '$2') . ''", $aMatches[1]);
            $aMatches[1] = trim($aMatches[1]);            
        }
        
        return '<iframe ' . $aMatches[1] . '></iframe>';
    }
    
    /**
     * Fixes the width of embedded videos.
     *
     * @param string $sInt Width or height of the video.
     * @param string $sType Define if we are dealing with the width or height of the video.
     * @param string $sEnd Last string of the embed code to see if we should close the video embed code.
     * @return string Fixed embed width/height attribute.
     */
    private function _fixEmbedWidth($sInt, $sType, $sEnd = null)
    {
        $bEnd = false;
        if (trim($sEnd) == '>')
        {
            $bEnd = true;    
        }
        
        $iInt = (int) trim(trim(trim(stripslashes($sInt)), '"'), "'");
        
        $iInt = ((isset($this->_aEmbedParams[$sType]) && $iInt > (int) $this->_aEmbedParams[$sType]) ? (int) $this->_aEmbedParams[$sType] : $iInt);
        
        return ' ' . $sType . '="' . $iInt . '"' . ($bEnd ? '>' : ' ');   
    }
    
    /**
     * Gets a pharse from the language package.
     *
     * @param string $aMatches ARRAY matches from preg_match.
     * @return string Returns the phrase if we can find it.
     */
    private function _getPhrase($aMatches)
    {
        return (isset($aMatches[1]) ? $aMatches[1] : $aMatches[0]);
    }
    
    /**
     * Fixes image widths.
     *
     * @param array $aMatches ARRAY of matches from a preg_match.
     * @return string Returns the image with max-width and max-height included.
     */
    private function _fixImageWidth($aMatches)
    {
        $aParts = Core::getLib('input')->getParams($aMatches[1]);
        $iWidth = (isset($this->_aImageParams['width']) ? (int) $this->_aImageParams['width'] : 400);
        $iHeight = (isset($this->_aImageParams['height']) ? (int) $this->_aImageParams['height'] : 400);
        
        return '<img style="max-width:' . $iWidth . 'px; max-height:' . $iHeight . '" ' . $aMatches[1] . '>';
    }
}
?>
