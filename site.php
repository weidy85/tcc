<?php

//header("Content-Type: application/json; charset=utf-8");
$url = $_POST["url"];
$compras = array();
$site = file_get_contents($url);
$DOM = new DOMDocument();
    libxml_use_internal_errors(true);
    $DOM->loadHTML($site);
    libxml_clear_errors();
    $finder = new DomXPath($DOM);
    $qntTotal = $finder->query("//table[@id=\"tabResult\"]//tr");
    echo "<!DOCTYPE html>";
    echo "<html><head>";
    echo "<meta charset=\"utf-8\">";
    echo "<meta name=\"viewport\" content=\"initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width\">";
    echo "<link href=\"http://code.ionicframework.com/nightly/css/ionic.css\" rel=\"stylesheet\">";
    echo "<script src=\"http://code.ionicframework.com/nightly/js/ionic.bundle.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"http://code.jquery.com/jquery-1.10.2.min.js\"></script>";

echo "<script type=\"text/javascript\">
var request, db, transaction;
$(document).ready(function(){
        request = window.indexedDB.open(\"compras\", 1);

        request.onupgradeneeded = function(event){
            console.log(\"Atualizando\");
            db = event.target.result;
            var objectStore = db.createObjectStore(\"compra\", { keyPath : \"codigo\" });
        };
        request.onerror = function(event){
            console.log(\"Erro ao abrir o banco de dados\", event);
        };
        request.onsuccess = function(event){
            console.log(\"Banco de dados aberto com sucesso\");
            db = event.target.result;
        };
    });
    function gravar(){
        var i = 1;
        $('.row').each(function(){
            var cod = $('#cod'+i).text();
            var prod = $('#produto'+i).text();
            var qnt = $('#qnt'+i).text();
            var run = $('#run'+i).text();
            var rvl = $('#rvl'+i).text();
            var valor = $('#valor'+i).text();
            
            
            
        
        
        var transaction = db.transaction('compra',\"readwrite\");
   transaction.oncomplete = function(event) 
   {
         console.log(\"Sucesso\");
   };

   transaction.onerror = function(event) 
   {
         console.log(\"Error\");
   };  
   
   var objectStore = transaction.objectStore(\"compra\");
   if($('#produto'+i).text()){
        objectStore.add({codigo: cod, produto: prod, qnt: qnt, run: run, rvl: rvl, valor: valor});
    }
    i++;
   });
    };
</script>";

    echo "</head><body>";
    echo "<div class=\"bar bar-header bar-assertive\" style=\"position: static;\">
                <div class=\"buttons buttons-left header-item\">
                    <span class=\"left-buttons\">
                        <button class=\"button button-icon button-clear ion-navicon\"></button>
                    </span>
                </div>
                <div class=\"h1 title\">Software de Controle de Compras</div>
                <div class=\"buttons buttons-right header-item\">
                    <span class=\"right-buttons\">
                        <button class=\"button button-icon button-clear ion-android-more-vertical\"></button>
                    </span>
                </div>
            </div>";
    echo "<table>
            <div class=\"row header\">
            <div class=\"col\">Cod</div>
            <div class=\"col\">Produto</div>
            <div class=\"col\">Quantidade</div>
            <div class=\"col\">Unidade</div>
            <div class=\"col\">Valor UN</div>
            <div class=\"col\">Valor</div>
            </div>";

    for ($i=1;$i <= $qntTotal->length;$i++){
    $id = "Item + $i";
    $produto = "";
    $nodes = $finder->query("//tr[@id=\"$id\"]//span[@class=\"txtTit2\"]");
    foreach ($nodes as $node) {
      $produto=$node->nodeValue;
    }
    $qnt = "";
    $nodes = $finder->query("//tr[@id=\"$id\"]//span[@class=\"Rqtd\"]");
    foreach ($nodes as $node) {
      $qnt = preg_replace("/[^0-9,]/", "", $node->nodeValue);
    }
	$RUN[0] = "";
	$RUN[1] = "";
    $nodes = $finder->query("//tr[@id=\"$id\"]//span[@class=\"RUN\"]");
    foreach ($nodes as $node) {
      $RUN=explode(":", $node->nodeValue);
    }
    $RvlUnit = "";
    $nodes = $finder->query("//tr[@id=\"$id\"]//span[@class=\"RvlUnit\"]");
    foreach ($nodes as $node) {
      $RvlUnit =  preg_replace("/[^0-9,]/", "", $node->nodeValue);
    }
	$valor = "";
    $nodes = $finder->query("//tr[@id=\"$id\"]//span[@class=\"valor\"]");
    foreach ($nodes as $node) {
      $valor=$node->nodeValue;
    }
    echo "<div class= \"row\">
                    <div id=\"cod$i\" class=\"col\" >$i</div>
                    <div id=\"produto$i\" class=\"col\" >$produto</div>
                    <div id=\"qnt$i\" class=\"col\">$qnt</div>
                    <div id=\"run$i\" class=\"col\">$RUN[1]</div>
                    <div id=\"rvl$i\" class=\"col\">$RvlUnit</div>
                    <div id=\"valor$i\" class=\"col\">$valor</div>
        </div>";
    //array_push($compras, array(\"produto\" => $produto, \"qnt\" => $qnt, \"run\" => $RUN[1], \"rvlUnit\" => $RvlUnit, \"valor\" => $valor));
    
}
echo "</table>";
echo "<a href=\"javascript: gravar()\" class=\"button button-block button-calm\">Gravar IndedDB</a>";
echo "</body></html>";
//$retorno[] = $compras;
//echo json_encode($retorno);
?>