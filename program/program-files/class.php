<?php
include("database.php");
class Classes extends Database
{	
	public function list()
	{
        ?><center><h1>Monitor Selling</h1></center>
        <form method="POST" class="form-inline">
            <input style="width:250px;" type="text" name="input_search" placeholder="Brand name">
            <input type="hidden" name="action" value="cmd_search">
            <input type="submit" class="btn btn-primary" value="Search">
        </form>
    <?php
        $resultPerPage=20;
        
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        /*if (!$this->result) {
            trigger_error('Invalid query: ' . $this->conn->error);
        }*/

        if(!isset($_GET['page']))
        {
            $page=1;
        }
        else{
            $page=$_GET['page'];
        }

        $search="";
        $pageNumber=0;
        $firstItem=($page-1)*$resultPerPage;

        if(isset($_GET['sort']) && $_GET['sort']=="valueMax")
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
            WHERE b.brand_name LIKE '".$search."%'
            ORDER BY price ASC";
            $this->result = $this->conn->query($this->sql);
            $pageNumber=ceil($this->result->num_rows/$resultPerPage);
            $this->sql.=" LIMIT ". $firstItem.','.$resultPerPage;
            echo $this->sql;
        }
        else if(isset($_GET['sort']) && $_GET['sort']=="valueMin")
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
            WHERE b.brand_name LIKE '".$search."%'
            ORDER BY price DESC";
            $this->result = $this->conn->query($this->sql);
            $pageNumber=ceil($this->result->num_rows/$resultPerPage);
            $this->sql.=" LIMIT ". $firstItem.','.$resultPerPage;
            echo $this->sql;
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
            WHERE b.brand_name LIKE '".$search."%'";
            $this->result = $this->conn->query($this->sql);
            $pageNumber=ceil($this->result->num_rows/$resultPerPage);
            $this->sql.=" LIMIT ". $firstItem.','.$resultPerPage;
            echo $this->sql;
        }
        $this->result = $this->conn->query($this->sql);
        ?>
        <table>
        <tr><th>Id</th><th>Inch</th><th>Resolution</th><th>Brand</th>
        <th>
        <?php if(isset($_GET['search'])){
            echo '<a href="index.php?page=1&sort=valueMin&search='.$_GET['search'].'">s</a>';
        }
        else{?>
            <a href="index.php?page=1&sort=valueMin">s</a>
        <?php } ?>
        Price
        <?php if(isset($_GET['search'])){
            echo '<a href="index.php?page=1&sort=valueMax&search='.$_GET['search'].'">d</a>';
        }
        else{?>
            <a href="index.php?page=1&sort=valueMax">d</a>
        <?php } ?>
        </th>
        <th>Discount price</th><th>Name</th><th>Description</th></tr>
        <?php
        if ($this->result->num_rows > 0) {
            while($this->row = $this->result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $this->row["id"]; ?></td>
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
                <td colspan="8">Empty Database</td>
            </tr>
            <?php
        }?>
        </table> <?php


        for($page=1;$page<=$pageNumber;$page++){
            if(isset($_GET['sort']) && isset($_GET['search']))
            {
                echo '<a href="index.php?page='.$page.'&sort='.$_GET['sort'].'&search='.$_GET['search'].'">'.$page.'getsearch</a>';
            }
            else if(isset($_POST["input_search"]))
            {
                echo '<a href="index.php?page='.$page.'&search='.$_POST["input_search"].'">'.$page.'search</a>';
            }
            else if(isset($_GET['search']))
            {
                echo '<a href="index.php?page='.$page.'&search='.$_GET['search'].'">'.$page.'search</a>';
            }
            else if(isset($_GET['sort']))
            {
                echo '<a href="index.php?page='.$page.'&sort='.$_GET['sort'].'">'.$page.'sr</a>';
            }
            else{
                echo '<a href="index.php?page='.$page.'">'.$page.'</a>';
            }
        }
	}
}?>