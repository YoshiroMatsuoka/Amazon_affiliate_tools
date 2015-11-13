<?php

require_once "Isbn_Tools.inc";

//Enter your IDs

define("AmzUrls","http://ecs.amazonaws.jp/onca/xml");
define("Access_Key_ID", "*****************");
define("SecretAccessKey",'****************');
define("Associate_tag", "*****************");

/*
 *AmazonからISBNコードをキーに情報を得るクラス 書籍限定
 */
class Amz_xml {
	var $obj;
	var $Request;

	function __construct($isbn) {
		if(trim($isbn) == "") {
			echo "error";
			exit;
		}
		$isbn = Isbn_Tools::ISBN13to10($isbn);
		$this->obj = $this->AsinSearch($isbn);
	}

	function AsinSearch($asin){
		//Define the request
		$params = array();
		$params['Service'] = "AWSECommerceService";
		$params['AWSAccessKeyId'] = Access_Key_ID;
		$params['Operation'] = "ItemLookup";
		$params['IdType'] = "ASIN";
		$params['ItemId'] = $asin;
		$params['ResponseGroup'] = "Large";
		$params['AssociateTag'] = Associate_tag;

		$params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');

		ksort($params);

		// canonical string を作成します
		$canonical_string = '';
		foreach ($params as $k => $v) {
			$canonical_string .= '&'.$this->urlencode_rfc3986($k).'='.$this->urlencode_rfc3986($v);
		}
		$canonical_string = substr($canonical_string, 1);

		// 署名を作成します
		// - 規定の文字列フォーマットを作成
		// - HMAC-SHA256 を計算
		// - BASE64 エンコード
		$parsed_url = parse_url(AmzUrls);
		$string_to_sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$canonical_string}";
		$signature = base64_encode(hash_hmac('sha256', $string_to_sign, SecretAccessKey, true));

		// URL を作成します
		// - リクエストの末尾に署名を追加
		$request = AmzUrls.'?'.$canonical_string.'&Signature='.$this->urlencode_rfc3986($signature);
		$this->Request = $request;

		$response = file_get_contents($request);
		return $parsed_xml = simplexml_load_string($response);
	}

	function getRequestSQL() {
		return $this->Request;
	}

	function GetProductName() {
		return $this->obj->Items->Item->ItemAttributes->Title;
	}

	function GetAuthor() {
		return $this->obj->Items->Item->ItemAttributes->Author;
	}

	function GetPublicationDate() {
		return $this->obj->Items->Item->ItemAttributes->PublicationDate;
	}

	function GetPublisher() {
		return $this->obj->Items->Item->ItemAttributes->Publisher;
	}

	function GetIsbn13() {
		return $this->obj->Items->Item->ItemAttributes->EAN;
	}

	function GetIsbn10() {
		return $this->obj->Items->Item->ItemAttributes->ISBN;
	}

	function GetUrlImgSmall() {
		if($this->obj->Items->Item->SmallImage->URL== "") {
			return -1;
		}
		return $this->obj->Items->Item->SmallImage->URL;
	}

	function GetUrlImgMedium() {
		if($this->obj->Items->Item->MediumImage->URL== "") {
			return -1;
		}
		return $this->obj->Items->Item->MediumImage->URL;
	}

	function GetUrlImgLarge() {
		if($this->obj->Items->Item->LargeImage->URL== "") {
			return -1;
		}
		return $this->obj->Items->Item->LargeImage->URL;
	}

	public function view_request() {
		return $this->Request;
	}

	// RFC3986 形式で URL エンコードする関数
	function urlencode_rfc3986($str)
	{
		return str_replace('%7E', '~', rawurlencode($str));
	}

}
$AMZ = new Amz_xml('9784048708418');
echo $AMZ->view_request();
echo $AMZ->GetProductName();
echo $AMZ->GetAuthor();
echo $AMZ->GetUrlImgSmall();
echo $AMZ->GetUrlImgMedium();
echo $AMZ->GetUrlImgLarge();
?>
