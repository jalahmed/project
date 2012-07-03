<?php // content="text/plain; charset=utf-8"
require_once ('../jpgraph.php');
require_once ('../jpgraph_pie.php');
require_once ('../jpgraph_pie3d.php');

//$gJpgBrandTiming=true;

// Some data
$data = array(40,60);

// Create the Pie Graph. 
$graph = new PieGraph(400,200,'auto');
$graph->SetShadow();

// Set A title for the plot
$graph->title->Set("");
$graph->title->SetFont(FF_FONT1,FS_BOLD);

// Create
$p1 = new PiePlot3D($data);
$p1->SetLegends(array("Male (%d)","Female(%d)"));
$targ=array("pie3d_csimex1.php?v=1","pie3d_csimex1.php?v=2");
$alts=array("val=%d","val=%d");
$p1->SetCSIMTargets($targ,$alts);

// Use absolute labels
$p1->SetLabelType(1);
$p1->value->SetFormat("%d kr");

// Move the pie slightly to the left
$p1->SetCenter(0.3,0.4);

$graph->Add($p1);


// Send back the HTML page which will call this script again
// to retrieve the image.
$graph->StrokeCSIM();

?>


