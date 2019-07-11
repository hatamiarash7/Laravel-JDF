<?php

namespace Hatamiarash7\JDF;

class Generator
{
	public function getTimestamp()
	{
		$persian = $this->getCDate($this->getCurrentTime()) . ' ' . $this->getCTime($this->getCurrentTime());
		$date = explode(' ', $persian)[0];
		$time = explode(' ', $persian)[1];
		return $date . ' - ' . $time;
	}

	public function convertDate($given_date)
	{
		$persian = $this->getCDate($given_date) . ' ' . $this->getCTime($given_date);
		$date = explode(' ', $persian)[0];
		$time = explode(' ', $persian)[1];
		return $date . ' - ' . $time;
	}

	public function getTime()
	{
		$persian = $this->getCDate($this->getCurrentTime()) . ' ' . $this->getCTime($this->getCurrentTime());
		return explode(' ', $persian)[1];
	}

	public function getDate()
	{
		$persian = $this->getCDate($this->getCurrentTime()) . ' ' . $this->getCTime($this->getCurrentTime());
		return explode(' ', $persian)[0];
	}

	private function getCurrentTime()
	{
		$now = date("Y-m-d", time());
		$time = date("H:i:s", time());
		return $now . ' ' . $time;
	}

	private function getCDate($date)
	{
		$now = explode(' ', $date)[0];
		$time = explode(' ', $date)[1];
		list($year, $month, $day) = array_map('intval', explode('-', $now));
		list($hour, $minute, $second) = array_map('intval', explode(':', $time));
		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
		$date = $this->jDate("Y/m/d", $timestamp);
		return $date;
	}

	private function getCTime($date)
	{
		$now = explode(' ', $date)[0];
		$time = explode(' ', $date)[1];
		list($year, $month, $day) = array_map('intval', explode('-', $now));
		list($hour, $minute, $second) = array_map('intval', explode(':', $time));
		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
		$date = $this->jDate("H:i", $timestamp);
		return $date;
	}

	public function convertToGregorian($date)
	{
		$date = explode('/', $date);
		return $this->jalali_to_gregorian($date[0], $date[1], $date[2]);
	}

	private function jdate($format, $timestamp = '', $time_zone = 'Asia/Tehran', $tr_num = 'fa')
	{
		$T_sec = 0;

		if ($time_zone != 'local') {
			date_default_timezone_set(($time_zone === '') ? 'Asia/Tehran' : $time_zone);
		}
		$ts = $T_sec + (($timestamp === '') ? time() : $this->tr_num($timestamp));
		$date = explode('_', date('H_i_j_n_O_P_s_w_Y', $ts));
		list($j_y, $j_m, $j_d) = $this->gregorian_to_jalali($date[8], $date[3], $date[2]);
		$doy = ($j_m < 7) ? (($j_m - 1) * 31) + $j_d - 1 : (($j_m - 7) * 30) + $j_d + 185;
		$kab = (((($j_y % 33) % 4) - 1) == ((int)(($j_y % 33) * 0.05))) ? 1 : 0;
		$sl = strlen($format);
		$out = '';
		for ($i = 0; $i < $sl; $i++) {
			$sub = substr($format, $i, 1);
			if ($sub == '\\') {
				$out .= substr($format, ++$i, 1);
				continue;
			}
			switch ($sub) {
				case'E':
				case'R':
				case'x':
				case'X':
					$out .= 'http://jdf.scr.ir';
					break;
				case'B':
				case'e':
				case'g':
				case'G':
				case'h':
				case'I':
				case'T':
				case'u':
				case'Z':
					$out .= date($sub, $ts);
					break;
				case'a':
					$out .= ($date[0] < 12) ? 'ق.ظ' : 'ب.ظ';
					break;
				case'A':
					$out .= ($date[0] < 12) ? 'قبل از ظهر' : 'بعد از ظهر';
					break;
				case'b':
					$out .= (int)($j_m / 3.1) + 1;
					break;
				case'c':
					$out .= $j_y . '/' . $j_m . '/' . $j_d . ' ،' . $date[0] . ':' . $date[1] . ':' . $date[6] . ' ' . $date[5];
					break;
				case'C':
					$out .= (int)(($j_y + 99) / 100);
					break;
				case'd':
					$out .= ($j_d < 10) ? '0' . $j_d : $j_d;
					break;
				case'D':
					$out .= $this->jdate_words(array('kh' => $date[7]), ' ');
					break;
				case'f':
					$out .= $this->jdate_words(array('ff' => $j_m), ' ');
					break;
				case'F':
					$out .= $this->jdate_words(array('mm' => $j_m), ' ');
					break;
				case'H':
					$out .= $date[0];
					break;
				case'i':
					$out .= $date[1];
					break;
				case'j':
					$out .= $j_d;
					break;
				case'J':
					$out .= $this->jdate_words(array('rr' => $j_d), ' ');
					break;
				case'k';
					$out .= $this->tr_num(100 - (int)($doy / ($kab + 365) * 1000) / 10, $tr_num);
					break;
				case'K':
					$out .= $this->tr_num((int)($doy / ($kab + 365) * 1000) / 10, $tr_num);
					break;
				case'l':
					$out .= $this->jdate_words(array('rh' => $date[7]), ' ');
					break;
				case'L':
					$out .= $kab;
					break;
				case'm':
					$out .= ($j_m > 9) ? $j_m : '0' . $j_m;
					break;
				case'M':
					$out .= $this->jdate_words(array('km' => $j_m), ' ');
					break;
				case'n':
					$out .= $j_m;
					break;
				case'N':
					$out .= $date[7] + 1;
					break;
				case'o':
					$jdw = ($date[7] == 6) ? 0 : $date[7] + 1;
					$dny = 364 + $kab - $doy;
					$out .= ($jdw > ($doy + 3) and $doy < 3) ? $j_y - 1 : (((3 - $dny) > $jdw and $dny < 3) ? $j_y + 1 : $j_y);
					break;
				case'O':
					$out .= $date[4];
					break;
				case'p':
					$out .= $this->jdate_words(array('mb' => $j_m), ' ');
					break;
				case'P':
					$out .= $date[5];
					break;
				case'q':
					$out .= $this->jdate_words(array('sh' => $j_y), ' ');
					break;
				case'Q':
					$out .= $kab + 364 - $doy;
					break;
				case'r':
					$key = $this->jdate_words(array('rh' => $date[7], 'mm' => $j_m));
					$out .= $date[0] . ':' . $date[1] . ':' . $date[6] . ' ' . $date[4] . ' ' . $key['rh'] . '، ' . $j_d . ' ' . $key['mm'] . ' ' . $j_y;
					break;
				case's':
					$out .= $date[6];
					break;
				case'S':
					$out .= 'ام';
					break;
				case't':
					$out .= ($j_m != 12) ? (31 - (int)($j_m / 6.5)) : ($kab + 29);
					break;
				case'U':
					$out .= $ts;
					break;
				case'v':
					$out .= $this->jdate_words(array('ss' => ($j_y % 100)), ' ');
					break;
				case'V':
					$out .= $this->jdate_words(array('ss' => $j_y), ' ');
					break;
				case'w':
					$out .= ($date[7] == 6) ? 0 : $date[7] + 1;
					break;
				case'W':
					$avs = (($date[7] == 6) ? 0 : $date[7] + 1) - ($doy % 7);
					if ($avs < 0) {
						$avs += 7;
					}
					$num = (int)(($doy + $avs) / 7);
					if ($avs < 4) {
						$num++;
					} elseif ($num < 1) {
						$num = ($avs == 4 or $avs == ((((($j_y % 33) % 4) - 2) == ((int)(($j_y % 33) * 0.05))) ? 5 : 4)) ? 53 : 52;
					}
					$aks = $avs + $kab;
					if ($aks == 7) {
						$aks = 0;
					}
					$out .= (($kab + 363 - $doy) < $aks and $aks < 3) ? '01' : (($num < 10) ? '0' . $num : $num);
					break;
				case'y':
					$out .= substr($j_y, 2, 2);
					break;
				case'Y':
					$out .= $j_y;
					break;
				case'z':
					$out .= $doy;
					break;
				default:
					$out .= $sub;
			}
		}
		return ($tr_num != 'en') ? $this->tr_num($out, 'fa', '.') : $out;
	}

	private function tr_num($str, $mod = 'en', $mf = '٫')
	{
		$num_a = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.');
		$key_a = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $mf);
		return ($mod == 'fa') ? str_replace($num_a, $key_a, $str) : str_replace($key_a, $num_a, $str);
	}

	public function jdate_words($array, $mod = '')
	{
		foreach ($array as $type => $num) {
			$num = (int)$this->tr_num($num);
			switch ($type) {
				case'ss':
					$sl = strlen($num);
					$xy3 = substr($num, 2 - $sl, 1);
					$h3 = $h34 = $h4 = '';
					if ($xy3 == 1) {
						$p34 = '';
						$k34 = array('ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده');
						$h34 = $k34[substr($num, 2 - $sl, 2) - 10];
					} else {
						$xy4 = substr($num, 3 - $sl, 1);
						$p34 = ($xy3 == 0 or $xy4 == 0) ? '' : ' و ';
						$k3 = array('', '', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود');
						$h3 = $k3[$xy3];
						$k4 = array('', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه');
						$h4 = $k4[$xy4];
					}
					$array[$type] = (($num > 99) ? str_replace(array('12', '13', '14', '19', '20')
								, array('هزار و دویست', 'هزار و سیصد', 'هزار و چهارصد', 'هزار و نهصد', 'دوهزار')
								, substr($num, 0, 2)) . ((substr($num, 2, 2) == '00') ? '' : ' و ') : '') . $h3 . $p34 . $h34 . $h4;
					break;
				case'mm':
					$key = array('فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند');
					$array[$type] = $key[$num - 1];
					break;
				case'rr':
					$key = array('یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه', 'ده', 'یازده', 'دوازده', 'سیزده'
					, 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده', 'بیست', 'بیست و یک', 'بیست و دو', 'بیست و سه'
					, 'بیست و چهار', 'بیست و پنج', 'بیست و شش', 'بیست و هفت', 'بیست و هشت', 'بیست و نه', 'سی', 'سی و یک');
					$array[$type] = $key[$num - 1];
					break;
				case'rh':
					$key = array('یکشنبه', 'دوشنبه', 'سه شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه', 'شنبه');
					$array[$type] = $key[$num];
					break;
				case'sh':
					$key = array('مار', 'اسب', 'گوسفند', 'میمون', 'مرغ', 'سگ', 'خوک', 'موش', 'گاو', 'پلنگ', 'خرگوش', 'نهنگ');
					$array[$type] = $key[$num % 12];
					break;
				case'mb':
					$key = array('حمل', 'ثور', 'جوزا', 'سرطان', 'اسد', 'سنبله', 'میزان', 'عقرب', 'قوس', 'جدی', 'دلو', 'حوت');
					$array[$type] = $key[$num - 1];
					break;
				case'ff':
					$key = array('بهار', 'تابستان', 'پاییز', 'زمستان');
					$array[$type] = $key[(int)($num / 3.1)];
					break;
				case'km':
					$key = array('فر', 'ار', 'خر', 'تی‍', 'مر', 'شه‍', 'مه‍', 'آب‍', 'آذ', 'دی', 'به‍', 'اس‍');
					$array[$type] = $key[$num - 1];
					break;
				case'kh':
					$key = array('ی', 'د', 'س', 'چ', 'پ', 'ج', 'ش');
					$array[$type] = $key[$num];
					break;
				default:
					$array[$type] = $num;
			}
		}
		return ($mod === '') ? $array : implode($mod, $array);
	}

	private function gregorian_to_jalali($gy, $gm, $gd, $mod = '')
	{
		list($gy, $gm, $gd) = explode('_', $this->tr_num($gy . '_' . $gm . '_' . $gd)); /* <= Extra :اين سطر ، جزء تابع اصلي نيست */
		$g_d_m = array(0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334);
		if ($gy > 1600) {
			$jy = 979;
			$gy -= 1600;
		} else {
			$jy = 0;
			$gy -= 621;
		}
		$gy2 = ($gm > 2) ? ($gy + 1) : $gy;
		$days = (365 * $gy) + ((int)(($gy2 + 3) / 4)) - ((int)(($gy2 + 99) / 100)) + ((int)(($gy2 + 399) / 400)) - 80 + $gd + $g_d_m[$gm - 1];
		$jy += 33 * ((int)($days / 12053));
		$days %= 12053;
		$jy += 4 * ((int)($days / 1461));
		$days %= 1461;
		$jy += (int)(($days - 1) / 365);
		if ($days > 365) {
			$days = ($days - 1) % 365;
		}
		if ($days < 186) {
			$jm = 1 + (int)($days / 31);
			$jd = 1 + ($days % 31);
		} else {
			$jm = 7 + (int)(($days - 186) / 30);
			$jd = 1 + (($days - 186) % 30);
		}
		return ($mod === '') ? array($jy, $jm, $jd) : $jy . $mod . $jm . $mod . $jd;
	}

	private function jalali_to_gregorian($jy, $jm, $jd, $mod = '')
	{
		list($jy, $jm, $jd) = explode('_', $this->tr_num($jy . '_' . $jm . '_' . $jd));
		if ($jy > 979) {
			$gy = 1600;
			$jy -= 979;
		} else {
			$gy = 621;
		}
		$days = (365 * $jy) + (((int)($jy / 33)) * 8) + ((int)((($jy % 33) + 3) / 4)) + 78 + $jd + (($jm < 7) ? ($jm - 1) * 31 : (($jm - 7) * 30) + 186);
		$gy += 400 * ((int)($days / 146097));
		$days %= 146097;
		if ($days > 36524) {
			$gy += 100 * ((int)(--$days / 36524));
			$days %= 36524;
			if ($days >= 365) $days++;
		}
		$gy += 4 * ((int)(($days) / 1461));
		$days %= 1461;
		$gy += (int)(($days - 1) / 365);
		if ($days > 365) $days = ($days - 1) % 365;
		$gd = $days + 1;
		$gm = null;
		foreach (array(0, 31, ((($gy % 4 == 0) and ($gy % 100 != 0)) or ($gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31) as $gm => $v) {
			if ($gd <= $v) break;
			$gd -= $v;
		}
		return ($mod === '') ? array($gy, $gm, $gd) : $gy . $mod . $gm . $mod . $gd;
	}
}
