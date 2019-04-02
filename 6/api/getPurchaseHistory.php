<?php 

    include '../dbConnection.php';
    $conn = getDatabaseConnection("ottermart");
    $productId = $_GET['productId'];
    $sql = "SELECT * 
            FROM om_product 
            NATURAL JOIN om_purchase 
            WHERE productId = :pId";
    
    $np = array();
    $np[':pId'] = $productId;
    $stmt = $conn->prepare($sql);
    $stmt->execute($np);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($records);

?>