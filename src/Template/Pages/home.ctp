<div class="card">
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-chart-line"></i> Estrat&eacute;gico</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-chart-line"></i> Anal&iacute;tico</a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
				<!--INICIO DOS INDICADORES-->
				<div class="row">
					<div class="col-sm">
						<div class="card text-white bg-info mb-3">
							<div class="card-body text-center">
								Efetivo Total<h1><strong><span id='txt_efetivo' name='txt_efetivo'>0</span></strong></h1>
							</div>
						</div>
					</div>
					<div class="col-sm">
						<div class="card text-white bg-danger mb-3">
							<div class="card-body text-center">
								Turnover ano<h1><strong><span id='txt_turnover' name='txt_turnover'>0</span></strong></h1>
							</div>
						</div>
					</div>
					<div class="col-sm">
						<div class="card text-white bg-warning mb-3">
							<div class="card-body text-center">
								Absente&iacute;smo ano<h1><strong><span id='txt_absenteismo' name='txt_absenteismo'>0</span></strong></h1>
							</div>
						</div>
					</div>
					<div class="col-sm">
						<div class="card text-white bg-success mb-3">
							<div class="card-body text-center">
								Taxa Desligamento<h1><strong><span id='txt_desligamento' name='txt_desligamento'>0</span></strong></h1>
							</div>
						</div>
					</div>
					<div class="col-sm">
						<div class="card text-white bg-secondary mb-3">
							<div class="card-body text-center">
								Frequ&ecirc;ncia ano<h1><strong><span id='txt_frequencia' name='txt_frequencia'>0</span></strong></h1>
							</div>
						</div>
					</div>
				</div>
				<!-- FIM DOS INDICADORES -->
				<br/>
				<!-- INICIO DO GRAFICO DE BARRAS -->
				<div class="row">
					<div class="col-sm">
						<canvas id="chart_qty_per_sector"></canvas>
					</div>
					<div class="col-sm">
						<canvas id="chart_val_per_sector"></canvas>
					</div>
				</div>
				<!-- FIM DO GRAFICO DE BARRAS -->
			</div>
			<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
				<div class="row">
					<div class="col-sm" id="bg_donut_genre">
						<canvas id="chart_qty_by_genre"></canvas>
					</div>
					<div class="col-sm" id="bg_donut_cia_time">
						<canvas id="chart_qty_by_cia_time"></canvas>
					</div>
					<div class="col-sm" id="bg_donut_age">
						<canvas id="chart_qty_per_age"></canvas>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col-sm">
						<canvas id="chart_salary_by_position"></canvas>
					</div>
					<div class="col-sm">
						<canvas id="chart_salary_by_study"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
	getKpi('eft');
	getKpi('trn');
	getKpi('des');
	getQtyPerSector();
	getValPerSector();
});


$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	if(e.target.id=="profile-tab"){
		getQtyPerGenre();
		getQtyPerCiaTime();
		getQtyPerAge();
		getSalaryByStudy();
		getSalaryByPosition();
	}else{
		//realiza a busca dos indicadores do painel principal
		getKpi('eft');
		getKpi('trn');
		getKpi('des');
		getQtyPerSector();
		getValPerSector();
	}
});
</script>