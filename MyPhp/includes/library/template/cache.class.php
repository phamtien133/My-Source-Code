<?php
class Template_Cache extends Template
{
    /**
     * Foreach stack.
     * 
     * @var array
     */
    private $_aForeachElseStack = array();
    
    /**
     * Require stack.
     * 
     * @var array
     */
    private $_aRequireStack = array();
    
    /**
     * PHP blocks. {php}{/php}
     * 
     * @var array
     */
    private $_aPhpBlocks = array();
    
    /**
     * Section blocks. {section}{/section}
     * 
     * @var array
     */
    private $_aSectionelseStack = array();
    
    /**
     * Module blocks.
     * 
     * @var array
     */
    private $_aModuleBlocks = array();
    
    /**
     * Literal blocks. {literal}{/literal}
     * 
     * @var array
     */
    private $_aLiterals = array();

    /**
     * String regex.
     * 
     * @var string
     */
    private $_sDbQstrRegexp = '"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"';
    
    /**
     * String regex.
     * 
     * @var string
     */    
    private $_sSiQstrRegexp = '\'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'';
    
    /**
     * Bracket regex.
     * 
     * @var string
     */    
    private $_sVarBracketRegexp = '\[[\$|\#]?\w+\#?\]';
    
    /**
     * Variable regex.
     * 
     * @var string
     */    
    private $_sSvarRegexp = '\%\w+\.\w+\%';
    
    /**
     * Function regex.
     * 
     * @var string
     */    
    private $_sFuncRegexp = '[a-zA-Z_]+';

    private $_sCurrentFile = '';
    
        /**
     * Class constructor. Build all the regex we will be using
     * with this class.
     */
    public function __construct()
    {
        $this->_sQstrRegexp = '(?:' . $this->_sDbQstrRegexp . '|' . $this->_sSiQstrRegexp . ')';

        $this->_sDvarRegexp = '\$[a-zA-Z0-9_]{1,}(?:' . $this->_sVarBracketRegexp . ')*(?:\.\$?\w+(?:' . $this->_sVarBracketRegexp . ')*)*';

        $this->_sCvarRegexp = '\#[a-zA-Z0-9_]{1,}(?:' . $this->_sVarBracketRegexp . ')*(?:' . $this->_sVarBracketRegexp . ')*\#';

        $this->_sVarRegexp = '(?:(?:' . $this->_sDvarRegexp . '|' . $this->_sCvarRegexp . ')|' . $this->_sQstrRegexp . ')';

        $this->_sModRegexp = '(?:\|@?[0-9a-zA-Z_]+(?::(?>-?\w+|' . $this->_sDvarRegexp . '|' . $this->_sQstrRegexp .'))*)';        
    }
    
    /**
     * Compile a template file and cache it to a PHP flat file.
     * 
     * @param string $sName Name of the template.
     * @param string $sData Contents of the template.
     * @param bool $bRemoveHeader TRUE to remove the time stamp we added to the header of each cache file.
     * @param bool $bSkipDbCheck TRUE to skip checks on the database to see if the cache file exists there as well.
     * @return mixed We only return the templates content if the installer does not have a writable directory.
     */
    public function compile($sName, $sData = null, $bRemoveHeader = false, $bSkipDbCheck = false)
    {
        $this->_sCurrentFile = $sName;

        $sData = $this->_parse((isset($aTemplate['html_data']) ? $aTemplate['html_data'] : $sData), $bRemoveHeader);

        $sContent = '';
        $aLines = explode("\n", $sData);

        foreach ($aLines as $sLine)
        {
            if (preg_match("/<\?php(.*?)\?>/i", $sLine))
            {
                if (substr(trim($sLine), 0, 5) == '<?php')
                {
                    $sContent .= trim($sLine) . "\n";
                }
                else
                {
                    $sContent .= $sLine . "\n";
                }
            }
            else
            {
                $sContent .= $sLine . "\n";
            }
        }
    
        //save to cache file.
        if ($rFile = @fopen($sName, 'w+'))
        {
            fwrite($rFile, $sContent);
            fclose($rFile);
            
           // Core::getLib('cache')->saveInfo($sName, 'template', $sContent, filesize($sName));
        }
        else
        {
            echo 'Unable to cache template file: ' . $sName;
            return false;
        }
        return $sContent;
    }
    
    /**
     * Parse a templates content and convert it into PHP.
     *
     * @param string $sData Content of the template.
     * @param bool $bRemoveHeader TRUE to remove cache headers in the template.
     * @return string Parsed and converted content.
     */
    private function _parse($sData, $bRemoveHeader = false)
    {
        $sLdq = preg_quote($this->sLeftDelim);
        $sRdq = preg_quote($this->sRightDelim);
        $aText = array();
        $sCompiledText = '';

        // Remove SVN headers
        //$sData = preg_replace("/\<\!core(.*?)\>/is", "", $sData);

        // Add a security token in a form
        // $sData = preg_replace("/<form(.*?)>(.*?)<\/form>/ise", "'' . \$this->_parseForm('$1', '$2') .''", $sData);
        $sData = preg_replace_callback("/<form(.*?)>(.*?)<\/form>/is", array($this, '_parseForm'), $sData);

        // remove all comments
        $sData = preg_replace("/{$sLdq}\*(.*?)\*{$sRdq}/s", "", $sData);

        // remove literal blocks
        preg_match_all("!{$sLdq}\s*literal\s*{$sRdq}(.*?){$sLdq}\s*/literal\s*{$sRdq}!s", $sData, $aMatches);
        $this->_aLiterals = $aMatches[1];
        $sData = preg_replace("!{$sLdq}\s*literal\s*{$sRdq}(.*?){$sLdq}\s*/literal\s*{$sRdq}!s", stripslashes($sLdq . "literal" . $sRdq), $sData);

        // remove php blocks
        preg_match_all("!{$sLdq}\s*php\s*{$sRdq}(.*?){$sLdq}\s*/php\s*{$sRdq}!s", $sData, $aMatches);
        $this->_aPhpBlocks = $aMatches[1];
        $sData = preg_replace("!{$sLdq}\s*php\s*{$sRdq}(.*?){$sLdq}\s*/php\s*{$sRdq}!s", stripslashes($sLdq . "php" . $sRdq), $sData);

        $aText = preg_split("!{$sLdq}.*?{$sRdq}!s", $sData);
        
        preg_match_all("!{$sLdq}\s*(.*?)\s*{$sRdq}!s", $sData, $aMatches);
        $aTags = $aMatches[1];
        
        $aCompiledTags = array();
        $iCompiledTags = count($aTags);
        for ($i = 0, $iForMax = $iCompiledTags; $i < $iForMax; $i++)
        {
            $aCompiledTags[] = $this->_compileTag($aTags[$i]);
        }
        
        $iCountCompiledTags = count($aCompiledTags);
        for ($i = 0, $iForMax = $iCountCompiledTags; $i < $iForMax; $i++)
        {
            if ($aCompiledTags[$i] == '')
            {
                $aText[$i+1] = preg_replace('~^(\r\n|\r|\n)~', '', $aText[$i+1]);
            }
            $sCompiledText .= $aText[$i].$aCompiledTags[$i];
        }
        $sCompiledText .= $aText[$i];
        return $sCompiledText;
    }
    
    /**
     * Compile custom tags. (eg. {literal})
     *
     * @param string $sTag Name of the tag to parse.
     * @return string Converted block of code based on the tag.
     */
    private function _compileTag($sTag)
    {
        preg_match_all('/(?:(' . $this->_sVarRegexp . '|' . $this->_sSvarRegexp . '|\/?' . $this->_sFuncRegexp . ')(' . $this->_sModRegexp . '*)(?:\s*[,\.]\s*)?)(?:\s+(.*))?/xs', $sTag, $aMatches);

        if ($aMatches[1][0]{0} == '$' || $aMatches[1][0]{0} == "'" || $aMatches[1][0]{0} == '"')
        {
            return "<?php echo " . $this->_parseVariables($aMatches[1], $aMatches[2]) . "; ?>";
        }
        
        $sTagCommand = $aMatches[1][0];
        $sTagModifiers = !empty($aMatches[2][0]) ? $aMatches[2][0] : null;
        $sTagArguments = !empty($aMatches[3][0]) ? $aMatches[3][0] : null;
        return $this->_parseFunction($sTagCommand, $sTagModifiers, $sTagArguments);
        
    }
    
    /**
     * Parse arguments. (eg. {for bar1=sample1 bar2=sample2}
     *
     * @param string $sArguments Arguments to parse.
     * @return array ARRAY of all the arguments.
     */
    private function _parseArgs($sArguments)
    {
        $aResult    = array();
        preg_match_all('/(?:' . $this->_sQstrRegexp . ' | (?>[^"\'=\s]+))+|[=]/x', $sArguments, $aMatches);
        $iState = 0;
        foreach($aMatches[0] as $mValue)
        {
            switch($iState)
            {
                case 0:
                    if (is_string($mValue))
                    {
                        $sName = $mValue;
                        $iState = 1;
                    }
                    else
                    {
                        echo 'Invalid Attribute Name';
                        return false;
                    }
                    break;
                case 1:
                    if ($mValue == '=')
                    {
                        $iState = 2;
                    }
                    else
                    {
                         echo "Expecting '=' After '{$sLastValue}'";
                         return false;
                    }
                    break;
                case 2:
                    if ($mValue != '=')
                    {
                        if(!preg_match_all('/(?:(' . $this->_sVarRegexp . '|' . $this->_sSvarRegexp . ')(' . $this->_sModRegexp . '*))(?:\s+(.*))?/xs', $mValue, $aVariables))
                        {
                            $aResult[$sName] = $mValue;
                        }
                        else
                        {
                            $aResult[$sName] = $this->_parseVariables($aVariables[1], $aVariables[2]);
                        }
                        $iState = 0;
                    }
                    else
                    {
                        echo "'=' cannot be an attribute value";
                        return false;
                    }
                    break;
            }
            $sLastValue = $mValue;
        }

        if($iState != 0)
        {
            if($iState == 1)
            {
                echo "expecting '=' after attribute name '{$sLastValue}'";
                return false;
            }
            else
            {
                echo "missing attribute value";
                return false;
            }
        }
        return $aResult;
    }
    
    /**
     * Parse all the custom tags used within templates. In templates we
     * do not use conventional PHP code as we seperate PHP logic from the
     * template. The tags we use work similar to that off SMARTY.
     *
     * @param string $sFunction Name of the function.
     * @param string $sModifiers Modifiers.
     * @param string $sArguments Any arguments we are passing.
     * @return string Converted PHP value of the function.
     */
    private function _parseFunction($sFunction, $sModifiers, $sArguments)
    {
        switch ($sFunction)
        {
            /**
             * SMARTY
             */    
            case 'php':
                list (,$sPhpBlock) = each($this->_aPhpBlocks);
                return '<?php ' . $sPhpBlock . ' ?>';
                break;
            case 'for':
                $sArguments = preg_replace("/\\$([A-Za-z0-9]+)/ise", "'' . \$this->_parseVariable('\$$1') . ''", $sArguments);
                return '<?php for (' . $sArguments . '): ?>';
                break;
            case '/for':
                return "<?php endfor; ?>";
                break;
            case 'l':
                return '{';
                break;
            case 'r':
                return '}';
                break;
            case 'assign':
                $aArgs = $this->_parseArgs($sArguments);
                if (!isset($aArgs['var']))
                {
                    return '';
                }
                if (!isset($aArgs['value']))
                {
                    return '';
                }
                return '<?php $this->assign(\'' . $this->_removeQuote($aArgs['var']) . '\', ' . $aArgs['value'] . '); ?>';
                break;
            case 'literal':
                list (,$sLiteral) = each($this->_aLiterals);
                return "<?php echo '" . str_replace("'", "\'", $sLiteral) . "'; ?>\n";
                break;
            case 'foreach':
                array_push($this->_aForeachElseStack, false);
                $aArgs = $this->_parseArgs($sArguments);
                if (!isset($aArgs['from']))
                {
                    return '';
                }
                if (!isset($aArgs['value']) && !isset($aArgs['item']))
                {
                    return '';
                }
                if (isset($aArgs['value']))
                {
                    $aArgs['value'] = $this->_removeQuote($aArgs['value']);
                }
                elseif (isset($aArgs['item']))
                {
                    $aArgs['value'] = $this->_removeQuote($aArgs['item']);
                }

                (isset($aArgs['key']) ? $aArgs['key'] = "\$this->_aVars['".$this->_removeQuote($aArgs['key'])."'] => " : $aArgs['key'] = '');

                $bIteration = (isset($aArgs['name']) ? true : false);

                $sResult = '<?php if (count((array)' . $aArgs['from'] . ')): ?>' . "\n";
                if ($bIteration)
                {
                    $sResult .= '<?php $this->_aCoreVars[\'iteration\'][\'' . $aArgs['name'] . '\'] = 0; ?>' . "\n";
                }
                $sResult .= '<?php foreach ((array) ' . $aArgs['from'] . ' as ' . $aArgs['key'] . '$this->_aVars[\'' . $aArgs['value'] . '\']): ?>';
                if ($bIteration)
                {
                    $sResult .= '<?php $this->_aCoreVars[\'iteration\'][\'' . $aArgs['name'] . '\']++; ?>' . "\n";
                }
                return $sResult;
                break;
            case 'foreachelse':
                $this->_aForeachElseStack[count($this->_aForeachElseStack)-1] = true;
                return "<?php endforeach; else: ?>";
                break;
            case '/foreach':
                if (array_pop($this->_aForeachElseStack))
                {
                    return "<?php endif; ?>";
                }
                else
                {
                    return "<?php endforeach; endif; ?>";
                }
                break;
            case 'if':
                return $this->_compileIf($sArguments);
                break;
            case 'else':
                return "<?php else: ?>";
                break;
            case 'elseif':
                return $this->_compileIf($sArguments, true);
                break;
            case '/if':
                return "<?php endif; ?>";
                break;
            case 'section':
                array_push($this->_aSectionelseStack, false);
                return $this->_compileSectionStart($sArguments);
                break;
            case 'sectionelse':
                $this->_aSectionelseStack[count($this->_aSectionelseStack)-1] = true;
                return "<?php endfor; else: ?>";
                break;
            case '/section':
                if (array_pop($this->_aSectionelseStack))
                {
                    return "<?php endif; ?>";
                }
                else
                {
                    return "<?php endfor; endif; ?>";
                }
                break;
            case 'title':
                return Core::getLib('template')->getTitle();
                break;
            case 'header':
                return Core::getLib('template')->getHeader();
                break;
            case 'block':
                $aArgs = $this->_parseArgs($sArguments);                
                
                $sContent = '';
                $sContent .= '<?php if ($this->bIsSample): ?>';
                $sContent .= '<?php else: ?>';
                $sContent .= '<?php $aBlocks = Core::getLib(\'module\')->getModuleBlocks(' . $aArgs['location'] . '); ?>';
                
                /* if user is designing the profile or the dashboard showing the block containers is needed */                
                $sContent .= '<?php $aUrl = Core::getLib(\'url\')->getParams(); ?>';          
                $sContent .= '<?php foreach ((array)$aBlocks as $sBlock): ?>' . "\n";
                
                $sContent .= '<?php if (is_array($sBlock) || !in_array(' . $aArgs['location'] . ', array(1, 2, 3, 4)))): ?>';
                $sContent .= '<?php eval(\' ?>\' . $sBlock[0] . \'<?php \'); ?>';
                $sContent .= '<?php else: ?>';
                $sContent .= '<?php Core::getBlock($sBlock); ?>';
                $sContent .= '<?php endif; ?>' . "\n";
                
                $sContent .= '<?php endforeach; ?>';
                
                $sContent .= '<?php endif; ?>';
                return $sContent;
                break;
            case 'image_path':
                return '<?php echo $this->getStyle(\'image\'); ?>';
                break;
            case 'module_path':
                return '<?php echo Core::getParam(\'core.url_module\'); ?>';
                break;
            case 'permalink':
                $aArgs = $this->_parseArgs($sArguments);
                $aExtra = $aArgs;
                unset($aExtra['module'], $aExtra['id'], $aExtra['title']);                

                return '<?php echo Core::permalink(' . $aArgs['module'] . ', ' . $this->_removeQuote($aArgs['id']) . '' . (empty($aArgs['title']) ? ', null' : ', ' . $this->_removeQuote($aArgs['title'])) . ', false, null, (array) ' . var_export($aExtra, true) . '); ?>';
                break;
            case 'url':
                $aArgs = $this->_parseArgs($sArguments);
                if (!isset($aArgs['link']))
                {
                    return '';
                }
                $sLink = $aArgs['link'];
                unset($aArgs['link']);
                $sArray = '';
                if (count($aArgs))
                {
                    $sArray = ', array(';
                    foreach ($aArgs as $sKey => $sValue)
                    {
                        $sArray .= '\'' . $sKey . '\' => ' . $sValue . ',';
                    }
                    $sArray = rtrim($sArray, ',') . ')';
                }
                return '<?php echo Core::getLib(\'url\')->makeUrl(' . $sLink . $sArray . '); ?>';
                break;
            case 'error':
                $sContent = '<?php if (!$this->bIsSample): ?>';
                $sContent .= '<?php $this->getLayout(\'error\'); ?>';
                $sContent .= '<?php endif; ?>';
                return $sContent;
                break;
            case 'breadcrumb':
                $sContent = '<?php if (!$this->bIsSample): ?>';
                $sContent .= '<?php $this->getLayout(\'breadcrumb\'); ?>';
                $sContent .= '<?php endif; ?>';
                return $sContent;
                break;
            case 'search':
                $sContent = '<?php if (!$this->bIsSample): ?>';
                $sContent .= '<?php $this->getLayout(\'search\'); ?>';
                $sContent .= '<?php endif; ?>';
                return $sContent;
                break;
            case 'content':
                $sContent = '<?php if (!$this->bIsSample): ?>';
                $sContent .= '<?php if (isset($this->_aVars[\'bSearchFailed\'])): ?>';
                $sContent .= '<div class="message">Unable to find anything with your search criteria.</div>';
                $sContent .= '<?php else: ?>';
                    // Dont do this for profiles/pages or core.index-member because those load the feed and there is a separate routine for this block
                    $sContent .= '<?php $sController = "'. Core::getLib('module')->getFullControllerName() .'"; ?>';
                    $sContent .= '<?php if ( Core::getLib("template")->shouldLoadDelayed("'. Core::getLib('module')->getFullControllerName() .'") == true ): ?>'. "\n";
                    $sContent .= '<div id="delayed_block_image" style="text-align:center; padding-top:20px;"><img src="' . Core::getLib('template')->getStyle('image', 'ajax/add.gif') . '" alt="" /></div>'."\n";
                    $sContent .= '<div id="delayed_block" style="display:none;"><?php echo Core::getLib(\'module\')->getFullControllerName(); ?></div>'."\n";
                    $sContent .= '<?php else: ?>'. "\n";
                        $sContent .= '<?php Core::getLib(\'module\')->getControllerTemplate(); ?>';
                    $sContent .= '<?php endif; ?>';
                $sContent .= '<?php endif; ?>';
                $sContent .= '<?php endif; ?>';
                return $sContent;
                break;
            case 'layout':
                $aArgs = $this->_parseArgs($sArguments);
                return '<?php $this->getLayout(' . $aArgs['file'] . '); ?>';
                return $sContent;
                break;
            case 'pager':
                $sReturn = '<?php if (!isset($this->_aVars[\'aPager\'])): Core::getLib(\'pager\')->set(array(\'page\' => Core::getLib(\'request\')->getInt(\'page\'), \'size\' => Core::getLib(\'search\')->getDisplay(), \'count\' => Core::getLib(\'search\')->getCount())); endif; ?>';
                $sReturn .= '<?php $this->getLayout(\'pager\'); ?>';                
                return $sReturn;
                break;
            case 'unset':
                $aArgs = $this->_parseArgs($sArguments);
                return '<?php unset(' . implode(', ', $aArgs) . '); ?>';
                break;
            case 'token':
                return '<?php echo \'<div><input type="hidden" name="\' . Core::getTokenName() . \'[security_token]" value="\' . Core::getService(\'log.session\')->getToken() . \'" /></div>\'; ?>';
                break;
            case 'img':
                $aArgs = $this->_parseArgs($sArguments);
                $sArray = '';
                foreach ($aArgs as $sKey => $sValue)
                {
                    $sArray .= '\'' . $sKey . '\' => ' . $sValue . ',';
                }
                return '<?php echo Core::getLib(\'image.helper\')->display(array(' . rtrim($sArray, ',') . ')); ?>';
                break;
            case 'template':
                $aArgs = $this->_parseArgs($sArguments);
                $sFile = $this->_removeQuote($aArgs['file']);
                return '<?php Core::getLib(\'template\')->getBuiltFile(\'' . $sFile . '\'); ?>';
                break;
            case 'module':
                $aArgs = $this->_parseArgs($sArguments);
                $sModule = $aArgs['name'];
                unset($aArgs['name']);
                $sArray = '';
                
                foreach ($aArgs as $sKey => $sValue)
                {
                    if (substr($sValue, 0, 1) != '$' && $sValue !== 'true' && $sValue !== 'false')
                    {
                        $sValue = '\'' . $this->_removeQuote($sValue) . '\'';
                    }
                    $sArray .= '\'' . $sKey . '\' => ' . $sValue . ',';
                }
                return '<?php Core::getBlock(' . $sModule . ', array(' . rtrim($sArray, ',') . ')); ?>';
                break;
            case 'editor':
                $aArgs = $this->_parseArgs($sArguments);
                $aParams = array();
                foreach ($aArgs as $sKey => $mParam)
                {
                    $aParams[$sKey] = $this->_removeQuote($mParam);
                }
                
                $sReturn = '<?php echo Core::getLib(\'editor\')->get(' . $aArgs['id'] . ', ' . var_export($aParams, true) . '); ?>';
                return $sReturn;
                break;
            case 'value':
                $aArgs = $this->_parseArgs($sArguments);
                $aArgs = array_map(array($this, '_removeQuote'), $aArgs);
                // Accept variables in ids
                if (substr($aArgs['id'], 0, 14) == '$this->_aVars[')
                {
                    $aArgs['id'] = '\'.' . $aArgs['id'] .'.\'';
                }
                switch($aArgs['type'])
                {
                    case 'input':
                        $sContent = '<?php $aParams = (isset($aParams) ? $aParams : Core::getLib(\'request\')->getArray(\'val\')); echo (isset($aParams[\'' . $aArgs['id'] . '\']) ? Core::getLib(\'output\')->clean($aParams[\'' . $aArgs['id'] . '\']) : (isset($this->_aVars[\'aForms\'][\'' . $aArgs['id'] . '\']) ? Core::getLib(\'output\')->clean($this->_aVars[\'aForms\'][\'' . $aArgs['id'] . '\']) : ' . (isset($aArgs['default']) ? '\'' . $aArgs['default'] . '\'' : '\'\'' ) . ')); ?>' . "\n";
                        break;
                    case 'radio':
                        $sContent = '<?php $aParams = (isset($aParams) ? $aParams : Core::getLib(\'request\')->getArray(\'val\'));';
                        $sContent .= "\n".'if (isset($this->_aVars[\'aForms\']) && is_numeric(\''.$aArgs["id"].'\') && in_array(\''.$aArgs["id"].'\', $this->_aVars[\'aForms\']) ){echo \' checked="checked"\';}';
                        $sContent .= "\n".'if ((isset($aParams[\'' . $aArgs['id'] . '\']) && $aParams[\'' . $aArgs['id'] . '\'] == \'' . $aArgs['default'] . '\'))';
                        $sContent .= "\n".'{echo \' checked="checked" \';}';
                        $sContent .= "\n".'else';
                        $sContent .= "\n".'{';
                        $sContent .= "\n".' if (isset($this->_aVars[\'aForms\']) && isset($this->_aVars[\'aForms\'][\'' . $aArgs['id'] . '\']) && !isset($aParams[\'' . $aArgs['id'] . '\']) && $this->_aVars[\'aForms\'][\'' . $aArgs['id'] . '\'] == \'' . $aArgs['default'] . '\')';
                        $sContent .= "\n".' {';
                        $sContent .= "\n".'    echo \' checked="checked" \';}';
                        $sContent .= "\n".' else';
                        $sContent .= "\n".' {';
                        if (isset($aArgs['selected']))
                        {
                            $sContent .= "\n".' if (!isset($this->_aVars[\'aForms\']) || ((isset($this->_aVars[\'aForms\']) && !isset($this->_aVars[\'aForms\'][\'' . $aArgs['id'] . '\']) && !isset($aParams[\'' . $aArgs['id'] . '\']))))';
                            $sContent .= "\n".'{';
                            $sContent .= "\n".' echo \' checked="checked"\';';
                            $sContent .= "\n".'}';
                        }

                        $sContent .= "\n".' }';
                        $sContent .= "\n".'}';
                        $sContent .= "\n".'?>' . " \n";
                        break;
                    case 'checkbox':
                    case 'multiselect':
                    case 'select':
                        $bIsCheckbox = ($aArgs['type'] == 'checkbox' ? 'checked="checked"' : 'selected="selected"');
                        $aArgs['default'] = $this->_removeQuote($aArgs['default']);
                        if (substr($aArgs['default'], 0, 1) == '$')
                        {
                            $sDefault = $aArgs['default'];
                        }
                        elseif (substr($aArgs['default'], 0, 2) == ".\$")
                        {
                            $sDefault = trim($aArgs['default'], '.');
                        }
                        else
                        {
                            $sDefault = "'{$aArgs['default']}'";
                        }

                        $sContent = '<?php $aParams = (isset($aParams) ? $aParams : Core::getLib(\'request\')->getArray(\'val\'));'.
                            "\n" . '';
                        $sContent .= "\n\n".'if (isset($this->_aVars[\'aField\']) && isset($this->_aVars[\'aForms\'][$this->_aVars[\'aField\'][\'field_id\']]) && !is_array($this->_aVars[\'aForms\'][$this->_aVars[\'aField\'][\'field_id\']]))
                            {
                                $this->_aVars[\'aForms\'][$this->_aVars[\'aField\'][\'field_id\']] = array($this->_aVars[\'aForms\'][$this->_aVars[\'aField\'][\'field_id\']]);
                            }';
                        $sContent .= "\n\n".'if (isset($this->_aVars[\'aForms\']'. (isset($aArgs['parent']) ? '[\''.$aArgs["parent"].'\']' : '') .')';
                        $sContent .= "\n".' && is_numeric(\''.$aArgs["id"].'\') && in_array(\''.$aArgs["id"].'\', $this->_aVars[\'aForms\']'. (isset($aArgs['parent']) ? '[\''.$aArgs["parent"].'\']' : '') .'))
                            '."\n".'{
                                echo \' ' . $bIsCheckbox . ' \';
                            }'."\n".'
                            if (isset($aParams[\'' . $aArgs['id'] . '\'])
                                && $aParams[\'' . $aArgs['id'] . '\'] == ' . $sDefault . ')'."\n".'
                            {'."\n".'
                                echo \' ' . $bIsCheckbox . ' \';'."\n".'
                            }'."\n".'
                            else'."\n".'
                            {'."\n".'
                                if (isset($this->_aVars[\'aForms\'][\'' . $aArgs['id'] . '\'])
                                    && !isset($aParams[\'' . $aArgs['id'] . '\'])
                                    && $this->_aVars[\'aForms\'][\'' . $aArgs['id'] . '\'] == ' . $sDefault . ')
                                {
                                 echo \' ' . $bIsCheckbox . ' \';
                                }
                                else
                                {
                                    echo ' . (isset($aArgs['selected']) ? '" ' . str_replace('"', '\"', $bIsCheckbox) . '"' : '""') . ';
                                }
                            }
                            ?>' . "\n";
                        break;
                    case 'wysiwyg':
                    case 'textarea':
                        $sContent = '<?php $aParams = (isset($aParams) ? $aParams : Core::getLib(\'request\')->getArray(\'val\')); echo (isset($aParams[\'' . $aArgs['id'] . '\']) ? Core::getLib(\'output\')->clean($aParams[\'' . $aArgs['id'] . '\']) : (isset($this->_aVars[\'aForms\'][\'' . $aArgs['id'] . '\']) ? Core::getLib(\'output\')->clean($this->_aVars[\'aForms\'][\'' . $aArgs['id'] . '\']) : \'\')); ?>' . "\n";
                        break;
                }
                return $sContent;
                break;
            case 'param':
                $aArgs = $this->_parseArgs($sArguments);
                return '<?php echo Core::getParam(\'' . $this->_removeQuote($aArgs['var']) . '\'); ?>';
                break;
            case 'body':
                return '<?php Core::getBlock(\'core.template-body\'); ?>';
                break;
            case 'menu_footer':
                return '<?php Core::getBlock(\'core.menufooter\'); ?>';
                break;      
            case 'footer':
                return '<?php Core::getBlock(\'core.template-footer\'); ?>'; 
                break;
            case 'menu_header':
                return '<?php Core::getBlock(\'core.menu-header\'); ?>';
                break;
            case 'breadcrumb_list':
                return '<?php Core::getBlock(\'core.template-breadcrumblist\'); ?>';
                break;    
            case 'breadcrumb_menu':
                return '<?php Core::getBlock(\'core.template-breadcrumbmenu\'); ?>';
                break;
            default:                
                
                if ($this->_compileCustomFunction($sFunction, $sModifiers, $sArguments, $sResult))
                {
                    return $sResult;
                }
                else
                {
                    echo 'Invalid function';
                    return false;
                }
                return $sResult;
        }
    }
    
    /**
     * Parse variables.
     *
     * @param array $aVariables ARRAY of variables.
     * @param array $aModifiers ARRAY of modifiers.
     * @return string Converted variable.
     */
    private function _parseVariables($aVariables, $aModifiers)
    {
        $sResult = "";
        foreach($aVariables as $mKey => $mValue)
        {
            if (empty($aModifiers[$mKey]))
            {
                $sResult .= $this->_parseVariable(trim($aVariables[$mKey])).'.';
            }
            else
            {
                $sResult .= $this->_parseModifier($this->_parseVariable(trim($aVariables[$mKey])), $aModifiers[$mKey]).'.';
            }
        }
        return substr($sResult, 0, -1);
    }
    
    /**
     * Parse a specific variable.
     *
     * @param string $sVariable Name of the variable we are parsing.
     * @return string Converted variable.
     */
    private function _parseVariable($sVariable)
    {
        if ($sVariable{0} == "\$")
        {

            return $this->_compileVariable($sVariable);
        }
        else
        {
            return $sVariable;
        }
    }
    
    /**
     * Parse HTML forms. This is where we automatically add our security token.
     *
     * @param string $aMatches ARRAY of regex matches
     * @return string Converted form.
     */
    private function _parseForm($aMatches)
    {
        $sForm = $aMatches[1];
        $sData = $aMatches[2];
        
        $sForm = '<form' . stripslashes($sForm) . ">";
        if (strpos($sData, '{token}') === false)
        {
            $sForm .= "\n" . '<?php echo \'<div><input type="hidden" name="\' . Core::getTokenName() . \'[security_token]" value="\' . Core::getService(\'log.session\')->getToken() . \'" /></div>\'; ?>';
        }
        $sForm .= stripslashes($sData) . "\n";
        $sForm .= '</form>' . "\n";

        return $sForm;
    }
    
    /**
     * Compile IF statments.
     *
     * @param string $sArguments If statment arguments.
     * @param bool $bElseif TRUE if this is an ELSEIF.
     * @param bool $bWhile TRUE of this is a WHILE loop.
     * @return string Returns the converted PHP if statment code.
     */
    private function _compileIf($sArguments, $bElseif = false, $bWhile = false)
    {
        $aAllowed = array(
            'defined', 'is_array', 'isset', 'empty', 'count', '=', 'CORE_IS_AJAX_PAGE'
        );
        
        $sResult = "";
        $aArgs = array();
        $aArgStack    = array();

        preg_match_all('/(?>(' . $this->_sVarRegexp . '|\/?' . $this->_sSvarRegexp . '|\/?' . $this->_sFuncRegexp . ')(?:' . $this->_sModRegexp . '*)?|\-?0[xX][0-9a-fA-F]+|\-?\d+(?:\.\d+)?|\.\d+|!==|===|==|!=|<>|<<|>>|<=|>=|\&\&|\|\||\(|\)|,|\!|\^|=|\&|\~|<|>|\%|\+|\-|\/|\*|\@|\b\w+\b|\S+)/x', $sArguments, $aMatches);
        $aArgs = $aMatches[0];
        
        $iCountArgs = count($aArgs);
        for ($i = 0, $iForMax = $iCountArgs; $i < $iForMax; $i++)
        {
            $sArg = &$aArgs[$i];
            switch (strtolower($sArg))
            {
                case '!':
                case '%':
                case '!==':
                case '==':
                case '===':
                case '>':
                case '<':
                case '!=':
                case '<>':
                case '<<':
                case '>>':
                case '<=':
                case '>=':
                case '&&':
                case '||':
                case '^':
                case '&':
                case '~':
                case ')':
                case ',':
                case '+':
                case '-':
                case '*':
                case '/':
                case '@':
                    break;
                case 'eq':
                    $sArg = '==';
                    break;
                case 'ne':
                case 'neq':
                    $sArg = '!=';
                    break;
                case 'lt':
                    $sArg = '<';
                    break;
                case 'le':
                case 'lte':
                    $sArg = '<=';
                    break;
                case 'gt':
                    $sArg = '>';
                    break;
                case 'ge':
                case 'gte':
                    $sArg = '>=';
                    break;
                case 'and':
                    $sArg = '&&';
                    break;
                case 'or':
                    $sArg = '||';
                    break;
                case 'not':
                    $sArg = '!';
                    break;
                case 'mod':
                    $sArg = '%';
                    break;
                case '(':
                    array_push($aArgStack, $i);
                    break;
                case 'is':
                    $iIsArgCount = count($aArgs);
                    $sIsArg = implode(' ', array_slice($aArgs, 0, $i - 0));
                    $aArgTokens = $this->_compileParseIsExpr($sIsArg, array_slice($aArgs, $i+1));
                    array_splice($aArgs, 0, count($aArgs), $aArgTokens);
                    $i = $iIsArgCount - count($aArgs);
                    break;
                default:
                    preg_match('/(?:(' . $this->_sVarRegexp . '|' . $this->_sSvarRegexp . '|' . $this->_sFuncRegexp . ')(' . $this->_sModRegexp . '*)(?:\s*[,\.]\s*)?)(?:\s+(.*))?/xs', $sArg, $aMatches);

                    if (isset($aMatches[0]{0}) && ($aMatches[0]{0} == '$' || $aMatches[0]{0} == "'" || $aMatches[0]{0} == '"'))
                    {
                        $sArg = $this->_parseVariables(array($aMatches[1]), array($aMatches[2]));
                    }
                    
                    if (Core::getParam('core.is_auto_hosted') && preg_match('/web_([a-zA-Z0-9]+)_template/i', $this->_sCurrentFile))
                    {
                        if (strtolower($sArg) != 'core' 
                                && !in_array(trim($sArg, "'"), $aAllowed)
                                && substr($sArg, 0, 2) != '::'
                                && substr($sArg, 0, 5) != '$this'                                
                            )
                        {
                            if (function_exists($sArg))
                            {
                                $sArg = '';
                            }
                        }
                    }

                    break;
            }
        }
        
        if($bWhile)
        {
            return implode(' ', $aArgs);
        }
        else
        {
            if ($bElseif)
            {
                return '<?php elseif ('.implode(' ', $aArgs).'): ?>';
            }
            else
            {
                return '<?php if ('.implode(' ', $aArgs).'): ?>';
            }
        }

        return $sResult;
    }
    
    /**
     * Compile IF statment expressions.
     *
     * @param string $sIsArg If expression arguments.
     * @param string $aArgs Arguments.
     * @return string Converted PHP code.
     */
    private function _compileParseIsExpr($sIsArg, $aArgs)
    {
        $iExprEnd = 0;
        $bNegateExpr = false;

        if (($first_arg = array_shift($aArgs)) == 'not')
        {
            $bNegateExpr = true;
            $sExprType = array_shift($aArgs);
        }
        else
        {
            $sExprType = $first_arg;
        }

        switch ($sExprType)
        {
            case 'even':
                if (isset($aArgs[$iExprEnd]) && $aArgs[$iExprEnd] == 'by')
                {
                    $iExprEnd++;
                    $eExprArg = $aArgs[$iExprEnd++];
                    $sExpr = "!(1 & ($sIsArg / " . $this->_parseVariable($eExprArg) . "))";
                }
                else
                {
                    $sExpr = "!(1 & $sIsArg)";
                }
                break;
            case 'odd':
                if (isset($aArgs[$iExprEnd]) && $aArgs[$iExprEnd] == 'by')
                {
                    $iExprEnd++;
                    $eExprArg = $aArgs[$iExprEnd++];
                    $sExpr = "(1 & ($sIsArg / " . $this->_parseVariable($eExprArg) . "))";
                }
                else
                {
                    $sExpr = "(1 & $sIsArg)";
                }
                break;
            case 'div':
                if (@$aArgs[$iExprEnd] == 'by')
                {
                    $iExprEnd++;
                    $eExprArg = $aArgs[$iExprEnd++];
                    $sExpr = "!($sIsArg % " . $this->_parseVariable($eExprArg) . ")";
                }
                else
                {
                    echo "expecting 'by' after 'div'";
                    return false;
                }
            break;
            default:
                echo "unknown 'is' expression - '$sExprType'";
                return false;
                break;
        }

        if ($bNegateExpr)
        {
            $sExpr = "!($sExpr)";
        }

        array_splice($aArgs, 0, $iExprEnd, $sExpr);

        return $aArgs;
    }
    
    /**
     * Compile all variables.
     *
     * @param string $sVariable Variable name.
     * @return string Converted variable.
     */
    private function _compileVariable($sVariable)
    {
        $sResult = '';
        $sVariable = substr($sVariable, 1);

        preg_match_all('!(?:^\w+)|(?:' . $this->_sVarBracketRegexp . ')|\.\$?\w+|\S+!', $sVariable, $aMatches);
        $aVariables = $aMatches[0];
        $sVarName = array_shift($aVariables);

        if ($sVarName == $this->sReservedVarname)
        {
            if ($aVariables[0]{0} == '[' || $aVariables[0]{0} == '.')
            {
                $aFind = array("[", "]", ".");
                switch(strtoupper(str_replace($aFind, "", $aVariables[0])))
                {
                    case 'GET':
                        $sResult = "\$_GET";
                        break;
                    case 'POST':
                        $sResult = "\$_POST";
                        break;
                    case 'COOKIE':
                        $sResult = "\$_COOKIE";
                        break;
                    case 'ENV':
                        $sResult = "\$_ENV";
                        break;
                    case 'SERVER':
                        $sResult = "\$_SERVER";
                        break;
                    case 'SESSION':
                        $sResult = "\$_SESSION";
                        break;
                    default:
                        $sVar = str_replace($aFind, "", $aVariables[0]);
                        $sResult = "\$this->_aCoreVars['$sVar']";
                        break;
                }
                array_shift($aVariables);
            }
            else
            {
                echo '$' . $sVarName.implode('', $aVariables) . ' is an invalid $core reference';
                return false;
            }
        }
        else
        {
            $sResult = "\$this->_aVars['$sVarName']";
        }

        foreach ($aVariables as $sVar)
        {
            if ($sVar{0} == '[')
            {
                $sVar = substr($sVar, 1, -1);
                if (is_numeric($sVar))
                {
                    $sResult .= "[$sVar]";
                }
                elseif ($sVar{0} == '$')
                {
                    $sResult .= "[" . $this->_compileVariable($sVar) . "]";
                }
                else
                {
                    $parts = explode('.', $sVar);
                    $section = $parts[0];
                    $section_prop = isset($parts[1]) ? $parts[1] : 'index';
                    $sResult .= "[\$this->_aSections['$section']['$section_prop']]";
                }
            }
            elseif ($sVar{0} == '.')
            {
                   $sResult .= "['" . substr($sVar, 1) . "']";
            }
            elseif (substr($sVar,0,2) == '->')
            {
                echo 'Call to object members is not allowed';
                return false;
            }
            else
            {
                echo '$' . $sVarName.implode('', $aVariables) . ' is an invalid reference';
                return false;
            }
        }
        return $sResult;
    }
    
    /**
     * Parse modifiers.
     *
     * @param string $sVariable Variable name.
     * @param string $sModifiers Modifiers.
     * @return string Converted modifier.
     */
    private function _parseModifier($sVariable, $sModifiers)
    {
        $aMods = array();
        $aArgs = array();

        $aMods = explode('|', $sModifiers);
        unset($aMods[0]);
        foreach ($aMods as $sMod)
        {
            $aArgs = array();
            if (strpos($sMod, ':'))
            {
                $aParts = explode(':', $sMod);
                $iCnt = 0;

                foreach ($aParts as $iKey => $sPart)
                {
                    if ($iKey == 0)
                    {
                        continue;
                    }

                    if ($iKey > 1)
                    {
                        $iCnt++;
                    }

                    $aArgs[$iCnt] = $this->_parseVariable($sPart);
                }

                $sMod = $aParts[0];
            }

            if ($sMod{0} == '@')
            {
                $sMod = substr($sMod, 1);
                $bMapArray = false;
            }
            else
            {
                $bMapArray = true;
            }

            $sArg = ((count($aArgs) > 0) ? ', '.implode(', ', $aArgs) : '');

            switch ($sMod)
            {
                case 'htmlspecialchars':
                    $sVariable = "Core::getLib('output')->htmlspecialchars({$sVariable})";
                    break;
                case 'filesize':
                    $sVariable = 'Core::getLib(\'file\')->filesize(' . $sVariable . ')';
                    break;
                case 'clean':
                    if (isset($aArgs[0]) )
                    {
                        $sVariable = 'Core::getLib(\'output\')->clean(' . $sVariable . ',' . $aArgs[0].')';
                    }
                    else
                    {
                        $sVariable = 'Core::getLib(\'output\')->clean(' . $sVariable . ')';
                    }
                    break;
                case 'clean_phrase':
                    $sVariable = 'md5('.$sVariable . ')';
                    break;
                case 'parse':
                    $sVariable = 'Core::getLib(\'output\')->parse(' . $sVariable . ')';
                    break;
                case 'sprintf':
                    $sVariable = 'sprintf(' . $sVariable . '' . $sArg . ')';
                    break;
                case 'date':
                    $sVariable = 'Core::getTime(Core::getParam(\'' . (empty($aArgs[0]) ? 'core.global_update_time' : $this->_removeQuote($aArgs[0])) . '\'), ' . $sVariable . ')';
                    break;
                case 'highlight':
                    $sVariable = 'Core::getLib(\'search\')->highlight(' . $aArgs[0] . ', ' . $sVariable . ')';
                    break;
                case 'feed_strip':
                    $sVariable = 'Core::getLib(\'output\')->feedStrip(' . $sVariable . ')';
                    break;    
                case 'max_line':
                    $sVariable = 'Core::getLib(\'output\')->maxLine(' . $sVariable . ')';
                    break;
                case 'translate':
                    $sPrefix = (isset($aArgs[0]) ? ', ' . $aArgs[0] : '');
                    $sVariable = 'Core::getLib(\'locale\')->translate(' . $sVariable . $sPrefix . ')';
                    break;
                case 'eval':
                    $sVariable = 'eval(\' ?>\' . ' . $sVariable . ' . \'<?php \')';
                    break;
                case 'tag_search':
                    $sVariable = 'str_replace(' . $aArgs[0] . ', \'<u>\' . ' . $aArgs[0] . ' . \'</u>\', ' . $sVariable . ')';
                    break;
                case 'shorten':
                    if (!empty($aArgs[0]) && is_string($aArgs[0]) && preg_match('/[a-z]+\.{1}[a-z\_]+/', $aArgs[0], $aMatches) > 0)
                    {                            
                        $sArg = $this->_removeQuote(trim(ltrim($aArgs[0], ', ')));
                        $sArg = ',' . Core::getParam($sArg);
                    }
                    $sVariable = 'Core::getLib(\'output\')->shorten(' . $sVariable  . $sArg . ')';
                    break;
                case 'split':
                    $sVariable = 'Core::getLib(\'output\')->split(' . $sVariable . ', ' . $aArgs[0] . ')';
                    break;
                case 'first_name':
                    $sVariable = 'Core::getService(\'user\')->getFirstname(' . $sVariable . ')';
                    break;
                case 'location':
                    $sVariable = 'Core::getService(\'core.country\')->getCountry(' . $sVariable . ')';
                    break;
                case 'location_child':
                    $sVariable = 'Core::getService(\'core.country\')->getChild(' . $sVariable . ')';
                    break;
                case 'stripbb':
                    $sVariable = 'Core::getLib(\'parse.bbcode\')->stripCode(' . $sVariable . ')';
                    break;
                case 'cleanbb':
                    $sVariable = 'Core::getLib(\'parse.bbcode\')->cleanCode(' . $sVariable . ')';
                    break;
                case 'convert_time':
                    $sVariable = 'Core::getLib(\'date\')->convertTime(' . $sVariable . '' . $sArg . ')';
                    break;
                case 'micro_time':
                    $sVariable = 'date(\'Y-d-m\', ' . $sVariable . ')';
                    break;                        
                case 'convert':
                    $sVariable = 'Core::getLib(\'locale\')->convert(' . $sVariable . ')';
                    break;
                case 'user':
                    $sSuffix = '';
                    $sExtra = '';

                    if (count($aArgs))
                    {
                        if (!empty($aArgs[0]))
                        {
                            $sSuffix = $this->_removeQuote($aArgs[0]);
                        }
                    }

                    $bAuthor = false;
                    $sValue = '\' . Core::getLib(\'output\')->shorten(Core::getService(\'user\')->getCurrentName(' . $sVariable . '[\'' . $sSuffix . 'user_id\'], ' . $sVariable . '[\'' . $sSuffix . 'full_name\']), Core::getParam(\'user.maximum_length_for_full_name\')) . \'';
                    if (count($aArgs))
                    {
                        if (!empty($aArgs[1]))
                        {
                            $sExtra .= $this->_removeQuote($aArgs[1]);
                        }

                        if (!empty($aArgs[2]))
                        {
                            if (preg_match('/[a-z]+\.{1}[a-z\_]+/', $aArgs[2], $aMatches) > 0)
                            {
                                $aArgs[2] = Core::getParam($this->_removeQuote($aArgs[2]));
                            }
                            $sValue = '\' . Core::getLib(\'output\')->shorten(Core::getService(\'user\')->getCurrentName(' . $sVariable . '[\'' . $sSuffix . 'user_id\'], ' . $sVariable . '[\'' . $sSuffix . 'full_name\']), ' . $this->_removeQuote($aArgs[2]) . ', \'...\') . \'';
                        }

                        if (isset($aArgs[3]))
                        {
                            $aArgs[3] = $this->_removeQuote($aArgs[3]);
                        }
                        if (!empty($aArgs[3]))
                        {
                            $sValue = '\' . Core::getLib(\'output\')->shorten(Core::getLib(\'output\')->split(Core::getService(\'user\')->getCurrentName(' . $sVariable . '[\'' . $sSuffix . 'user_id\'], ' . $sVariable . '[\'' . $sSuffix . 'full_name\']), ' . $this->_removeQuote($aArgs[3]) . '' . (empty($aArgs[3]) ? '' : ', true') . '), Core::getParam(\'user.maximum_length_for_full_name\')) . \'';
                        }
                        
                        if (isset($aArgs[4]))
                        {
                            $aArgs[4] = $this->_removeQuote($aArgs[4]);
                            if (!empty($aArgs[4]))
                            {
                                $bAuthor = true;
                                $sExtra .= ' rel="author" ';
                            }
                        }                            
                    }
                    $sVariable = '\'<span class="user_profile_link_span" id="js_user_name_link_\' . ' . $sVariable . '[\'' . $sSuffix . 'user_name\'] . \'"' . ($bAuthor ? ' itemprop="author"' : '') . '><a href="\' . Core::getLib(\'url\')->makeUrl(\'profile\', array(' . $sVariable . '[\'' . $sSuffix . 'user_name\'], ((empty(' . $sVariable . '[\'' . $sSuffix . 'user_name\']) && isset(' . $sVariable . '[\'' . $sSuffix . 'profile_page_id\'])) ? ' . $sVariable . '[\'' . $sSuffix . 'profile_page_id\'] : null))) . \'"' . $sExtra . '>' . $sValue . '</a></span>\'';
                    break;
                case 'gender':
                    $sVariable = 'Core::getService(\'user\')->gender(' . $sVariable . $sArg . ')';
                    break;
                case 'age':
                    $sVariable = 'Core::getService(\'user\')->age(' . $sVariable . ')';
                    break;
                case 'currency_symbol':
                    $sVariable = 'Core::getService(\'core.currency\')->getSymbol(' . $sVariable . ')';
                    break;
                case 'currency':
                    $sVariable = 'Core::getService(\'core.currency\')->getCurrency(' . $sVariable . $sArg . ')';
                    break;
                case 'hide_email':
                    $sVariable = 'Core::getLib(\'parse.format\')->hideEmail(' . $sVariable . ')';
                    break;
                case 'privacy_phrase':
                    $sVariable = 'Core::getService(\'privacy\')->getPhrase(' . $sVariable . ')';
                    break;
                case 'category_display':
                    $sVariable = 'Core::getService(\'core.category\')->displayView(' . $sVariable . $sArg . ')';
                    break;
                case 'remove_operator':
                    $sVariable = 'Core::getService(\'core.paser\')->removeOperator(' . $sVariable . ')';
                    break;
                default:
                    if (function_exists($sMod))
                    {
                        $sVariable = '' . $sMod . '(' . $sVariable . $sArg . ')';
                    }
                    else
                    {
                        $sVariable = "return Core::setError(\"'" . $sMod . "' modifier does not exist\")";
                    }
            }
        }

        return $sVariable;
    }
    
    /**
     * Complie sections {section}{/section}
     *
     * @param string $sArguments Section arguments.
     * @return string Converted PHP foreach().
     */
    private function _compileSectionStart($sArguments)
    {
        $aAttrs = $this->_parseArgs($sArguments);

        $sOutput = '<?php ';
        $sSectionName = $aAttrs['name'];
        if (empty($sSectionName))
        {
            echo 'missing section name';
            return false;
        }

        $sOutput .= "if (isset(\$this->_aSections['$sSectionName'])) unset(\$this->_aSections['$sSectionName']);\n";
        $sSectionProps = "\$this->_aSections['$sSectionName']";

        foreach ($aAttrs as $sAttrName => $sAttrValue)
        {
            switch ($sAttrName)
            {
                case 'loop':
                    $sOutput .= "{$sSectionProps}['loop'] = is_array($sAttrValue) ? count($sAttrValue) : max(0, (int)$sAttrValue);\n";
                    break;
                case 'show':
                    if (is_bool($sAttrValue))
                    {
                        $bShowAttrValue = $sAttrValue ? 'true' : 'false';
                    }
                    else
                    {
                        $bShowAttrValue = "(bool)$sAttrValue";
                    }
                    $sOutput .= "{$sSectionProps}['show'] = $bShowAttrValue;\n";
                    break;
                case 'name':
                    $sOutput .= "{$sSectionProps}['$sAttrName'] = '$sAttrValue';\n";
                    break;
                case 'max':
                case 'start':
                    $sOutput .= "{$sSectionProps}['$sAttrName'] = (int)$sAttrValue;\n";
                    break;
                case 'step':
                    $sOutput .= "{$sSectionProps}['$sAttrName'] = ((int)$sAttrValue) == 0 ? 1 : (int)$sAttrValue;\n";
                    break;
                default:
                    echo "unknown section attribute - '$sAttrName'";
                    return false;
                    break;
            }
        }

        if (!isset($aAttrs['show']))
        {
            $sOutput .= "{$sSectionProps}['show'] = true;\n";
        }

        if (!isset($aAttrs['loop']))
        {
            $sOutput .= "{$sSectionProps}['loop'] = 1;\n";
        }

        if (!isset($aAttrs['max']))
        {
            $sOutput .= "{$sSectionProps}['max'] = {$sSectionProps}['loop'];\n";
        }
        else
        {
            $sOutput .= "if ({$sSectionProps}['max'] < 0)\n" .
                        "    {$sSectionProps}['max'] = {$sSectionProps}['loop'];\n";
        }

        if (!isset($aAttrs['step']))
        {
            $sOutput .= "{$sSectionProps}['step'] = 1;\n";
        }

        if (!isset($aAttrs['start']))
        {
            $sOutput .= "{$sSectionProps}['start'] = {$sSectionProps}['step'] > 0 ? 0 : {$sSectionProps}['loop']-1;\n";
        }
        else
        {
            $sOutput .= "if ({$sSectionProps}['start'] < 0)\n" .
                       "    {$sSectionProps}['start'] = max({$sSectionProps}['step'] > 0 ? 0 : -1, {$sSectionProps}['loop'] + {$sSectionProps}['start']);\n" .
                       "else\n" .
                       "    {$sSectionProps}['start'] = min({$sSectionProps}['start'], {$sSectionProps}['step'] > 0 ? {$sSectionProps}['loop'] : {$sSectionProps}['loop']-1);\n";
        }

        $sOutput .= "if ({$sSectionProps}['show']) {\n";
        if (!isset($aAttrs['start']) && !isset($aAttrs['step']) && !isset($aAttrs['max']))
        {
            $sOutput .= "    {$sSectionProps}['total'] = {$sSectionProps}['loop'];\n";
        }
        else
        {
            $sOutput .= "    {$sSectionProps}['total'] = min(ceil(({$sSectionProps}['step'] > 0 ? {$sSectionProps}['loop'] - {$sSectionProps}['start'] : {$sSectionProps}['start']+1)/abs({$sSectionProps}['step'])), {$sSectionProps}['max']);\n";
        }
        $sOutput .= "    if ({$sSectionProps}['total'] == 0)\n" .
                   "        {$sSectionProps}['show'] = false;\n" .
                   "} else\n" .
                   "    {$sSectionProps}['total'] = 0;\n";

        $sOutput .= "if ({$sSectionProps}['show']):\n";
        $sOutput .= "
            for ({$sSectionProps}['index'] = {$sSectionProps}['start'], {$sSectionProps}['iteration'] = 1;
                 {$sSectionProps}['iteration'] <= {$sSectionProps}['total'];
                 {$sSectionProps}['index'] += {$sSectionProps}['step'], {$sSectionProps}['iteration']++):\n";
        $sOutput .= "{$sSectionProps}['rownum'] = {$sSectionProps}['iteration'];\n";
        $sOutput .= "{$sSectionProps}['index_prev'] = {$sSectionProps}['index'] - {$sSectionProps}['step'];\n";
        $sOutput .= "{$sSectionProps}['index_next'] = {$sSectionProps}['index'] + {$sSectionProps}['step'];\n";
        $sOutput .= "{$sSectionProps}['first']      = ({$sSectionProps}['iteration'] == 1);\n";
        $sOutput .= "{$sSectionProps}['last']       = ({$sSectionProps}['iteration'] == {$sSectionProps}['total']);\n";

        $sOutput .= "?>";

        return $sOutput;
    }
    
    /**
     * Remove quotes from PHP variables.
     *
     * @param string $string PHP variable to work with.
     * @return string Converted PHP variable.
     */
    private function _removeQuote($string)
    {
        if (($string{0} == "'" || $string{0} == '"') && $string{strlen($string)-1} == $string{0})
        {
            return substr($string, 1, -1);
        }
        else
        {
            return $string;
        }
    }
}
?>
