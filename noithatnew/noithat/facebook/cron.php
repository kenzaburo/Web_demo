<?php
    @session_start();
    require_once('define.inc.php');
	require_once(STL_CLASS.'STL_MainUser.php');
	
	$oDB = new STL_MainUser();
    $oDB->attTableName = "p_table";
    $filename = 'noithathuyphat.rss';
    $arrTitle = array("Đồ gỗ cao cấp  Huy Phát",
                        "Nội thất Huy Phát",
                        "Nội thất thuần việt huy phát",
                        "Đồ gỗ cao cấp cho mọi nhà",
                        "Huy Phát thể hiện sự thành đạt của bạn",
                        "Đồ gỗ nội thất cho người việt");
    $arrDes =  array("Sản xuất các sản phẩm đồ gỗ trang trí nội thất: bàn ghế, giường, tủ, kệ bếp,...v...v... cao cấp, bền đẹp bằng các loại gỗ tự nhiên. Bán ngay tại xưởng sản xuất, đúng giá bán buôn.",
                        "Chuyên thực hiện thiết kế và sản xuất các sản phẩm: nội thất văn phòng, trường học, khách sạn, nhà hàng, siêu thị như : Bàn làm việc, tủ hồ sơ, bàn họp, quầy tiếp tân, phòng tiếp khách, đại sảnh, phòng ngủ khách sạn, quầy kệ siêu thị",
                        "Chúng tôi tự hào là công ty có nhiều kinh nghiệm trong việc lĩnh vực thiết kế cung cấp và bảo trì nội thất tại việt nam. Hơn 10 năm kinh nghiệm kể từ khi bước vào làng nội thất chúng tôi đã tích luỹ cho mình rất nhiều kinh nghiệm và được rất nhiều sự ủng hộ từ phía khách hàng.",
                        "Tư vấn thiết kế và thi công trang trí nội thất cho các công trình như: Hội trường, trụ sở làm việc, căn hộ gia đình, nhà hàng, bar-café sân vườn...",
                        "Cung cấp các sản phẩm về gổ như:Tủ,Giường,Kệ trang trí,Bàn phấn,v,v...đủ chủng loại,đa dạng về chất liệu,mầu sắc,kích thước,...lăp đặt tại nhà,giá cả hợp lí,bảo hành dài hạn",
                        "Với phương châm:  \"Nội Thất Huy Phát   thể hiện sự thành đạt của bạn \" bằng sự sáng tạo và làm ra những sản phẩm đẹp có tính thẩm mỹ cao. Công Ty Nội Thất Huy Phát cam kết mang đến cho Quý khách hàng sự an tâm về uy tín và chất lượng sản phẩm cũng như giá cả khi khách hàng đặt niềm tin vào Công Ty chúng tôi");
    
    $txtfile = file_get_contents("index.txt", true);
    $arrI = explode("," , $txtfile) ;
    
    if(isset($arrI[2])) $linkI = $arrI[2] + 1;
    else $linkI = 4;
    if($linkI % 4 == 0) @unlink($filename);
    if($linkI >= 127){
        $linkI = 1;
        @unlink($filename);
    }
    $titleI = rand(0, count($arrTitle)-1); while($titleI==$arrI[0]){$titleI = rand(0, count($arrTitle)-1);}
    $desI = rand(0, count($arrDes)-1); while($desI==$arrI[1]){$desI = rand(0, count($arrDes)-1);}
    $fp = fopen('index.txt', 'w');
    fwrite($fp, $titleI . "," . $desI . "," . $linkI);
    fclose($fp);
    $arrData = $oDB->stl_GetAllImage(0, 50);
    $num = rand(0, count($arrData));
    $image = $arrData[$num]->Image == "" ? "images_002.jpg":$arrData[$num]->Image;
	$h = "1";/* Hour for time zone goes here e.g. +7 or -4, just remove the + or -*/
    $hm = $h * 60;
    $ms = $hm * 60;
    $gmdate = gmdate("D, d M Y h:i:s T", time()-($ms));
	
   
    $link = "http://noithathuyphat.com/noithat/htm/product.php?pTable=$linkI";
    if (file_exists($filename)) {
        $filecontent = file_get_contents($filename, true);
        $remain = substr($filecontent, strpos ($filecontent, "<item>"));
        $new = '<?xml version="1.0" encoding="utf-8"?>
            <rss version="2.0">
              <channel>
                <title>Đồ gỗ cao cấp - noithathuyphat.com</title>
                <description>noithathuyphat - Đồ gỗ cao cấp ở Việt Nam</description>
                <link>http://noithathuyphat.com/noithat/htm/</link>
                <copyright>noithathuyphat.com</copyright>
                <generator>noithathuyphat.com:http://noithathuyphat.com</generator>
                <pubDate>' . gmdate("D, d M Y h:i:s T") . '</pubDate>
                <lastBuildDate>' . gmdate("D, d M Y h:i:s T") . '</lastBuildDate>
                    <item>
                        <title><![CDATA[ ' . $arrTitle[$titleI] . ' ]]></title>
                        <description>
                        
                        <![CDATA[ 
                        <img title="Đồ gỗ cao cấp" alt="Đồ gỗ cao cấp" src="http://noithathuyphat.com/noithat/data/table/' . $image . '" />
                         ' . $arrDes[$desI] . '.<BR />> <A  href="http://noithathuyphat.com/">Nội thất Huy Phát</A>
                         ]]>
                        </description>
                        <link>'. $link .'</link>
                        <pubDate>' . $gmdate . '</pubDate>
                    </item>' . chr(13).chr(9).chr(9).chr(9).chr(9).chr(9);
        $newcontent =  $new .   $remain;
        if(@unlink($filename)){
            $handle = fopen($filename, "w");
            fwrite($handle, $newcontent);
            fclose($handle);
        }
       
    } else {
        $content =  '<?xml version="1.0" encoding="utf-8"?>
            <rss version="2.0">
              <channel>
                <title>Đồ gỗ cao cấp - noithathuyphat.com</title>
                <description>noithathuyphat - Đồ gỗ cao cấp ở Việt Nam</description>
                <link>http://noithathuyphat.com/noithat/htm/</link>
                <copyright>noithathuyphat.com</copyright>
                <generator>noithathuyphat.com:http://noithathuyphat.com</generator>
                <pubDate>' . gmdate("D, d M Y h:i:s T") . '</pubDate>
                <lastBuildDate>' . gmdate("D, d M Y h:i:s T") . '</lastBuildDate>
                    <item>
                        <title><![CDATA[ ' . $arrTitle[$titleI] . ']]></title>
                        <description>
                        
                        <![CDATA[ 
                        <img title="Đồ gỗ cao cấp" alt="Đồ gỗ cao cấp" src="http://noithathuyphat.com/noithat/data/table/' . $image . '" />
                        ' . $arrDes[$desI] . '.<BR />> <A  href="http://noithathuyphat.com/">Nội thất Huy Phát</A>
                        ]]>
                        </description>
                        <link>'. $link .'/</link>
                        <pubDate>' . $gmdate . '</pubDate>
                       
                    </item>
              </channel>
            </rss>';
	
        $handle = fopen($filename, "w");
        fwrite($handle, $content);
        fclose($handle);
       
    }
?>