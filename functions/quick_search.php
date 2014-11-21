<?php

    session_start();

    require_once("inc_functions.php");
    
    $db = Db::getDbInstance();
    
    $chemicals = array();
    $objects = array();
	$analysis = new Analysis();

    $sql =
    "
    SELECT chemical_name
    FROM chemical
    ";

    $chemicals = $db->getRset($sql);

    $sql =
    "
    SELECT object_name
    FROM object
    ";

    $objects = $db->getRset($sql);
	
	if (isset($_POST['element']) || isset($_POST['object'])){
		$element = trim(sanitize(cleanInput($_POST['element'])));
		$object = trim(sanitize(cleanInput($_POST['object'])));
		
		$analysis->search($element, $object);
		
		exit();
	}
   
        $title = "Quick Search";
        openHeader($title);
?>
	
<script>

function searchChange(){

var element = document.getElementById("elementSelect").value;
var object = document.getElementById("objectSelect").value;
var dataString = {element:element, object:object};



$.ajax({        
                type: "POST",
                url: <?php echo "'".BASE_URL."quick_search.php'" ?>,
                data: dataString,
                cache: false,
                success: function(html)
                {
                    document.getElementById('resultsTextarea').innerHTML = html;
                }
            });
		return;	
  }

</script>

<?php closeHeader($title); ?> 


    
    <div id="content">
        <form name="contentForm" id="contentForm">
    	   <div id="contentLeftWindow">
    		  <div id="contentLeftWindowContents">
    			 Element:<br/>
    			 <select name="elementSelect" id="elementSelect" onChange="searchChange()">
    				 <option value="">All</option>
                     <?php $analysis->listChemicals($chemicals); ?>
    			 </select><br/><br/>
    			 Item type:<br/>
    			 <select name="objectSelect" id="objectSelect" onChange="searchChange()">
    				 <option value="">All</option>
                     <?php $analysis->listObjects($objects); ?>
    			 </select><br/>
    		  </div>
    	   </div>
    	   <div id="contentRightWindow">
    		  <div id="contentRightWindowContents">
    			 <div name="resultsTextarea" id="resultsTextarea" style="resize:none; overflow:auto; overflow-x:hidden; width:100%; height:99%;";><?php $analysis->initialContent(); ?></div>
    		  </div>
    	   </div>
        </form>
    </div>

<?php outputFooter(); ?>
