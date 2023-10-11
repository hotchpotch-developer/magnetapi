<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Document</title>
    <style>
        *{
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="width:100%;margin-right:auto;margin-left:auto">
        <table class="body-wrap" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: transparent; margin: 0;">
            <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                <td style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
                <td class="container" width="600" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
                    <div class="content" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
                        <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;">
                            <tr style="font-family: 'Roboto', sans-serif; font-size: 14px; margin: 0;">
                                <td class="content-wrap" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; color: #495057; font-size: 14px; vertical-align: top; margin: 0;padding: 30px; box-shadow: 0 3px 15px rgba(30,32,37,.06); ;border-radius: 7px; background-color: #fff;" valign="top">
                                    <meta itemprop="name" content="Confirm Email" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
                                    <table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;background: #8c68cd;" valign="top">
                                                <div style="margin-bottom: 15px;text-align: center;">
                                                    <img src="https://the-magnet.netlify.app/images/logo-light.png" alt="Logo" height="23" style="margin-top: 33px;" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 24px; vertical-align: top; margin: 0; padding: 0 0 10px;  text-align: center;" valign="top">
                                                <h4 style="font-family: 'Roboto', sans-serif; margin-bottom: 0px; margin-top: 20px;font-weight: 500; line-height: 1.5;">Forgot your password</h5>
                                            </td>
                                        </tr>
                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block" style="font-family: 'Roboto', sans-serif; color: #878a99; box-sizing: border-box; font-size: 15px; vertical-align: top; margin: 0; padding: 0 0 12px; text-align: center;" valign="top">
                                                <p style="margin-bottom: 13px; line-height: 1.5;">You can change your password for security reasons or reset it if you forget it.</p>
                                            </td>
                                        </tr>
                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 22px; text-align: center;" valign="top">
                                                <a href="{{ $url }}" itemprop="url" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 1.05rem; color: #FFF; text-decoration: none; font-weight: 400; text-align: center; cursor: pointer; display: inline-block; border-radius: .25rem; text-transform: capitalize; background-color: #8c68cd; margin: 0; border-color: #8c68cd; border-style: solid; border-width: 1px; padding: 1rem 2rem;">Reset Password</a>
                                            </td>
                                        </tr>

                                        <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                            <td class="content-block" style="color: #878a99; font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0; padding-top: 5px" valign="top">
                                                <h5 style="font-family: 'Roboto', sans-serif; margin-bottom: 5px;font-weight: 500; line-height: 1.5;font-size: 15px;">Forgot your password</h5>
                                                <ul style="margin-bottom: 10px;gap: 0.5rem; flex: 1 1 auto;">
                                                    <li>
                                                        Click on "Reset Password" button. If it's not clickable, so kindly click on below URL.
                                                    </li>
                                                    <li>
                                                        Enter your "New Password" and "Confirm Password" and submit.
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                {{ $url }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <div style="text-align: center; margin: 28px auto 0px auto;">
                            <p style="font-family: 'Roboto', sans-serif; font-size: 14px;color: #98a6ad; margin: 0px;">&copy; copyright 2023 The Magnet.</p>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>