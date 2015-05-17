<?php

define('VCITA_PAY_NOW_SHORTCODE', 'vcita_pay_now');

add_shortcode(VCITA_PAY_NOW_SHORTCODE, 'vcita_pay_now_shortcode');
function vcita_pay_now_shortcode($attrs, $content='') {
  $preview = (bool)@$attrs['preview'];
  $uid = vcita_get_uid();
  if (!$uid) return 'Button disabled.';
  extract(vcita_get_add_options());
  $text = $button_label;
  $style = trim(str_replace("\n", ' ', str_replace("\r", ' ', $button_style)));
  if ($preview) {
    $style = 'margin-left: auto; margin-right: auto; '.$style;
  }
  $url = 'https://www.vcita.com/v/'.$uid.'/make_payment';
  $data_str = '';
  if ($predefine_info) {
    $url .= '/?amount='.urlencode($payment_amount).
            '&pay_for="'.urlencode($payment_title).'"';
    $data_str = ' data-amount="'.urlencode($payment_amount).'" '.
            'data-title="'.urlencode($payment_title).'"';
  }
  $vcita_widget = (array)get_option(VCITA_WIDGET_KEY);  
  $engage_active = @$vcita_widget['engage_active'];
  if ($engage_active == 'false') $engage_active = false;
  if ($engage_active) {
    $onclick = 'return false;';
  } else {
    $onclick = 'window.open(\''.esc_attr($url).
        '\', \'vcita_pay_now_window\', \'width=800, height=660, location=no, '.
        'menubar=no, status=no, titlebar=no, left=\' + Math.round(screen.width/2-404) '.
        '+ \', top=\' + Math.round(screen.height/2-360));return false;';
  }
  $html =
      '<div class="vcita-pay-now-wrap" style="display:inline-block;text-align:center;padding:2px">'."\n".
      '  <button class="vcita-paypal-pay-now-button livesite-pay"'.
         $data_str.' type="button" onclick="'.$onclick.'" style="'.
           esc_attr($style).'">'.esc_html($text).'</button>'."\n";
  if ($add_icons) {
    $img_src = plugins_url('images/payment-icons.png', __FILE__);
    $html .=
      '  <div class="vcita-payment-icons" style="padding-top:8px">'."\n".
      '    <img src="'.$img_src.'" width="153" height="20">'."\n".
      "  </div>\n";
  }
  $html .=
      "</div>\n";
  return $html;
}

