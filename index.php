<?php 

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

$DEBUGGING=False;

?>
<?php
$testurl="http://your_web_server/some_path/ listening_test_for_synthetic_speech_with_noise/index.php";

$testclosed=False;
$maxtesters=31;

$resultdir="/share/public_html/some_path/ listening_test_for_synthetic_speech_with_noise/results/";

$testtablestyle="width=570 align=center cellpadding=5 cellspacing=5";
$testtdstyle="bgcolor=#FFDDFF";

$infotablestyle="width=750 align=center cellpadding=25 cellspacing=1 border=1 bordercolor=#ffcf00";
$infotdstyle="bgcolor=#ffcf00";

if ($testclosed)
{
    print "<h3> Thanks for your interest! However, the test closed at 14:30 +2GMT on March 25th,  and we're busy analysing the results. </h3>\n";
}


// We'll check first if we have an email or not:

$listener=$_GET["listener"];
$page=$_GET["page"];

if (!$page || !$listener) {
    $page=0;
    $nextpage=$page+1;
}
else {
    $nextpage=$page+1;
    $testurl.="?page=${nextpage}&listener=${listener}";
}

// 
// ****  Handle previous inputs ****
//


//print "<h1>$listenerdir - $resultdir/$listenerdir</h2>";

if ( isset( $_POST["page"] ) )
{

    $listenerdir="".$listener;
    $listenerdir=preg_replace( "[@]", "_at_", $listenerdir);
    $listenerdir=preg_replace( "[\.]", "_", $listenerdir);
    $listenerdir=preg_replace( "[^a-zA-Z0-9_]", "", $listenerdir);
    
    if ( ! file_exists(  $resultdir . $listenerdir  ) ) {
	mkdir($resultdir . $listenerdir);
    }

    $resultfile=$resultdir . $listenerdir ."/".$listenerdir.".".$_POST['type'].".". $_POST['page'];

    if ($DEBUGGING)
	print "<br>Resultfile: $resultfile \n";

    $fh = fopen($resultfile, 'w');

    fwrite($fh, date(DATE_RFC3339)."\n");
    foreach ($_POST as $key => $value) {
	fwrite($fh, "$key\t$value\n");
    }
    fclose($fh);

    $startedfile= $resultdir ."started/".$listenerdir; 
    if ($DEBUGGING)
	print "<br>started_file: $startedfile" ;
    if ( ! file_exists( $startedfile ) ) {
	$fh = fopen($startedfile, 'w');
	fwrite($fh, date(DATE_RFC3339));
	fclose($fh);	
    }
}






//
// ****  Test prompts ****
//

$naturalitytext1="<p>Play the sample and attending <b>ONLY to the SPEECH SIGNAL</b>, select the category which best describes the sample you just heard."; 
$naturalitytext2="<p>The <b>SPEECH SIGNAL</b> in this signal was:";

$naturalitytext1_fi="<p>Kuuntele n‰yte ja keskity <b>VAIN PUHESIGNAALIIN</b>. Sen j‰lkeen valitse vaihtoehto, joka parhaiten kuvaa kuuntelemaasi n‰ytett‰.";
$naturalitytext2_fi="<p><b>PUHUJAN ƒƒNI</b> t‰ss‰ n‰ytteess‰ oli:";

$naturalitycats=array(
    "5" => "Completely natural <br><i> T‰ysin luonnollinen </i>",
    "4" => "Quite natural <br><i> Verrattain luonnollinen </i>",
    "3" => "Somewhat unnatural but acceptable <br><i> Hieman keinotekoinen mutta hyv‰ksytt‰v‰</i>",
    "2" => "Quite unnatural <br><i> Verrattain keinotekoinen</i>",
    "1" => "Completely unnatural <br><i> T‰ysin keinotekoinen</i>" );


$similaritytext1="<p>Play both samples, and attending <b>ONLY to the SPEECH SIGNAL</b>, select the category which best describes the second sample to the reference sample.";
$similaritytext2="<p>The voices in the <b>SPEECH SIGNALS</b> of the samples sounded:";


$similaritytext1_fi="<p>Kuuntele molemmat n‰ytteet ja keskity <b>VAIN PUHESIGNAALIIN</b>. Sen j‰lkeen valitse vaihtoehto, joka parhaiten kuvaa kuuntelemiasi n‰ytteit‰.";
$similaritytext2_fi="<p>Kuultujen n‰ytteiden <b>PUHUJIEN ƒƒNET</b> kuulostivat:";

$similaritycats=array(
    "5" => "Exactly like the same person <br><i> T‰ysin samalta puhujalta<i>",
    "4" => "Quite like the same person <br><i>Aika lailla samalta puhujalta</i>",
    "3" => "Somewhat different but recognisable as the same person<br><i>Verrattain erilaisilta, mutta tunnistettavissa samaksi puhujaksi</i>",
    "2" => "Quite like a different person <br><i>Aika lailla eri puhujilta</i>" ,
    "1" => "Like a totally different person <br> <i>T‰ysin eri puhujilta</i>" );


$noisytext1="<p>Play the sample and attending <b>ONLY to the BACKGROUND</b>, select the category which best describes the sample you just heard.";
$noisytext2="<p>the <b>BACKGROUND</b> in this signal was ";

$noisytext1_fi="<p>Kuuntele n‰yte ja keskity <b>VAIN TAUSTAAN</b>. Sen j‰lkeen valitse vaihtoehto, joka parhaiten kuvaa kuuntelemaasi n‰ytett‰.";
$noisytext2_fi="<p><b>TAUSTA</b> t‰ss‰ ‰‰nisignaalissa oli ";


// from  http://www.sciencedirect.com/science/article/pii/S0167639311001634
// and 

$noisycats=array(
    "5" => "Clean <br> <i>Hiljainen</i>",
    "4" => "Quite clean <br> <i>Melko hiljainen</i>",
    "3" => "Somewhat noisy but not intrusive <br> <i>Jokseenkin meluisa mutta ei h‰iritsev‰</i>",
    "2" => "Quite noisy and somewhat intrusive <br><i> Aika lailla meluisa, jokseenkin h‰iritsev‰</i>",
    "1" => "Very noisy and very intrusive <br><i>Eritt‰in meluisa ja eritt‰in h‰iritsev‰</i>" );



//$preferencetext1="<p>The methods evaluated in this test aim to create synthetic voices that mimic specific human speakers. The methods could be used in mobile devices, video games, audio books, and other such applications. 
//<p>Play the reference sentence. Then play both sample sentences. 
//Considering the <b>OVERALL QUALITY</b> of the signal,
//select the one you would prefer <i><b>to represent the reference voice</b></i> in such applications.
//";

$preferencetext1="<p>Play the reference sentence. Then play both sample sentences. 
Considering the <b>OVERALL QUALITY</b> of the signal,
select the one you would prefer <i><b>to represent the reference voice</b></i> in applications like mobile devices, video games, audio books etc.";

$preferencetext2="
<p>Regarding the <b>OVERALL QUALITY:</b>  ";

$preferencetext1_fi="<p>Kuuntele ensin referenssilause. Kuuntele sen j‰lkeen testin‰ytepari ja merkitse, <b><i>kumpi n‰yte sopii paremmin edustamaan
alkuper‰ist‰ puhujaa</b></i> mobiililaitteissa, videopeleiss‰, ‰‰nikirjoissa tai vastaavissa. Huomioi n‰ytteiden <b>LAATU</b> sek‰ <b>PUHEEN</b> ett‰ <b>TAUSTAMELUN</b> osalta";

$preferencetext2_fi="Ottaen huomioon n‰ytteiden <b>LAADUN:</b>";



$prefcats=array(
    "A" => "Sample A is better <br><i>N‰yte A on parempi</i>", 
    "B" => "Sample B is better <br><i>N‰yte B on parempi</i>",
    "same" => "The samples sound exactly the same <br><i>N‰ytteet kuulostavat t‰ysin samalta</i>");



// 
// ****  Test properties ie. size, samples, etc. ****
//


// Tests: 
// 0: Female, 
// 1: Female, Factory noise
// 2: Male, babble
// 3: Male, Factory noise

$maletestspeaker="FM3";
$femaletestspeaker="FF6";

//$group=0;
//if ( crc32($listener)%2 == 1)
//    $group=1;


// Let's force all listeners into the same group:
$group=1;


// New stuff:


$testtask_sent=array(
    array("train/FM3.noisy_babble_5_realcleandur.csmaplr.5_origdur.0080.wav","M3_babnoi"),
    array("train/FM3.cleaned_babble_5_invenergy_realcleandur.csmaplr.5_origdur.0080.wav","M3_babconf"),
    array("train/FM3.noisy_babble_5_realcleandur.csmaplr.5_origdur.0080.wav","M3_babnoi"),
    );

$testtask_ref=array("train/FM3_FIN_0025_0.16k.wav","M3_ref");
$testtask_prefref=array("train/FM3_FIN_0025_0.16k.wav","M3_ref");

$testtasks=array("noi","nat","sim");

$testtask_prefsent=array( array(
    array("train/FM3.noisy_babble_5_realcleandur.csmaplr.5_origdur.0080.wav","M3_babnoi"), 
    array("train/FM3.cleaned_babble_5_invenergy_realcleandur.csmaplr.5_origdur.0080.wav","M3_babconf"),),
);


if ($group==1) {
    $ref=array( 
	array("test_stimuli/F1_clean_orig_0008.wav","FF1_ref"), 
	array("test_stimuli/M1_clean_orig_0008.wav", "FM1_ref"),
	array("test_stimuli/F2_clean_orig_0009.wav","FF2_ref"), 
	array("test_stimuli/M2_clean_orig_0009.wav", "FM2_ref"),
	array("test_stimuli/F3_clean_orig_0010.wav","FF3_ref"), 
	array("test_stimuli/M3_clean_orig_0010.wav", "FM3_ref"),
	);
    
    
    $task1_sent_a=array(
	array("test_stimuli/F1_clean_cmllr10sent_0005.wav", "F1_clean_cmllr10sent_0005", $ref[0][0]),
	array("test_stimuli/F1_clean_eigen10sent_0005.wav", "F1_clean_eigen10sent_0005", $ref[0][0]),
	array("test_stimuli/F1_factory1_5_cmllr10sent_0005.wav", "F1_factory1_5_cmllr10sent_0005", $ref[0][0]),
	array("test_stimuli/F1_factory1_5_eigen10sent_0005.wav", "F1_factory1_5_eigen10sent_0005", $ref[0][0]),
	array("test_stimuli/F1_clean_averagevoice_0005.wav", "F1_average_0005", $ref[0][0]),                    

	array("test_stimuli/M1_clean_cmllr10sent_0005.wav", "M1_clean_cmllr10sent_0005", $ref[1][0]),
	array("test_stimuli/M1_clean_eigen10sent_0005.wav", "M1_clean_eigen10sent_0005", $ref[1][0]),
	array("test_stimuli/M1_factory1_5_cmllr10sent_0005.wav", "M1_factory1_5_cmllr10sent_0005", $ref[1][0]),
	array("test_stimuli/M1_factory1_5_eigen10sent_0005.wav", "M1_factory1_5_eigen10sent_0005", $ref[1][0]),
	array("test_stimuli/M1_clean_averagevoice_0005.wav", "M1_average_0005", $ref[1][0]),                    // 5

	array("test_stimuli/M2_clean_cmllr10sent_0006.wav", "M2_clean_cmllr10sent_0006.wav", $ref[3][0]),
	array("test_stimuli/M2_clean_eigen10sent_0006.wav", "M2_clean_eigen10sent_0006", $ref[3][0]),
	array("test_stimuli/M2_factory1_5_cmllr10sent_0006.wav", "M2_factory1_5_cmllr10sent_0006", $ref[3][0]),
	array("test_stimuli/M2_factory1_5_eigen10sent_0006.wav", "M2_factory1_5_eigen10sent_0006", $ref[3][0]),
	array("test_stimuli/M2_clean_averagevoice_0006.wav", "M2_average_0006", $ref[3][0]),                    // 8

	array("test_stimuli/F3_clean_cmllr10sent_0007.wav", "F3_clean_cmllr10sent_0007", $ref[4][0]),
	array("test_stimuli/F3_clean_eigen10sent_0007.wav", "F3_clean_eigen10sent_0007", $ref[4][0]),
	array("test_stimuli/F3_factory1_5_cmllr10sent_0007.wav", "F3_factory1_5_cmllr10sent_0007", $ref[4][0]),
	array("test_stimuli/F3_factory1_5_eigen10sent_0007.wav", "F3_factory1_5_eigen10sent_0007", $ref[4][0]),
	array("test_stimuli/F3_clean_averagevoice_0007.wav", "F3_average_0007", $ref[4][0]),                    // 8

//	array("test_stimuli/F2_clean_cmllr10sent_0006.wav", "F2_clean_cmllr10sent_0006", $ref[2][0]),
//	array("test_stimuli/F2_clean_eigen10sent_0006.wav", "F2_clean_eigen10sent_0006", $ref[2][0]),
//	array("test_stimuli/F2_factory1_5_cmllr10sent_0006.wav", "F2_factory1_5_cmllr10sent_0006", $ref[2][0]),
//	array("test_stimuli/F2_factory1_5_eigen10sent_0006.wav", "F2_factory1_5_eigen10sent_0006", $ref[2][0]),
//	array("test_stimuli/F2_clean_averagevoice_0006.wav", "F2_average_0006", $ref[2][0]),                    // 8

//	array("test_stimuli/M3_clean_cmllr10sent_0007.wav", "M3_clean_cmllr10sent_0007", $ref[5][0]),
//	array("test_stimuli/M3_clean_eigen10sent_0007.wav", "M3_clean_eigen10sent_0007", $ref[5][0]),
//	array("test_stimuli/M3_factory1_5_cmllr10sent_0007.wav", "M3_factory1_5_cmllr10sent_0007", $ref[5][0]),
//	array("test_stimuli/M3_factory1_5_eigen10sent_0007.wav", "M3_factory1_5_eigen10sent_0007", $ref[5][0]),
//	array("test_stimuli/M3_clean_averagevoice_0007.wav", "M3_average_0007", $ref[5][0]),                    // 8

	);
}
else {

    $task1_ref=("stimuli/FF1/reference.wav");

    $ref=array("ref/FM3_FIN_0007_0.16k.wav","M3_ref");
    $task1_sent_a=array(     
	array("synth/FM3.clean.csmaplr.5.0067.wav", "M3_clean", $ref[0]),
	array("synth/FM3.cleaned_babble_5_invenergy_realcleandur.csmaplr.5_origdur.0067.wav", "M3_bab_conf", $ref[0]),
	array("synth/FM3.cleaned_babble_5_realcleandur.csmaplr.5_origdur.0067.wav", "M3_bab_sep", $ref[0]),
	array("synth/FM3.cleaned_factory1_5_invenergy_realcleandur.csmaplr.5_origdur.0067.wav", "M3_fac_conf", $ref[0]),
	array("synth/FM3.cleaned_factory1_5_realcleandur.csmaplr.5_origdur.0067.wav", "M3_fac_sep", $ref[0]),
	array("synth/FM3.noisy_babble_5_realcleandur.csmaplr.5_origdur.0067.wav", "M3_bab_noi", $ref[0]),
	array("synth/FM3.noisy_factory1_5_realcleandur.csmaplr.5_origdur.0067.wav", "M3_fac_noi", $ref[0]),
	array("synth/average.0067.wav", "M3_aver", $ref[0]),
	array("nat/FM3_FIN_0067_0.16k.wav", "M3_natural", $ref[0]),
	);
    
}


srand(crc32($listener));
SEOshuffle($task1_sent_a, crc32($listener));


if ($group==1) {
    
    $taskB_ref=	array(
	array("test_stimuli/F1_clean_orig_0009.wav","FF1_ref"), 
	array("test_stimuli/M1_clean_orig_0009.wav", "FM1_ref"),
	array("test_stimuli/F2_clean_orig_0010.wav","FF2_ref"), 
	array("test_stimuli/M2_clean_orig_0010.wav", "FM2_ref"),
	array("test_stimuli/F3_clean_orig_0008.wav","FF3_ref"), 
	array("test_stimuli/M3_clean_orig_0008.wav", "FM3_ref"),
	);

    $taskB_sent=array( 
	array(
	    array("test_stimuli/F1_factory1_5_cmllr10sent_0007.wav", "F1_factory1_5_cmllr10sent_0007", $ref[0][0]), // 0
	    array("test_stimuli/F1_factory1_5_eigen10sent_0007.wav", "F1_factory1_5_eigen10sent_0007", $ref[0][0]), // 1
	    array("test_stimuli/F1_clean_averagevoice_0007.wav", "F1_average_0007", $ref[0][0]),                    // 2


	    array("test_stimuli/M1_factory1_5_cmllr10sent_0007.wav", "M1_factory1_5_cmllr10sent_0007", $ref[1][0]), // 3
	    array("test_stimuli/M1_factory1_5_eigen10sent_0007.wav", "M1_factory1_5_eigen10sent_0007", $ref[1][0]), // 4
	    array("test_stimuli/M1_clean_averagevoice_0007.wav", "M1_average_0007", $ref[1][0]),                    // 5


	    array("test_stimuli/M2_factory1_5_cmllr10sent_0005.wav", "M2_factory1_5_cmllr10sent_0005", $ref[3][0]), // 9
	    array("test_stimuli/M2_factory1_5_eigen10sent_0005.wav", "M2_factory1_5_eigen10sent_0005", $ref[3][0]), // 10
	    array("test_stimuli/M2_clean_averagevoice_0005.wav", "M2_average_0005", $ref[3][0]),                    // 11


	    array("test_stimuli/F3_factory1_5_cmllr10sent_0006.wav", "F3_factory1_5_cmllr10sent_0006", $ref[4][0]), // 12
	    array("test_stimuli/F3_factory1_5_eigen10sent_0006.wav", "F3_factory1_5_eigen10sent_0006", $ref[4][0]), // 13
	    array("test_stimuli/F3_clean_averagevoice_0006.wav", "F3_average_0006", $ref[4][0]),                    // 14


//	    array("test_stimuli/M3_factory1_5_cmllr10sent_0006.wav", "M3_factory1_5_cmllr10sent_0006", $ref[5][0]), // 15
//	    array("test_stimuli/M3_factory1_5_eigen10sent_0006.wav", "M3_factory1_5_eigen10sent_0006", $ref[5][0]), // 16
//	    array("test_stimuli/M3_clean_averagevoice_0006.wav", "M3_average_0006", $ref[5][0]),                    // 17

//	    array("test_stimuli/F2_factory1_5_cmllr10sent_0005.wav", "F2_factory1_5_cmllr10sent_0005", $ref[2][0]), // 6
//	    array("test_stimuli/F2_factory1_5_eigen10sent_0005.wav", "F2_factory1_5_eigen10sent_0005", $ref[2][0]), // 7
//	    array("test_stimuli/F2_clean_averagevoice_0005.wav", "F2_average_0005", $ref[2][0]),                    // 8

	    
	    ),
	);
}
else {
    $taskB_ref=array("nat/FF6_FIN_0081_0.16k.wav"  );
    
    $taskB_sent=array(  
	array(
	    array("synth/FF6.clean.csmaplr.5.0081.wav", "F6_clean"),
	    array("synth/FF6.cleaned_babble_5_invenergy_realcleandur.csmaplr.5_origdur.0081.wav", "F6_bab_conf"),
	    array("synth/FF6.cleaned_babble_5_realcleandur.csmaplr.5_origdur.0081.wav", "F6_bab_sep"),
	    array("synth/FF6.cleaned_factory1_5_invenergy_realcleandur.csmaplr.5_origdur.0081.wav", "F6_fac_conf"),
	    array("synth/FF6.cleaned_factory1_5_realcleandur.csmaplr.5_origdur.0081.wav", "F6_fac_sep"),
	    array("synth/FF6.noisy_babble_5_realcleandur.csmaplr.5_origdur.0081.wav", "F6_bab_noi"),
	    array("synth/FF6.noisy_factory1_5_realcleandur.csmaplr.5_origdur.0081.wav", "F6_fac_noi"),
	    array("resynth/F6_babble_5_clean.0081.wav", "F6_resyn"),
	    array("synth/average.0081.wav", "F6_aver"),
	    array("nat/FF6_FIN_0081_0.16k.wav", "F6_natural"),
	    ),
	
	);
       
}


$taskB_order=array( 
    array(0,1), 
    array(1,2), 
    array(2,0), 

    array(3,4), 
    array(4,5), 
    array(3,5), 

    array(6,7), 
    array(7,8), 
    array(6,8), 

    array(9,10),
    array(10,11),
    array(9,11),

//    array(12,13),
//    array(13,14),
//    array(12,14),

//    array(15,16),
//    array(16,17),
//    array(15,17),
    );


for ($n=0;$n<count($taskB_order);$n++) {

    if ( crc32($listener . $n) % 2 == 0  )
    { 


	$tmp = $taskB_order[$n][0];
	$taskB_order[$n][0] =$taskB_order[$n][1];
	$taskB_order[$n][1] = $tmp;

    }

}


// Test size:
     
$testtestpagecount=3;
$preftesttestpagecount=1;
$test1pagecount=count($task1_sent_a)*3;
$test2pagecount=count($taskB_order);

// pages:

$intropagelimit=0; // test the sound on first page

$preftesttestpagelimit=$intropagelimit+$preftesttestpagecount;  // learn how to do the test, part II

$test2pagelimit=$preftesttestpagelimit + $test2pagecount;

$testtestpagelimit=$test2pagelimit + $testtestpagecount; // learn how to do the test
//print "testtestpagelimit: $testtestpagelimit";
$test1pagelimit=$testtestpagelimit + $test1pagecount; // do the test itself
//print "test1pagelimit: $test1pagelimit";


//$preftesttestpagelimit=$testtestpagelimit+$preftesttestpagecount;  // learn how to do the test, part II
//$testtestpagelimit=$intropagelimit + $testtestpagecount; // learn how to do the test
//$test1pagelimit=$preftesttestpagelimit + $test1pagecount; // do the test itself
//$test2pagelimit=$test1pagelimit + $test2pagecount;



if ( $page > $test1pagelimit)
{
    $finishedfile= $resultdir ."finished/".$listenerdir; 
    
    if ( ! file_exists( $finishedfile ) ) {
//	print "should be writing: $finishedfile";
	$fh2 = fopen($finishedfile, 'w');
	fwrite($fh2, date(DATE_RFC3339));
	fclose($fh2);	
    }
}








// Permutations for the test:
$permutations = array( array(0,1), array(1,0) );


srand(crc32($listener));
SEOshuffle($permutations, crc32($listener));

srand(crc32($listener));
SEOshuffle($taskB_order, crc32($listener));




// 
// ****  Print the current test page ****
//




$colorcount=0;
$colortable=array("#FFCCCC","#CCCCFF");

print "<html><head><title>Speech synthesis listening test [page $page]</title>\n";
print "<!-- Bender head from http://www.iconeasy.com/iconset/simpsons-vol-09-icons/ available for noncommercial use -->\n";
print "<link rel=\"SHORTCUT ICON\" HREF=\"http://your_web_server/some_path/ listening_test_for_synthetic_speech_with_noise/benderhead_small.ico\"></head>\n";


print "<script type=\"text/javascript\">
// Radio Button Validation
// copyright Stephen Chapman, 15th Nov 2004,14th Sep 2005
// you may copy this function but please keep the copyright notice with it

// ... Somehow modified by Reima Karhila, Aug 15 2012
function valButton(button) {
    var btn=document.forms['testform'][button];
    var cnt = -1;
    for (var i=btn.length-1; i > -1; i--) {
        if (btn[i].checked) {cnt = i; i = -1;}
    }
    if (cnt > -1) return true;
    else {
       alert(\"Please finish this task before continuing! \\n\\n  Ole yst‰v‰llinen ja tee t‰m‰ koe loppuun ennen kuin jatkat!\");
       return false;
    }
}
</script>
";

print "<STYLE TYPE=\"text/css\">
   <!-- 
body {  font-family: Arial, Helvetica, sans-serif; 
        font-size: small;
        background-image:url('graphic3_vertical.png');
        background-repeat:no-repeat;
        background-attachment:fixed;
        background-position:right bottom; }

-->
   </STYLE>
";

print "<body>\n";
if ($DEBUGGING) {
    print "The test is running in debug mode - this might not be desireable?<br>";
}


/*
print "<pre>Post\n";
print_r ($_POST);
print "get\n";
print_r ($_GET);
print "</pre>\n";
*/



$progresswidth=540;
if ($page < 0.5 * $test1pagelimit) {
    printf ("<table align=center height=20 width=%d bgcolor=996699><tr><td bgcolor=FFCCFF width=%d><td bgcolor=FFFFFF width=%d>%d%% complete</td></tr></table>",
	    $progresswidth, $progresswidth*$page/($test1pagelimit+1), $progresswidth*(1-$page/($test1pagelimit+1)), 100*$page/($test1pagelimit+1));
} else {
    printf ("<table align=center height=20 width=%d bgcolor=996699><tr><td bgcolor=FFCCFF width=%d>%d%% complete</td><td bgcolor=FFFFFF width=%d></td></tr></table>",
	    $progresswidth, $progresswidth*$page/($test1pagelimit+1), 100*$page/($test1pagelimit+1), $progresswidth*(1-$page/($test1pagelimit+1)));
}    




if ($page == 0) {

// open this directory 
    $myDirectory = opendir($resultdir."/started/");
// get each entry
    while($entryName = readdir($myDirectory)) {
	$dirArray[] = $entryName;
    }
// close directory
    closedir($myDirectory);
    
//	count elements in array
    $indexCount	= count($dirArray)-2;

    

// open this directory 
    $myDirectory = opendir($resultdir."/finished/");
// get each entry
    while($entryName = readdir($myDirectory)) {
	$closedirArray[] = $entryName;
    }
// close directory
    closedir($myDirectory);
    $finishCount=count($closedirArray)-2;

//    print "<table $infotablestyle><tr><td $infotdstyle> This test will open on Monday 12th November at 14:00 +2GMT</td></tr></table>";


    if ($indexCount >=   $maxtesters) {
	print "<table $infotablestyle><tr><td $infotdstyle> $indexCount listeners have already started the test, and we have a budget for rewards only for $maxtesters testers!";
	if ($finishCount < $maxtesters) {
	    $notyetfinished=$maxtesters-$finishCount;
	    print "<p>$notyetfinished testers have not finished the test and it is possible that some may stop in the middle, so you can check for vacancies for example tomorrow.</td></tr></table>"; 
	}
	else
	    print "<p>You can still do the test, but unfortunately we cannot offer a reward.</td></tr></table>";
	
    }
    

    print "
<br><br>
<table $testtablestyle>";
//<tr><td><img src=graphic3.png style=align=center></td></tr>
    

print "<tr><td $testtdstyle colspan=2>
<p>Welcome to our listening test! 
You can do the test if
<ul>
<li>You are a native speaker of Finnish
<li>you have headphones and
<li>you have a possibility to spend 30 minutes in a quiet space...
<li>..before we close the test on Monday 25th March 2 PM +2GMT.
</ul>

<p>The methods evaluated in this test aim to create synthetic voices that mimic
specific human speakers. The methods could be used in <b>mobile devices, video
games, audio books, and other such applications.</b>
<p>Please start the test by playing the sample below:<br>
<i>
<p>Tervetuloa kuuntelukokeeseemme! Voit osallistua kokeeseemme jos
<ul>
<li> ‰idinkielesi on suomi,
<li> sinulla on kuulokkeet sek‰
<li> mahdollisuus istua n. 30 min hiljaisessa tilassa...
<li> ..ennen kuin suljemme testin maanantaina 25.3. klo 14:00 +2GMT.
</ul>
<p>T‰ss‰ tutkimuksessa arvioitavat menetelm‰t pyrkiv‰t keinotekoisesti j‰ljittelem‰‰n tietyn ihmisen puhetta. Menetelmien mahdollisia k‰yttˆkohteita ovat <b>mobiililaitteet, videopelit, ‰‰nikirjat ja muut vastaavat sovellukset.</b> 
<p>
Palkkioksi voit valita leffalipun, 3 lounaslippua TUASin kuppilaan tai suklaalevyn. Valinta tehd‰‰n testin lopussa.
<p>
Aloita kuuntelemalla oheinen ‰‰nin‰yte:<br></i>
</td></tr>
<tr><td colspan=2 $testtdstyle>
<audio src=train/SC005S01.16k.wav controls width=100></audio>
</td></tr>
<tr><td $testtdstyle colspan=2>
<p>
If there are clicks or other distortion in the playback, try using another web browser <br>
<p>
If it sounds ok, begin by typing your email address and clicking submit:
<br><i><p>
Jos ‰‰nen toistossa oli naksahduksia tai muuta v‰‰ristym‰‰, koeta avata t‰m‰ sivu toisella web-selaimella
<p>
Jos ‰‰ness‰ ei ollut vikaa, aloita testi kirjoittamalla s‰hkˆpostiosoitteesi alla olevaan kentt‰‰n ja klikkaamalla submit-nappia.
</td></tr>
<tr><td colspan=2 $testtdstyle>
   <form method=get action=$testurl>
    <input type=hidden name=page value=$nextpage>
Your email address:<br>
    <input type=text name=listener>
    <input type=submit disabled/>    </form>

</tr>
<tr><td $testtdstyle colspan=2>
<p>So far $indexCount people have started the test and $finishCount have finished it. 
We have reserved a small reward (your choice of movie ticket, lunch vouchers etc.) for the  $maxtesters first listeners.
</td></tr></table>


";
}


// 
// ****  Second test -- preference test ****
//

elseif ($page <= $preftesttestpagelimit )  {
    $theme="Practise round for Experiment 1";
//    $categorypage=$page-$testtestpagelimit;
//  $categorypagecount=$preftesttestpagecount;

    $categorypage=$page;
    $categorypagecount=$preftesttestpagecount;

    print "<table $infotablestyle><tr><td $infotdstyle width=50%>This listening test consists of 2 experiments and takes around 30 minutes to complete. <br><br>\n";
    print "The test begins with this practise round to check that everything is working.</td>\n";
    print "<td $infotdstyle width=50%>";
    print "T‰m‰ koe koostuu kahdesta osasta ja kest‰‰ yhteens‰ n. 30 minuuttia.<br><br>T‰m‰ on harjoituskierros testin ensimm‰iseen osioon.</td></tr></table>\n";
     

    print generate_preftest( $testtask_prefref[0], $testtask_prefsent[$page-1][0][0],$testtask_prefsent[$page-1][1][0]);

}

elseif ($page <= $test2pagelimit )  {

    $theme="Listening Experiment 1 / 2";
    $categorypage=$page-$preftesttestpagelimit;
    $categorypagecount=$test2pagecount;   
    
    if ($DEBUGGING)
	print  "<br>foo: ".$categorypage."/".$taskB_order[$categorypage-1][0];

    if ($categorypage == 1)
    {
	print "<table $infotablestyle><tr><td $infotdstyle width=50%>The first experiment starts now!</td>";
	print "<td $infotdstyle width=50%>Ensimm‰inen testi alkaa nyt!</td>";
	print "</td></tr></table>\n";
    }
    
    print generate_preftest( $taskB_sent[0][ $taskB_order[$categorypage-1][0]][2], $taskB_sent[0][ $taskB_order[$categorypage-1][0]][0],$taskB_sent[0][ $taskB_order[$categorypage-1][1]][0]);


}




// 
// ****  First test -- rotation of 3 subjective evaluations ****
//


elseif ($page <= $testtestpagelimit ) {
    $theme="Practise round for Experiment 2";
//    $categorypage=$page;
//    $categorypagecount=$testtestpagecount;

    $categorypage=$page-$test2pagelimit;
    $categorypagecount=$testtestpagecount;

    if ( $categorypage%3 == 1) {
	print "<table $infotablestyle><tr><td $infotdstyle width=50%>First experiment complete! Now for experiment 2. <br><br>\n";
	print "In this experiment you will listen to a speech sample and evaluate it according to three different criteria.<br><br> ";
	print "This practise round will introduce the questions.\n</td>";
	print "<td $infotdstyle width=50%>Testin ensimm‰inen osa on suoritettu! Seuraavaksi osa 2.<br><br>";
	print "Testin t‰ss‰ osassa arvioit jokaista puhen‰ytett‰ kolmella eri perusteella.<br><br>";
	print "Tutustut arviointiperusteisiin ja kysymyksiin t‰ll‰ harjoituskierroksella.";
	print "</td></tr></table>\n";

	print generate_noitest( $testtask_sent[0][0]);


    }
    elseif ( $categorypage%3 == 2) {

	print "<table $infotablestyle><tr><td $infotdstyle width=50%>Practise round for experiment 2 continues</td>\n";
	print "<td $infotdstyle width=50%>Harjoituskierros testille 2 jatkuu</td></tr></table>\n";

	print generate_nattest( $testtask_sent[0][0]);
		
    }    
    else {

	print "<table $infotablestyle><tr><td $infotdstyle width=50%>Practise round for experiment 2 continues</td>\n";
	print "<td $infotdstyle width=50%>Harjoituskierros testille 2 jatkuu</td></tr></table>\n";


	print generate_simtest( $testtask_ref[0], $testtask_sent[0][0]);
    }
}


elseif ($page <= $test1pagelimit ) {
    $theme="Listening Experiment 2 / 2";
//    $categorypage=$page-$preftesttestpagelimit;
//    $categorypagecount=$test1pagecount;    
    $categorypage=$page-$testtestpagelimit;
    $categorypagecount=$test1pagecount;    

    if (floor(($categorypage-1)/3) < 0.5*$test1pagecount/3)
    {
	if ($categorypage == 1)
	{
	    print "<table $infotablestyle><tr><td $infotdstyle width=50%>The second experiment starts now for real!</td>";
	    print "<td $infotdstyle width=50%>Testin toinen osio alkaa nyt!</td></tr></table>\n";
	}
	if ($DEBUGGING)
	{
	    $index=floor(($categorypage-1)/3);
	    print "task1_sent_a[$index][0]";
	    print "Random seeding: ".crc32($listener);
	    print "<br>\n";
	    print_r ($task1_sent_a);
	}
	if ( $categorypage%3 -1 == 0) 
	    print generate_noitest( $task1_sent_a[floor(($categorypage-1)/3)][0]);
	elseif ( $categorypage%3 -1 == 1) 
	    print generate_nattest( $task1_sent_a[floor(($categorypage-1)/3)][0]);
	else 
	    print generate_simtest( $task1_sent_a[floor(($categorypage-1)/3)][2], $task1_sent_a[floor(($categorypage-1)/3)][0]);
    }
    else
    {
	if (floor(($categorypage-1)) == floor(0.5*$test1pagecount))
	{
	    print "<table $infotablestyle><tr><td $infotdstyle width=50%>You are now halfway through this last experiment, and the tasks will now be in reverse order.";
	    print "<br><br>This is a good time to have a coffee, tea or cigarette break.</td>";
	    print "<td $infotdstyle width=50%>Olet nyt 2. testin puoliv‰liss‰, ja kysymysten j‰rjestys k‰‰nnet‰‰n.<br><br> Nyt olisi hyv‰ hetki pit‰‰ pieni kahvi-, tee- tai tupakkatauko, jos silt‰ tuntuu.</td></tr></table>\n";
	}
	if ( $categorypage%3 -1 == 0) 
	    print generate_simtest( $task1_sent_a[floor(($categorypage-1)/3)][2], $task1_sent_a[floor(($categorypage-1)/3)][0]);	
	elseif ( $categorypage%3 -1 == 1) 
	    print generate_nattest( $task1_sent_a[floor(($categorypage-1)/3)][0]);
	else 
	    print generate_noitest( $task1_sent_a[floor(($categorypage-1)/3)][0]);
    }
}


elseif ($page == $test1pagelimit +1 )  {
    print  "<table $testtablestyle>\n";
    print "<tr><td colspan=2 $testtdstyle >";
    print "Thanks for your patience!";
    print "<br>Please choose your reward from the selection below.";

    print "<p><i>Kiitoksia k‰rsiv‰llisyydest‰si!</i>";
    print "<br><i>Valitse toivomasi palkkio allaolevasta listasta.</i>";
    
    print "<form name=\"testform\" method=\"post\" action=\"$testurl\" onsubmit='return valButton(\"reward\")'>\n";
    print "<input type=hidden name=\"type\" value=\"reward\">\n";
    print "<input type=hidden name=\"page\" value=\"100\">\n";
    print "<input type=hidden name=\"listener\" value=\"$listener\">\n";
    print "</td></tr>";
    print "<tr><td colspan=2>I want...</td></tr><tr><td>";
    print "<ol><li> TUAS cafeteria lunch vouchers for around 8&euro; (ie. 3 student lunches)<br>";
    print "a. <input name=\"reward\" value=\"lunch_tuas_student\" type=\"radio\"  required=\"required\"> student lunch tickets for TUAS cafeteria<br>";    
    print "b. <input name=\"reward\" value=\"lunch_tuas_personnel\" type=\"radio\"  required=\"required\"> personnel lunch ticket for TUAS cafeteria<br>";
    print "c. <input name=\"reward\" value=\"lunch_kvarkki_student\" type=\"radio\"  required=\"required\"> student lunch tickets for Kvarkki cafeteria<br>";    
    print "d. <input name=\"reward\" value=\"lunch_kvarkki_personnel\" type=\"radio\"  required=\"required\"> personnel lunch ticket for Kvarkki cafeteria<br>";
    print "<li><input name=\"reward\" value=\"movie\" type=\"radio\"  required=\"required\"> A movie ticket (Finnkino)<br>";
    print "<li><input name=\"reward\" value=\"chocolate\" type=\"radio\"  required=\"required\"> A chocolate bar (Fazerin sininen, 200g) (Only delivery options 1, 2 and 3 possible!)<br>";
    print "<li><input name=\"reward\" value=\"nothing\" type=\"radio\"  required=\"required\">I am content with my life - I do not want anything!<br></td></tr>";
    print "</table>";


    print  "<table $testtablestyle>\n";
    print "<tr><td colspan=3 $testtdstyle >And please select delivery:</td></tr>";
    print "<tr><td><input name=\"delivery\" value=\"collect\" type=\"radio\"  required=\"required\" checked></td><td colspan=2>1. Collect from room I318 in ELEC-building</td></tr>";
    print "<tr><td><input name=\"delivery\" value=\"riippari\" type=\"radio\"  required=\"required\"> </td><td colspan=2>2.  Riippari: </td></tr>";
    print "<tr><td> </td><td> Name:</td><td> <input type=text name=\"riippari-name\" width=40></td></tr>";
    print "<tr><td> </td><td> Guild:</td><td> ";
    print "<select name \"riippari-guild\">";
    print "<option value=\"AS\">AS</option>";
    print "<option value=\"Athene\">Athene</option>";
    print "<option value=\"Prodeko\">Prodeko</option>";
    print "<option value=\"TIK\">TIK</option>";
    print "<option value=\"ELEC\">SIK</option></select>";
    print "</select></td></tr>";
//    "<input type=text name=\"riippari-guild\" width=20></td></tr>";
    print "<tr><td> </td><td> Year:</td><td>";
//    <input type=text name=\"riippari-year\" width=1></td></tr>";
    print "<select name \"riippari-year\">";
    print "<option value=\"1\">1</option>";
    print "<option value=\"2\">2</option>";
    print "<option value=\"3\">3</option>";
    print "<option value=\"4\">4</option>";
    print "<option value=\"n\">n</option>";
    print "</select></td></tr>";

    print "<tr><td><input name=\"delivery\" value=\"aaltopost\" type=\"radio\"  required=\"required\"> </td><td colspan=2>3. Aalto internal post  </td></tr>";
    print "<tr><td> </td><td>  Name: </td><td><input type=text name=\"aalto-name\" width=40></td></tr>";
    print "<tr><td> </td><td>  P.O.Box</td><td> <input type=text name=\"aalto-box\" width=40></td></tr>";


    print "<tr><td><input name=\"delivery\" value=\"mail\" type=\"radio\"  required=\"required\"> </td><td colspan=2>4.  By mail</td></tr>";
    print "<tr><td> </td><td> Name:</td><td> <input type=text name=\"postal-name\" width=40></td></tr>";
    print "<tr><td> </td><td> Street address:</td><td> <input type=text name=\"postal-street\" width=40></td></tr>";
    print "<tr><td> </td><td> City and postal code:</td><td> <input type=text name=\"postal-code\" width=40></td></tr>";

    
    print "<tr><td colspan=3 $testtdstyle >Any comments about the experiment itself</td></tr>";

    print "<tr><td colspan=3>Feel free to write here:<br><textarea name=\"comments\" cols=\"60\" rows=\"5\"></textarea></td></tr>";

    print "<tr><td  $testtdstyle colspan=3 align=right><input type=\"submit\" >    </td></tr></table>";

}

else {

    print  "<table $testtablestyle><tr><td>It's all good! <br>";
    
    $rew = "nothing";
    if ( preg_match ('/lunch.*/',$_POST['reward']) ) { #if ($_POST['reward'] == "lunch_student" | $_POST['reward'] == "lunch_personnel") {
	$rew="lunch tickets";
    } 
    elseif ($_POST['reward'] == "movie") {
	$rew="movie ticket";
    }    
    elseif ($_POST['reward'] == "chocolate") {
	$rew="chocolate bar";
    }

    if ($rew != "nothing") {
	if ($_POST['delivery'] == "collect") {
	    print "Please collect your $rew after 6th April from room  or C311 in T-talo (Konemiehentie 2)";
	}
	elseif ($_POST['delivery'] == "aaltopost") {
	    print "We'll mail your $rew to \"".$_POST['aalto-name'].", P.O.Box ".$_POST['aalto-box']."\" at latest on 6th April";	    
	}
	elseif ($_POST['delivery'] == "mail") {
	    print "We'll mail your $rew to \"".$_POST['postal-name'].", ".$_POST['postal-street'].", ".$_POST['postal-code']."\" at latest on 6th April";	    	    
	}
	elseif ($_POST['delivery'] == "riippari") {    	   
	    print "We'll drop your $rew in your riippari \"".$_POST['riippari-name'].", ".$_POST['riippari-guild']."-".$_POST['riippari-year']."\" at latest on 6th April";	    	    
	}
    }
    print "</td></tr></table>";
}

print "
<br>
<table $testtablestyle><tr><td $testtdstyle> <small> Any trouble with the test? Contact me at email@yourserver / +353 1234 5678 <br>
cheers, N.N.</small></td></tr></table>

</body></html>";



 /* Helper functions */

function generate_noitest( $wavfile ) {

    global $testtablestyle, $testtdstyle, $noisytext1,$noisytext1_fi,$noisytext2, $noisytext2_fi, $noisycats, $page, $listener, $nextpage, $testurl, $theme, $categorypage, $categorypagecount, $DEBUGGING;

    $str="";
    $str .= "<form name=\"testform\" method=\"post\" action=\"$testurl\" onsubmit='return valButton(\"noitest\")'>\n";
    $str .=  "<input type=hidden name=\"type\" value=\"noi\">\n";
    $str .=  "<input type=hidden name=\"page\" value=\"$page\">\n";
    $str .=  "<input type=hidden name=\"listener\" value=\"$listener\">\n";
    $str .=  "<input type=hidden name=\"sample\" value=\"$wavfile\">\n";
    $str .=  "<table $testtablestyle>\n";
//    $str .= "<tr><td colspan=2><img src=graphic3.png style=align=center></td></tr>\n";

    $str .=  "<tr><th colspan=2 $testtdstyle>$theme, trial ". ceil($categorypage/3)." / ".($categorypagecount/3).", task A </th></tr>\n";

//    $str .=  "<tr><td $testtdstyle>$noisytext1</td><td $testtdstyle>$noisytext1_fi</td></tr></table><table $testtablestyle>\n\n";
    $str .=  "<tr><td $testtdstyle>$noisytext1<br><i>$noisytext1_fi</i></td></tr></table><table $testtablestyle>\n\n";
    if ($DEBUGGING) {
	$str .="<tr><td $testtdstyle colspan=2>$wavfile ></td></tr>";
    }
    $str .= 	"<tr><td $testtdstyle colspan=2><audio src=$wavfile controls width=100></audio></td></tr>\n";
//    $str .=  "<tr><td $testtdstyle>$noisytext2</td><td $testtdstyle>$noisytext2_fi</td></tr></table><table $testtablestyle>\n\n";
    $str .=  "<tr><td $testtdstyle colspan=2>$noisytext2<br><i>$noisytext2_fi</i></td></tr>\n\n";
    
    foreach ($noisycats as $key => $value) {
	$str .=  "<tr><td $testtdstyle><input name=\"noitest\" value=\"noi$key\" type=\"radio\"  required=\"required\"> $key.</td><td $testtdstyle>$value </td></tr>\n";
    }
    $str .=  "<tr><td $testtdstyle colspan=2 align=right><input type=\"submit\">  </td></tr>\n";

//    $str .=  "<tr><td $testtdstyle colspan=2>You can comment the test in the text box below:<br><textarea name=\"comments\" cols=\"60\" rows=\"5\"></textarea> </td></tr>\n";

    $str .=  "</table></form>\n";
    return $str;


 }

function generate_nattest( $wavfile) {

    global $testtablestyle, $testtdstyle, $naturalitytext1, $naturalitytext1_fi, $naturalitytext2, $naturalitytext2_fi,$naturalitycats, $page, $listener, $nextpage, $testurl, $theme, $categorypage, $categorypagecount, $DEBUGGING;

        $str="";

	$str =  "<form name=\"testform\" method=\"post\" action=\"$testurl\" onsubmit='return valButton(\"nattest\")'>\n";
	$str .=  "<input type=hidden name=\"type\" value=\"nat\">\n";
	$str .=  "<input type=hidden name=\"page\" value=\"$page\">\n";
	$str .=  "<input type=hidden name=\"listener\" value=\"$listener\">\n";
	$str .=  "<input type=hidden name=\"sample\" value=\"$wavfile\">\n";

	$str .=  "<table $testtablestyle>\n";
//	$str .= "<tr><td colspan=2><img src=graphic3.png style=align=center></td></tr>\n";

	$str .=  "<tr><th colspan=2 $testtdstyle>$theme, trial ". ceil($categorypage/3)." / ".($categorypagecount/3).", task B </th></tr>\n";
//	$str .=  "<tr><td $testtdstyle>$naturalitytext1</td><td $testtdstyle>$naturalitytext1_fi</td></tr></table><table $testtablestyle>\n";
	$str .=  "<tr><td $testtdstyle colspan=2>$naturalitytext1<br><i>$naturalitytext1_fi</i></td></tr>\n";

	if ($DEBUGGING) {
	    $str .="<tr><td $testtdstyle colspan=2>$wavfile ></td></tr>";
	}

	$str .= 	"<tr><td $testtdstyle colspan=2><audio src=$wavfile controls width=100></audio></td></tr>\n";
//	$str .=  "<tr><td $testtdstyle>$naturalitytext2</td><td $testtdstyle>$naturalitytext2_fi</td></tr></table><table $testtablestyle>\n";
	$str .=  "<tr><td $testtdstyle colspan=2>$naturalitytext2<br><i>$naturalitytext2_fi</i></td></tr>\n";
	foreach ($naturalitycats as $key => $value) {
	    $str .=  "<tr><td $testtdstyle><input name=\"nattest\" value=\"nat$key\" type=\"radio\"  required=\"required\"> $key.</td><td $testtdstyle> $value </td></tr>\n";
	}
	$str .=  "<tr><td $testtdstyle colspan=2 align=right><input type=\"submit\">  </td></tr>\n";

//    $str .=  "<tr><td $testtdstyle colspan=2>You can comment the test in the text box below:<br><textarea name=\"comments\" cols=\"60\" rows=\"5\"></textarea> </td></tr>\n";

	$str .=  "</table></form>\n";
	return $str;

	
}

function generate_simtest( $reffile, $testfile ) {

    global $testtablestyle, $testtdstyle, $similaritytext1, $similaritytext1_fi,  $similaritytext2, $similaritytext2_fi, $similaritycats, $page, $listener, $nextpage, $testurl, $theme, $categorypage, $categorypagecount, $DEBUGGING;
    
    $str="";
    
    $str .=  "<form name=\"testform\" method=\"post\" action=\"$testurl\"  onsubmit='return valButton(\"simtest\")'>\n";
    $str .=  "<input type=hidden name=\"type\" value=\"sim\">\n";
    $str .=  "<input type=hidden name=\"page\" value=\"$page\">\n";
    $str .=  "<input type=hidden name=\"listener\" value=\"$listener\">\n";
    $str .=  "<input type=hidden name=\"reference\" value=\"$reffile\">\n";
    $str .=  "<input type=hidden name=\"sample\" value=\"$testfile\">\n";

    $str .=  "<table $testtablestyle>\n";
//    $str .= "<tr><td colspan=2><img src=graphic3.png style=align=center></td></tr>\n";

    $str .=  "<tr><th colspan=2 $testtdstyle>$theme, trial ". ceil($categorypage/3)." / ".($categorypagecount/3).", task C </th></tr>\n";

//    $str .=  "<tr><td $testtdstyle>$similaritytext1</td><td $testtdstyle>$similaritytext1_fi</td></tr></table><table $testtablestyle>\n";
    $str .=  "<tr><td $testtdstyle colspan=2>$similaritytext1<br><i>$similaritytext1_fi</i></td></tr></table><table $testtablestyle>\n";
    
    if ($DEBUGGING) {
	$str .="<tr><td $testtdstyle colspan=2>$reffile ></td></tr>";
    }
    $str .= 	"<tr><td $testtdstyle colspan=2>Reference:<br><audio src=$reffile controls width=100></audio></td></tr>\n";
    if ($DEBUGGING) {
	$str .="<tr><td $testtdstyle colspan=2>$testfile ></td></tr>";
    }
    $str .= 	"<tr><td $testtdstyle colspan=2>Test sample:<br><audio src=$testfile controls width=100></audio></td></tr>\n";
//    $str .=  "<tr><td $testtdstyle>$similaritytext2</td><td $testtdstyle>$similaritytext2_fi</td></tr></table><table $testtablestyle>\n";
    $str .=  "<tr><td $testtdstyle>$similaritytext2<br><i>$similaritytext2_fi</i></td></tr></table><table $testtablestyle>\n";

    foreach ($similaritycats as $key => $value) {
	$str .=  "<tr><td $testtdstyle><input name=\"simtest\" value=\"sim$key\" type=\"radio\"  required=\"required\"> $key.</td><td $testtdstyle> $value </td></tr>\n";
    }
    $str .=  "<tr><td $testtdstyle colspan=2 align=right><input type=\"submit\">  </td></tr>\n";
//    $str .=  "<tr><td $testtdstyle colspan=2>You can comment the test in the text box below:<br><textarea name=\"comments\" cols=\"60\" rows=\"5\"></textarea> </td></tr>\n";

    $str .=  "</table></form>\n";	
    return $str;


}

function generate_preftest( $reffile, $testfileA, $testfileB) {

    global $testtablestyle, $testtdstyle, $preferencetext1, $preferencetext1_fi,  $preferencetext2, $preferencetext2_fi, $prefcats, $page, $listener, $nextpage, $testurl, $theme, $categorypage, $categorypagecount, $DEBUGGING;
    
    $tablew="635";
    $questionw="300";
    $fillerw="120";
    $playerw="200";
    

    $str="";
    
    $str .=  "<form name=\"testform\" method=\"post\" action=\"$testurl\" onsubmit='return valButton(\"preftest\")'>\n";
    $str .=  "<input type=hidden name=\"type\" value=\"prf\">\n";
    $str .=  "<input type=hidden name=\"page\" value=\"$page\">\n";
    $str .=  "<input type=hidden name=\"listener\" value=\"$listener\">\n";
    $str .=  "<input type=hidden name=\"reference\" value=\"$reffile\">\n";
    $str .=  "<input type=hidden name=sampleA value=\"$testfileA\">\n";
    $str .=  "<input type=hidden name=sampleB value=\"$testfileB\">\n";
    $str .=  "<table $testtablestyle >\n";
//    $str .= "<tr><td colspan=2><img src=graphic3.png style=align=center></td></tr>\n";

    $str .=  "<tr><th colspan=4 $testtdstyle>$theme, trial $categorypage / $categorypagecount </th></tr>\n";
//    $str .=  "<tr><td $testtdstyle width=50%>$preferencetext1</td><td $testtdstyle width=50%>$preferencetext1_fi</td></tr></table><table $testtablestyle>\n";
    $str .=  "<tr><td $testtdstyle colspan=4 width=$questionw>$preferencetext1<br><i>$preferencetext1_fi</i></td></tr></table><table align=center cellpadding=5 cellspacing=2 width=$tablew>\n";

    if ($DEBUGGING) {
	$str .="<tr><td $testtdstyle colspan=4>$reffile ></td></tr>";
    }
    
    $str .= 	"<tr><td width=$fillerw $testtdstyle></td><td $testtdstyle colspan=2 width=$questionw>Reference:<br><audio src=$reffile controls width=$playerw></audio></td><td width=$fillerw $testtdstyle></td></tr>\n";

    if ($DEBUGGING) {
	$str .="<tr><td $testtdstyle colspan=4>$testfileA ></td></tr>";
    }
    
    $str .= 	"<tr><td $testtdstyle colspan=2 width=$questionw>Test sample A:<br><audio src=$testfileA controls width=$playerw></audio></td>\n";

    if ($DEBUGGING) {
	$str .="<tr><td $testtdstyle colspan=2>$testfileB ></td></tr>";
    }

    $str .= 	"<td $testtdstyle colspan=2 width=$questionw>Test sample B:<br><audio src=$testfileB controls width=$playerw></audio></td></tr>\n";
    
//    $str .=  "<tr><td $testtdstyle width=50%>$preferencetext2</td><td $testtdstyle width=50%>$preferencetext2_fi</td></tr></table><table $testtablestyle>\n";
    $str .=  "<tr><td $testtdstyle colspan=4 width=$questionw>$preferencetext2<br><i>$preferencetext2_fi</i></td></tr>\n";

//    foreach ($prefcats as $key => $value) {
    $keys=array_keys($prefcats);
    $key=$keys[0];
    $value=$prefcats[$key];

    $str .=  "<tr><td $testtdstyle colspan=2><table><tr><td><input name=\"preftest\" value=\"$key\" type=\"radio\" required=\"required\" ></td><td>$value </td></tr></table></td>\n";

    $key=$keys[1];
    $value=$prefcats[$key];
    $str .=  "<td $testtdstyle colspan=2><table><tr><td><input name=\"preftest\" value=\"$key\" type=\"radio\" required=\"required\" ></td><td $testtdstyle>$value </td></tr></table></td></tr>\n";

    $key=$keys[2];
    $value=$prefcats[$key];
    $str .=  "<td  width=$fillerw $testtdstyle></td><td $testtdstyle colspan=2><table width=$questionw><tr><td><input name=\"preftest\" value=\"$key\" type=\"radio\" required=\"required\" ></td><td>$value </td><tr></table></td><td width=$fillerw  $testtdstyle></td>\n";

    $str .=  "<tr><td $testtdstyle colspan=4 align=right><input type=\"submit\">  </td></tr>\n";

//    $str .=  "<tr><td $testtdstyle colspan=2>You can comment the test in the text box below:<br><textarea name=\"comments\" cols=\"60\" rows=\"5\"></textarea> </td></tr>\n";

    $str .=  "</table></form>\n";
    
    return $str;
}

/* tweaked from http://www.php.net/manual/en/function.shuffle.php#105931 */
/* $seed variable is optional */
function SEOshuffle(&$items, $seed=false) {
  $original = md5(serialize($items));
  //mt_srand(crc32(($seed) ? $seed : $items[0]));
  for ($i = count($items) - 1; $i > 0; $i--){
      $j = crc32(($seed+$i)) % $i; //@mt_rand(0, $i);
    list($items[$i], $items[$j]) = array($items[$j], $items[$i]);
  }
  if ($original == md5(serialize($items))) {
    list($items[count($items) - 1], $items[0]) = array($items[0], $items[count($items) - 1]);
  }
}



?>
