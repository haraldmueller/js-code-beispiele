<?php
session_start();

function getThisFile($myFile){
  return substr($myFile, 1+strrpos($myFile, "/"));
}
function deleteFile($theFile) {
    unlink("./".$_SESSION['userDir']."/".$theFile);
}
if ($_REQUEST['toDelete'] != ""){
    //echo "deleteFile(".$_REQUEST['toDelete'].")";
    deleteFile($_REQUEST['toDelete']);
    $_REQUEST['toDelete'] = "";
}

if ($_REQUEST['loeschenaktivieren'] == 'on') {
  $loeschenChecked='checked';
} else {
  $loeschenChecked='';
}
$thisFile = getThisFile($_SERVER["PHP_SELF"]);

?>

      	<h2>Willkommen im Transferbereich</h2>
        <form name="frm_Eingabe" action="uploader.php" method="post" enctype="multipart/form-data">
      		<p>powerlook.ch > transfer > <?php echo $_SESSION['userDir']?></p>
      		<br/>
      		<input type="hidden" name="MAX_FILE_SIZE" value="900000000" />
	        <div  class="transferSteuerBalken">
				<input type="file"   name="uploaded" id="uploaded"  />
				<input type="submit" name="btnFileupload" id="btnFileupload" onclick="jsFktBtnUpload()"/>
	        </div>
 	       <div style="float: right; margin: 5px 0;" >loeschen Aktivieren <input type="checkbox" name="loeschenaktivieren" id="loeschenaktivieren" onclick="jsFktLoeschenAktiv(this.checked);" <?= $loeschenChecked ?> /></div>
	   </form> 
		
		<br style="clear: both;" />
	    	    
       <form name="frm_Loeschen" action="<?= $thisFile ?>" method="post">
       <input type="hidden" name="toDelete" id="toDelete" value="">
            <div style="clear; margin-top:10px; float:left; height:240px; overflow-y:scroll;; overflow-x: no;">
              <?= dokusAnzeigen(); ?>
            </div>
            <div style="margin-top:10px; float:right; height:240px; overflow-y:scroll;">
              <?= bilderAnzeigen(); ?>
            </div>
            <div style="float: none; clear: both;"></div>
       </form>

		<br style="clear: both" />
       
 
<?php
function dokusAnzeigen() {
  chdir("/transfer/data");
	$directory = ".";
	if(is_dir($directory) == true){
		$handle = opendir($directory);
        echo('<table class="docTable">
                <tr class="cssKopf">
                    <th colspan="2" align="left">Dokumente/Texte:</th>');
        $zaehler=0;
		while (false !== ($file = readdir($handle))){
              $endung2 = strtolower(substr($file, -2));
              $endung3 = strtolower(substr($file, -3));
              $endung4 = strtolower(substr($file, -4));
              if ($endung4 == "docx") 	{$endung3 = "doc";}
              if ($endung4 == "xlsx") 	{$endung3 = "xls";}
              if ($endung4 == "pptx") 	{$endung3 = "ppt";}
              
              if (
                $endung3 == "doc" ||
                $endung3 == "ppt" ||
                $endung3 == "txt" ||
                $endung3 == "xls" ||
                $endung3 == "zip" ||
                $endung3 == "pdf") {

                    $gezeigtesDok = $file;

          		    $trbg = 'cssLineEven';
          		  	if ($zaehler % 2 > 0) {
          		  	  $trbg = 'cssLineOdd';
          		    }
                    $zaehler++;

                    $abfallEimerchen = '<a href="#" onClick="loescheDatei(\''.$gezeigtesDok.'\');"><img src="button_delete.gif" border="0" title="loescht Dokument"></a>';
                    if ($_REQUEST['loeschenaktivieren'] != 'on') {
                      $abfallEimerchen = '';
                    }//end if
                    echo ("<tr class='".$trbg."'><td>
                            <table><tr><td><a href='".$_SESSION['userDir']."/".$gezeigtesDok."' target='_blank'><img src='miniicon_".$endung3.".gif' border='0' height='16' width='16' ></a></td><td class='DokListeAnzeige'>".anzeigeFileName($gezeigtesDok)."</td></tr></table>
                            </td>
                            <td>".$abfallEimerchen."</td>
                           </tr>");
                }
          }//end while
        echo("</table>");
		closedir($handle);
	} //end if
}//end function dokusAnzeigen


function bilderAnzeigen() {
  chdir("/transfer/data");
	$directory = ".";
	$zaehler=0;
	if(is_dir($directory) == true){
		$handle = opendir($directory);
        echo('<table class="docTable">
                <tr class="cssKopf">
                    <th colspan="2" align="left">Bilder/Grafiken:</th>');
        while (false !== ($file = readdir($handle))){
			$endung = strtolower( substr($file, -4) );
            $endung2 = strtolower(substr($file, -2));
              if(
				$endung2 == ".ai" ||
				$endung2 == ".ps" ||
				$endung == ".tif" ||
				$endung == ".eps" ||
				$endung == ".emf" ||
				$endung == ".gif" ||
				$endung == ".jpg" ||
				$endung == ".psd" ||
				$endung == "jpeg" ||
				$endung == "tiff" ||
				$endung == ".png" ||
				$endung == ".bmp"
				){
  		    $trbg = 'cssLineEven';
  		  	if ($zaehler % 2 > 0) {
  		  	  $trbg = 'cssLineOdd';
  		    }
            $zaehler++;
					$gezeigtesBild = $file;
					$bildGroesseErmitteln = getimagesize($gezeigtesBild);
					$bildBreite = $bildGroesseErmitteln[0] + 20;
					$bildHoehe = $bildGroesseErmitteln[1] + 20;
					$dateityp = "fotos";

                    $abfallEimerchen = '<a href="#" onClick="loescheDatei(\''.$gezeigtesBild.'\');"><img src="button_delete.gif" border="0" title="loescht Datei"></a>';
					//echo "<a href='#toFoto' onClick='window.open(\"$gezeigtesBild\",\"Bild\",\"width=$bildBreite,height=$bildHoehe,left=0,top=0,toolbar=no,status=no\");'><img src='".$gezeigtesBild."' height='32' border='1'></a>&nbsp;";
					if ($_REQUEST['loeschenaktivieren'] != 'on') {
                        $abfallEimerchen = '';
                    }
                    echo ("<tr class='".$trbg."'><td>
                            <table><tr><td><a href='".$_SESSION['userDir']."/".$gezeigtesBild."' target='_blank'><img src='miniicon_gif.gif' border='0' height='16' width='16' ></a></td><td class='DokListeAnzeige'>".anzeigeFileName($gezeigtesBild)."</td></tr></table>
                            </td>
                            <td>".$abfallEimerchen."</td>
                           </tr>");

				}//end if
			}//end while
            echo("</table>");
			closedir($handle);
		} //end if
}//end function

function anzeigeFileName($fileName){
  $ret = $fileName;
  $schwellenLaenge = 25;
  if (strlen($fileName) > $schwellenLaenge){
      $ret = substr( $fileName, 0,$schwellenLaenge-4) . ".." . substr($fileName, strlen($fileName)-4, 4);
  }
  return $ret;
}

?>


<script type="text/javascript">
function loescheDatei(dieDatei){
   document.getElementById('toDelete').value = dieDatei;
   document.frm_Loeschen.submit();
}
function jsFktLoeschenAktiv(isChecked) {
    if (isChecked) {
      document.getElementById('loeschenaktivieren').checked=true;
    } else {
      document.getElementById('loeschenaktivieren').checked=false;
    }
    document.frm_Eingabe.action = "";
  document.frm_Eingabe.submit();
}

</script>