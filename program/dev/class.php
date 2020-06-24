<?php
/**
 * EXTEND from Database
 */
include("database.php");
class Classes extends Database implements Test
{	
	public function list()
	{
        /**
         * HEADLINE
         * SEARCH FORM with action='cmd_search'
         */
        ?><center><h1>Monitor Selling</h1></center>
        <form method="POST" class="form-inline">
            <input style="width:300px;" type="text" name="input_search" placeholder="Brand name, Resolution, Size(in inches)">
            <input type="hidden" name="action" value="cmd_search">
            <input type="submit" class="btn btn-primary" value="Search">
        </form>
        <?php
        /**
         * what is PAGE NUMBER?
         */
        if(!isset($_GET['page']))
        {
            $page=1;
        }
        else{
            $page=$_GET['page'];
        }

        /**
         * init properties
         * @var int results on a page
         * @var string search value
         * @var int how many pages
         * @var int first item on this page
         */
        $resultPerPage=20;
        $search="";
        $pageNumber=0;
        $firstItem=($page-1)*$resultPerPage;

        /**
         * LIST what is the sql statement?
         * case 1: ASC
         * case 2: DESC
         * case 3: else
         * 
         * every case contains:
         *  is there SEARCH 
         *  what is all page number
         *  add LIMIT for pagination
         */
        if(isset($_GET['sort']) && $_GET['sort']=="valueAsc")
        {
            if (isset($_POST["input_search"])) {
                $search = $_POST["input_search"];
                $_GET['search']= $_POST["input_search"];   
            }
            else if(isset($_GET['search']))
            {
                $search = $_GET['search'];
            }
            $this->sql="SELECT m.id, s.inch, r.resolution_value, b.brand_name, price, discount_price, name, description FROM monitor m
            LEFT JOIN brand b ON b.id=m.brand_id
            LEFT JOIN resolution r ON r.id=m.resolution_id
            LEFT JOIN size s ON s.id=m.size_id
            WHERE b.brand_name LIKE '%".$search."%' OR r.resolution_value LIKE '%".$search."%' OR s.inch LIKE '%".$search."%'
            ORDER BY price ASC";
            $this->result = $this->conn->query($this->sql);
            $pageNumber=ceil($this->result->num_rows/$resultPerPage);
            $this->sql.=" LIMIT ". $firstItem.','.$resultPerPage;
        }
        else if(isset($_GET['sort']) && $_GET['sort']=="valueDesc")
        {
            if (isset($_POST["input_search"])) {
                $search = $_POST["input_search"];
                $_GET['search']= $_POST["input_search"];
            }
            else if(isset($_GET['search']))
            {
                $search = $_GET['search'];
            }
            $this->sql="SELECT m.id, s.inch, r.resolution_value, b.brand_name, price, discount_price, name, description FROM monitor m
            LEFT JOIN brand b ON b.id=m.brand_id
            LEFT JOIN resolution r ON r.id=m.resolution_id
            LEFT JOIN size s ON s.id=m.size_id
            WHERE b.brand_name LIKE '%".$search."%' OR r.resolution_value LIKE '%".$search."%' OR s.inch LIKE '%".$search."%'
            ORDER BY price DESC";
            $this->result = $this->conn->query($this->sql);
            $pageNumber=ceil($this->result->num_rows/$resultPerPage);
            $this->sql.=" LIMIT ". $firstItem.','.$resultPerPage;
        }
        else
        {
            if (isset($_POST["input_search"])) {
                $search = $_POST["input_search"];   
                $_GET['search']= $_POST["input_search"];
            }
            else if(isset($_GET['search']))
            {
                $search = $_GET['search'];
            }
            $this->sql="SELECT m.id, s.inch, r.resolution_value, b.brand_name, price, discount_price, name, description FROM monitor m
            LEFT JOIN brand b ON b.id=m.brand_id
            LEFT JOIN resolution r ON r.id=m.resolution_id
            LEFT JOIN size s ON s.id=m.size_id
            WHERE b.brand_name LIKE '%".$search."%' OR r.resolution_value LIKE '%".$search."%' OR s.inch LIKE '%".$search."%' 
            ORDER BY id";
            $this->result = $this->conn->query($this->sql);
            $pageNumber=ceil($this->result->num_rows/$resultPerPage);
            $this->sql.=" LIMIT ". $firstItem.','.$resultPerPage;
        }
        /**
         * execute SQL
         */
        $this->result = $this->conn->query($this->sql);

        /**
         * LIST table header
         */
        ?>
        <table>
            <tr>
                <th>Inch</th>
                <th>Resolution</th>
                <th>Brand</th>
                <th>
                <?php if(isset($_GET['search'])){
                    echo '<a href="index.php?page=1&sort=valueDesc&search='.$_GET['search'].'">d</a>';
                }
                else{?>
                    <a href="index.php?page=1&sort=valueDesc">d</a>
                <?php } ?>
                Price
                <?php if(isset($_GET['search'])){
                    echo '<a href="index.php?page=1&sort=valueAsc&search='.$_GET['search'].'">a</a>';
                }
                else{?>
                    <a href="index.php?page=1&sort=valueAsc">a</a>
                <?php } ?>
                </th>
                <th>Discount price</th>
                <th>Name</th>
                <th>Description</th>
            </tr>
        <?php
        /**
         * LIST table items
         */
        if ($this->result->num_rows > 0) {
            while($this->row = $this->result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $this->row["inch"]; ?></td>
                    <td><?php echo $this->row["resolution_value"]; ?></td>
                    <td><?php echo $this->row["brand_name"]; ?></td>
                    <td><?php echo $this->row["price"]." HUF"; ?></td>
                    <td><?php echo $this->row["discount_price"]." HUF"; ?></td>
                    <td><?php echo $this->row["name"]; ?></td>
                    <td><?php echo $this->row["description"]; ?></td>
                </tr>
        <?php } 
        } 
        else{
            ?>
            <tr>
                <td colspan="7">Empty Database</td>
            </tr>
            <?php
        }?>
        </table> <?php

        /**
         * pagination on every situation
         */
        for($page=1;$page<=$pageNumber;$page++){
            if(isset($_GET['sort']) && isset($_GET['search']))
            {
                echo '<a href="index.php?page='.$page.'&sort='.$_GET['sort'].'&search='.$_GET['search'].'">'.$page.'</a>';
            }
            else if(isset($_POST["input_search"]))
            {
                echo '<a href="index.php?page='.$page.'&search='.$_POST["input_search"].'">'.$page.'</a>';
            }
            else if(isset($_GET['search']))
            {
                echo '<a href="index.php?page='.$page.'&search='.$_GET['search'].'">'.$page.'</a>';
            }
            else if(isset($_GET['sort']))
            {
                echo '<a href="index.php?page='.$page.'&sort='.$_GET['sort'].'">'.$page.'</a>';
            }
            else{
                echo '<a href="index.php?page='.$page.'">'.$page.'</a>';
            }
        }
    }
    public function testCase($minimumData)
    {
        $this->sql = "SELECT * FROM monitor";
        $this->result = $this->conn->query($this->sql);
        if($this->result->num_rows<$minimumData)
        {
            echo "Not enough data";
        }
    }
}

interface Test
{
    public function testCase($minimumData);
}


?>