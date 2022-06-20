<?php
if (!function_exists('dateFormat')) {
  // accepted for format 'yyyy-mm-dd hh:ii:ss
  function dateFormat($date='', $format='dd-mm-yyyy')
  {
    $monthName = [
      'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember'
    ];

    $dateStr = @explode(' ', $date)[0];
    $timeStr = @explode(' ', $date)[1];

    $formated = preg_replace_callback('/(yyyy|mmmm|mm|dddd|ddd|dd|hh|ii|ss)/', function ($matches) use($dateStr, $timeStr, $monthName){
      if ( !isset($matches[0]) ) return '';

      switch ($matches[0]) {
        case 'yyyy': 
          return @explode('-', $dateStr)[0];
          break;
        case 'mmmm': 
          $idx = intval(@explode('-', $dateStr)[1]);
          $idx = !$idx ? 0 : $idx-1;

          return @$monthName[$idx];
          break;
        case 'mm': 
          return @explode('-', $dateStr)[1];
          break;
        case 'dd': 
          return @explode('-', $dateStr)[2];
          break;
        case 'hh': 
          return @explode('-', $dateStr)[0];
          break;
        case 'ii': 
          return @explode('-', $dateStr)[1];
          break;
        case 'ss': 
          return @explode('-', $dateStr)[2];
          break;

        default:
          return $matches[0];
          break;
      };
        
    }, $format);

    return $formated;
  }
}
