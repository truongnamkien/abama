<table style="font-family:Arial, Helvetica, sans-serif;margin:0px!important;padding:0px!important;border:0px!important;outline:0px;" align="center" border="0" width="100%" cellpadding="0" cellspacing="0">
    <tbody>
        <tr>
            <td colspan="3" align="center" bgcolor="#FFA945">
                <table style="color:#FFF;font-size:10px;border-top:3px solid #FFA945;border-bottom:2px solid #FFA945;" border="0" width="600" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td align="center">
                                <?php echo $header_bar; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center" bgcolor="#fe8a03">
                <table border="0" width="600" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td height="10"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                        </tr>
                        <tr>
                            <td width="2%">&nbsp;</td>
                            <td width="30%">
                                <a rel="nofollow" target="_blank" href="<?php echo site_url(); ?>" title="<?php echo PAGE_TITLE; ?>">
                                    <img src="<?php echo asset_url('images/logo.png'); ?>" border="0" height="35" width="210" />
                                </a>
                            </td>
                            <td align="right" valign="middle" width="40%">
                                <img src="<?php echo asset_url('css/images/mobile.png'); ?>" border="0" height="25" width="25" />
                            </td>
                            <td align="left" valign="middle" width="22%">
                                <span style="font-size:18px;font-weight:bolder;color:#fff;"><?php echo $mobile; ?></span>
                            </td>
                            <td align="left" width="6%">
                                <a rel="nofollow" target="_blank" href="<?php echo $facebook_page; ?>">
                                    <img src="<?php echo asset_url('css/images/facebook.png'); ?>" border="0" height="25" width="25" />
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td height="10"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                            <td align="right"></td>
                        </tr>
                    </tbody>
                </table>

            </td>
        </tr>
        <tr>
            <td height="10"></td>
            <td height="10"></td>
            <td height="10"></td>
        </tr>
        <tr>
            <td align="left" width="2%"></td>
            <td align="left" width="96%">
                <?php echo $content; ?>
            </td>
            <td align="left" width="2%"></td>
        </tr>
        <tr>
            <td height="10"></td>
            <td height="10"></td>
            <td height="10"></td>
        </tr>
        <tr>
            <td colspan="3" style="background:#FFA945;color:#fff;">
                <table border="0" width="100%" cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td style="font-size:13px;font-weight:bold;text-align:center;" height="50">
                                <?php echo lang('email_footer', '', $email, $mobile); ?>
                            </td>
                        </tr>
                        <?php if (!empty($to_email)): ?>
                            <tr>
                                <td style="text-align:center;font-size:10px;line-height:17px;">
                                    <?php echo lang('email_footer_subscribe'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:center;font-size:10px;line-height:17px;">
                                    <?php echo lang('email_footer_unsubscribe', '', site_url('email/unsubscribe?email=' . $to_email)); ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
