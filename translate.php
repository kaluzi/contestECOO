<?php
$level=0;//in global, in procedure, or in loop or if...
$inglobal=true; //currently in global part of program
$procedures=array();//list of all procedures/functions created
$variables=array(array(array()));//list of all variables created

function translate($source, $progname)
{
	global $level;
	global $inglobal; 
	global $procedures;
	global $variables;

	$output="";//string containing translated result to be outputed
	$s=explode("\n",$source);
	$ns=array();
	$firstlabel=true; //is start of switch/case statement
	//$c="";
	$incase=false; //is inside switch/case statement
	/*
	level:	variable: 	name:
						type:
						active/inactive
			variable: 	name:
						type:
						active/inactive
	...		...
	*/
	$globalprog="";//parts of program not in main or procedure
	$intype=false;
	$typeprog="";//parts of program not in main class
	$onlymain=true;//when there are no procedures
	$onlyprog="";//program when there are no procedures

	for($i=0;$i<10;$i++)//initialize $variable[][][]
	{
		for($j=0;$j<10;$j++)
			for($k=0;$k<10;$k++)
				$variables[$i][$j][$k]="--";
	}
	$trtable=file_get_contents("tr.txt");//get table of built-in functions
	$trtable=explode("\n",$trtable);

	//-----------------------translating--------------------------------------------------------------------
	for($i=0;$i<sizeof($s);$i++)//looks at program line by line
	{
		if(!$inglobal&&$level==0)
			$level=1;
			
		$ns[$i]=$s[$i];
		$ln="";
		
		$ns[$i]=str_replace ( "PHP_EOL" , "" , $ns[$i]);
		$ns[$i]=str_replace ( "\r" , "" , $ns[$i]);
		$ns[$i]=str_replace ( "\n" , "" , $ns[$i]);
		
		//does not translate comments
		$comment="";
		if(preg_match("/%/", $ns[$i])==1)
		{
			$tt=explode("%",$ns[$i]);
			//var_dump($ln);
			$comment=$tt[1];
			if($comment!="")
				$comment="//".$comment."\r\n";
			$ns[$i]=$tt[0];
		}

		
		$str=explode("\"", $ns[$i]);
		//"remove all quoted strings;
		for($j=0; $j<sizeof($str);$j++)
		{
			if($j%2==0)
				$ln=$ln.$str[$j];
			else
				$ln=$ln." @@@@".$j; //replaces string with @@@@1 to be replaced with corresponding string in line 273
		}
		

		if(preg_match("/end record/", $ln)==1)//marks end of class members
		{
			$intype=false;
			$typeprog=$typeprog."}\r\n";
			$ln="";
		}
		if(preg_match("/end case/", $ln)==1)//marks end of switch/case statement
		{
			$incase=false;
			$firstlabel=true;
		}

		if(preg_match("/end/", $ln)==1)
		{
			$t=explode("end", $ln);
			$ns[$i]=$t[0]."\r\n}\r\n";
			//sets all variables in this level and above as inactive
			for($h=$level; $h<10;$h++)
				for($j=0;$j<sizeof($variables[$h]);$j++)
				{
					$variables[$h][$j][2]="inactive";
				}
			//sets all variables below this level as active
			for($j=0;$j<sizeof($variables[$level]);$j++)
			{
				for($k=0;$k<$level;$k++)
				{
					for($l=0;$l<sizeof($variables[$k]);$l++)
					{
						if($variables[$level][$j][0]==$variables[$k][$l][0] && $variables[$k][$l][2]=="active")
						{
							$variables[$level][$j][2]="active";
							break;
						}
					}
				}
			}
			//goes down a level
			$level--;
			$onlyprog=$onlyprog.$ns[$i]."\r\n";
			continue;
		}

		if(preg_match("/for/", $ln)==1)
		{
			$level++;		//goes up a level
			$ns[$i]=changefor($ln);		//line 468
		}
		
		if(preg_match("/exit when/", $ln)==1)
		{
			$ns[$i]=changeexitwhen($ln);//line 531
		}
		if(preg_match("/loop/", $ln)==1)
		{
			$level++;
			$tabloop=explode("loop",$ln);
			$ns[$i]=$tabloop[0]."while(true)\r\n".$tabloop[0]."{";
		}

		if(preg_match("/if/", $ln)==1)
		{
			$level++;
			$ns[$i]=changeif($ln);//line 537
		}	
		if(preg_match("/else/", $ln)==1)
		{
			$ns[$i]="}\r\n".$ns[$i]."\r\n{";
		}
		if(preg_match("/then/", $ns[$i])==1)
		{
			$ns[$i]=changethen($ln);//line 549
		}	

		if(preg_match("/var /", $ln)==1 ||preg_match("/const /", $ln)==1)
		{
			$ns[$i]=changevar($ln);//line 555
		}	
		if(preg_match("/procedure/", $ln)==1)
		{
			$level=1;
			$inglobal=false;
			$onlymain=false;
			$ns[$i]=changeprocedure($ln);//line 627
		
		}	
		if(preg_match("/function/", $ln)==1)
		{
			$level=1;
			$inglobal=false;
			$onlymain=false;
			$ns[$i]=changefunction($ln);//line 674
		}	
		if(preg_match("/open/", $ln)==1)
		{
			$ns[$i]=changeopen($ln);//line 727
		}	
		if(preg_match("/get( )?\:/", $ln)==1)
		{
			$ns[$i]=changeget($ln);//line 733
		}	
		if(preg_match("/put/", $ln)==1)
		{
			$ns[$i]=changeput($ln);//line 777
		}	
		if(preg_match("/case/", $ln)==1)
		{
			$ns[$i]=changecase($ln);//line 804
			$incase=true;
			$level++;
		}	
		if(preg_match("/label/", $ln)==1) //for switch/case statements
		{
			$tab=explode("label",$ln);
			$label=explode("label",$ln);
			$label=explode(":",$label[1]);
			$label=trim($label[0]);
			if($label=="")
				$ln= preg_replace("/label/", "default",$ln);
			else
				$ln= preg_replace("/label/", "case",$ln);
			if(!$firstlabel)
				$ns[$i]=$tab[0].$tab[0]."break;\r\n".$ln;
			else
				$ns[$i]=$ln;
			$firstlabel=false;
		}

		if(preg_match("/type/", $ln)==1)
		{
			$intype=true;
			$ns[$i]=changetype($ln);//line 814
			if(preg_match("/\:/", $ln)==1)
				$intype=false;
		}	
		if(preg_match("/\:/", $ns[$i])==1 && $intype)
		{
			$ns[$i]=changerecord($ns[$i]);//line 820
		}

		if(preg_match("/assert/", $ln)==1)
		{
			if(preg_match("/\bf\b/", $ln)==1)
				$ns[$i]="";
			else
			{
				$condition=explode("assert",$ln);
				$ns[$i]=$condition[0]."if(!".$condition[1].") break";
			}
		}
		
		for($j=1;$j<sizeof($variables[9]);$j++) //changes all array(i) to array[i]
		{
			$ar=explode(" ",$variables[9][$j][1]);
			if(preg_match("/\b".trim($variables[9][$j][0])."\b/", $ns[$i])==1 && $ar[0]=="array")
			{
				$st=str_split($ns[$i]); //char array of line
				preg_match("/\b".trim($variables[9][$j][0])."\b/",$ns[$i], $tempid, PREG_OFFSET_CAPTURE);
				$id=$tempid[0][1];//index of array name
				$arlev=0; //which level of array currently in for array1[array2[i]]
				$started=false; //found 1st [
				for($k=$id;$k<sizeof($st);$k++)
				{
					if($st[$k]=='(')
					{
						$arlev++;
						$st[$k]='[';
						$started=true;
					}
					if($st[$k]==')')
						$arlev--;
					if($arlev==0&&$started) //found closing bracket
					{
						$st[$k]=']';
						break;
					}
				}
				$ns[$i]=implode("",$st);
				
			}
		}
		//var_dump($variables[9]);
		
		//adds ;
		$tempns=trim($ns[$i]);
		//if not "", last char not ; or ) or { or } or / or : 
		if(trim($ns[$i])!="" &&substr($tempns, sizeof($tempns)-2)!=';' && substr($tempns, sizeof($tempns)-2)!='public' && preg_match("/{/", $ns[$i])==0 && preg_match("/\//", $ns[$i])==0 && preg_match("/}/", $ns[$i])==0 && substr($tempns, sizeof($tempns)-2)!=':')
			$ns[$i]=$ns[$i].';';
		//if line is calling procedure
		for($j=0; $j<sizeof($procedures);$j++)
		{
			if(preg_match("/".trim($procedures[$j])."/",$ns[$i]) && preg_match("/{/", $ns[$i])==0)
			{
				$ns[$i]=$ns[$i].';';
				break;
			}
		}
		
		
		$ns[$i]=replacing($ns[$i]);//line 399
		
		//insert all quoted strings
		for($j=0; $j<sizeof($str);$j++)
		{
			if($j%2==1)
			{
				$ns[$i]=preg_replace("/ @@@@".$j."/", "\"".$str[$j]."\"", $ns[$i]); //replace @@@@index with str[index]
			}
		}

		//writes all class member part separately
		if($intype)
		{
			$typeprog=$typeprog.$ns[$i]."\r\n";
			$ns[$i]="";
		}
		
		if($onlymain &&!$intype)
			$onlyprog=$onlyprog.$ns[$i]."\r\n";
			
		//writes all global part separately	and add static to global variables
		if($inglobal &&!$intype)
		{
			for($j=1;$j<sizeof($variables[0]);$j++)
			{
				if(preg_match("/".trim($variables[0][$j][0])."/",$ns[$i])==1 &&$ns[$i]!=""&&$ns[$i]!=" ")
				{
					$ns[$i]=trim($ns[$i]);
					$ns[$i]="static ".$ns[$i];
					break;
				}
			}
			if($ns[$i]!="")
			{
			$globalprog=$globalprog.$ns[$i]."\r\n";
			$ns[$i]="";
			}
		}
		
		if(!preg_match("/\r\n/", $ns[$i]))
		{
			$ns[$i]=$ns[$i]."\r\n";
		}
		
		//insert comments
		$ns[$i]=$ns[$i].$comment;
		
	}

	//--------------------------outputing---------------------------------------------------------------
			
	if(!$inglobal)//not a procedureless program
	{		
		$mainprog=array();
		$inmain=true;
		//puts lines starting from end of program to end of last procedure in main
		for($i=sizeof($ns)-1;$i>=0;$i--)
		{
			for($j=0;$j<sizeof($procedures);$j++)
				if(preg_match("/end ".trim($procedures[$j])."/", $s[$i])==1)
					$inmain=false;
			if(!$inmain)
				break;
			$mainprog[sizeof($mainprog)]=$ns[$i];
				$ns[$i]="";	
		}
		//adds () to all procedures called without parameters
		for($i=0;$i<sizeof($mainprog);$i++)
		{
			for($j=0;$j<sizeof($procedures);$j++)
			{
				if(preg_match("/\(*\)/", $mainprog[$i])==0 && preg_match("/".trim($procedures[$j])."/", $mainprog[$i])==1)
				{
					$mainprog[$i]=explode(";",$mainprog[$i]);
					$mainprog[$i]=$mainprog[$i][0]."();";
				}
			}
		}
		for($i=0;$i<sizeof($ns);$i++)
		{
			for($j=0;$j<sizeof($procedures);$j++)
			{
				if(preg_match("/\(*\)/", $ns[$i])==0 && preg_match("/".trim($procedures[$j])."/", $ns[$i])==1)
				{
					$ns[$i]=$ns[$i]."()";
				}
			}
		}

		$including="import java.io.*;\r\nimport java.util.*;\r\n";
		//fwrite($file,$including);
		$output=$output.$including;
		//fwrite($file,$typeprog);
		$output=$output.$typeprog;
		//fwrite($file,"public class ".$progname."\r\n{\r\n");
		$output=$output."public class ".$progname."\r\n{";//\r\n";
		//fwrite($file,$globalprog);
		$output=$output.$globalprog;
		$startmain="public static void main(String args[]) throws IOException\r\n{\r\n";
		//fwrite($file,$startmain);
		$output=$output.$startmain;
		//fwrite($file,initarrays());//line 383 writes array=new int[2], etc. for global arrays
		$output=$output.initarrays();
		for($i=sizeof($mainprog)-1;$i>=0;$i--)
		{
		  if($mainprog[$i]!="")
				//fwrite($file,"\t".$mainprog[$i]."\r\n"); //writes lines of main procedure
				$output=$output."\t".$mainprog[$i];//."\r\n";
		}
		//fwrite($file,"}\r\n");
		$output=$output."}\r\n";

		for($i=0;$i<sizeof($ns);$i++)
		{
			if($ns[$i]!="")
				//fwrite($file,$ns[$i]."\r\n");//writes lines of user-defined procedures
				$output=$output.$ns[$i];//."\r\n";
		}
	}
	else //simple program without procedures
	{
		$including="import java.io.*;\r\nimport java.util.*;\r\n";
		//fwrite($file,$including);
		$output=$output.$including;
		//fwrite($file,$typeprog);
		$output=$output.$typeprog;
		//fwrite($file,"public class ".$progname."\r\n{\r\n");
		$output=$output."public class ".$progname."\r\n{\r\n";
		$startmain="public static void main(String args[]) throws IOException\r\n{\r\n";
		//fwrite($file,$startmain);
		$output=$output.$startmain;
		//fwrite($file,$onlyprog);
		$output=$output.$onlyprog;
		//fwrite($file,"}\r\n");
		$output=$output."}\r\n";
	}
	//fwrite($file,"}");//close the main class
	$output=$output."}";
	//$output=strip_tags ( $output);
	$output=str_replace ( ";;" , ";" , $output);
	return $output;
}


function initarrays()//writes array=new int[2], etc. for global arrays
{
	global $variables;
	$nln="";
	for($i=1;$i<sizeof($variables[0]);$i++)
	{
		if(preg_match("/array/", $variables[0][$i][1])==1)
		{
			$t=explode("array",$variables[0][$i][1]);
			$nln=$nln.$variables[0][$i][0]."=".$t[1].";\r\n";
		}
	}
	return $nln;
}

function replacing($ln)
{
	global $trtable;
	
	if(preg_match("/\&/",$ln))
	{
		$ln=preg_replace("/\&/","&&",$ln);
	}
	if(preg_match("/\band\b/",$ln))
	{
		$ln=preg_replace("/\band\b/","&&",$ln);
	}
	if(preg_match("/\|/",$ln))
	{
		$ln=preg_replace("/\|/","||",$ln);
	}
	if(preg_match("/\bor\b/",$ln))
	{
		$ln=preg_replace("/\bor\b/","||",$ln);
	}
	if(preg_match("/\~/",$ln))
	{
		$ln=preg_replace("/\~/","!",$ln);
	}
	if(preg_match("/\bnot\b/",$ln))
	{
		$ln=preg_replace("/\bnot\b/","!",$ln);
	}
	if(preg_match("/\brem\b/",$ln))
	{
		$ln=preg_replace("/\brem\b/","%",$ln);
	}
	if(preg_match("/\bmod\b/",$ln))
	{
		$ln=preg_replace("/\bmod\b/","%",$ln);
	}

	if(preg_match("/\bdiv\b/",$ln))
	{
		$ln=preg_replace("/\bdiv\b/","/",$ln);
	}
	if(preg_match("/=/",$ln) && !preg_match("/\:=/",$ln) && !preg_match("/\!=/",$ln)&& !preg_match("/\+=/",$ln)&& !preg_match("/\-=/",$ln)&& !preg_match("/\*=/",$ln)&& !preg_match("/\/=/",$ln))
	{
		$ln=preg_replace("/=/","==",$ln);
	}
	if(preg_match("/:=/",$ln))
	{
		$ln=preg_replace("/:=/","=",$ln);
	}
	if(preg_match("/\bresult\b/",$ln))
	{
		$ln=preg_replace("/\bresult\b/","return",$ln);
	}
	if(preg_match("/\bstring\b/",$ln))
	{
		$ln=preg_replace("/\bstring\b/","String",$ln);
	}
	if(preg_match("/\brecord\b/",$ln))
	{
		$ln="";
	}
	for($i=0;$i<sizeof($trtable);$i++) //replaces everything using tr.txt
	{
		$tr=explode("\t",$trtable[$i]);
		if(preg_match("/\b".$tr[0]."\b/",$ln))
		{
			$ln=preg_replace("/\b".$tr[0]."\b/",$tr[1],$ln);;
		}
	}
	return $ln;
}



function changefor($ln)
{
	global $level;
	global $variables;
	$dec=false;
	if(preg_match("/decreasing/", $ln)==1)
	{
	  $dec=true;
	}
	$tab=explode("for",$ln);
	//finds counter variable
	$var=explode("for",$ln);
	$var=explode(":",$var[1]);
	$sign="<";
	if($dec)
	{
	  $var=explode("decreasing",$var[0]);
	  $var[0]=$var[1];
	  $sign=">";
	}
	//adds counter variable to variables array
	for($i=$level;$i<10;$i++)
		{
			$n=sizeof($variables[$i]);
			$variables[$i][$n][0]=$var[0];
			$variables[$i][$n][1]="int";
			$variables[$i][$n][2]="active";
		}
	//find starting value
	$start=explode(":",$ln);
	$start=explode("..",$start[1]);
	//find ending value
	$end=explode("..",$ln);
	$end=explode("by",$end[1]);
	//increase/decrease by number greater than 1
	$inc="";
	if(preg_match("/\bby\b/", $ln)==1)
	{
	  $inc=explode("by",$ln);
	  if($dec)
	  {
	    $inc="-=".$inc[1];
	  }
	  else
	  {
	    $inc="+=".$inc[1];
	  }
	}
	//increase/decrease by 1
	else
	{
	  if($dec)
	  {
	    $inc="--";
	  }
	  else
	  {
	    $inc="++";
	  }
	}
	if(preg_match("/\)\r\n/", $ln))
		$nln=$tab[0]."for( int".$var[0].":=".$start[0].";".$var[0].$sign.$end[0].";".$var[0].$inc.")".$tab[0]."{";
	else
		$nln=$tab[0]."for( int".$var[0].":=".$start[0].";".$var[0].$sign.$end[0].";".$var[0].$inc.")\r\n".$tab[0]."{\r\n";
		//echo $nln;
	return $nln;
}

function changeexitwhen($ln)// changes exit when condition to if(condition) break, for while loops
{
	$nln=preg_replace("/exit when/","if( ",$ln);
	$nln=$nln." ) break";
	return $nln;
}
function changeif($ln)
{
	$tab=explode("if",$ln);
	//find condition statement
	$state=explode("if",$ln);
	$state=explode("then",$state[1]);
	$nln=$tab[0]."if (".$state[0].")";
	//see if then is on same line, if not, change it in next function
	if(preg_match("/then/", $ln)==1)
	{
		$nln=$nln."\r\n".$tab[0]."{";
	}	
	return $nln."\r\n";
}
function changethen($ln)
{
	$tab=explode("then",$ln);
	$nln=preg_replace("/then/"," )\r\n".$tab[0]."{",$ln);
	return $nln;
}
function changevar($ln)
{
	global $variables; //array of all variables declared
	global $inglobal;
	global $level;//current level
	//see if it is var or const
	$final=explode("var",$ln);
	$final=$final[0];
	$varconst="var";
	if(preg_match("/const/", $ln)==1)
	{
		$final=explode("const",$ln);
		$final=$final[0]."final ";
		$varconst="const";
	}
	//see if it's array
	$isarray=false;
	$var=explode($varconst,$ln);
	$var=explode(":", $var[1]);
	$type=array();
	if(preg_match("/array/", $ln)==1)
	{
	  $isarray=true;
	}
	
	if(!$isarray)
	{
	  $type=explode(":",$ln);
	  //var_dump($type);
	  //echo $type[1];
	  $ln=preg_replace("/:*".$type[1]."/","",$ln);
	  $ln=preg_replace("/".$varconst."/",trim($type[1]),trim($ln));
	  $ln=$final.$ln;
	}
	else
	{
		$type=explode("of",$ln);
		$t=trim($type[1]);
		//finds # of dimensions and range
		$elements=explode("array",$ln);
		$elements=explode("of",$elements[1]);
		$elements=explode(",",$elements[0]);
		
		//creates line used to initialize array
		$t2=$t;
		$init="";
		for($i=0;$i<sizeof($elements);$i++)
			$t=$t."[]";
		$ln=explode(":",$ln);
		$ln=$ln[0];
		$ln=preg_replace("/var/",$t,$ln);
		
		for($i=0;$i<sizeof($elements);$i++)
		{
			$e=$elements[$i];
			$e=explode("..",$e);
			$init=$init."[".$e[1]."]";
		}
		if(!$inglobal)
		{
			$ln=$ln." := new ".$t2.$init;
		}
		$type[1]="array new ".$type[1].$init; // variables[level][var number][1]=array new int[2][2], etc.
	}
	//what to do with array??
	//what to do with global static variables??
		//var_dump($variables);
	$tempvar=explode(",", $var[0]); //array of all variables created in this line
	for($h=0;$h<sizeof($tempvar);$h++)//writes all variables created into variables array
		for($i=$level;$i<10;$i++)//writes variable in all variables above current level
		{
			$n=sizeof($variables[$i]);
			$variables[$i][$n][0]=$tempvar[$h];
			$variables[$i][$n][1]=$type[1];
			$variables[$i][$n][2]="active";
		}
	return $ln;
}
function changeprocedure($ln)
{
	global $procedures;
	global $level;
	global $variables;
	$nln="";
	$tab=explode("procedure",$ln);
	//finds name of procedure
	$name=explode("procedure",$ln);
	$name=explode("(",$name[1]);
	$procedures[sizeof($procedures)]=$name[0];//writes name into procedures array
	//get parameters
	$parameter=explode("(",$ln);
	$nln=$tab[0]."public static void ".$name[0]."( ";
	if(sizeof($parameter)>1)//if has variables in parameter, aka has ( after name
	{
		$parameter=explode(")",$parameter[1]);
		$t=explode(':',$parameter[0]);
		//get array of variable names and types from parameter (a,b,c: int, d,e,f: string)
		$var=array(array());
		$type=array();
		$var[0]=explode(',',$t[0]);//list of variables
		for($i=1;$i<sizeof($t); $i++)//get list of variables from each type
		{
			$temp=explode(',',$t[$i]);
			$type[$i-1]=$temp[0];
			for($j=0;$j<sizeof($temp)-1;$j++)
				$var[$i][$j]=$temp[$j+1];		
		}	
		//write into variables array
		for($i=0;$i<sizeof($type);$i++)
		{
			for($j=0;$j<sizeof($var[$i]);$j++)
			{
				$nln=$nln.$type[$i]." ".$var[$i][$j].", ";
				for($k=$level;$k<10;$k++)
				{
					$n=sizeof($variables[$k]);
					$variables[$k][$n][0]=$var[$i][$j];
					$variables[$k][$n][1]=$type[$i];
					$variables[$k][$n][2]="active";
				}

			}
		}
		$nln=substr($nln,0,strrpos ( $nln , ',' ));
	}
	if(preg_match("/\)\r\n/", $ln))
		$nln=$nln." )".$tab[0]."{";
	else
		$nln="\r\n".$nln." )\r\n".$tab[0]."{";
	return $nln;
}
function changefunction($ln)
{
	global $procedures;
	global $level;
	global $variables;
	$nln="";
	$tab=explode("function",$ln);
	$name=explode("function",$ln);
	$name=explode("(",$name[1]);
	$functtype=explode(":",$ln);
	$functtype=$functtype[sizeof($functtype)-1];
	$procedures[sizeof($procedures)]=$name[0];
	//what to do with variables??
	$parameter=explode("(",$ln);
	$nln=$tab[0]."public static ".$functtype.$name[0]."( ";

	if(sizeof($parameter)>1)
	{
		$parameter=explode(")",$parameter[1]);
		$t=explode(':',$parameter[0]);
		$var=array(array());
		$type=array();
		$var[0]=explode(',',$t[0]);
		for($i=1;$i<sizeof($t); $i++)
		{
			$temp=explode(',',$t[$i]);
			$type[$i-1]=$temp[0];
			for($j=0;$j<sizeof($temp)-1;$j++)
				$var[$i][$j]=$temp[$j+1];		
		}
		
		for($i=0;$i<sizeof($type);$i++)
		{
			for($j=0;$j<sizeof($var[$i]);$j++)
			{
				$nln=$nln.$type[$i]." ".$var[$i][$j].", ";
				for($k=$level;$k<10;$k++)
				{
					$n=sizeof($variables[$k]);
					$variables[$k][$n][0]=$var[$i][$j];
					$variables[$k][$n][1]=$type[$i];
					$variables[$k][$n][2]="active";
				}

			}
		}
		$nln=substr($nln,0,strrpos ( $nln , ',' ));
	}
	if(preg_match("/\)\r\n/", $ln))
		$nln=$nln." )".$tab[0]."{";
	else
		$nln="\r\n".$nln." )\r\n".$tab[0]."{";
	
	//$nln=$tab[0]."public static ".$type.$name[0];
	return $nln;
}
function changeopen($ln)
{
	$datafile=explode(',',$ln);
	$nln="Scanner in:= new Scanner( new FileInputStream( ".$datafile[1]." ));";
	return $nln;	
}
function changeget($ln)
{
	$tab=explode("get",$ln);
	global $variables;
	global $level;
	$nln="";
	//finds variables to read into
	$var=explode(':',$ln);
	$var=explode(',',$var[1]);
	//determine nextLine() or next()
	$read="";
	if(preg_match("/\*/", $ln)==1)
		$read="Line";
	//looks at all variables to be read into
	for($i=1;$i<sizeof($var);$i++)
	{
		$ind=0;
		//find index of variable in variables array
		for($j=1;$j<sizeof($variables[$level]);$j++)
		{
			$vtemp=explode("(",$var[$i]);
			$vtemp=$vtemp[0];
			if(trim($variables[$level][$j][0])==trim($vtemp) && $variables[$level][$j][2]=="active")
			{
				$ind=$j;
				break;
			}
		}
		if($ind==0) //variable not found in array
		{
			$nln=$nln."//undefined variable: ".$var[$i];
			continue;
		}
		//determine next(), nextInt etc.
		if(preg_match("/int/",$variables[$level][$ind][1])==1)
			$read="Int";
		if(preg_match("/real/",$variables[$level][$ind][1])==1)
			$read="Double";
		if(preg_match("/boolean/",$variables[$level][$ind][1])==1)
			$read="Boolean";
		$rn="";
		if($i!=sizeof($var)-1)//not last variable
			$rn="\r\n";
		$nln=$nln.$tab[0].$var[$i]." := in.next".$read."();".$rn;
	}
	return $nln;
}
function changeput($ln)
{
	$println="ln";
	if(preg_match("/\.\./", $ln)==1)//System.out.print instead of println
	{
		$println="";
		$ln=explode("..",$ln);
		$ln=$ln[0];
	}
	$nln=preg_replace("/put/", "System.out.print".$println."( ",$ln);
	//put "abc":10, "def":20
	//adds extra spaces
	$space=explode(':',$nln);
	for($i=1;$i<sizeof($space);$i++)
	{
		$ns=explode(",",$space[$i]);
		$ns=$ns[0];
		$sp="";
		for($j=0;$j<$ns;$j++)
		{
			$sp=$sp." ";
		}
		$nln=preg_replace("/:".$ns."/", "+\"".$sp."\"",$nln);

	}
	$nln=preg_replace("/,/", "+",$nln);
	$nln=$nln. " );";
	return $nln;
}
function changecase($ln)
{
	$tab=explode("case",$ln);
	$nln="";
	$var=explode("case",$ln);
	$var=explode("of",$var[1]);
	$var=$var[0];
	$nln=$tab[0]."switch( ".$var." )\r\n".$tab[0]."{";
	return $nln;
}
function changetype($ln)
{
	$ln=preg_replace("/type/", "public class",$ln)."{";
	$ln=preg_replace("/\:/", "",$ln);
	return $ln;
}
function changerecord($ln)
{
/*
type triple :
    record
        x, y, z : int
    end record
*/
	$var=explode(":", $ln);
	$type=$var[1];
	$v=trim($var[0]);
	$ln=preg_replace("/\:*".$type."/","",$ln);
	$ln="\t".$type." ".$v;
	return $ln;
}
?>
