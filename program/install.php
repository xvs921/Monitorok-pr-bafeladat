<?php
include("program-files/database.php");
class Install extends Database
{
    public function createDB()
	{
        $this->sql = "CREATE DATABASE IF NOT EXISTS ".$this->dbname." DEFAULT CHARACTER SET utf8 COLLATE utf8_hungarian_ci";
        $this->result = $this->conn->query($this->sql);
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    }
    public function createTableMonitor()
	{
        $this->sql = "CREATE TABLE `monitor` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `size_id` int(11) NOT NULL,
            `resolution_id` int(11) NOT NULL,
            `brand_id` int(11) NOT NULL,
            `price` int(11) NOT NULL,
            `discount_price` int(11) NOT NULL,
            `name` varchar(120) COLLATE utf8_hungarian_ci NOT NULL,
            `description` varchar(400) COLLATE utf8_hungarian_ci NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;";
        $this->result = $this->conn->query($this->sql);
    }
    public function createTableBrand()
	{
        $this->sql = "CREATE TABLE `brand` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `brand_name` varchar(30) COLLATE utf8_hungarian_ci NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;";
		$this->result = $this->conn->query($this->sql);
    }
    public function createTableResolution()
	{
        $this->sql = "CREATE TABLE `resolution` (
          `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `resolution_value` varchar(30) COLLATE utf8_hungarian_ci NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;";
		$this->result = $this->conn->query($this->sql);
    }
    public function createTableSize()
	{
        $this->sql = "CREATE TABLE `size` (
            `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `inch` double NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;";
		$this->result = $this->conn->query($this->sql);
    }
    public function foreignKeys()
    {
        $this->sql0 = "ALTER TABLE `monitor`
        ADD KEY `brand_id` (`brand_id`),
        ADD KEY `resolution_id` (`resolution_id`),
        ADD KEY `size_id` (`size_id`)";
        $this->result = $this->conn->query($this->sql0);

        $this->sql = "ALTER TABLE `monitor`
        ADD CONSTRAINT `monitor_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON UPDATE CASCADE,
        ADD CONSTRAINT `monitor_ibfk_2` FOREIGN KEY (`resolution_id`) REFERENCES `resolution` (`id`) ON UPDATE CASCADE,
        ADD CONSTRAINT `monitor_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `size` (`id`) ON UPDATE CASCADE;";
        $this->result = $this->conn->query($this->sql);
    }
    public function dataUpload()
    {
        $this->sql = "INSERT INTO brand(brand_name) VALUES ('Samsung'), ('Apple'), ('LG'), ('Huawei'), ('Xiaomi'), ('Thomson'), ('Lenovo')";
        $this->result = $this->conn->query($this->sql);
        $this->sql = "INSERT INTO size(inch) VALUES (21.5), (23.8), (24), (31.5), (34), (23.5)";
        $this->result = $this->conn->query($this->sql);
        $this->sql = "INSERT INTO resolution(resolution_value) VALUES ('HD'),('FUll HD'),('4K UHD'),('QHD'),('HD+')";
        $this->result = $this->conn->query($this->sql);
    }
    public function dataUpload2($discount)
    {
        $price=rand(40000,180000);
        $discountPrice=$price/100*(100-$discount);
        $length=20;
            $characters = ' 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
        $length1=210;
            $charactersLength1 = strlen($characters);
            $randomString1 = '';
            for ($i = 0; $i < $length1; $i++) {
                $randomString1 .= $characters[rand(0, $charactersLength1 - 1)];
            }
            $this->sql = "SELECT id FROM size ORDER BY RAND() LIMIT 1";
            $this->result = $this->conn->query($this->sql);
            $this->row = $this->result->fetch_assoc();
            $size_id=$this->row["id"];  

        $this->sql = "INSERT INTO monitor(size_id, resolution_id, brand_id, price, discount_price, name, description) 
        VALUES ((SELECT id FROM size ORDER BY RAND() LIMIT 1),1, 
        1,".$price.",".$discountPrice.",'".$randomString."','".$randomString1."')";
        echo $this->sql;
        $this->result = $this->conn->query($this->sql);
    }
}
?>