<?php
require_once 'php/db_config.php';

// Delete all old products
$conn->query("DELETE FROM products");
$conn->query("ALTER TABLE products AUTO_INCREMENT = 1");

$products = [
    [1,'Sony WH-1000XM4 Wireless Headphones','Industry-leading noise cancellation',10000.00,'https://m.media-amazon.com/images/I/71o8Q5XJS5L._AC_SL1500_.jpg','headphones','available',4.80],
    [2,'Apple AirPods Pro','Active Noise Cancellation for immersive sound',24999.00,'https://m.media-amazon.com/images/I/71bhWgQK-cL._AC_SL1500_.jpg','earbuds','available',4.70],
    [3,'Samsung Galaxy Watch 5','Advanced health monitoring with elegant design',27999.00,'https://m.media-amazon.com/images/I/61aVQDazNHL._AC_SL1500_.jpg','smartwatch','available',4.60],
    [4,'Anker PowerCore 26800mAh','High-capacity portable charger with 3 USB ports',4999.00,'https://m.media-amazon.com/images/I/61pBvlYz5FL._AC_SL1500_.jpg','powerbank','available',4.50],
    [5,'Apple Watch Series 8','Advanced health features with Always-On Retina display',45999.00,'https://m.media-amazon.com/images/I/71XMTLtZd5L._AC_SL1500_.jpg','smartwatch','upcoming',4.90],
    [6,'Google Pixel Buds Pro','Premium sound quality with Active Noise Cancellation',19999.00,'https://m.media-amazon.com/images/I/61PnHlc0HCL._AC_SL1500_.jpg','earbuds','available',4.40],
    [7,'Samsung Galaxy Buds2 Pro','Intelligent Active Noise Cancellation with premium sound',18999.00,'https://m.media-amazon.com/images/I/51j1kKqYHqL._AC_SL1500_.jpg','earbuds','upcoming',4.60],
    [8,'Mi Power Bank 20000mAh','Fast charging power bank with dual USB ports',2999.00,'https://m.media-amazon.com/images/I/71lVwl3q-kL._AC_SL1500_.jpg','powerbank','available',4.30],
    [9,'Bose QuietComfort 45','World-class noise cancellation with premium comfort',32999.00,'https://m.media-amazon.com/images/I/51JbsHSktkL._AC_SL1500_.jpg','headphones','available',4.70],
    [10,'Nothing Ear (2)','Hi-Res Audio Certified with Dual Connection',14999.00,'https://m.media-amazon.com/images/I/61WY2tV2C2L._AC_SL1500_.jpg','earbuds','available',4.50],
    [11,'Garmin Venu 2 Plus','Advanced fitness tracking with built-in GPS',39999.00,'https://m.media-amazon.com/images/I/71SN8i4-fIL._AC_SL1500_.jpg','smartwatch','available',4.80],
    [12,'ROMOSS 30000mAh Power Bank','30W PD Fast Charging with LED Display',3999.00,'https://m.media-amazon.com/images/I/71EMi-0ZOEL._AC_SL1500_.jpg','powerbank','available',4.40],
    [13,'JBL Tune 760NC Headphones','Active Noise Cancelling headphones with deep bass',12999.00,'https://m.media-amazon.com/images/I/61HXCeozUjL._AC_SL1500_.jpg','headphones','available',4.50],
    [14,'OnePlus Buds Pro 2','Spatial Audio with Dynamic Head Tracking',16999.00,'https://m.media-amazon.com/images/I/61nScEBtJhL._AC_SL1500_.jpg','earbuds','available',4.60],
    [15,'Huawei Watch GT 3 Pro','Premium design with comprehensive health monitoring',32999.00,'https://m.media-amazon.com/images/I/61YVqHdFRxL._AC_SL1500_.jpg','smartwatch','available',4.70],
    [16,'Baseus 65W Power Bank','20000mAh with PD Fast Charging',5999.00,'https://m.media-amazon.com/images/I/71BkL4RhvmL._AC_SL1500_.jpg','powerbank','available',4.60],
    [17,'Jabra Elite 85h','SmartSound Audio with Advanced ANC',24999.00,'https://m.media-amazon.com/images/I/61ZDwiW1CxL._AC_SL1500_.jpg','headphones','available',4.40],
    [18,'Xiaomi Redmi Watch 3','1.75 AMOLED Display with GPS',8999.00,'https://m.media-amazon.com/images/I/61SOib7YdLL._AC_SL1500_.jpg','smartwatch','available',4.30],
    [19,'Soundcore Liberty Air 2 Pro','Targeted Active Noise Cancellation',9999.00,'https://m.media-amazon.com/images/I/61J6+xcVexL._AC_SL1500_.jpg','earbuds','available',4.50],
    [20,'UGREEN 145W Power Bank','25000mAh with LED Display',7999.00,'https://m.media-amazon.com/images/I/71MkFRb+hpL._AC_SL1500_.jpg','powerbank','available',4.70],
    [21,'Fitbit Sense 2','Advanced Health Metrics with ECG App',10000.00,'https://m.media-amazon.com/images/I/71J8VhpsPBL._AC_SL1500_.jpg','smartwatch','available',4.40],
    [22,'Sennheiser HD 450BT','Active Noise Cancellation with Deep Bass',14999.00,'https://m.media-amazon.com/images/I/71p1vhsqjWL._AC_SL1500_.jpg','headphones','available',4.60],
];

$stmt = $conn->prepare("INSERT INTO products (id,name,description,price,image,category,status,rating) VALUES (?,?,?,?,?,?,?,?)");
$ok = 0;
foreach ($products as $p) {
    $stmt->bind_param("issdsssd", $p[0],$p[1],$p[2],$p[3],$p[4],$p[5],$p[6],$p[7]);
    if ($stmt->execute()) $ok++;
}
$stmt->close();
$conn->close();

echo "<h2>Done! $ok/22 products inserted.</h2>";
echo "<p>Old local-image products removed. <a href='index.html'>Go to Home</a></p>";
echo "<p style='color:red'>Delete this file after running: <strong>setup_products.php</strong></p>";
?>
