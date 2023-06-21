<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use App\Kardex as Kardex;
use App\Materias as Materias;
use App\Alumnos as Alumnos;
//use Mpdf\Mpdf;
use Carbon\Carbon;
use App\CalificacionesTmp as CalificacionesTmp;
use App\Maestros as Maestros;
use App\MateriasTmp as MateriasTMP;
use App\Periodos as Periodo;
use App\User as User;

//use Barryvdh\DomPDF\PDF;


class alumnosController extends Controller
{
		public function index()
    {
				$response = array(
						'menssage' => 'success',
						'action' => 'get all alumnos data',
						'codigo' => 200
				);
				return response()->json($response, 200);
				return 'hi desde alumnos get';
        return Alumnos::all();
    }

    public function show(Alumnos $alumno)
    {
        return $alumno;
    }

    public function store(Request $request)
    {
        $alumno = Alumnos::create($request->all());

        return response()->json($alumno, 201);
    }

    public function update(Request $request, Alumnos $alumno)
    {
        $alumno->update($request->all());

        return response()->json($alumno, 200);
    }

    public function delete(Alumnos $alumno)
    {
        $alumno->delete();

        return response()->json(null, 204);
    }

	//Funcion para validar calificaiones y agregarlas al Kardex
	public function ValidarCalf(Request $request)
    {
		//validar token
		//validar que venga la matricula
		if((isset($request['matricula'])) && (!empty($request['matricula'])) && ($request['matricula'] != '')){
			//validar que la matricula exista
			//$data = DB::select('SELECT * FROM alumnos WHERE matricula = ? limit 1', [$request['matricula']]);
			$alumno = Alumnos::where('matricula','=',$request['matricula'])->first();
			$periodo_activo = Periodo::where('estado','=',1)->latest('id_periodo')->first();
			$validar = CalificacionesTmp::where('alumnoMatricula','=',$request['matricula'])->where('periodo','=',$periodo_activo['periodo_evaluacion'])->orderBy('materiaId')->get();
			//$data = DB::select($sql);
			if(empty($alumno)){
				return response()->json($response = array('menssage' => 'Matricula no encontrada.','matricula' => $request['matricula']), 202);
			}else{
				if(empty($periodo_activo)){
					return response()->json($response = array('menssage' => 'Periodo activo no encontrado.'), 202);
				}else{
					if(empty($validar)){
						return response()->json($response = array('menssage' => 'Materias registradas no encontradas ','matricula' => $request['matricula']), 202);
					}else{
						$update = CalificacionesTmp::where('alumnoMatricula','=',$request['matricula'])->where('periodo','=',$periodo_activo['periodo_evaluacion'])->update(array('Validada' => 1));
						$response = array(
							'menssage'	 	=> 'Calificaciones validadas',
							'action' 		=> 'ValidarCalf',
							'matricula' 	=> $request['matricula'],
							'codigo' 		=> 200
						);
						return response()->json($response, 200);
					}
				}
				
			}
		}else{
			return response()->json($response = array('menssage' => 'Matricula es un campo necesario.','matricula' => $request['matricula']), 202);
		}
    }

	//funciones para obtener kardex y boleta
	public function getKardex(Request $request)
    {
		//validar token
		//validar que venga la matricula
		if((isset($request['matricula'])) && (!empty($request['matricula'])) && ($request['matricula'] != '')){
			//validar que la matricula exista
			//$data = DB::select('SELECT * FROM alumnos WHERE matricula = ? limit 1', [$request['matricula']]);
			$alumno = Alumnos::where('matricula','=',$request['matricula'])->first();

			//$data = DB::select($sql);
			if(empty($alumno)){
				return response()->json($response = array('menssage' => 'Matricula no encontrada.','matricula' => $request['matricula']), 202);
			}else{
				//obtener informacion de kardex
				$kardex_data = Kardex::where('matricula','=',$request['matricula'])->orderBy('materia')->get();
				$kardex_data2 = Kardex::where('matricula','=',$request['matricula'])->orderBy('materia')->get();
				if (count($kardex_data)==0) {
					return response()->json($response = array('menssage' => 'Matricula sin datos de kardex.','matricula' => $request['matricula']), 202);
				}
				$html_kardex = $this->getKardexHtml($kardex_data,$alumno,$kardex_data2);



				/*
				$namefile = 'kardex_'.$alumno->matricula.'.pdf';
			    $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
			    $fontDirs = $defaultConfig['fontDir'];
			    $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
			    $fontData = $defaultFontConfig['fontdata'];
			    $mpdf = new Mpdf([
			        'fontDir' => array_merge($fontDirs, [
			            public_path() . '/fonts',
			        ]),
			        'default_font' => 'arial',
			        // "format" => "A4",
			        "format" => [264.8,188.9],
			    ]);
			    $mpdf->SetDisplayMode('fullpage');
			    $mpdf->WriteHTML($html_kardex);
			    $mpdf->Output(public_path().'/pdf/kardex_'.$alumno->matricula.'.pdf',"F");				
				$pdf_enconded = chunk_split(base64_encode(file_get_contents(public_path().'/pdf/kardex_'.$alumno->matricula.'.pdf')));
				*/

				$options = new Options();
				$options->set('isRemoteEnabled', true);
				$dompdf = new Dompdf($options);
				$dompdf->loadHtml($html_kardex);
				// Render the HTML as PDF
				$dompdf->render();

				// Output the generated PDF to Browser
				$file = $dompdf->output();
				file_put_contents(public_path().'/pdf/kardex_'.$alumno->matricula.'.pdf', $file);
				//$pdf_enconded = chunk_split(base64_encode(file_get_contents(public_path().'/pdf/kardex_'.$alumno->matricula.'.pdf')));
				

			}
		}else{
			return response()->json($response = array('menssage' => 'Matricula es un campo necesario.','matricula' => $request['matricula']), 202);
		}
		//convertir pdf a base 64
		//$pdf_enconded = chunk_split(base64_encode(file_get_contents($this->pdfdoc)));
		$response = array(
			'menssage'	 	=> 'success matricula found',
			'action' 		=> 'getKardex',
			'name'			=> $alumno->nombre_completo,
			'matricula' 	=> $request['matricula'],
			'data' 			=> json_encode($alumno),
			'kardexURL'		=> url('/pdf/kardex_'.$alumno->matricula.'.pdf'),
			'download'=> url('/kardex/download/'.$alumno->matricula),
			//'kardexBase64'	=> $pdf_enconded,
			'codigo' 		=> 200
		);
		return response()->json($response, 200);
    }

    public function getKardexHtml($kardex_data,$alumno,$kardex_data2){

    	$current_date = Carbon::now()->format('d/m/Y');
		$creditostotal = 0;
		$creditoscubtotal = 0;
		$materiastotal = 0;
		$promediodec = 0.0;
		$descuentotent = 0;
		$descuento = 'No Aplica';
		$descuentoporcentaje = 0;
		$totalpromedio = 0;
		$i = 0;
		$salto = '';
		$Porcentaje_General = 0;
		
		foreach ($kardex_data as $_kardex_data) {
			//obtener informacion de materia
			$_materia_data = Materias::where('id_materia','=',$_kardex_data->materia)->orderBy('id_materia')->first();
			$periodo_activo = Periodo::where('estado','=',1)->latest('id_periodo')->first();
		
			if (isset($_materia_data['nombre'])) {
				$materia_nombre = $_materia_data['nombre'];
			}else{
				$materia_nombre = 'NULL';
			}
				
			$materiastotal++;
		}

		foreach ($kardex_data2 as $_kardex_data2) {
			//obtener informacion de materia
			$_materia_data = Materias::where('id_materia','=',$_kardex_data2->materia)->orderBy('id_materia')->first();
			$periodo_activo = Periodo::where('estado','=',1)->latest('id_periodo')->first();
			if (isset($_materia_data['cred'])) {
				$materia_cred = $_materia_data['cred'];
			}else{
				$materia_cred = 'NULL';
			}
		
			$cred 			= $materia_cred;
			IF (($_kardex_data2->periodo_def == $periodo_activo['periodo_evaluacion']) && (intval($_kardex_data2->defin) < 70 )){
				$creditostotal = $creditostotal + intval($cred);
			}
			IF (intval($_kardex_data2->defin) < 70 ){
				$descuentotent = $descuentotent + 1;
			}
			
			IF (intval($_kardex_data2->defin) >= 70 ){
				$creditoscubtotal = $creditoscubtotal + intval($cred);
			}
			$promediodec = $promediodec + intval($_kardex_data2->defin);
		}
		
		$totalpromedio = ($promediodec/$materiastotal);
		
		$Porcentaje_General = number_format(((100 / 260)*$creditoscubtotal), 1, '.', '');
		
		$descuentoporcentaje = number_format($totalpromedio, 2, '.', '');
			IF ($descuentotent <= 0){
				IF ($descuentoporcentaje >= 80 && $descuentoporcentaje < 90){
					$descuento = '33%';
				}
				IF ($descuentoporcentaje >= 90 && $descuentoporcentaje < 95){
					$descuento = '50%';
				}
				IF ($descuentoporcentaje >= 95 && $descuentoporcentaje <= 100){
					$descuento = '100%';
				}
			}else{
				$descuento = 'NO APLICA';
			}
		
    	//crear kardex html
    	$html_kardex = '		
		<!DOCTYPE html>
		<html lang="en">
		  <head>
		    <meta charset="UTF-8" />
		    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
		    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		    <title>Kardex</title>
		  </head>
		    <style>
			* {
		          margin: 0;
		          padding: 0;
		          font-family: sans-serif;
		          text-transform: uppercase;
		      }
		        .container {
		          /* width: 1195px; */
		          padding: 0px 0px 30px 30px;
		        }

		        .header {
				  top: 0px;
		          width: 100%;
		        }
				
		        img {
		          object-fit: contain;
		          width: 100%;
		        }

		        .title {
		          font-size:large;
		          text-align: center;
		          letter-spacing: -1px;
		        }

		        .content2 {
		          font-size: 0.4rem;
				  position: relative;
		        }

		        .content2 img {
				  position: absolute;
		          top: 0;
		          left: 0;
		          right: 0;
		          width: 100%;
		          opacity: 0.2;
		        }
				footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3.3cm;
            color: white;
            text-align: center;
        }
		body {
            margin: 1cm 1cm 1cm;
        }
		div.breakNow { page-break-inside:avoid; page-break-after:always; }
		    </style>
		    <div class="container">
		      <div class="title">
		          DEPARTAMENTO DE CONTROL ESCOLAR | TECNOLÓGICO DE ESTUDIOS SUPERIORES
		          DE IXTAPALUCA
		        <div>KARDEX</div>
		      </div>
			  <br>
				<table style="width:100%; font-size: 0.8rem;">
				<thead>
				 <tr>
				  <th style="width:20%" valign="middle" align="center"></th>
				  <th style="width:30%" valign="middle" align="center"></th>
				  <th style="width:20%" valign="middle" align="center"></th>
				  <th style="width:30%" valign="middle" align="center"></th>
				  <th style="width:20%" valign="middle" align="center"></th>
				  <th style="width:30%" valign="middle" align="center"></th>
				 </tr>
				</thead>
				<tbody>
				  <tr>
					<td style="text-align : justify;"><b>NOMBRE: </b></td><td style="text-align : justify;">'.$alumno->nombre_alumno.' '.$alumno->apellidop_alumno.' '.$alumno->apellidom_alumno.'</td>
					<td style="text-align : justify;"></td><td style="text-align : justify;"></td>
					<td style="text-align : justify;"><b>CARRERA:</b></td><td style="text-align : justify;">'.$alumno->carrera.'</td>
				  </tr>
				  <tr>
					<td style="text-align : justify;"><b>CREDITOS:</b></td><td style="text-align : justify;">'.'260'.'</td>
					<td style="text-align : justify;"></td><td style="text-align : justify;"></td>
				<td style="text-align : justify;"><b>CREDITOS CUBIERTOS:</b></td><td style="text-align : justify;">'.$creditoscubtotal.'</td>
				  </tr>
				  <tr>
					<td style="text-align : justify;"><b>MATERIAS CURSADAS:</b></td><td style="text-align : justify;">'.$materiastotal.'</td>
					<td style="text-align : justify;"></td><td style="text-align : justify;"></td>
					<td style="text-align : justify;"><b>MATRICULA:</b></td><td style="text-align : justify;">'.$alumno->matricula.'</td>
				  </tr>
				  <tr>
					<td style="text-align : justify;"><b>PROMEDIO:</b></td><td style="text-align : justify;">'.number_format($totalpromedio, 2, '.', '').'</td>
					<td style="text-align : justify;"></td><td style="text-align : justify;"></td>
					<td style="text-align : justify;"><b>FECHA:</b></td><td style="text-align : justify;">'.$current_date.'</td>
				  </tr>
				</tbody>
				</table>
				<br>
			<main>
			<body>
		      <div class="content2">
			  <img src="'.url('https://controlescolar.tesi.org.mx/storage/app/public/Images/Kardex/R.jpg').'" />
		        <table>
		          <thead>
		            <tr>
		              <th scope="col">CLAVE</th>
		              <th scope="col">CRED</th>
		              <th scope="col">NOMBRE ASIGNATURA</th>
		              <th scope="col">Eval.ORD</th>
		              <th scope="col">PERIODO</th>
					  <th scope="col">2DA OP ORD</th>
		              <th scope="col">PERIODO 2da O. ORD</th>
		              <th scope="col">RECURSAMIENTO</th>
		              <th scope="col">PERIODO RECURSAMIENTO</th>
		              <th scope="col">2da OP REC</th>
		              <th scope="col">PEIRODO 2da OP. REC</th>
		              <th scope="col">Curso Especial 1</th>
		              <th scope="col">Periodo CE 1</th>
		              <th scope="col">Curso Especial 2</th>
		              <th scope="col">Periodo CE 2</th>
		              <th scope="col">EQUIVALENCIA</th>
		              <th scope="col">PERIODO EQUIVALENCIA</th>
		              <th scope="col">DEF.</th>
		              <th scope="col">PERIODO DEF.</th>
		            </tr>
		          </thead>
		          <tbody class="signaturesTable">
		';
		
		foreach ($kardex_data as $_kardex_data) {
			//obtener informacion de materia
			if( $i == 60 ){ $salto= '<div class="breakNow"></div>'; $i = 0;}
			
			$_materia_data = Materias::where('id_materia','=',$_kardex_data->materia)->orderBy('id_materia')->first();

			if (isset($_materia_data['cred'])) {
				$materia_cred = $_materia_data['cred'];
			}else{
				$materia_cred = 'NULL';
			}
			if (isset($_materia_data['nombre'])) {
				$materia_nombre = substr($_materia_data['nombre'],0,15);
			}else{
				$materia_nombre = 'NULL';
			}
		
			$clave 			= $_kardex_data->materia;
			$cred 			= $materia_cred;
			$nombre 		= $materia_nombre;
			$ev_or 			= $_kardex_data->evaluacion_ordinaria;
			$ev_or_per 		= $_kardex_data->periodo;
			$SEGOP 			= $_kardex_data['2da_op_ord'];
			$SEGOP_PER 		= $_kardex_data->periodo_2da_op_ord;
			$REC 			= $_kardex_data->recursamiento;
			$REC_PER 		= $_kardex_data->periodo_recursamiento;
			$SEGOP_REC 		= $_kardex_data['2da_op_rec'];
			$SEGOP_REC_PER	= $_kardex_data->periodo_2da_op_rec;
			$CUR_ESP 		= $_kardex_data->curso_especial;
			$CUR_ESP_PER 	= $_kardex_data->periodo_ce;
			$SEGOP_CE 		= $_kardex_data['2da_op_ce'];
			$SEGOP_CE_PER 	= $_kardex_data->periodo_2da_op_ce;
			$EQUIVAL 		= $_kardex_data->equivalencia;
			$EQUIVAL_PER 	= $_kardex_data->periodo_equivalencia;
			$DEFIN 			= $_kardex_data->defin;
			$DEFIN_PER 		= $_kardex_data->periodo_def;

			if ($ev_or == 0 || $ev_or == '0'){ $ev_or = '';}
			if ($ev_or_per == 0 || $ev_or_per == '0'){ $ev_or_per = '';}
			if ($SEGOP == 0 || $SEGOP == '0'){ $SEGOP = '';}
			if ($SEGOP_PER == 0 || $SEGOP_PER == '0'){ $SEGOP_PER = '';}
			if ($REC == 0 || $REC == '0'){ $REC = '';}
			if ($REC_PER == 0 || $REC_PER == '0'){ $REC_PER = '';}
			if ($SEGOP_REC == 0 || $SEGOP_REC == '0'){ $SEGOP_REC = '';}
			if ($SEGOP_REC_PER == 0 || $SEGOP_REC_PER == '0'){ $SEGOP_REC_PER = '';}
			if ($CUR_ESP == 0 || $CUR_ESP == '0'){ $CUR_ESP = '';}
			if ($CUR_ESP_PER == 0 || $CUR_ESP_PER == '0'){ $CUR_ESP_PER = '';}
			if ($SEGOP_CE == 0 || $SEGOP_CE == '0'){ $SEGOP_CE = '';}
			if ($SEGOP_CE_PER == 0 || $SEGOP_CE_PER == '0'){ $SEGOP_CE_PER = '';}
			if ($EQUIVAL == 0 || $EQUIVAL == '0'){ $EQUIVAL = '';}
			if ($EQUIVAL_PER == 0 || $EQUIVAL_PER == '0'){ $EQUIVAL_PER = '';}
			if ($DEFIN == 0 || $DEFIN == '0'){ $DEFIN = '';}
			if ($DEFIN_PER == 0 || $DEFIN_PER == '0'){ $DEFIN_PER = '';}

			$html_kardex .= '
		            <!-- Mapear el tr -->
		            <tr>
		              <td>'.$clave.'</td>
		              <td>'.$cred.'</td>
		              <td><strong style="font-size: 5px;">'.$nombre.'</strong></td>
		              <td>'.$ev_or.'</td>
		              <td>'.$ev_or_per.'</td>
		              <td>'.$SEGOP.'</td>
		              <td>'.$SEGOP_PER.'</td>
		              <td>'.$REC.'</td>
		              <td>'.$REC_PER.'</td>
		              <td>'.$SEGOP_REC.'</td>
		              <td>'.$SEGOP_REC_PER.'</td>
		              <td>'.$CUR_ESP.'</td>
		              <td>'.$CUR_ESP_PER.'</td>
		              <td>'.$SEGOP_CE.'</td>
		              <td>'.$SEGOP_CE_PER.'</td>
		              <td>'.$EQUIVAL.'</td>
		              <td>'.$EQUIVAL_PER.'</td>
		              <td>'.$DEFIN.'</td>
					  <td>'.$DEFIN_PER.'</td>
					  
		            </tr>
					'.$salto.'
					
			';
			if( $i == 0 ){ $salto= '';}
		  $i ++;
		}
		
		$html_kardex .= '
		          </tbody>
		        </table>
		      </div>
			 </main>
			 <div style="font-size: 10px;">
				<div>
				<br>
					PORCENTAJE CUBIERTO GENERAL: <b>'.$Porcentaje_General.'%</b>
				</div>
				<div>
				<br>
					DESCUENTO POR PROMEDIO GENERAL: <b>'.$descuento.'</b>
				</div>
				<div>
				<br>
					MATERIAS REPROBADAS: <b>'.$descuentotent.'</b>
				</div>
			 </div>
			 <footer>
            	<img src="'.url('https://controlescolar.tesi.org.mx/storage/app/public/Images/Kardex/footer.png').'"/>
        	</footer>
		   </body>
		</html>
		';

		return $html_kardex;
    }

    public function getBoleta(Request $request)
    {
		//validar token
		//validar que venga la matricula
		if((isset($request['matricula'])) && (!empty($request['matricula'])) && ($request['matricula'] != '')){
			//validar que la matricula exista
			$alumno = Alumnos::where('matricula','=',$request['matricula'])->first();
			if(empty($alumno)){
				return response()->json($response = array('menssage' => 'Matricula no encontrada.','matricula' => $request['matricula']), 202);
			}else{
				//obtener informacion de kardex
				$kardex_data = Kardex::where('matricula','=',$request['matricula'])->orderBy('materia')->get();
				if (count($kardex_data)==0) {
					return response()->json($response = array('menssage' => 'Matricula sin datos de boleta.','matricula' => $request['matricula']), 202);
				}
				//Obetener el semestre en caso de que en la tabla Alumno no aparezca este dato
				$Semestre_Aux = Kardex::where('matricula','=',$request['matricula'])->latest('materia')->first();

				$html_boleta = $this->getBoletaHtml($kardex_data,$alumno,$Semestre_Aux);
				$options = new Options();
				$options->set('isRemoteEnabled', true);
				$dompdf = new Dompdf($options);
				$dompdf->loadHtml($html_boleta);
				// Render the HTML as PDF
				$dompdf->render();

				// Output the generated PDF to Browser
				$file = $dompdf->output();
				file_put_contents(public_path().'/pdf/boleta_'.$alumno->matricula.'.pdf', $file);
				$pdf_enconded = chunk_split(base64_encode(file_get_contents(public_path().'/pdf/boleta_'.$alumno->matricula.'.pdf')));
			}
		}else{
			return response()->json($response = array('menssage' => 'Matricula es un campo necesario.','matricula' => $request['matricula']), 202);
		}
		//convertir pdf a base 64
		//$pdf_enconded = chunk_split(base64_encode(file_get_contents($this->pdfdoc)));
		$response = array(
			'menssage'	 	=> 'success matricula found',
			'action' 		=> 'getBoleta',
			'name'			=> $alumno->nombre_completo,
			'matricula' 	=> $request['matricula'],
			'data' 			=> json_encode($alumno),
			'boletaURL'		=> url('/pdf/boleta_'.$alumno->matricula.'.pdf'),
			'download'=> url('/boleta/download/'.$alumno->matricula),
			//'boletaBase64'	=> $pdf_enconded,
			'codigo' 		=> 200
		);
		return response()->json($response, 200);	
    }

    public function getBoletaHtml($kardex_data,$alumno,$Semestre_Aux){
    	$current_date = Carbon::now()->format('d/m/Y');
		$totalpromedio = 0;
		$materiastotal = 0;
		$promediodec = 0.0;
		$Semestre_Alumno = '';
		
		$periodo_activo = Periodo::where('estado','=',1)->latest('id_periodo')->first();
		$Semestre_Auxiliar = substr($Semestre_Aux['materia'], 1, 1);

		if ($alumno->semestre <> ''){
			$Semestre_Alumno = $alumno->semestre;
		}else{
			  if($Semestre_Auxiliar == 1){ $Semestre_Alumno='PRIMERO'; }
			  if($Semestre_Auxiliar == 2){ $Semestre_Alumno='SEGUNDO'; }
			  if($Semestre_Auxiliar == 3){ $Semestre_Alumno='TERCERO'; }
			  if($Semestre_Auxiliar == 4){ $Semestre_Alumno='CUARTO'; }
			  if($Semestre_Auxiliar == 5){ $Semestre_Alumno='QUINTO'; }
			  if($Semestre_Auxiliar == 6){ $Semestre_Alumno='SEXTO'; }
			  if($Semestre_Auxiliar == 7){ $Semestre_Alumno='SEPTIMO'; }
			  if($Semestre_Auxiliar == 8){ $Semestre_Alumno='OCTAVO'; }
			  if($Semestre_Auxiliar == 9){ $Semestre_Alumno='NOVENO'; }
			  if($Semestre_Auxiliar == ''){ $Semestre_Alumno='SIN SEMESTRE'; }
		}	

		foreach ($kardex_data as $_kardex_data) {
			if ($_kardex_data->periodo_def == $periodo_activo['periodo_evaluacion']) {
				//obtener informacion de materia
				$_materia_data = Materias::where('id_materia','=',$_kardex_data->materia)->orderBy('id_materia')->first();

				if (isset($_materia_data['cred'])) {
					$materia_cred = $_materia_data['cred'];
				}else{
					$materia_cred = 'NULL';
				}
				if (isset($_materia_data['nombre'])) {
					$materia_nombre = $_materia_data['nombre'];
				}else{
					$materia_nombre = 'NULL';
				}
			
				$clave 			= $_kardex_data->materia;
				$cred 			= $materia_cred;
				$nombre 		= $materia_nombre;
				$ev_or_per 		= $_kardex_data->periodo;
				IF ($_kardex_data->periodo_def == $periodo_activo['periodo_evaluacion']) {
					$ev_or 			= $_kardex_data->evaluacion_ordinaria;
					$SEGOP 			= $_kardex_data['2da_op_ord'];
					$SEGOP_PER 		= $_kardex_data->periodo_2da_op_ord;
					$REC 			= $_kardex_data->recursamiento;
					$REC_PER 		= $_kardex_data->periodo_recursamiento;
					$SEGOP_REC 		= $_kardex_data['2da_op_rec'];
					$SEGOP_REC_PER	= $_kardex_data->periodo_2da_op_rec;
					$CUR_ESP 		= $_kardex_data->curso_especial;
					$CUR_ESP_PER 	= $_kardex_data->periodo_ce;
					$SEGOP_CE 		= $_kardex_data['2da_op_ce'];
					$SEGOP_CE_PER 	= $_kardex_data->periodo_2da_op_ce;
					$EQUIVAL 		= $_kardex_data->equivalencia;
					$EQUIVAL_PER 	= $_kardex_data->periodo_equivalencia;
					$DEFIN 			= $_kardex_data->defin;
					$DEFIN_PER 		= $_kardex_data->periodo_def;
					
					
					$promediodec = $promediodec + intval($_kardex_data->defin);
					$materiastotal++;
				}
				$totalpromedio = ($promediodec/$materiastotal);
				
			}
		}
		
		
    	$html_boleta = '
    		<!DOCTYPE html>
			<html lang="en">
			  <head>
			    <meta charset="UTF-8" />
			    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
			    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
			    <title>Boleta</title>
			  </head>
			  <body>
			    <style>
			      * {
			          margin: 0;
			          padding: 0;
			          font-family: sans-serif;
			          text-transform: uppercase;
			        }
			        .container {
			          /* width: 1195px; */
			          /* min-height: 800px; */
			          padding: 30px 40px 0 40px;
			          overflow: hidden;
			        }

			        .header {
			          width: 100%;
			          display: flex;
			        }

			        img {
			          object-fit: contain;
			          width: 100%;
			        }

			        .card {
			          border: 1px solid black;
			          border-radius: 30px;
			          position: relative;
			          overflow: hidden;
			        }

			        #background-image {
			          background-image: url("'.url('https://controlescolar.tesi.org.mx/storage/app/public/Images/Boleta/tesibg.png').'");
			          width: 100%;
			          top: 0;
			          left: 0;
			          height: 100%;
			          opacity: 0.1;
			          position: absolute;
			          background-repeat: repeat-x;
			          background-size: auto 17%;
			          padding: 0;
			          margin: 0;
			          z-index: -1;
			        }
			        #background-image2 {
			          background-image: url("'.url('https://controlescolar.tesi.org.mx/storage/app/public/Images/Boleta/tesibg.png').'");
			          width: 100%;
			          top: 24%;
			          left: 4%;
			          height: 100%;
			          opacity: 0.1;
			          position: absolute;
			          background-repeat: repeat-x;
			          background-size: auto 17%;
			          padding: 0;
			          margin: 0;
			          z-index: -1;
			        }
			        #background-image3 {
			          background-image: url("'.url('https://controlescolar.tesi.org.mx/storage/app/public/Images/Boleta/tesibg.png').'");
			          width: 100%;
			          top: 44%;
			          left: 0;
			          height: 100%;
			          opacity: 0.1;
			          position: absolute;
			          background-repeat: repeat-x;
			          background-size: auto 17%;
			          padding: 0;
			          margin: 0;
			          z-index: -1;
			        }
			        #background-image4 {
			          background-image: url("'.url('https://controlescolar.tesi.org.mx/storage/app/public/Images/Boleta/tesibg.png').'");
			          width: 100%;
			          top: 64%;
			          left: 4%;
			          height: 100%;
			          opacity: 0.1;
			          position: absolute;
			          background-repeat: repeat-x;
			          background-size: auto 17%;
			          padding: 0;
			          margin: 0;
			          z-index: -1;
			        }
			        #background-image5 {
			          background-image: url("'.url('https://controlescolar.tesi.org.mx/storage/app/public/Images/Boleta/tesibg.png').'");
			          width: 100%;
			          top: 84%;
			          left: 0;
			          height: 100%;
			          opacity: 0.1;
			          position: absolute;
			          background-repeat: repeat-x;
			          background-size: auto 17%;
			          padding: 0;
			          margin: 0;
			          z-index: -1;
			        }
			        #background-image6 {
			          background-image: url("'.url('https://controlescolar.tesi.org.mx/storage/app/public/Images/Boleta/tesibg.png').'");
			          width: 100%;
			          top: 104%;
			          left: 4%;
			          height: 100%;
			          opacity: 0.1;
			          position: absolute;
			          background-repeat: repeat-x;
			          background-size: auto 17%;
			          padding: 0;
			          margin: 0;
			          z-index: -1;
			        }

			        .content1 {
			          display: flex;
			          justify-content: space-between;
			          font-size: 0.8rem;
			        }

			        .content1 th,
			        td {
			          padding: 3px;
			        }

			        .content2 {
			          font-size: 0.8rem;
			          position: relative;
			        }

			        .content2 img {
			          position: absolute;
			          top: 0;
			          left: 0;
			          bottom: 0;
			          right: 0;
			          width: 100%;
			          height: auto;
			          opacity: 0.2;
			        }

			        .content2 table {
			          width: 100%;
			        }

			        .content2 td {
			          text-align: center;
			        }

			        .content2 thead th,
			        td {
			          padding: 2px;
			        }

			        .signaturesTable td {
			          padding: 7px;
			        }

			        .tableContent td,
			        th,
			        table {
			          border: 1px solid black;
			          border-collapse: collapse;
			        }

			    </style>
			    <div class="container">
			      <div class="header">
			        <div style="width: 25%">
			          <img src="'.url('https://controlescolar.tesi.org.mx/storage/app/public/Images/Boleta/R.jpg').'" />
			        </div>
					</div>
					<br><br>
			        <div align="center"
			          style="
			            font-size: 1.4rem;
			            font-weight: 600;
			          "
			        >
					
			          <div>Tecnológico de Estudios Superiores de Ixtapaluca</div>
			          <div>Departamento de control escolar</div>
			        
			      </div>
				  <br><br>
			      <div class="card">
			        <div id="background-image"></div>
			        <div id="background-image2"></div>
			        <div id="background-image3"></div>
			        <div id="background-image4"></div>
			        <div id="background-image5"></div>
			        <div id="background-image6"></div>
			        <div
			          style="
			            width: 100%;
			            text-align: center;
			            font-size: x-large;
			            font-weight: 600;
			          "
			        >
					
			          BOLETA DE CALIFICACIONES
			        </div>
					<main>
			        <table style="width: 100%">
			            <tr>
                            <td style="font-weight: 600"><b>- NOMBRE:</b></td><td>'.$alumno->nombre_alumno.' '.$alumno->apellidop_alumno.' '.$alumno->apellidom_alumno.'</td>
                        </tr>
			            <tr>
                            <td style="font-weight: 600"><b>- No. DE MATRICULA:</b></td><td>'.$alumno->matricula.'</td>
			            </tr>
			            <tr>
			              <td style="font-weight: 600"><b>- PERIODO:</b></td><td>'.$periodo_activo['periodo_evaluacion'].'</td>
			            </tr>
			            <tr>
			              <td style="font-weight: 600"><b>- FECHA:</b></td><td>'.$current_date.'</td>
			            </tr>
			            <tr>
			              <td style="font-weight: 600"><b>- CARRERA:</b></td><td>'.$alumno->carrera.'</td>
			            </tr>
			            <tr>
			              <td style="font-weight: 600"><b>- SEMESTRE:</b></td><td>'.$Semestre_Alumno.'</td>
			            </tr>
			            <tr>
			              <td></td>
			              <td></td>
			            </tr>
			            <tr>
			              <td style="font-weight: 600"><b>- PROMEDIO:</b></td><td>'.number_format($totalpromedio, 2, '.', '').'</td>
			            </tr>
			          </table>
			        </div>
			        <div class="content2">
			          <table class="tableContent">
			            <thead style="background-color: #41b141">
			              <tr>
			                <th rowspan="2">CLAVE</th>
			                <th rowspan="2">MATERIA</th>
			                <th rowspan="2">CRED.</th>
			                <th colspan="6">CALIFICACIÓN</th>
			              </tr>
			              <tr>
			                <th>ORD.</th>
			                <th>2da.ORD</th>
			                <th>REC.</th>
			                <th>2da.REC</th>
			                <th>ESPEC</th>
							<th>2da.ESPEC</th>
			              </tr>
			            </thead>
			            <tbody class="signaturesTable">
		';

		foreach ($kardex_data as $_kardex_data) {
			if ($_kardex_data->periodo_def == $periodo_activo['periodo_evaluacion']) {
				//obtener informacion de materia
				$_materia_data = Materias::where('id_materia','=',$_kardex_data->materia)->orderBy('id_materia')->first();

				if (isset($_materia_data['cred'])) {
					$materia_cred = $_materia_data['cred'];
				}else{
					$materia_cred = 'NULL';
				}
				if (isset($_materia_data['nombre'])) {
					$materia_nombre = $_materia_data['nombre'];
				}else{
					$materia_nombre = 'NULL';
				}
			
				
				
				
				IF ($_kardex_data->periodo_def == $periodo_activo['periodo_evaluacion']) {
					$ev_or_per 		= $_kardex_data->periodo;
					$clave 			= $_kardex_data->materia;
					$cred 			= $materia_cred;
					$nombre 		= $materia_nombre;
					$ev_or 			= $_kardex_data->evaluacion_ordinaria;
					$SEGOP 			= $_kardex_data['2da_op_ord'];
					$SEGOP_PER 		= $_kardex_data->periodo_2da_op_ord;
					$REC 			= $_kardex_data->recursamiento;
					$REC_PER 		= $_kardex_data->periodo_recursamiento;
					$SEGOP_REC 		= $_kardex_data['2da_op_rec'];
					$SEGOP_REC_PER	= $_kardex_data->periodo_2da_op_rec;
					$CUR_ESP 		= $_kardex_data->curso_especial;
					$CUR_ESP_PER 	= $_kardex_data->periodo_ce;
					$SEGOP_CE 		= $_kardex_data['2da_op_ce'];
					$SEGOP_CE_PER 	= $_kardex_data->periodo_2da_op_ce;
					$EQUIVAL 		= $_kardex_data->equivalencia;
					$EQUIVAL_PER 	= $_kardex_data->periodo_equivalencia;
					$DEFIN 			= $_kardex_data->defin;
					$DEFIN_PER 		= $_kardex_data->periodo_def;
				}

				$html_boleta .= '
				              <tr>
				                <td>'.$clave.'</td>
				                <td>'.$nombre.'</td>
				                <td>'.$cred.'</td>
				                <td>'.$ev_or.'</td>
				                <td>'.$SEGOP.'</td>
				                <td>'.$REC.'</td>
				                <td>'.$SEGOP_REC.'</td>
				                <td>'.$CUR_ESP.'</td>
								<td>'.$SEGOP_CE.'</td>
				              </tr>
				';
			}
		}



		
		$html_boleta .= '
			            </tbody>
			          </table>
			        </div>
			      </div>
			    </div></main>
			  </body>
			</html>
    	';



    	return $html_boleta;
    }

    public function downloadKardex($matricula)
    {
    	$kardex = public_path().'/pdf/kardex_'.$matricula.'.pdf';
    	if(isset($matricula) && ($matricula!='') && (file_exists($kardex))){
        	return response()->download($kardex);
    	}else{
    		return redirect('/');
    	}
    }

    public function downloadBoleta($matricula)
    {
    	$boleta = public_path().'/pdf/boleta_'.$matricula.'.pdf';
    	if(isset($matricula) && ($matricula!='') && (file_exists($boleta))){
        	return response()->download($boleta);
    	}else{
    		return redirect('/');
    	}
    }

    public function loginRequired(){
    	$response = array(
			'menssage'	 	=> 'login es requerido',
			'codigo' 		=> 400
		);
		return response()->json($response, 400);
    }

    public function getCalificacion(Request $request){
    	if((isset($request['id_alumno'])) && (!empty($request['id_alumno'])) 
            && (isset($request['matricula'])) && (!empty($request['matricula']))
        ){

    		$calificaciones = CalificacionesTmp::where('alumnoMatricula','=',$request['matricula'])->get();
    		if ((!empty($calificaciones)) && (count($calificaciones)>0)) {

    			foreach ($calificaciones as $_calificacion) {
    				$id_maestro = $_calificacion['id_maestro'];
    				$maestro = Maestros::where('id','=',$id_maestro)->first();
    				$name_materia = MateriasTMP::where('id_materia','=',$_calificacion['materiaId'])->first();
    				
    				if (!empty($maestro)) {
    					$name_maestro= $maestro['name'].' '.$maestro['lastname'];
    					$_calificacion['maestro_name'] = $name_maestro;
    				}else{
    					$name_maestro = 'Maestro no encontrado';
    					$_calificacion['maestro_name'] = $name_maestro;
    				}

    				if (!empty($name_materia)) {
    					$_name_materia = $name_materia['nombre'];
    					$_calificacion['materia_name'] = $_name_materia;
						$_calificacion['Validada'] = boolval($_calificacion['Validada']);
						$_calificacion['Registrada'] = boolval($_calificacion['Registrada']);
    				}else{
    					$_name_materia = 'Materia no encontrada';
    					$_calificacion['materia_name'] = $_name_materia;
    				}


    			}
    			$response = array(
	                'menssage'      	=> 'success',
	                'action'        	=> 'getCalificacion',
	                'calificaciones'	=> $calificaciones,
	                'codigo'        	=> 200
	            );
	            return response()->json($response, 200);
    		}else{
    			$response = array(
	                'menssage'      => 'No se encontraron calificaciones para este alumno.',
	                'action'        => 'getCalificacion',
	                'codigo'        => 200
	            );
	            return response()->json($response, 200);
    		}
        }else{
            $response = array(
                'menssage'      => 'La petición es sintácticamente incorrecta.',
                'action'        => 'getMaterias',
                'codigo'        => 400
            );
            return response()->json($response, 400);
        }
    }


	public function updateAlumno(Request $request){
    	
		$user = User::find($request['id_alumno']);
		$user->fill($request->all());
		$user->fecha_nacimiento = Carbon::parse($user->fecha_nacimiento)->format('Y-m-d');
		$user->fecha_solicitud = Carbon::parse($user->fecha_solicitud)->format('Y-m-d');
		$user->fecha_nacimiento_padre = Carbon::parse($user->fecha_nacimiento_padre)->format('Y-m-d');
		$user->fecha_nacimiento_madre = Carbon::parse($user->fecha_nacimiento_madre)->format('Y-m-d');
	
		$user->save();
		$response = array(
			'menssage'	 	=> 'success',
			'action' 		=> 'updateAlumno',
			'user' 			=> $user,
			'codigo' 		=> 200
		);
		return response()->json($response, 200);
	}
}



/*
public function updateAlumno(Request $request){
    	
	$user = User::find($request['id_alumno']);
	$user->fill($request->all());
	$user->fecha_nacimiento = Carbon::parse($user->fecha_nacimiento)->format('Y-m-d');
	$user->fecha_solicitud = Carbon::parse($user->fecha_solicitud)->format('Y-m-d');
	$user->fecha_nacimiento_padre = Carbon::parse($user->fecha_nacimiento_padre)->format('Y-m-d');
	$user->fecha_nacimiento_madre = Carbon::parse($user->fecha_nacimiento_madre)->format('Y-m-d');

	$user->save();
	$response = array(
		'menssage'	 	=> 'success',
		'action' 		=> 'updateAlumno',
		'user' 			=> $user,
		'codigo' 		=> 200
	);
	return response()->json($response, 200);
}*/
//cierre de clase principal