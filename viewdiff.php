
<style type="text/css">
body {margin:0;border:0;padding:0;font:11pt sans-serif}
body > h1 {margin:0 0 0.5em 0;font:2em sans-serif;background-color:#def}
body > div {padding:2px}
p {margin-top:0}
ins {color:green;background:#dfd;text-decoration:none}
del {color:red;background:#fdd;text-decoration:none}
#params {margin:1em 0;font: 14px sans-serif}
.panecontainer > p {margin:0;border:1px solid #bcd;border-bottom:none;padding:1px 3px;background:#def;font:14px sans-serif}
.panecontainer > p + div {margin:0;padding:2px 0 2px 2px;border:1px solid #bcd;border-top:none}
.pane {margin:0;padding:0;border:0;width:100%;min-height:20em;overflow:auto;font:12px monospace}
#htmldiff {color:gray}
#htmldiff.onlyDeletions ins {display:none}
#htmldiff.onlyInsertions del {display:none}
</style>


<?php
// http://www.php.net/manual/en/function.get-magic-quotes-gpc.php#82524


include 'finediff.php';

$cache_lo_water_mark = 900;
$cache_hi_water_mark = 1100;
$compressed_serialized_filename_extension = '.store.gz';

/*
granularity
0=Párrafo / líneas
1= Frases
2= Palabras
3= Caracteres
*/

$granularity = 2;
$from_text = '';
$to_text = '';
$diff_opcodes = '';
$diff_opcodes_len = 0;
$data_key = '';

$start_time = gettimeofday(true);

$from_text = "hola saludos, que tal, todo bien";
$to_text ="hola david, como estas, todo bien";


// limit input
$from_text = substr($from_text, 0, 1024*100);
$to_text = substr($to_text, 0, 1024*100);

// ensure input is suitable for diff
$from_text = mb_convert_encoding($from_text, 'HTML-ENTITIES', 'UTF-8');
$to_text = mb_convert_encoding($to_text, 'HTML-ENTITIES', 'UTF-8');

$granularityStacks = array(
	FineDiff::$paragraphGranularity,
	FineDiff::$sentenceGranularity,
	FineDiff::$wordGranularity,
	FineDiff::$characterGranularity
);

$diff_opcodes = FineDiff::getDiffOpcodes($from_text, $to_text, $granularityStacks[$granularity]);
$diff_opcodes_len = strlen($diff_opcodes);



$rendered_diff = FineDiff::renderDiffToHTMLFromOpcodes($from_text, $diff_opcodes);
$from_len = strlen($from_text);
$to_len = strlen($to_text);
?>

<div class="panecontainer" style="width:99%">
	<p>Observar Palabras 
		<input type="radio" name="htmldiffshow" onclick="setHTMLDiffVisibility('deletions','htmldiff1');">Eliminadas&ensp;
		<input type="radio" name="htmldiffshow" onclick="setHTMLDiffVisibility('insertions','htmldiff1');">Agregadas&ensp;
		<input type="radio" name="htmldiffshow" checked="checked" onclick="setHTMLDiffVisibility('all','htmldiff1');">Todas&ensp;
	</p>
<div>

<div id="htmldiff1" class="pane" style="white-space:pre-wrap">
	<?php echo $rendered_diff; ?>
</div>


<script type="text/javascript">
<!--
function setHTMLDiffVisibility(what,element) {
	$("#"+element).css("color","gray");
	$("#"+element+".onlyDeletions ins").css("display","none");
	$("#"+element+".onlyInsertions del").css("display","none");
	
	var htmldiffEl = document.getElementById(element),
		className = htmldiffEl.className;
	className = className.replace(/\bonly(Insertions|Deletions)\b/g, '').replace(/\s{2,}/g, ' ').replace(/\s+$/, '').replace(/^\s+/, '');
	if ( what === 'deletions' ) {
		htmldiffEl.className = className + ' onlyDeletions';
		}
	else if ( what === 'insertions' ) {
		htmldiffEl.className = className + ' onlyInsertions';
		}
	else if ( what === 'all' ) {
		htmldiffEl.className = className;
		}
	}
// -->
</script>

