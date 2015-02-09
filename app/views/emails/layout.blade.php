<table style="width: 100%; border: 0;"  cellspacing="0" cellpadding="0">
    <tr>
        <td style="width: 100%;" align="center">
            <table style="width: 700px;"  cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width: 700px;height: 235px;">
                        <img src="{{ asset('assets/images/email_template/header.jpg') }}" style="width: 700px; height: 235px;display: block;" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="width: 100%;" align="center">
            <table style="width: 700px;" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top" style="width: 55px; height: 83px; background-color: #f7f7f7;">
                        <img src="{{ asset('assets/images/email_template/left_corner.jpg') }}" style="width: 55px;height: 83px;display: block;" />
                    </td>
                    <td style="width: 590px; background-color: #FFF; height: 236px; padding: 10px 25px 10px 25px; border: 1px solid #bec3d6; font-family: 'Lucida Grande', sans-serif; font-size: 9pt;">
                        @yield('main')
                    </td>
                    <td valign="top" style="width: 55px; height: 83px; background-color: #f7f7f7;">
                        <img src="{{ asset('assets/images/email_template/right_corner.jpg') }}" style="width: 55px; height: 83px;display: block;" />
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="width: 100%;" align="center">
            <table style="width: 700px; background-color: #f7f7f7;" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width: 700px; padding-top: 17px;padding-bottom: 20px;" align="center">
                        <a href="{{ route('home.index') }}" target="_blank"><img src="{{ asset('assets/images/email_template/button.jpg') }}" style="width:122px; height: 25px;display: block;" /></a>
                    </td>
                </tr>
                <tr>
                    <td style="width: 700px;" align="center">
                        <img src="{{ asset('assets/images/email_template/footer.jpg') }}" style="width:700px; height: 119px;display: block;" />
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>