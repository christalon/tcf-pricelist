/*var items = [
    {"brand":"Crown","description":"uPVC Pipe", "size":"1/2", "type":"PVC", "price":"240"},
    {"brand":"Neltex","description":"PPR Pipe", "size":"1/2", "type":"PPR", "price":"120"},
    {"brand":"Crown","description":"Black uPVC Pipe", "size":"1/2", "type":"PVC", "price":"100"},
    {"brand":"Royu","description":"Light switch 3 gang", "size":"None", "type":"Light Switch", "price":"200"},
    {"brand":"Firefly","description":"Incandescent Bulb", "size":"1", "type":"Bulb", "price":"100"}
];

var fuseOptions = { keys: ["brand", "description", "size", "type"]};
var options = { display: "description", key: "description", fuseOptions: fuseOptions };*/

function uploadPricelist() {
    var file = document.getElementById("customFile").files[0];
    var loadingScreen = document.getElementById("uploadingScreen");

    if (file != null) {
        loadingScreen.style.display = "block";
        Papa.parse(file, {
            download: true,
            complete: function (results) {
                console.log(results.data);
                results.data.pop();
                localStorage.setItem("pricelist", JSON.stringify(results.data));
                uploadDatabase(results.data);
                //location.reload();
            }
        });
    }
}

function uploadDatabase(pricelist){
    var posting = $.post("uploadprices.php", {sPricelist: pricelist});

    posting.done(function () {
        autoRedirect = true;
        location.reload();
    });

    posting.fail(function (xhr, status, error) { // Ajax request failed.
        var errorMessage = xhr.status + ": " + xhr.statusText;
        alert("Error - " + errorMessage);
        autoRedirect = true;
        location.reload();
    });
}

