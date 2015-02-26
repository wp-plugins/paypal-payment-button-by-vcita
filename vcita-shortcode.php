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
  if ($predefine_info) {
    $url .= '/?amount='.urlencode($payment_amount).
            '&pay_for="'.urlencode($payment_title).'"';
  }
  $onclick = 'window.open(\''.esc_attr($url).
      '\', \'vcita_pay_now_window\', \'width=800, height=660, location=no, '.
      'menubar=no, status=no, titlebar=no, left=\' + Math.round(screen.width/2-404) '.
      '+ \', top=\' + Math.round(screen.height/2-360));return false;';
  return '<button class="vcita-paypal-pay-now-button" type="button" onclick="'.$onclick.'" style="'.
      esc_attr($style).'">'.esc_html($text).'</button>';
}

