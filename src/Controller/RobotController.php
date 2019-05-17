<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use \Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Number;
use Cake\I18n\Time;
use Cake\I18n\Date;

/**
 * A ideia desta classe eh criar um robo que faca o preenchimento das informacoes de usuarios
 * dentro do banco de dados
 */
class RobotController extends AppController{	
    
    public function initialize() {
        parent::initialize();
    }
	
	public function index(){
		
		$this->autoRender = false;
		
		$tblEmployer = TableRegistry::get('Employer');
		
		$m = 0;
		for($i=0;$i<60;$i++){
			/*$estudoh = $this->getStudy();
			$admish  = $this->getAdmission();
			echo "+ ".$this->getFirstName("H")." ".$this->getLastName().",".$estudoh.",".$this->getPosition($estudoh).",".$admish.",M,".$this->getResignation($admish).",".$this->getBirthday()."<br/>";
			*/
			
			
			$employer = $tblEmployer->newEntity();
			$employer->name = $this->getFirstName("H")." ".$this->getLastName();
			$employer->idsector = $this->getSector();
			$employer->idstudy_level = $this->getStudy();
			$employer->idposition = $this->getPosition($employer->idstudy_level);
			$employer->birthday = $this->getBirthday();
			$employer->admission = $this->getAdmission();
			$employer->resignation = $this->getResignation($employer->admission);
			$employer->genre = 'M';
			$employer->salary = $this->getSalary($employer->idposition);
			$m += ($tblEmployer->save($employer)?1:0);
		}
		
		echo "Foram salvas $m informa&ccedil;&otildes;es de funcion&aacute;rios masculinos!<br/>";
		
		$f = 0;
		for($i=0;$i<60;$i++){
			/*$estudo = $this->getStudy();
			$admisf = $this->getAdmission();
			echo "- ".$this->getFirstName("F")." ".$this->getLastName().",".$estudo.",".$this->getPosition($estudo).",".$admisf.",F,".$this->getResignation($admisf).",".$this->getBirthday()."<br/>";
			*/
			
			$employer = $tblEmployer->newEntity();
			$employer->name = $this->getFirstName("F")." ".$this->getLastName();
			$employer->idsector = $this->getSector();
			$employer->idstudy_level = $this->getStudy();
			$employer->idposition = $this->getPosition($employer->idstudy_level);
			$employer->birthday = $this->getBirthday();
			$employer->admission = $this->getAdmission();
			$employer->resignation = $this->getResignation($employer->admission);
			$employer->genre = 'F';
			$employer->salary = $this->getSalary($employer->idposition);
			$f += ($tblEmployer->save($employer)?1:0);
		}
		
		echo "Foram salvas $f informa&ccedil;&otildes;es de funcion&aacute;rios feminios!";
	}
	
	private function getFirstName($genre){
		
		if($genre=="H"){
			$homen_fname[] = 'Alexandre'; 	$homen_fname[] = 'Andre';
			$homen_fname[] = 'Alberto';		$homen_fname[] = 'Alfredo';
			$homen_fname[] = 'Baltazar';	$homen_fname[] = 'Claudio';
			$homen_fname[] = 'Cleiton';		$homen_fname[] = 'Edson';
			$homen_fname[] = 'Everson';		$homen_fname[] = 'Fabio';
			$homen_fname[] = 'Fernando';	$homen_fname[] = 'Fabricio';
			$homen_fname[] = 'Gabriel';		$homen_fname[] = 'Helio';
			$homen_fname[] = 'Ivan';		$homen_fname[] = 'Juliano';
			$homen_fname[] = 'Jadson'; 		$homen_fname[] = 'Jose';
			$homen_fname[] = 'Julio';		$homen_fname[] = 'Leandro';
			$homen_fname[] = 'Lorival'; 	$homen_fname[] = 'Lucas';
			$homen_fname[] = 'Mario'; 		$homen_fname[] = 'Marcio';
			$homen_fname[] = 'Manoel';		$homen_fname[] = 'Noel';
			$homen_fname[] = 'Odair';		$homen_fname[] = 'Pablo';
			$homen_fname[] = 'Ricardo';		$homen_fname[] = 'Rubens';
			
			$homen_fname[] = 'Rogerio';		$homen_fname[] = 'Luan';
			$homen_fname[] = 'Jader';		$homen_fname[] = 'Joel';
			$homen_fname[] = 'Roberto';		$homen_fname[] = 'Ibere';
			$homen_fname[] = 'Joao';		$homen_fname[] = 'Juvenal';
			$homen_fname[] = 'Emerson';		$homen_fname[] = 'Eduardo';
			$homen_fname[] = 'Elieser';		$homen_fname[] = 'Florencio';
			$homen_fname[] = 'Gaudencio';	$homen_fname[] = 'Guilherme';
			$homen_fname[] = 'Clodoaldo';	$homen_fname[] = 'Luiz';
			$homen_fname[] = 'Pedro';		$homen_fname[] = 'Paulo';
			$homen_fname[] = 'Heitor';		$homen_fname[] = 'Flavio';
			$homen_fname[] = 'Jurandir';	$homen_fname[] = 'Everton';
			$homen_fname[] = 'Mateus';		$homen_fname[] = 'Henrique';
			$homen_fname[] = 'Jobel';		$homen_fname[] = 'Severio';
			$homen_fname[] = 'Cicero';		$homen_fname[] = 'Loriano';
			$homen_fname[] = 'Wilson';		$homen_fname[] = 'Hugo';
			
			
			$num = rand(0,count($homen_fname)-1);
			return $homen_fname[$num];
		}else{
			$mulher_fname[] = 'Ana';		$mulher_fname[] = 'Alice';
			$mulher_fname[] = 'Amanda';		$mulher_fname[] = 'Betina';
			$mulher_fname[] = 'Beatriz';	$mulher_fname[] = 'Bianca';
			$mulher_fname[] = 'Claudia';	$mulher_fname[] = 'Clarice';
			$mulher_fname[] = 'Claudete';	$mulher_fname[] = 'Dinora';
			$mulher_fname[] = 'Doroteia';	$mulher_fname[] = 'Dilma';
			$mulher_fname[] = 'Eugenia';	$mulher_fname[] = 'Efigenia';
			$mulher_fname[] = 'Fabiana';	$mulher_fname[] = 'Florinda';
			$mulher_fname[] = 'Fernanda';	$mulher_fname[] = 'Glaucia';
			$mulher_fname[] = 'Genoveva';	$mulher_fname[] = 'Iolanda';
			$mulher_fname[] = 'Ivone';		$mulher_fname[] = 'Julia';
			$mulher_fname[] = 'Juliana';	$mulher_fname[] = 'Jandira';
			$mulher_fname[] = 'Karina';		$mulher_fname[] = 'Karen';
			$mulher_fname[] = 'Karmen';		$mulher_fname[] = 'Laura';
			$mulher_fname[] = 'Luana';		$mulher_fname[] = 'Luiza';
			
			$mulher_fname[] = 'Larissa';	$mulher_fname[] = 'Maria';
			$mulher_fname[] = 'Marcia';		$mulher_fname[] = 'Margarete';
			$mulher_fname[] = 'Mirian';		$mulher_fname[] = 'Manoela';
			$mulher_fname[] = 'Nadia';		$mulher_fname[] = 'Neiva';
			$mulher_fname[] = 'Patricia';	$mulher_fname[] = 'Paula';
			$mulher_fname[] = 'Perla';		$mulher_fname[] = 'Rafaela';
			$mulher_fname[] = 'Raissa';		$mulher_fname[] = 'Suzana';
			$mulher_fname[] = 'Suelen';		$mulher_fname[] = 'Sandra';
			$mulher_fname[] = 'Tatiana';	$mulher_fname[] = 'Tessalia';
			$mulher_fname[] = 'Veronica';	$mulher_fname[] = 'Valeria';
			$mulher_fname[] = 'Vilma';		$mulher_fname[] = 'Wanda';
			$mulher_fname[] = 'Anita';		$mulher_fname[] = 'Josefa';
			$mulher_fname[] = 'Fiorela';	$mulher_fname[] = 'Marina';
			$mulher_fname[] = 'Leticia';	$mulher_fname[] = 'Flavia';
			$mulher_fname[] = 'Izabel';		$mulher_fname[] = 'Iara';
			$mulher_fname[] = 'Eliane';		$mulher_fname[] = 'Izadora';
			$num = rand(0,count($mulher_fname)-1);
			return $mulher_fname[$num];
		}
	}
	
	private function getLastName(){
		$lname[] = 'Silva';		$lname[] = 'Carvalho';
		$lname[] = 'Coelho';	$lname[] = 'Oliveira';
		$lname[] = 'Santos';	$lname[] = 'Souza';
		$lname[] = 'Lima';		$lname[] = 'Costa';
		$lname[] = 'Pereira';	$lname[] = 'Almeida';
		$lname[] = 'Rodrigues';	$lname[] = 'Andrade';
		$lname[] = 'Alves';		$lname[] = 'Barbosa';
		$lname[] = 'Barros';	$lname[] = 'Batista';
		$lname[] = 'Borges';	$lname[] = 'Campos';
		$lname[] = 'Cardoso';	$lname[] = 'Castro';
		$lname[] = 'Costa';		$lname[] = 'Dias';
		$lname[] = 'Duarte';	$lname[] = 'Freitas';
		$lname[] = 'Fernandes';	$lname[] = 'Ferreira';
		$lname[] = 'Garcia';	$lname[] = 'Gomes';
		$lname[] = 'Goncalves';	$lname[] = 'Lima';
		$lname[] = 'Lopes';		$lname[] = 'Machado';
		$lname[] = 'Marques';	$lname[] = 'Martins';
		$lname[] = 'Medeiros';	$lname[] = 'Melo';
		$lname[] = 'Mendes';	$lname[] = 'Miranda';
		$lname[] = 'Monteiro';	$lname[] = 'Moraes';
		$lname[] = 'Moreira';	$lname[] = 'Moura';
		$lname[] = 'Nascimento';$lname[] = 'Nunes';
		$lname[] = 'Oliveira';	$lname[] = 'Parreira';
		$lname[] = 'Ramos';		$lname[] = 'Reis';
		$lname[] = 'Ribeiro';	$lname[] = 'Rocha';
		$lname[] = 'Rodrigues';	$lname[] = 'Santana';
		$lname[] = 'Soares';	$lname[] = 'Teixeira';
		$lname[] = 'Vieira';	$lname[] = 'Rivas';
		$lname[] = 'Paz';		$lname[] = 'Aquino';
		$lname[] = 'Mayer';		$lname[] = 'Blat';
		$num = rand(0,count($lname)-1);
		return $lname[$num];
	}
	
	//como sao apenas 8 cargos, farei um randomico de 1 a 7 (irei remover as diretorias
	private function getPosition($study){
		if($study==3 || $study==4 || $study==5 || $study==6){
			$min = 1; $max = 6;
		}else{
			$min = 7; $max = 8;
		}
		return rand($min,$max);
	}
	
	private function getSalary($position){
		$salary = 0;
		switch($position){
			case 1: $salary = 40000; break;//diretor
			case 2: $salary = 15000; break;//gerente
			case 3: $salary = 7000; break;//coordenador
			case 4: $salary = 5500; break;//analista
			case 5: $salary = 6000; break;//consultor
			case 6: $salary = 8500; break;//vendedor
			case 7: $salary = 1700; break;//auxiliar
			case 8: $salary = 998; break;//menor aprendiz
		}
		
		return $salary;
	}
	
	/**
	 * Metodo que retorna o nivel de escolaridade
	 */
	private function getStudy(){
		return rand(1,6);
	}
	
	private function getSector(){
		return rand(1,8);
	}
	
	/**
	 * Metodo que busca a data de admissao
	 */
	private function getAdmission(){
		$start = strtotime("1998-01-01");
		$end = strtotime("2019-04-08");
		
		$timestamp = mt_rand($start,$end);
		
		return date("Y-m-d",$timestamp);
	}
	
	private function getResignation($admission){
		$demissao[0] = null;
		$demissao[1] = null;
		$demissao[2] = null;
		$demissao[3] = null;
		$demissao[4] = null;
		$demissao[5] = null;
		$demissao[6] = date("Y-m-d",mt_rand( strtotime($admission), strtotime("2019-04-08")));
		
		return $demissao[rand(0,count($demissao)-1)];
	}
	
	private function getBirthday(){
		$start = strtotime("1960-01-01");
		$end = strtotime("2009-04-08");
		
		$timestamp = mt_rand($start,$end);
		
		return date("Y-m-d",$timestamp);
	}
}