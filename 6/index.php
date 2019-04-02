<!DOCTYPE html>
<html>
    <head>
        <title> OtterMart Product Search  </title>
        <!--<link href="css/styles.css" rel="stylesheet" type="text/css" />-->
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
       
        <script>
            /*global $*/
            $(document).ready(function(){
                
                $.ajax({
                    type: "GET",
                    url: "api/getCategories.php",
                    dataType: "json",
                    success: function(data, status) {
                        data.forEach(function(key){
                            $("[name=category]").append("<option value=" + key["catId"] + ">" + key["catName"] + "</option>");
                        });
                    }
                });
                
                $("#searchForm").on("click", function(){
                    console.log("in search click");
                    $.ajax({
                        type: "GET",
                        url: "api/getSearchResults.php",
                        dataType: "json",
                        data: {
                            "product" : $("[name=product]").val(),
                            "category" : $("[name=category]").val(),
                            "priceFrom" : $("[name=priceFrom]").val(),
                            "priceTo" : $("[name=priceTo]").val(),
                            "orderBy" : $("[name=orderBy]:checked").val()
                        },
                        success: function(data, status){
                            $("#results").html("<h3> Products Found: </h3>");
                            data.forEach(function(key){
                                $("#results").append("<a href='#' class='historyLink' id='" + key['productId'] + "'>History</a> ");
                                
                                $("#results").append(key["productName"] + " " + key["productDescription"] + " $" + key["price"] + "<br>");
                            });
                        }
                });
            });
            
            $(document).on('click', '.historyLink', function(){
                $('#purchaseHistoryModal').modal("show");
                $.ajax({
                    type: "GET",
                    url: "api/getPurchaseHistory.php",
                    dataType: "json",
                    data: {"productId" : $(this).attr("id")},
                    success: function(data, status){
                        if (data.length != 0){ // checks if API returned a non-empty list
                            $("#history").html(""); //clears content
                            $("#history").append(data[0]['productName'] + "<br />");
                            $("#history").append("<img src='"+data[0]['productImage']+"' width='200' /> <br />");
                            data.forEach(function(key){
                                $("#history").append("Purchase Date: " + key['purchaseData'] + "<br />");
                                $("#history").append("Unit Price: " + key['unitPrice'] + "<br />");
                                $("#history").append("Quantity: " + key['quantity'] + "<br />");
                            });
                        } else{
                            $("#history").html("No purchase history for this item");
                        }
                    }
                });
            });
            
        });
            
            
        </script>
    </head>
    <body>
        <div>
            <h1> OtterMart Product Search </h1>
        
        
        <form>
            
            Product: <input type="text" name="product" />
            <br>
            Category: 
                <select name="category">
                    <option value=""> Select One </option>
                </select>
            <br>
            Price:  From <input type="text" name="priceFrom" size="7"/>
                    To   <input type="text" name="priceTo" size="7"/>
            <br>
            Order result by:
            <br>
            
            <input type="radio" name="orderBy" value="price"/> Price <br>
            <input type="radio" name="orderBy" value="name"/> Name
            
            <br><br>
        </form>
        <button id="searchForm">Search</button>
        
        
        </div>
        <br>
        <hr>
        <div id="results"></div>
        
        <div class="modal" id="purchaseHistoryModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Product History</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="history"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>