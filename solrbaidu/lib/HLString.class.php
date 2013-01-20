<?php
class HLString {
	/**
	 * ��ͬ������ַ����е����Ķ�Ӧ���ֽ���
	 */    
	static public function GetBytesLen($sCharset = "UTF-8") {
		empty($sCharset) && $sCharset = "UTF-8";
		strtoupper($sCharset) == "GB2312" && $sCharset = "GBK";
		$sCharset = strtoupper($sCharset);

		$aChineseBytesLen = array(
				'UTF-8' => 3,
				'GBK'   => 2
				);
		return array_key_exists($sCharset, $aChineseBytesLen) ? $aChineseBytesLen[$sCharset] : 3;
	}

	/**
	 * �� ��ȡ�����ַ�������ʱ֧��GBK,UTF-8���룬�����Ҫ֧���������ַ����룬����Ҫ����Ӧ�ĵ���
	 * 
	 * for example
	 *     $title = "�л�a����3���͹�";
	 * 
	 *     ��ȡ�ַ��ĳ���
	 *     debug('subString', Tools::subString($title, 3, false); //����subString:|�л�a...|     
	 *     debug('subString', Tools::subString($title, 3, false) //����subString:|�л�a...|
	 * 
	 *     ��ȡ�ֽڵĳ��ȣ�����һ������ʱ��ȡ��һ�������ĺ���,3���ֽڱ�ʾ1.5�����ֳ��ȣ�ȡ����Ϊ1�����ֵĳ���($bAddFlagΪfalse)��2�����ֵĳ���($bAddFlagΪtrue)
	 *     debug('subString', Tools::subString($title, 3, true)); //����subString:|�л�...|
	 *     debug('subString', Tools::subString($title, 3, true, false)); //����subString:|��...|
	 *      
	 *     debug('subString', Tools::subString($title, 3, true)) //����subString:|�л�...|
	 *     debug('subString', Tools::subString($title, 3, true, false)) //����subString:|��...|
	 * 
	 * @param string $sMsg      Ҫ����ȡ���ַ���
	 * @param string $iCutSize  Ҫ��ȡ���ַ��ĳ��Ȼ��ֽڵĳ���,�ο�����$bByteFlag˵��
	 * @param bool   $bByteFlag Ҫ��ȡ�����ֽڵĳ��Ȼ����ַ��ĳ��ȣ�true:��ʾ�ֽ����ĳ���(��ʱ���ֿ��������ֽڣ����֡���ĸ����1���ֽ�)��false:��ʾ�ַ��ĳ��ȣ���ʱ���֡���ĸ�����ֵȶ�����1���ֽڵĳ���
	 * @param bool   $bAddflag  ����ȡ���ַ�����ĳ�����ֵ�һ����ʱ����������ȡ�˺���(true)�����ǲ���ȡ���ַ���(false)
	 * @param string $sCharset  $sMsg�ı���
	 * @param string $sSuffix   ��$sMsg�ĳ��ȴ���$iCutSizeʱ�ں�����ʾ���ַ�
	 * 
	 * @version 1.0 03/02/2007
	 */
	static public function SubString($sMsg, $iCutSize, $bByteFlag = true, $bAddFlag = true, $sCharset = "UTF-8", $sSuffix = "...") {
		if ($iCutSize <= 0) return $sMsg;
		if (empty($sMsg)) return false;        
		$sCharset = strtoupper($sCharset);

		/**
		 * $iCharLen��Ҫ���������$han���
		 */
		$iCharLen = $bByteFlag ? 2 : 1;

		/**
		 * ���ݲ���$bByteFlag��ֵ�����ַ����ĳ���
		 */
		$iTotalLen = $bByteFlag ? self::Strlen($sMsg, 3, $sCharset) : self::Strlen($sMsg, 2, $sCharset);

		/**
		 * $sMsg�ĳ���С��Ҫ��ȡ�ĳ��ȣ����������ַ���
		 */
		if ($iTotalLen < $iCutSize) {
			return $sMsg;
		}

		/**
		 * ���Ҫ��ȡ�����ַ��ĳ��ȣ���ֱ����mb_substr��ȡ���ɣ���mb_substr�����ڰ�����ֵ�����
		 */
		if (!$bByteFlag) {
			$str = mb_substr($sMsg, 0, $iCutSize, $sCharset);
			if ($iCutSize < $iTotalLen) $str .= $sSuffix;
			return $str;
		}

		//$i��ʾѭ��ʱָ���ƫ����
		//$iCutSize <= $iTotalLen
		$i = 1;
		while ($i <= $iTotalLen) {
			if (ord($sMsg[$i - 1]) > 127) {
				$han++;
				// utf-8ÿ��������3�����ȣ�gbk��2������
				$i += self::GetBytesLen($sCharset);
			} else {
				// ���֡��ַ���1������
				$eng++;
				$i = $i + 1;
			}

			//2007-3-26,��$i>=$iCutSizeʱ������ֹѭ������������ѭ��һ��ѭ���꣬��������$eng���������ֵ��׼ȷ�����ִ���
			if ($i > $iCutSize) break;
		}

		//$iChinese��ʾ���ֵ��ֽ���,$eng��ʾ��ȡ����Ϊ$iCutSize���ַ��������ּ��ֳ��ֵĴ���
		$iChinese = $iCutSize - $eng;
		$iRemain = $iChinese % self::GetBytesLen($sCharset);
		//ȷ����ȡ���������ĺ��֣������ǰ��
		if ($iRemain != 0) {
			if ($bAddFlag) {
				$iCutSize += self::GetBytesLen($sCharset) - $iRemain;
			} else {
				$iCutSize -= $iRemain;
			}
		}
		$iCutSize = ceil($iCutSize);
		for ($i = 0; $i < $iCutSize; $i++) {
			$str .= $sMsg[$i];
		}

		/**
		 * ���Ҫ��ȡ���ַ����ĳ���С��ԭ�ַ����ĳ��ȣ������$sSuffix
		 */        
		if ($iCutSize < $iTotalLen) $str .= $sSuffix;
		return $str;
	}

	public static function Strlen($sStr, $iType = 1, $sCharset = 'UTF-8') {
		if (empty($sStr)) return false;        
		$sCharset = strtoupper($sCharset);        
		switch ($iType) {
			case 1:
				/**
				 * $i��ʾѭ��ʱָ���ƫ����
				 * $iLen��ʾ�ַ����ĳ���
				 */
				$i = 1;
				while ($i <= strlen($sStr)) {
					if (ord($sStr[$i - 1]) > 127) {
						$iLen += 2;
						$i += self::GetBytesLen($sCharset);
					} else {
						$iLen += 1;
						$i += 1;
					}
				}
				break;
			case 2:
				$iLen = mb_strlen($sStr, $sCharset);
				break;
			case 3:
				$iLen = strlen($sStr);
				break;
		}
		return $iLen;
	}

	/**
	 * Returns true if the first arg starts with the second arg
	 * @param    string    $big_string
	 * @param    string    $little_string
	 * @return   true or false
	 */
	static public function StartWith($big_string, $little_string) {
		return !($len = strlen($little_string)) ||
			isset($big_string[$len - 1]) &&
			substr_compare($big_string, $little_string, 0, $len) === 0;
	}

	static public function StripslashesRecursive($a) {
		if (!is_array($a)) {
			return stripslashes($a);
		}
		$ret = array();
		foreach ($a as $key => $val) {
			$ret[stripslashes($key)] = self::StripslashesRecursive($val);
		}
		return $ret;
	}


	/**
	 * Undoes any magic quote slashing from an array, like the GET or POST
	 * @param    array    $a    Probably either $_GET or $_POST or $_COOKIES
	 * @return   array    The array with all of the values in it noslashed
	 *
	 * In many cases, this can be a drop-in replacement for stripslashes_recursive
	 * since this is what we typically use stripslashes_recursive for.  This is
	 * somewhat different in that if we ever turn off magic quotes, it will still
	 * behave correctly and not double stripslashes.
	 *
	 */
	static public function NoslashesRecursive($a) {
		if (get_magic_quotes_gpc()) {
			$a = self::StripslashesRecursive($a);
		}
		return $a;
	}

	/**
	 * Sanitizes a string to make sure it is valid UTF that will not break in
	 * json_encode or something else dastaradly like that.
	 *
	 * @param string $str String with potentially invalid UTF8
	 * @return string Valid utf-8 string
	 */
	static public function Utf8Sanitize($str) {
		return iconv('utf-8', 'utf-8//IGNORE', $str);
	}

	/**
	 * Escapes text to make it safe to use with Javascript
	 *
	 * It is usable as, e.g.:
	 *  echo '<script>alert(\'begin'.escape_js_quotes($mid_part).'end\');</script>';
	 * OR
	 *  echo '<tag onclick="alert(\'begin'.escape_js_quotes($mid_part).'end\');">';
	 * Notice that this function happily works in both cases; i.e. you don't need:
	 *  echo '<tag onclick="alert(\'begin'.txt2html_old(escape_js_quotes($mid_part)).'end\');">';
	 * That would also work but is not necessary.
	 *
	 * @param  string $str    The data to escape
	 * @param  bool   $quotes should wrap in quotes (isn't this kind of silly?)
	 * @return string         Escaped data
	 */
	static public function EscapeJsQuotes($str, $quotes=false) {
		if ($str === null) {
			return;
		}
		$str = strtr($str, array('\\'=>'\\\\', "\n"=>'\\n', "\r"=>'\\r', '"'=>'\\x22', '\''=>'\\\'', '<'=>'\\x3c', '>'=>'\\x3e', '&'=>'\\x26'));
		return $quotes ? '"'. $str . '"' : $str;
	}

}

