@include('emails.includes.header')

<h4 style="font-size: 36px; margin: 0; color: #191919">Patchworks Alert</h4>
<h5 style="font-size: 18px; color: #777777; margin: 12px 0 4px 0; font-weight: normal;">Dear {{$name}},</h5>
<p  style="font-size: 18px; color: #777777; margin: 0;">Based on your subscription preferences we created this email as an alert to services that you have running in Patchworks.</p>
<br />
<table width="100%" style="margin-top: 24px;">
    <th id="alert-header-gen"></th>
    <tr>
        <td>

            <table width="100%" style="font-size: 12px; border: 1px solid #DDDDDD; border-radius: 6px; border-spacing: 0;">
                <th id="alert-subheader-gen"></th>
                <thead style="font-weight: bold;">
                    <tr style="color: #191919; font-weight: bold;">
                        <td style="padding: 16px; border-bottom: 1px solid #DDDDDD;">Alert Level</td>
                        <td style="padding: 16px; border-bottom: 1px solid #DDDDDD;">Service</td>
                        <td style="padding: 16px; border-bottom: 1px solid #DDDDDD;">Datetime</td>
                    </tr>
                </thead>
                <tbody style="color: #333333;">
                    <tr>
                        <td style="padding: 16px; border-bottom: 1px solid #DDDDDD;">{{ ucwords($alert['alert_type']) }}</td>
                        <td style="padding: 16px; border-bottom: 1px solid #DDDDDD;">{{ $alert['service_id'] }}</td>
                        <td style="padding: 16px; border-bottom: 1px solid #DDDDDD;">{{ $timestamp }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold;padding: 16px; color: #191919;">Message:</td>
                        <td colspan="4" style="padding: 16px;">An alert has been generated, please check your thresholds set on your service id above.</td>
                    </tr>
                </tbody>
            </table>

        </td>
    </tr>
</table>
<br />
<p style="font-size: 18px; color: #777777">If you have any questions about the contents of this email or if action is required, please visit 
    <span style="color: #191919"> 
        <a href="https://app.wearepatchworks.com" target="_blank"  style="color: #191919">https://app.wearepatchworks.com</a>
    </span>
    to create a support ticket
</p>


@include('emails.includes.footer')
