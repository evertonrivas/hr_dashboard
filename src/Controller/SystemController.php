<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use \Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Number;
use Cake\I18n\Time;
use Cake\I18n\Date;

class SystemController extends AppController{	
    
    public function initialize() {
        parent::initialize();
    }
	
	/**
	 * Metodo que realiza a busca dos indicadores do sistema, para buscar basta passar o parametro indicator conforme abaixo
	 * @indicator $indicator string os possiveis valores para este caso sao: eft para Efetivo, trn para Turnover, abs para Absenteismo, des para Desligamento, prd para Produtividade
	 */
	public function getKpi($indicator){
		
		$this->autoRender = false;
		
		$retorno = 0;
		if($indicator=="trn"){
			//Calculo do Tornover: (((admitidos + demitidos)/2)/total)*100
			
			//realiza a busca dos que foram admitidos esse ano e nao foram demitidos
			$qryAdmitidos = TableRegistry::get('Employer')->find();
			$qryAdmitidos->select(['total' => $qryAdmitidos->func()->count('idemployer')]);
			$qryAdmitidos->where(function($exp){ return $exp->isNull('resignation'); });
			$qryAdmitidos->where(['YEAR(admission)' => date("Y")]);
			$admitidos = $qryAdmitidos->first()->total;
			
			//realiza a busca dos que foram demitidos neste ano
			$qryDemitidos = TableRegistry::get('Employer')->find();
			$qryDemitidos->select(['total' => $qryDemitidos->func()->count('idemployer')]);
			$qryDemitidos->where(['YEAR(resignation)' => date("Y")]);
			$demitidos = $qryDemitidos->first()->total;
			
			//realiza a busca de todos os funcionarios ativos da empresa ateh o ano anterior
			//ou seja remove todos os que foram demitidos no ano atual
			$qryTotal = TableRegistry::get('Employer')->find();
			$qryTotal->select(['total' => $qryTotal->func()->count('idemployer')]);
			$qryTotal->where(function($exp){
				return $exp->or_(function($or){
					return $or->isNull("resignation")
						->lt("YEAR(resignation)",date("Y"));
				});
			});
			$total = $qryTotal->first()->total;
			//print($qryTotal);
			
			//print("Admitidos: ".$admitidos.", Demitidos: ".$demitidos.", Total".$total);
			
			$retorno = number_format(((($admitidos + $demitidos)/2)/$total)*100,2);
			
		}elseif($indicator=="abs"){
			//o absenteismo eh composto por faltas, atrasos e saidas antecipadas justificadas ou nao
			
		}elseif($indicator=="des"){
			// a taxa de desligamento eh calculado pelo numero de (profissionais desligados/total de colaboradores) * 100
			
			//realiza a busca dos que foram demitidos
			$qryDemitidos = TableRegistry::get('Employer')->find();
			$qryDemitidos->select(['total' => $qryDemitidos->func()->count('idemployer')]);
			$qryDemitidos->where(function($exp){ return $exp->isNotNull('resignation'); });
			$demitidos = $qryDemitidos->first()->total;
			
			//realiza a busca de todos os funcionarios ativos da empresa
			$qryTotal = TableRegistry::get('Employer')->find();
			$qryTotal->select(['total' => $qryTotal->func()->count('idemployer')]);
			$qryTotal->where(function($exp){ return $exp->isNull('resignation'); });
			$total = $qryTotal->first()->total;
			
			$retorno = number_format(($demitidos/$total)*100,2);
			
		}elseif($indicator=="prd"){
			// o tempo de producao eh calculado com a (quantidade de horas extras/quantidade de horas trabalhadas) * 100
			
		}elseif($indicator=="eft"){
			$query = TableRegistry::get('Employer')->find();
			$query->select(['total' => $query->func()->count('idemployer')]);
			$retorno = $query->first()->total;
		}
		return $this->response->withStringBody(json_encode($retorno));
	}
	
	/**
	 * Realiza a busca do numero de funcionarios por setor
	 *
	 */
	public function getQtyPerSector(){
		$this->autoRender = false;
		
		//Primeiramente busca todos os setores para garantir os dados
		$sectors = TableRegistry::get('Sector')->find();
		foreach($sectors as $sector){
			$retorno['labels'][] = html_entity_decode($sector->name);
			//realiza a busca agora da contagem de funcionarios do setor, 
			//isso irah garantir que quanto nao houver funcionario a contagem sera zerada
			$employers = TableRegistry::get('Employer')->find();
			$employers->select(['total' => $employers->func()->count('idemployer')]);
			$employers->where(['idsector' => $sector->idsector]);
			
			$retorno['dados'][] = $employers->first()->total;
		}
		
		return $this->response->withType('application/json')->withStringBody(json_encode($retorno));
	}
	
	public function getValPerSector(){
		$this->autoRender = false;
		
		//Primeiramente busca todos os setores para garantir a existencia de dados mesmo nao havendo registros
		$sectors = TableRegistry::get('Sector')->find();
		foreach($sectors as $sector){
			$retorno['labels'][] = html_entity_decode($sector->name);
			//realiza a busca agora da contagem de funcionarios do setor, 
			//isso irah garantir que quanto nao houver funcionario a contagem sera zerada
			$employers = TableRegistry::get('Employer')->find();
			$employers->select(['total_salary' => $employers->func()->sum('salary'),'avg_salary' => $employers->func()->avg('salary')]);
			$employers->where(['idsector' => $sector->idsector]);
			
			$retorno['dados']['total'][] = (float)$employers->first()->total_salary;
			$retorno['dados']['media'][] = str_replace(",","",number_format($employers->first()->avg_salary,2));
		}
		
		return $this->response->withType('application/json')->withStringBody(json_encode($retorno));
	}
	
	public function getQtyPerGenre(){
		
		$this->autoRender = false;
		
		//para esse exemplo fixarei os generos em masculino, feminino e trans para facilitar
		
		//busca a contagem do feminino
		$query = TableRegistry::get('Employer')->find();
		$query->select(['total' => $query->func()->count('idemployer')]);
		$query->where(['genre' => 'F']);
		
		$retorno['genre'][] = 'Feminino';
		$retorno['total'][] = $query->first()->total;
		
		//busca a contagem do masculino
		$query = TableRegistry::get('Employer')->find();
		$query->select(['total' => $query->func()->count('idemployer')]);
		$query->where(['genre' => 'M']);
		
		$retorno['genre'][] = 'Masculino';
		$retorno['total'][] = $query->first()->total;
		
		//busca a contagem do trans
		$query = TableRegistry::get('Employer')->find();
		$query->select(['total' => $query->func()->count('idemployer')]);
		$query->where(['genre' => 'T']);
		
		$retorno['genre'][] = 'Transexual';
		$retorno['total'][] = $query->first()->total;
		
		return $this->response->withType('application/json')->withStringBody(json_encode($retorno));
	}
	
	/**
	 * Metodo que busca o numero de funcionarios por tempo de casa
	 */
	public function getQtyPerCiaTime(){
		
		$this->autoRender = false;
		
		//busca o funcionario e a data de admissao
		$query = TableRegistry::get('Employer')->find();
		$query->select(['idemployer','admission']);
		//coloca a restricao na query para buscar apessa funcionarios que estiverem com a data de demissao em branco
		$query->where(function($exp){ return $exp->isNull('resignation'); });
		
		$retorno = [];
		$hoje = new Date();
		
		//realiza o calculo de data e adiciona o funcionario em determinado periodo
		if($query->count()>0){
			
			//se houverem registros entao monta os intervalos de anos
			$retorno['labels'] = array('menos de 5',
				'de 5 a 10',
				'de 10 a 20',
				'de 20 a 30',
				'de 30 a 35',
				'mais de 35');
			
			//monta-se uma array pre-definida para colocar o somatorio
			$retorno['dados'][0] = 0;
			$retorno['dados'][1] = 0;
			$retorno['dados'][2] = 0;
			$retorno['dados'][3] = 0;
			$retorno['dados'][4] = 0;
			$retorno['dados'][5] = 0;
			
			$tempo_de_casa = 0;
			foreach($query as $row){
				
				//realiza o calculo baseado em ano, mes e dia para determinar o tempo em anos
				$tempo_de_casa = $hoje->format("Y") - $row->admission->format("Y");
				$mes_diff = $hoje->format("m") - $row->admission->format("m");
				$dia_diff = $hoje->format("d") - $row->admission->format("d");
				
				if($mes_diff < 0){
					$tempo_de_casa--;
				}elseif($mes_diff == 0 && $dia_diff < 0){
					$tempo_de_casa--;
				}
				
				//conforme o valor da variavel tempo de casa sera incrementado o valor
				if($tempo_de_casa <= 5){
					$retorno['dados'][0] += 1;
				}elseif($tempo_de_casa > 5 && $tempo_de_casa <= 10){
					$retorno['dados'][1] += 1;
				}elseif($tempo_de_casa > 10 && $tempo_de_casa <= 20){
					$retorno['dados'][2] += 1;
				}elseif($tempo_de_casa > 20 && $tempo_de_casa <= 30){
					$retorno['dados'][3] += 1;
				}elseif($tempo_de_casa > 30 && $tempo_de_casa <= 35){
					$retorno['dados'][4] += 1;
				}else{
					$retorno['dados'][5] += 1;
				}
			}
		}
		
		return $this->response->withType('application/json')->withStringBody(json_encode($retorno));
	}
	
	/**
	 * Metodo que busca o numero de funcionarios por idade
	 */
	public function getQtyPerAge(){
		
		$this->autoRender = false;
		
		//busca o funcionario e a data de aniversario
		$query = TableRegistry::get('Employer')->find();
		$query->select(['idemployer','birthday']);
		//coloca a restricao na query para buscar apessa funcionarios que estiverem com a data de demissao em branco
		$query->where(function($exp){ return $exp->isNull('resignation'); });
		
		$retorno = [];
		$hoje = new Date();
		
		//realiza o calculo de data e adiciona o funcionario em determinado periodo
		if($query->count()>0){
			
			//se houverem registros entao monta os intervalos de anos
			$retorno['labels'] = array('< 18','de 18 a 23','de 24 a 34','de 35 a 50','maiores de 50');
			$retorno['dados'][0] = 0;
			$retorno['dados'][1] = 0;
			$retorno['dados'][2] = 0;
			$retorno['dados'][3] = 0;
			$retorno['dados'][4] = 0;
			
			$idade = 0;
			foreach($query as $row){
				
				//realiza o calculo baseado em ano, mes e dia para determinar o tempo em anos
				$idade = $hoje->format("Y") - $row->birthday->format("Y");
				$mes_diff = $hoje->format("m") - $row->birthday->format("m");
				$dia_diff = $hoje->format("d") - $row->birthday->format("d");
				
				if($mes_diff < 0){
					$idade--;
				}elseif($mes_diff == 0 && $dia_diff < 0){
					$idade--;
				}
				
				//conforme o valor da variavel tempo de casa sera incrementado o valor
				if($idade <= 18){
					$retorno['dados'][0] += 1;
				}elseif($idade > 18 && $idade <=23){
					$retorno['dados'][1] += 1;
				}elseif($idade > 23 && $idade <=34){
					$retorno['dados'][2] += 1;
				}elseif($idade > 34 && $idade <= 50){
					$retorno['dados'][3] += 1;
				}else{
					$retorno['dados'][4] += 1;
				}
			}
		}
		
		return $this->response->withType('application/json')->withStringBody(json_encode($retorno));
	}
	
	/**
	 * Metodo que busca o somatorio dos salarios dos colaboradores por setor e tambem a media salarial do setor
	 */
	public function getSalaryByPosition(){
				
		$this->autoRender = false;
		
		$positions = TableRegistry::get("Position")->find();
		
		$retorno = [];
		
		foreach($positions as $position){
			$query = TableRegistry::get("Employer")->find();
			$query->select(['total_salary' => $query->func()->sum("salary"),'avg_salary' => $query->func()->avg("salary")]);
			$query->where(['idposition' => $position->idposition]);
			$query->where(function($exp){ return $exp->isNull('resignation'); });
			
			$retorno['labels'][]       = html_entity_decode($position->name);
			$retorno['total_salary'][] = (float)$query->first()->total_salary;
			$retorno['avg_salary'][]   = str_replace(",","",number_format($query->first()->avg_salary,2));
		}
		
		return $this->response->withType('application/json')->withStringBody(json_encode($retorno));
	}
	
	/**
	 * Metodo que busca o somatorio dos salarios dos colaboradores por nivel de estudo e tambem a media salarial
	 */
	public function getSalaryByStudy(){
		
		$this->autoRender = false;
		
		$studies = TableRegistry::get("StudyLevel")->find();
		
		$retorno = [];
		
		foreach($studies as $study){
			$query = TableRegistry::get("Employer")->find();
			$query->select(['total_salary' => $query->func()->sum("salary"),'avg_salary' => $query->func()->avg("salary")]);
			$query->where(['idstudy_level' => $study->idstudy_level]);
			$query->where(function($exp){ return $exp->isNull('resignation'); });
			
			$retorno['labels'][]       = html_entity_decode($study->name);
			$retorno['total_salary'][] = (float)$query->first()->total_salary;
			$retorno['avg_salary'][]   = str_replace(",","",number_format($query->first()->avg_salary,2));
		}
		
		return $this->response->withType('application/json')->withStringBody(json_encode($retorno));
	}
}