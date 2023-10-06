@include('emails.alerts.includes.transaction-header')
@php
    $transaction_remaining = (intval($meta['transaction_limit'] / 1024) - intval($meta['transaction_total'] / 1024)).'k';
    if ($meta['transaction_total'] >= $meta['transaction_limit']) {
        $transaction_remaining = 0;
    }
@endphp

<table style="margin-top: 24px; margin-bottom: 0; width: 100%;" aria-label="Notification">
<th id="alert-header-gen"></th>
    <tr>
        <div style="text-align:center; max-width: 540px; margin: 0 auto;">
            <h4 style="font-size: 30px; margin: 0 0 16px 0; color: #191919; text-align:center;">{{ $meta['company_name'] }}</h4>
            <h3 style="font-size: 22px; margin: 0 0 16px 0; color: #191919; text-align:center;">Your account has reached a limit</h3>
            <p  style="font-size: 18px; color: #777777; margin: 0; text-align:center;">
                We wanted to let you know that your account limit has been reached on one or more of your Patchworks services, to continue to use the service uninterrupted you will need to take action. Below are details of your current services and usage.
            </p>
            <br />
        </div>
    </tr>
</table>

<table style="margin-top: 0; margin-bottom: 24px; width: 100%;" aria-label="Notification">
<th id="alert-header-gen"></th>
    <tr>
        <td>
        <div style="display: flex; border-bottom: 1px solid #EEEEEE; padding-bottom: 48px;">
            <div style="border: 1px solid #DDDDDD;border-radius: 6px; padding: 24px; width: 48%; margin-right: 4%; text-align: center;">
                <img src="https://s3.eu-west-1.amazonaws.com/minidash-images.pwks.co/public-images/icon-check.png" alt="Check Icon" style="width: 48px; margin-bottom: 19px" />
                <h4 style="margin: 0 0 8px 0; color: #2BC443; font-size: 14px;">Services</h4>
                <h3 style="margin: 0 0 15px 0; color: #2BC443;font-size: 24px;">{{ $meta['active_services'] }} / {{ $meta['total_services'] }}</h3>
                <p style="color: #777777;font-size: 12px;">{{ intval($meta['total_services']) - intval($meta['active_services']) }} services remaining</p>
            </div>
            <div style="border: 1px solid #DDDDDD;border-radius: 6px; padding: 24px; width: 48%; text-align: center;">
                <img src="https://s3.eu-west-1.amazonaws.com/minidash-images.pwks.co/public-images/icon-warning.png" alt="Wanning Icon" style="width: 48px; margin-bottom: 19px" />
                <h4 style="margin: 0 0 8px 0; color: #E03F29; font-size: 14px;">Transaction Total</h4>
                <h3 style="margin: 0 0 15px 0; color: #E03F29;font-size: 24px;">{{ intval($meta['transaction_total']) }} / {{ intval($meta['transaction_limit']) }}</h3>
                <p style="color: #777777;font-size: 12px;">{{ $transaction_remaining }} Transactions remaining</p>
            </div>
        </div>
        </td>
    </tr>
</table>

<table style="margin-bottom: 24px; width: 100%;" aria-label="Notification">
<th id="alert-header-gen"></th>
    <tr>
        <div style="text-align:center; max-width: 530px; margin: 0 auto;">
            <h4 style="font-size: 24px; margin: 0 0 16px 0; color: #191919; text-align:center;">What should you do next?</h4>
            <p  style="font-size: 18px; color: #777777; margin: 0; text-align:center;">
                We don’t want there to be any interruptions to your service, so to continue you’ll need to upgrade your plan. You may also want to review your account and ensure you are getting the most out of your subscription.
            </p>
        </div>
    </tr>
    <tr>
        <div style="display: flex; border-bottom: 1px solid #EEEEEE;    padding-bottom: 48px;     text-align: center;    max-width: 400px;    margin: 24px auto 0;">
            <a style="border: 1px solid #E3E3E3; border-radius: 2px; display: block; padding: 14px; color: #ffffff; font-size: 13px; width: 49%; margin-right: 2%; background: #50134C;"
                href="https://app.wearepatchworks.com/settings/billing">
                Upgrade Plan
            </a>
            <a style="background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, #EEEEEE 100%), #FFFFFF;
                        border: 1px solid #E3E3E3;
                        border-radius: 2px;
                        display: block;
                        padding: 14px;
                        color: #191919;
                        font-size: 13px;
                        width: 49%;" href="https://app.wearepatchworks.com/settings/billing">
            Manage Plan</a>
        </div>
    </tr>
</table>

<table style="width: 100%;" aria-label="Notification">
<th id="alert-header-gen"></th>
    <tr>
        <div style="text-align:center; max-width: 530px; margin: 0 auto;">
            <h4 style="font-size: 24px; margin: 0 0 16px 0; color: #191919; text-align:center;">Still not sure?</h4>
            <p  style="font-size: 18px; color: #777777; margin: 0; text-align:center;">
            You can speak to one of our client services team and discuss your options further. You can contact them by clicking here.
            </p>
        </div>
    </tr>
    <tr>
        <div style="text-align:center; max-width: 400px;    margin: 24px auto 0;">
            <a style="background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, #EEEEEE 100%), #FFFFFF;
                        border: 1px solid #E3E3E3;
                        border-radius: 2px;
                        display: block;
                        padding: 14px;
                        color: #191919;
                        font-size: 13px;" href="https://app.wearepatchworks.com/settings/billing">
            Upgrade Plan</a>
        </div>
    </tr>
</table>




@include('emails.alerts.includes.transaction-footer')
