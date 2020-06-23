<?php
/**
 * EXTEND from Classes
 */
include("program-files/class.php");
class Install extends Classes
{
    public function createDB()
	{
        /**
         * CREATE DATABASE and SET $this->conn
         */
        $this->sql = "CREATE DATABASE IF NOT EXISTS ".$this->dbname." DEFAULT CHARACTER SET utf8 COLLATE utf8_hungarian_ci";
        $this->result = $this->conn->query($this->sql);
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        /**
         * CREATE TABLE monitor with error showing
         */
        $this->sql = "CREATE TABLE IF NOT EXISTS `monitor` (
            `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `size_id` int(11) NOT NULL,
            `resolution_id` int(11) NOT NULL,
            `brand_id` int(11) NOT NULL,
            `price` int(11) NOT NULL,
            `discount_price` int(11) NOT NULL,
            `name` varchar(120) COLLATE utf8_hungarian_ci NOT NULL,
            `description` varchar(400) COLLATE utf8_hungarian_ci NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;";
        $this->result = $this->conn->query($this->sql);

        if (!$this->result) {
            trigger_error('Invalid query: ' . $this->conn->error);
        }

        /**
         * CREATE TABLE brand with error showing
         */
        $this->sql = "CREATE TABLE IF NOT EXISTS `brand` (
            `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT ,
            `brand_name` varchar(30) COLLATE utf8_hungarian_ci NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;";
		$this->result = $this->conn->query($this->sql);

        if (!$this->result) {
            trigger_error('Invalid query: ' . $this->conn->error);
        }

        /**
         * CREATE TABLE resolution with error showing
         */
        $this->sql = "CREATE TABLE IF NOT EXISTS `resolution` (
            `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `resolution_value` varchar(30) COLLATE utf8_hungarian_ci NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;";
        $this->result = $this->conn->query($this->sql);

        if (!$this->result) {
            trigger_error('Invalid query: ' . $this->conn->error);
        }

        /**
         * CREATE TABLE size with error showing
         */
        $this->sql = "CREATE TABLE IF NOT EXISTS `size` (
            `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `inch` double NOT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;";
        $this->result = $this->conn->query($this->sql);
        
        if (!$this->result) {
            trigger_error('Invalid query: ' . $this->conn->error);
        }

        /**
         * ADD FOREIGN KEYS to monitor table
         */
        $this->sql = "ALTER TABLE `monitor`
        ADD KEY `brand_id` (`brand_id`),
        ADD KEY `resolution_id` (`resolution_id`),
        ADD KEY `size_id` (`size_id`)";
        $this->result = $this->conn->query($this->sql);

        $this->sql = "ALTER TABLE `monitor`
        ADD CONSTRAINT `monitor_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON UPDATE CASCADE,
        ADD CONSTRAINT `monitor_ibfk_2` FOREIGN KEY (`resolution_id`) REFERENCES `resolution` (`id`) ON UPDATE CASCADE,
        ADD CONSTRAINT `monitor_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `size` (`id`) ON UPDATE CASCADE;";
        $this->result = $this->conn->query($this->sql);
    }

    public function staticDataUpload()
    {
        /**
        * CHECK database state 
        * IS THERE any data in my database?
        */
        $this->sql = "SELECT * FROM monitor";
        $this->result = $this->conn->query($this->sql);
        /**
        * IF EMPTY
        */
        if($this->result->num_rows==0)
        {
            /**
            * STATIC DATA UPLOAD to brand table
            */
            $this->sql = "INSERT INTO brand(brand_name) VALUES ('Samsung'), ('Apple'), ('LG'), ('Huawei'), ('Xiaomi'), ('Thomson'), ('Lenovo')";
            $this->result = $this->conn->query($this->sql);
            /**
            * STATIC DATA UPLOAD to size table
            */
            $this->sql = "INSERT INTO size(inch) VALUES (21.5), (23.8), (24), (31.5), (34), (23.5)";
            $this->result = $this->conn->query($this->sql);
            /**
            * STATIC DATA UPLOAD to resolution table
            */
            $this->sql = "INSERT INTO resolution(resolution_value) VALUES ('HD'),('FUll HD'),('4K UHD'),('QHD'),('HD+')";
            $this->result = $this->conn->query($this->sql);

            $_SESSION['piece']=rand(50,120);
            echo $_SESSION['piece'];
        }
    }

    /**
    * RANDOM DATA UPLOAD to monitor table
    */
    public function randomDataUpload()
    {

        /**
        * CHECK database state 
        * IS THERE any data in my database?
        */
        $this->sql = "SELECT * FROM monitor";
        $this->result = $this->conn->query($this->sql);
        /**
        * IF EMPTY
        */
        if($this->result->num_rows==0)
        {
            for($i=1;$i<=$_SESSION['piece'];$i++)
            {
                    /**
                    * price with random value
                    */
                    $price=rand(40000,180000);

                    /**
                     * discount random
                     * discount compute
                     */
                    $discount=rand(5,65);
                    $discountPrice=$price/100*(100-$discount);

                    /**
                     * characters for random string
                     */
                    $characters = '       0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

                    /**
                     * random string to name
                     */
                    $lengthName=rand(5,20);
                    $charactersLengthName = strlen($characters);
                    $randomStringName = '';
                    while (strlen($randomStringName)!=$lengthName) {
                        $randomStringName .= $characters[rand(0, $charactersLengthName - 1)];
                    }
                    
                    /**
                     * random string to description
                     */
                    $lengthDesc=rand(50,120);
                    $charactersLengthDesc = strlen($characters);
                    $randomStringDesc = '';
                    while (strlen($randomStringDesc)!=$lengthDesc) {
                        $randomStringDesc .= $characters[rand(0, $charactersLengthDesc - 1)];
                    }

                    /**
                     * INSERT INTO monitor table
                     */
                    $this->sql = "INSERT INTO monitor(size_id, resolution_id, brand_id, price, discount_price, name, description) 
                    VALUES ((SELECT id FROM size ORDER BY RAND() LIMIT 1),(SELECT id FROM resolution ORDER BY RAND() LIMIT 1), 
                    (SELECT id FROM brand ORDER BY RAND() LIMIT 1),".$price.",".$discountPrice.",'".$randomStringName."','".$randomStringDesc."')";
                    $this->result = $this->conn->query($this->sql);        
            }
        }
    }
}

/**
 * Get array of properties
 * concatenated with a provided joint
 * 
 * @param string $joint
 * @return array
 */



 /**
  * @var string
  */

  
 /**
  * @var string|int
  */


?>