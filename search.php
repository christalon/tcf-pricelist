<?php

session_start();

if (!isset($_SESSION["member_id"]) || $_SESSION["member_id"] != true){
  header("location:index.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>TCF Price List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/fuzzycomplete.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/b-1.7.0/b-html5-1.7.0/r-2.2.7/sc-2.0.3/sp-1.2.2/sl-1.3.3/datatables.min.css"/>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/b-1.7.0/b-html5-1.7.0/r-2.2.7/sc-2.0.3/sp-1.2.2/sl-1.3.3/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.22/features/scrollResize/dataTables.scrollResize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.71/pdfmake.min.js" integrity="sha512-q+jWnBtVH327w/3nlp2Th9Dtjmlfj3Mb4tXCGbYjYWUqtFyIhl1Ul8GXoMkzbWvdIRVlS0P1pyteYUzxArKYOg==" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.6.0/jszip.min.js" integrity="sha512-uVSVjE7zYsGz4ag0HEzfugJ78oHCI1KhdkivjQro8ABL/PRiEO4ROwvrolYAcZnky0Fl/baWKYilQfWvESliRA==" crossorigin="anonymous"></script>
    <script src="js/fuse.js"></script>
    <script src="js/fuzzycomplete.min.js"></script>
    <script src="js/papaparse.min.js"></script>
    
</head>
  <!-- Coded with love by Christian Talon -->
  <body>
    <div id="uploadingScreen" style="height: 100%;
      width: 100%;
      position: absolute;
      z-index: 9999;
      background: #4256459c;
      display:none;
      text-align: center">
      <h1 class="mx-auto" style="color: white;
      top: 50%;
      transform: translateY(-50%);
      position: relative;
      -webkit-transform: translateY(-50%);">
      Uploading Pricelist...Please Wait
      </h1>
      <img style="
      top: 50%;
      transform: translateY(-50%);
      position: relative;
      -webkit-transform: translateY(-50%);
      display: flex;
      left: 50%;
      -webkit-transform: translateX(-50%);" src="images/loading2.gif">
      </div>
    <div class="container h-100" style="max-width: initial;">
      <div class="d-flex justify-content-center h-100" style="flex-flow: column;">
        <div> 
          <a href="logout.php" class="btn btn-light col-1" style="margin-top: 20px;
          float: right; max-width: 90px;"> Logout </a>
          
        </div>
        <div class="brand_logo_container" style="margin: auto;">
          <img src="images/logo.png" class="brand_logo" alt="Logo">
        </div>
        <div class="searchbar" style="margin: auto; margin-top: 20px;">
          <input class="search_input" id="searchBar" type="text" name="" placeholder="Search...">
          <a class="search_icon"><i class="fas fa-search"></i></a>
        </div>
        <div class="col-5" style="margin: auto; max-width: 80%; margin-top: 5px; ">
          <table class="table table-striped custab" id="pricelistTable" style="background-color: #e8e9f3; margin-top: 10px; width: 100%;">
            <thead>
              <tr>
                  <th>Brand</th>
                  <th>Description</th>
                  <th>Size</th>
                  <th>Unit</th>
                  <th>Price</th>
              </tr>
              <tbody>
              </tbody>
          </thead>
                  
          </table>
        </div>
        <div class="col-1" style="margin: auto; max-width: 80%; margin-top: 5px; margin-bottom: 100px;">
          <p style="color: white;"> Upload/Update Pricelist </p>
          <form>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="customFile">
              <label class="custom-file-label" for="customFile">Choose file</label>
              <button type="button" class="btn btn-success col-1" style="margin-top: 20px;
              float: right; min-width: 50px; max-width: 90px;" onclick="uploadPricelist();">Upload</button>
            </div>
          </form>
        </div>
      </div>   
    </div>
  </body>
  <script src="js/search.js"></script>
  <script>
    var priceTable;
    var localPrices = JSON.parse(localStorage.getItem("pricelist"));

    $(document).ready(function(){
      //$("#searchBar").fuzzyComplete(items, options);

      if(localPrices != null){
        priceTable = $('#pricelistTable').DataTable({
          data: localPrices, 
          responsive: true,  
          scrollResize: true, 
          scrollY: 100, 
          scrollCollapse: true, 
          paging: false,
          select: true,
          dom: 'Bfrtip',
          buttons: {
            dom: {
              button: {
                className: 'btn btn-dark mr-2' //Primary class for all buttons
              },
            },
            buttons:[
              {
                text: 'Add',
                action: function (){
                  alert("add");
                }
              }, 
              {
                text: 'Edit',
                className: "modify",
                action: function (){
                  alert("edit");
                }
              }, 
              {
                text: 'Remove',
                className: "modify",
                action: function (){
                  alert("remove");
                }
              }, 'excelHtml5', 'pdf'
          ]}
        });
        priceTable.buttons( '.modify' ).disable();
        document.getElementById("pricelistTable_info").style.color = "white";
        document.getElementById("pricelistTable_filter").style.display = "none";
      }
      else{
        getPriceFromDatabase();
      }
      
    });

    $('#searchBar').on( 'keyup', function () {
        priceTable.search( this.value ).draw();
    });

    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    function getPriceFromDatabase(){
      var posting = $.post("getprices.php", function(data){
        var resultArray = [];
        var parsedResult = JSON.parse(data);
        //alert("Data:" + parsedResult[0].brand);
        parsedResult.forEach(element => {
          resultArray.push([element.brand, element.description, element.size, element.unit, element.price]);
        });

        localStorage.setItem("pricelist", JSON.stringify(resultArray));
        priceTable = $('#pricelistTable').DataTable({data: resultArray, responsive: true,  scrollResize: true, scrollY: 100, scrollCollapse: true, paging: false});
        document.getElementById("pricelistTable_info").style.color = "white";
        document.getElementById("pricelistTable_filter").style.display = "none";
      });
    }

  </script>
</html>

