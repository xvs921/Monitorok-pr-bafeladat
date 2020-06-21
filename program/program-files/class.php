<?php
include("database.php");
class Classes extends Database
{	
	public function list()
	{        
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        $this->sql = "SELECT m.id, s.inch, r.resolution_value, b.brand_name, price, discount_price, name, description FROM monitor m
        LEFT JOIN brand b ON b.id=m.brand_id
        LEFT JOIN resolution r ON r.id=m.resolution_id
        LEFT JOIN size s ON s.id=m.size_id";
        $this->result = $this->conn->query($this->sql); ?>
        <table>
        <tr><th>Id</th><th>Inch</th><th>Resolution</th><th>Brand</th><th>Price</th><th>Discount price</th><th>Name</th><th>Description</th></tr> 
        <?php 
        if (!$this->result) {
            trigger_error('Invalid query: ' . $this->conn->error);
        }
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
        } ?>
        </table><?php
	}
}?>