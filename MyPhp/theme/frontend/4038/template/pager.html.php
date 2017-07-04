<div class="js_pager_view_more_link">
<?php if (isset ( $this->_aVars['aPager'] ) && $this->_aVars['aPager']['totalPages'] > 1): ?>
    <div class="pager_outer">
        <ul class="pager">
<?php if (! isset ( $this->_aVars['bIsMiniPager'] )): ?>
                    <li class="pager_total">Page <?php echo $this->_aVars['aPager']['current']; ?> of <?php echo $this->_aVars['aPager']['totalPages']; ?></li>
<?php endif; ?>
<?php if (isset ( $this->_aVars['aPager']['firstUrl'] )): ?>
            <li class="first">
                <a <?php if ($this->_aVars['sAjax']): ?>href="<?php echo $this->_aVars['aPager']['firstUrl']; ?>" onclick="$(this).parent().parent().parent().parent().find('.sJsPagerDisplayCount').html($.ajaxProcess('Loading...')); $.ajaxCall('<?php echo $this->_aVars['sAjax']; ?>', 'page=<?php echo $this->_aVars['aPager']['firstAjaxUrl']; ?><?php echo $this->_aVars['aPager']['sParams']; ?>'); $Core.addUrlPager(this); return false;"<?php else: ?>href="<?php echo $this->_aVars['aPager']['firstUrl']; ?>"<?php endif; ?>>
                    First
                </a>
            </li>
<?php endif; ?>
<?php if (isset ( $this->_aVars['aPager']['prevUrl'] )): ?>
            <li>
                <a <?php if ($this->_aVars['sAjax']): ?>href="<?php echo $this->_aVars['aPager']['prevUrl']; ?>" onclick="$(this).parent().parent().parent().parent().find('.sJsPagerDisplayCount').html($.ajaxProcess('Loading...')); $.ajaxCall('<?php echo $this->_aVars['sAjax']; ?>', 'page=<?php echo $this->_aVars['aPager']['prevAjaxUrl']; ?><?php echo $this->_aVars['aPager']['sParams']; ?>'); $Core.addUrlPager(this); return false;"<?php else: ?>href="<?php echo $this->_aVars['aPager']['prevUrl']; ?>"<?php endif; ?>>
                    Previous
                </a>
            </li>
<?php endif; ?>
<?php if (count((array)$this->_aVars['aPager']['urls'])): ?>
<?php $this->_aCoreVars['iteration']['pager'] = 0; ?>
<?php foreach ((array) $this->_aVars['aPager']['urls'] as $this->_aVars['sLink'] => $this->_aVars['sPage']): ?><?php $this->_aCoreVars['iteration']['pager']++; ?>

            <li <?php if (! isset ( $this->_aVars['aPager']['firstUrl'] ) && $this->_aCoreVars['iteration']['pager'] == 1): ?> class="first"<?php endif; ?>>
                <a <?php if ($this->_aVars['sAjax']): ?>href="<?php echo $this->_aVars['sLink']; ?>" onclick="<?php if ($this->_aVars['sLink']): ?>$(this).parent().parent().parent().parent().find('.sJsPagerDisplayCount').html($.ajaxProcess('Loading...')); $.ajaxCall('<?php echo $this->_aVars['sAjax']; ?>', 'page=<?php echo $this->_aVars['sPage']; ?><?php echo $this->_aVars['aPager']['sParams']; ?>'); $Core.addUrlPager(this);<?php endif; ?> return false;<?php else: ?>href="<?php if ($this->_aVars['sLink']): ?><?php echo $this->_aVars['sLink']; ?><?php else: ?>javascript:void(0);<?php endif; ?><?php endif; ?>"<?php if ($this->_aVars['aPager']['current'] == $this->_aVars['sPage']): ?> class="active"<?php endif; ?>>
<?php echo $this->_aVars['sPage']; ?>
                </a>
            </li>
<?php endforeach; endif; ?>
<?php if (isset ( $this->_aVars['aPager']['nextUrl'] )): ?>
            <li>
                <a <?php if ($this->_aVars['sAjax']): ?>href="<?php echo $this->_aVars['aPager']['nextUrl']; ?>" onclick="$(this).parent().parent().parent().parent().find('.sJsPagerDisplayCount').html($.ajaxProcess('Loading...')); $.ajaxCall('<?php echo $this->_aVars['sAjax']; ?>', 'page=<?php echo $this->_aVars['aPager']['nextAjaxUrl']; ?><?php echo $this->_aVars['aPager']['sParams']; ?>'); $Core.addUrlPager(this); return false;"<?php else: ?>href="<?php echo $this->_aVars['aPager']['nextUrl']; ?>"<?php endif; ?>>
                        Next
                </a>
            </li>
<?php endif; ?>
<?php if (isset ( $this->_aVars['aPager']['lastUrl'] )): ?>
            <li>
                <a <?php if ($this->_aVars['sAjax']): ?>href="<?php echo $this->_aVars['aPager']['lastUrl']; ?>" onclick="$(this).parent().parent().parent().parent().find('.sJsPagerDisplayCount').html($.ajaxProcess('Loading...')); $.ajaxCall('<?php echo $this->_aVars['sAjax']; ?>', 'page=<?php echo $this->_aVars['aPager']['lastAjaxUrl']; ?><?php echo $this->_aVars['aPager']['sParams']; ?>'); $Core.addUrlPager(this); return false;"<?php else: ?>href="<?php echo $this->_aVars['aPager']['lastUrl']; ?>"<?php endif; ?>>Last
                </a>
            </li>
<?php endif; ?>
        </ul>    
        <div class="clear"></div>
<?php endif; ?>
</div>
