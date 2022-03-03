<?php
include "db.php";
$jsonArray = array();
    // stock ekleme kısmı
 if($_SERVER['REQUEST_METHOD'] == "POST") {

    $product_id = addslashes($_POST["product_id"]);
    $name = addslashes($_POST["name"]);
    $stock = addslashes($_POST["stock"]);
    $created_date = date("Y-m-d H:i:s");

    // gelen stock kodu daha önce kayıtta var mı?
    $stocklar = $db->query("SELECT * from stock WHERE product_id='$product_id'");
    
    if(empty($product_id) || empty($name) || empty($stock)) {
		$jsonArray["hata"] = TRUE;
        $jsonArray["hataMesaj"] = "Boş Alan Bırakmayınız.";
	}
    else if($stocklar->rowCount() !=0) {
        $jsonArray["hata"] = TRUE; // bir hata olduğu bildirilsin.
        $jsonArray["hataMesaj"] = "Ürün daha önceden kaydedilmiş";
    }else {
    	
			$ex = $db->prepare("INSERT INTO stock SET 
			product_id= :product_id, 
			name= :name, 
			stock= :stock, 
			created_date= :created_date");
		$ekle = $ex->execute(array(
			"product_id" => $product_id,
			"name" => $name,
			"stock" => $stock,
			"created_date" => $created_date
		));
		
		
		if($ekle) {
			$jsonArray["code"] = "0";
			$jsonArray["mesaj"] = "Success";
            $bilgiler = $db->query("select product_id,name,stock,created_date from  stock WHERE product_id='$product_id'")->fetch(PDO::FETCH_ASSOC);
            $jsonArray["data"] = $bilgiler;

		}else {
			 $jsonArray["hata"] = TRUE; // bir hata olduğu bildirilsin.
       		 $jsonArray["hataMesaj"] = "Sistem Hatası.";
		}
	}
}else if($_SERVER['REQUEST_METHOD'] == "GET") {

			$bilgiler = $db->query("select product_id,name,stock,created_date from  stock");
     while($sorgu=$bilgiler->fetchall(PDO::FETCH_ASSOC))
     {
         $jsonArray["data"] = $sorgu;
     }

            $jsonArray["code"] = "0";
			$jsonArray["mesaj"] = "Success";

}


echo json_encode($jsonArray);
?>