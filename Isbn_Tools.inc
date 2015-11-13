<?php

class Isbn_Tools {
	public function ISBN13to10($isbn) {
		if(strlen($isbn) == 13) {
			$plus_tmp = array();
			$j= 10;
			for($i=0;$i<12;$i++) {
				if($i >= 3) {
					$plus_tmp[] = $j * substr($isbn,$i,1);
					$j--;	
				}
			}
	
			$max = 0;
	
			for($i=0;$i<count($plus_tmp);$i++) {
				$max += $plus_tmp[$i];
			}
	
			$digit = 11 - ($max % 11);
			if($digit == 10) {
				$digit = 'X';
			} elseif($digit == 11) {
				$digit = '0';
			}
			return substr($isbn,3,9).$digit;
		}
		return $isbn;
	}

	public function ISBN10to13($isbn) {
		if(strlen($isbn) == 10) {
			$isbn = '978'.$isbn;
			$tmp = 0;
			for($i=0;$i<12;$i++) {
				if($i % 2 == 0) {
					$k = 1;
				} else {
					$k = 3;
				}
				$tmp += $k * substr($isbn,$i,1);
			}

			$digit = 10 - ($tmp % 10);
			if($digit == 10) $digit = 0;
			$isbn = substr($isbn,0,12).$digit;
		}

		return $isbn;
	}
}

?>
