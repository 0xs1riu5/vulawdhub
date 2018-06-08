<?php
define('PHP_XLS','1.1');

class PHP_XLS
{
var $sheet;              /*current sheet number*/
var $n;                  /*current object number*/
var $buffer;             /*buffer holding in-memory XLS*/
var $sheets;             /*array containing pages*/
var $styles;             /*array containing cell styles (formatings)*/
var $CurrentStyle;       /*current cell style*/
var $state;              /*current document state  0=new  1=open 2=editing worksheet 3=closed*/

var $company;            /*subject*/
var $author;             /*author*/

function PHP_XLS()
{
	$this->n=0;
	$this->buffer='';
	$this->sheets=array();
	$this->styles=array();
	$this->state=0;
	$this->FillColor='#FFFFFF';
	$this->TextColor='#000000';
	$this->author='Anonimous';
	$this->company='Dumy Corp.';
	$this->CurrentStyle=0;     /*means default style*/
}


function SetAuthor($author)
{
	$this->author=$author;
}

function SetCompany($company)
{
	$this->company=$company;
}

function Open(){
	$this->state=1;
}

function NewStyle($name)
{
	if (!$this->styles[$name])
	{
		$this->styles[$name]=array('uses=>0');
		$this->CurrentStyle=$name;
		return 1;
	}
	else
		return 0;
}

function CopyStyle($source,$destination)
{
	if (isset($this->styles[$source]))
	{
		$this->styles[$destination]=$this->styles[$source];
		$this->styles[$destination]['uses']=0;
		$this->CurrentStyle=$destination;
		return 1;
	}
	else
		return 0;
}

function SetActiveStyle($style)
{
	if (isset($this->styles[$style]))
	{
		$this->CurrentStyle=$style;
		return 1;
	}
	else
		return 0;
}


function StyleSetBackground($color='#FFFFFF', $pattern='Solid')
{
	$this->styles[$this->CurrentStyle]['Interior']=array('Color'=>$color, 'Pattern'=>$pattern);
}

function StyleSetFont($fontname=0, $size=0, $color=0, $bold=0, $italic=0, $underline=0)
{
	$this->styles[$this->CurrentStyle]['Font']=array();
	if ($fontname)
		$this->styles[$this->CurrentStyle]['Font']['FontName']=$fontname;
	if ($size)
		$this->styles[$this->CurrentStyle]['Font']['Size']=$size;
	if ($color)
		$this->styles[$this->CurrentStyle]['Font']['Color']=$color;
	if ($bold)
		$this->styles[$this->CurrentStyle]['Font']['Bold']=$bold;
	if ($italic)
		$this->styles[$this->CurrentStyle]['Font']['Italic']=$italic;
	if ($underline==1)
		$this->styles[$this->CurrentStyle]['Font']['Underline']='Single';
	if ($underline==2)
		$this->styles[$this->CurrentStyle]['Font']['Underline']='Double';
}

function StyleSetAlignment($horizontal, $vertical=0)
{
	if (!isset($this->styles[$this->CurrentStyle]['Alignment']))
		$this->styles[$this->CurrentStyle]['Alignment']=array();
	
	if ($horizontal===1)
		$this->styles[$this->CurrentStyle]['Alignment']['Horizontal']='Right';
	else
	if ($horizontal==="Right")
		$this->styles[$this->CurrentStyle]['Alignment']['Horizontal']='Right';
	else
	if ($horizontal===0)
		$this->styles[$this->CurrentStyle]['Alignment']['Horizontal']='Center';
	else
	if ($horizontal==="Center")
		$this->styles[$this->CurrentStyle]['Alignment']['Horizontal']='Center';
	else
		$this->styles[$this->CurrentStyle]['Alignment']['Horizontal']='Left';

	if ($vertical==="Top")
		$this->styles[$this->CurrentStyle]['Alignment']['Vertical']='Top';
	else
	if ($vertical===-1)
		$this->styles[$this->CurrentStyle]['Alignment']['Vertical']='Top';
	else
	if ($vertical===1)
		$this->styles[$this->CurrentStyle]['Alignment']['Vertical']='Bottom';
	else
	if ($vertical==="Bottom")
		$this->styles[$this->CurrentStyle]['Alignment']['Vertical']='Bottom';
	else
		$this->styles[$this->CurrentStyle]['Alignment']['Vertical']='Center';
}

function StyleAddBorder($position, $color='#000000', $thick=1, $style='Continuous')
{
	if (in_array($position,array("Bottom","Left","Right","Top","DiagonalLeft","DiagonalRight"),true))
	{
		if (! isset($this->styles[$this->CurrentStyle]['Borders']))
			$this->styles[$this->CurrentStyle]['Borders']=array();
		$this->styles[$this->CurrentStyle]['Borders'][$position]=array('LineStyle'=>$style, 'Weight'=>$thick, 'Color'=>$color );
	}
}

function AddSheet($name='Sheet')
{
	/*Start a new Worksheet*/
	if($this->state==0)
		$this->Open();

	/*Start new page*/
	$this->_beginsheet($name);
}

function SetColWidth($col, $width)
{
	/*set column Width in pixels*/
	if($this->state!=2)
		$this->AddSheet();
	$this->sheets[$this->n]['col_widths'][$col]=$width*0.75;
	ksort($this->sheets[$this->n]['col_widths']);
}

function SetRowHeight($row, $height)
{
	/*set column Height in pixels*/
	if($this->state!=2)
		$this->AddSheet();
	
	if (isset($this->sheets[$this->n]['rows_data'][$row]))
		$this->sheets[$this->n]['rows_data'][$row]['Height']=$height*0.75;
	else
		$this->sheets[$this->n]['rows_data'][$row]=array('Height'=>($height*0.75));
}

function Cell($row, $col, $data='', $type='String')
{
	if ($row>65535 or $col>255)  /*if cell range overflow return error;*/
		return 1;
		
	if($this->state!=2)
		$this->AddSheet();

	$this->sheets[$this->n]['rows']=max($this->sheets[$this->n]['rows'],$row);
	$this->sheets[$this->n]['cols']=max($this->sheets[$this->n]['cols'],$col);
	
	if (isset($this->sheets[$this->n]['rows_data'][$row]))
	{
		if (isset($this->sheets[$this->n]['rows_data'][$row][$col]))
		{
			$this->sheets[$this->n]['rows_data'][$row][$col]['data']=$data;
			$this->sheets[$this->n]['rows_data'][$row][$col]['type']=$type;
			$this->sheets[$this->n]['rows_data'][$row][$col]['style']=$this->CurrentStyle;
			if ($this->CurrentStyle)
				$this->styles[$this->CurrentStyle]['uses']++;
		}
		else
		{
			$this->sheets[$this->n]['rows_data'][$row][$col]=array('data'=>$data, 'type'=>$type, 'style'=>$this->CurrentStyle);
			if ($this->CurrentStyle)
				$this->styles[$this->CurrentStyle]['uses']++;
		}
	}
	else
	{
		$this->sheets[$this->n]['rows_data'][$row]=array($col=> array('data'=>$data, 'type'=>$type, 'style'=>$this->CurrentStyle));
		if ($this->CurrentStyle)
			$this->styles[$this->CurrentStyle]['uses']++;
	}
	return 0;
}

function Text($row, $col, $txt)
{
	
	return $this->Cell($row, $col, iconv("GBK","UTF-8",$txt), 'String');
}

function Textc($row, $col, $txt)
{
	return $this->Cell($row, $col, $txt, 'String');
}

function Number($row, $col, $num)
{
	return $this->Cell($row, $col, $num, 'Number');
}

function Close()
{
	if($this->state==3)
		return;
	if($this->sheet==0)
		$this->AddSheet();
	$this->_endsheet();
	$this->_parsedoc();
}

function Output($name='')
{
	if($this->state<3)
		$this->Close();
	if($name=='')
		{
			$name='Book1.xls';
		}
	if(ob_get_length())
	$this->Error('Data has already been sent, can\'t send XLS file');	
	ob_clean();
	ob_start();
	header('Content-Type: application/x-download');
	if(headers_sent())
		$this->Error('Data has already been sent, can\'t send XLS file');
	header('Content-Length: '.strlen($this->buffer));
	/*中文字符集转换*/
	$name=mb_convert_encoding($name,"GBK","auto");
	header('Content-Disposition: attachment; filename="'.$name.'"');
	header('Pragma: public');
	header('Cache-Control: max-age=0, must-revalidate, private');
	echo $this->buffer;
}

function Error($msg)
{
	die('<b>FPDF error:</b> '.$msg);
}


function _beginsheet($name)
{
	$this->sheet++;
	$this->n=$this->sheet;
	$this->sheets[$this->sheet]=array('name'=>"$name", 'cols'=>0, 'rows'=>0, 'rows_data'=>array(), 'col_widths'=>array());
	$this->state=2;
}

function _endsheet()
{
	$this->state=1;
}


function _out($s)
{
	$this->buffer.=$s."\n";
}


function _putworkbook()
{
	$this->_out('<?xml version="1.0"?>');
	$this->_out('<?mso-application progid="Excel.Sheet"?>');
	$this->_out('<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' );
	$this->_out(' xmlns:o="urn:schemas-microsoft-com:office:office"');
	$this->_out(' xmlns:x="urn:schemas-microsoft-com:office:excel"');
	$this->_out(' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"');
	$this->_out(' xmlns:html="http://www.w3.org/TR/REC-html40">');
}

function _putdocprop()
{
	$this->_out(' <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">') ;
	$this->_out("  <Author>$this->author</Author>");
	$this->_out("  <LastAuthor>$this->author</LastAuthor>");
	$this->_out('  <Created>' . date(DATE_ATOM) . '</Created>');
	$this->_out("  <Company>$this->company</Company>");
	$this->_out('  <Version>' . PHP_XLS . '</Version>');
	$this->_out(' </DocumentProperties>');
}

function _putexcelworbook()
{
	$this->_out(' <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">');
	$this->_out('  <WindowHeight>5010</WindowHeight>');
	$this->_out('  <WindowWidth>12300</WindowWidth>');
	$this->_out('  <WindowTopX>120</WindowTopX>');
	$this->_out('  <WindowTopY>60</WindowTopY>');
	$this->_out('  <ProtectStructure>False</ProtectStructure>');
	$this->_out('  <ProtectWindows>False</ProtectWindows>');
	$this->_out(' </ExcelWorkbook>');
}

function _putstyles()
{
	$this->_out(' <Styles>');
	$this->_out('  <Style ss:ID="Default" ss:Name="Normal">');
	$this->_out('   <Alignment ss:Vertical="Bottom"/>');
	$this->_out('   <Borders/>');
	$this->_out('   <Font/>');
	$this->_out('   <Interior/>');
	$this->_out('   <NumberFormat/>');
	$this->_out('   <Protection/>');
	$this->_out('  </Style>');
	
	foreach ($this->styles as $name => $style)
	{
		if ($style['uses'])
		{
			$this->_out('  <Style ss:ID="' . $name . '" ss:Name="' . $name . '">');
			if (isset($style['Interior']))
				$this->_out('   <Interior ss:Color="'.$style['Interior']['Color'].'" ss:Pattern="'.$style['Interior']['Pattern'].'"/>');
			
	  		if (isset($style['Font']))
	  		{
	  			$font='';
	  			if (isset($style['Font']['FontName']))
	  				$font.=' ss:FontName="' . $style['Font']['FontName'] . '"';
	  			if (isset($style['Font']['Size']))
	  				$font.=' ss:Size="' . $style['Font']['Size'] . '"';
	  			if (isset($style['Font']['Color']))
	  				$font.=' ss:Color="' . $style['Font']['Color'] . '"';
	  			if (isset($style['Font']['Bold']) and $style['Font']['Bold']==1)
	  				$font.=' ss:Bold="1"';
	  			if (isset($style['Font']['Italic']) and $style['Font']['Italic']==1)
	  				$font.=' ss:Italic="1"';
	  			if (isset($style['Font']['Underline']))
	  				$font.=' ss:Underline="' . $style['Font']['Underline'] . '"';
				$this->_out('   <Font'.$font.'/>');
	  		}
		
	  		if (isset($style['Alignment']))
				$this->_out('   <Alignment ss:Horizontal="' . $style['Alignment']['Horizontal'] . '" ss:Vertical="' . $style['Alignment']['Vertical'] . '" ss:WrapText="1"/>');
	
	  		if (isset($style['Borders']) and count($style['Borders']))
	  		{
				$this->_out('   <Borders>');
				foreach ($style['Borders'] as $border_name => $border)
					$this->_out('    <Border ss:Position="'.$border_name.'" ss:LineStyle="'.$border['LineStyle'].'" ss:Weight="'.$border['Weight'].'" ss:Color="'.$border['Color'].'"/>');
				$this->_out('   </Borders>');
	  		}
			$this->_out('  </Style>' );
		}
	}
	$this->_out(' </Styles>' );
}


function _putsheet($sheet_no)
{
	$this->_out(' <Worksheet ss:Name="' . $this->sheets[$sheet_no]['name'] . '">');
	
	if ($this->sheets[$sheet_no]['cols'] && $this->sheets[$sheet_no]['rows'])
	{
		$this->_out('  <Table ss:ExpandedColumnCount="' . $this->sheets[$sheet_no]['cols'] . '" ' .
				'ss:ExpandedRowCount="' . $this->sheets[$sheet_no]['rows'] . '" ' .
				'x:FullColumns="1" ' .
				'x:FullRows="1">');

		foreach ($this->sheets[$sheet_no]['col_widths'] as $col => $width) 
		{
   			$this->_out('   <Column ss:Index="' . $col . '" ss:AutoFitWidth="0" ss:Width="' . $width . '"/>');
   		}


		for($r=1;$r<=$this->sheets[$sheet_no]['rows'];$r++)
		if (isset($this->sheets[$sheet_no]['rows_data'][$r]))
		{
			if (isset($this->sheets[$sheet_no]['rows_data'][$r]['Height']))
				$this->_out('   <Row ss:Index="' . $r . '" ss:AutoFitHeight="0" ss:Height="' . $this->sheets[$sheet_no]['rows_data'][$r]['Height'] . '">');
			else
				$this->_out('   <Row ss:Index="' . $r . '">');
			
			for($c=1;$c<=$this->sheets[$sheet_no]['cols'];$c++)
			if (isset($this->sheets[$sheet_no]['rows_data'][$r][$c]))
			{
				if ($this->sheets[$sheet_no]['rows_data'][$r][$c]['style'])
					$this->_out('    <Cell ss:Index="' . $c . '" ss:StyleID="'.$this->sheets[$sheet_no]['rows_data'][$r][$c]['style'].'">' .
							'<Data ss:Type="' . $this->sheets[$sheet_no]['rows_data'][$r][$c]['type'] .
							'">' . $this->sheets[$sheet_no]['rows_data'][$r][$c]['data'] .
					  		'</Data></Cell>');
				else
					$this->_out('    <Cell ss:Index="' . $c . '">' .
							'<Data ss:Type="' . $this->sheets[$sheet_no]['rows_data'][$r][$c]['type'] .
				 			'">' . $this->sheets[$sheet_no]['rows_data'][$r][$c]['data'] .
				  			'</Data></Cell>');
			}
			$this->_out('   </Row>');
		}
		$this->_out('  </Table>');
	}
	
	$this->_out('  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">');
	if ($sheet_no==1)
		$this->_out('   <Selected/>');
	$this->_out('   <ProtectObjects>False</ProtectObjects>');
	$this->_out('   <ProtectScenarios>False</ProtectScenarios>');
	$this->_out('  </WorksheetOptions>');
	$this->_out(' </Worksheet>');
}


function _parsedoc()
{
	$this->buffer='';
	$this->_putworkbook();
	$this->_putdocprop();
	$this->_putexcelworbook();
	$this->_putstyles();
	for($i=1;$i<=$this->sheet;$i++)
		$this->_putsheet($i);
		
	$this->_out('</Workbook>');
	
	$this->state=3;
}
}
if ( isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']=='contype' )
{
	header('Content-Type: application/vnd.ms-excel;');
	exit;
}

?>
