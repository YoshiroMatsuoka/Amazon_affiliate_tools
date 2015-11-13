# Amazon_affiliate_tools

Amazonアフィリエイトを利用するための簡易クラスです。
利用用途的に書籍に限定していますが、改造でどうとでもなるかと。

ISBNコード(10桁もしくは13桁)を引数に呼び出してやると
該当する書籍に関する情報が取得出来ます。

各define値に関しては、

〇ファイル構成
●Amz_xml.php
 本体クラス。

 利用方法はこんな感じ。

$AMZ = new Amz_xml('9784048708418');

//Amazonへ対する生の呼び出しクエリ
echo $AMZ->view_request();

//書籍名
echo $AMZ->GetProductName();

//著者名
echo $AMZ->GetAuthor();

//発売日
echo $AMZ->GetPublicationDate();

//発行社
echo $AMZ->GetPublisher();

//ISBNコード13桁
echo $AMZ->GetISBN13();

//ISBNコード10桁
echo $AMZ->GetISBN10();

//画像URL(小)
echo $AMZ->GetUrlImgSmall();

//画像URL(中)
echo $AMZ->GetUrlImgMedium();

//画像URL(大)
echo $AMZ->GetUrlImgLarge();

●Isbn_Tools.inc
ISBNコードの10桁 13桁の相互変換関数。

//13桁を10桁に
function ISBN13to10($isbn)

//10桁を13桁に
function ISBN10to13($isbn)

