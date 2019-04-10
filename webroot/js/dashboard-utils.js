//aqui estao as cores que serao utilizadas nos graficos do dashboard
barsColors = {
	red: 'rgba(255, 99, 132, 0.5)',
	orange: 'rgba(255, 159, 64, 0.5)',
	yellow: 'rgba(255, 205, 86, 0.5)',
	green: 'rgba(75, 192, 192, 0.5)',
	blue: 'rgba(54, 162, 235, 0.5)',
	purple: 'rgba(153, 102, 255, 0.5)',
	grey: 'rgba(201, 203, 207, 0.5)',
	black: 'rgba(0, 0, 0, 0.5)',
	teal: 'rgba(0, 128, 128, 0.5)',
	pink: 'rgba(255,192,203,0.5)'
};

chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)',
	black: 'rgb(0, 0, 0)',
	teal: 'rgb(0, 128, 128)',
	pink: 'rgb(255,192,203)'
};

function getKpi(wish){
	$.ajax({
		headers:{
			'X-CSRF-Token': csrf
		},
		type: 'post',
		url: '/system/get_kpi/'+wish,
		dataType: 'json',
		success: function(retorno){
			if(wish=='trn'){
				$("#txt_turnover").html(retorno);
			}
			if(wish=='abs'){
				$("#txt_absenteismo").html(retorno);
			}
			if(wish=='des'){
				$("#txt_desligamento").html(retorno);
			}
			if(wish=='frq'){
				$("#txt_frequencia").html(retorno);
			}
			if(wish=='eft'){
				$("#txt_efetivo").html(retorno);
			}
		}
	});
}

function getQtyPerSector(){
	$.ajax({
		headers: {
			'X-CSRF-Token': csrf
		},
		type: 'post',
		url: '/system/get_qty_per_sector/',
		dataType: 'json',
		success:function(retorno){
			var myBarChart = new Chart(document.getElementById("chart_qty_per_sector"), {
				type: 'bar',
				data: {
					labels: retorno.labels,
					datasets:[{
						label: "Setores",
						data : retorno.dados,
						fill : false,
						backgroundColor : [barsColors.red,barsColors.orange,barsColors.yellow,barsColors.green,barsColors.blue,barsColors.purple,barsColors.grey,barsColors.black],
						borderColor     : [chartColors.red,chartColors.orange,chartColors.yellow,chartColors.green,chartColors.blue,chartColors.purple,chartColors.grey,chartColors.black],
						borderWidth     : 1
					}]
				},
				options : {
					scales : {
						yAxes :[{
							ticks : {
								beginAtZero : true
							}
						}]
					}
				}
			});
		}
	});
}

function getValPerSector(){
	$.ajax({
		headers: {
			'X-CSRF-Token': csrf
		},
		type: 'post',
		url: '/system/get_val_per_sector/',
		dataType: 'json',
		success:function(retorno){
			var myBarChart = new Chart(document.getElementById("chart_val_per_sector"), {
				type: 'bar',
				data: {
					labels: retorno.labels,
					datasets:[{
						type: "line",
						label: "Media salarial",
						data: retorno.dados.media,
						borderColor: chartColors.teal,
						borderWidth: 2,
						fill: false
					},
					{
						type: "bar",
						label: "Folha Total",
						data : retorno.dados.total,
						fill : false,
						backgroundColor: [barsColors.red,barsColors.orange,barsColors.yellow,barsColors.green,barsColors.blue,barsColors.purple,barsColors.grey,barsColors.black],
						borderColor: [chartColors.red,chartColors.orange,chartColors.yellow,chartColors.green,chartColors.blue,chartColors.purple,chartColors.grey,chartColors.black],
						borderWidth: 1
					}]
				},
				options: {
					scales: {
						yAxes:[{
							ticks: {
								beginAtZero: true
							}
						}]
					}
				}
			});
		}
	});
}

function getQtyPerGenre(){
	$.ajax({
		headers:{
			'X-CSRF-Token': csrf
		},
		type: 'post',
		url: '/system/get_qty_per_genre/',
		dataType: 'json',
		success:function(retorno){
			var myPieChart = new Chart(document.getElementById('chart_qty_by_genre'),{
				type:'doughnut',
				data:{
					datasets: [{
						data: retorno.total,
						backgroundColor: [chartColors.pink,chartColors.blue,chartColors.orange,chartColors.teal,chartColors.purple],
						label: 'Genero'
					}],
					labels: retorno.genre
				},
				options:{
					responsive : true,
					animation : {
						animateScale : true,
						animateRotate : true
					},
					legend:{
						position: 'left'
					}
				}
			});
		}
	});
}

function getQtyPerCiaTime(){
	$.ajax({
		headers:{
			'X-CSRF-Token': csrf
		},
		type: 'post',
		url: '/system/get_qty_per_cia_time/',
		dataType: 'json',
		success:function(retorno){
			var myPieChart = new Chart(document.getElementById('chart_qty_by_cia_time'),{
				type:'doughnut',
				data:{
					datasets: [{
						data: retorno.dados,
						backgroundColor: [chartColors.red,chartColors.orange,chartColors.yellow,chartColors.green,chartColors.purple,chartColors.grey,chartColors.black,chartColors.teal,chartColors.pink],
					}],
					labels: retorno.labels
				},
				options:{
					responsive : true,
					animation : {
						animateScale : true,
						animateRotate : true
					},
					legend:{
						position: 'left'
					}
				}
			});
		}
	});
}

function getQtyPerAge(){
	$.ajax({
		headers:{
			'X-CSRF-Token': csrf
		},
		type: 'post',
		url: '/system/get_qty_per_age/',
		dataType: 'json',
		success:function(retorno){
			var myPieChart = new Chart(document.getElementById('chart_qty_per_age'),{
				type:'doughnut',
				data:{
					datasets: [{
						data: retorno.dados,
						backgroundColor: [chartColors.red,chartColors.orange,chartColors.yellow,chartColors.green,chartColors.purple,chartColors.grey,chartColors.black,chartColors.teal,chartColors.pink],
					}],
					labels: retorno.labels
				},
				options:{
					responsive : true,
					animation : {
						animateScale : true,
						animateRotate : true
					},
					legend:{
						position: 'left'
					}
				}
			});
		}
	});
}

function getSalaryByStudy(){
	$.ajax({
		headers: {
			'X-CSRF-Token': csrf
		},
		type: 'post',
		url: '/system/get_salary_by_study/',
		dataType: 'json',
		success:function(retorno){
			var myBarChart = new Chart(document.getElementById("chart_salary_by_study"), {
				type: 'bar',
				data: {
					labels: retorno.labels,
					datasets:[{
						type: "line",
						label: "Media salarial",
						data: retorno.avg_salary,
						borderColor: chartColors.teal,
						borderWidth: 2,
						fill: false
					},
					{
						type: "bar",
						label: "Nivel de Estudo",
						data : retorno.total_salary,
						fill : false,
						backgroundColor: [barsColors.red,barsColors.orange,barsColors.yellow,barsColors.green,barsColors.blue,barsColors.purple,barsColors.grey,barsColors.black],
						borderColor: [chartColors.red,chartColors.orange,chartColors.yellow,chartColors.green,chartColors.blue,chartColors.purple,chartColors.grey,chartColors.black],
						borderWidth: 1
					}]
				},
				options: {
					scales: {
						yAxes:[{
							ticks: {
								beginAtZero: true
							}
						}]
					}
				}
			});
		}
	});
}

function getSalaryByPosition(){
	$.ajax({
		headers: {
			'X-CSRF-Token': csrf
		},
		type: 'post',
		url: '/system/get_salary_by_position/',
		dataType: 'json',
		success:function(retorno){
			var myBarChart = new Chart(document.getElementById("chart_salary_by_position"), {
				type: 'bar',
				data: {
					labels: retorno.labels,
					datasets:[{
						type: "line",
						label: "Media salarial",
						data: retorno.avg_salary,
						borderColor: chartColors.teal,
						borderWidth: 2,
						fill: false
					},
					{
						type: "bar",
						label: "Cargo",
						data : retorno.total_salary,
						fill : false,
						backgroundColor: [barsColors.red,barsColors.orange,barsColors.yellow,barsColors.green,barsColors.blue,barsColors.purple,barsColors.grey,barsColors.black],
						borderColor: [chartColors.red,chartColors.orange,chartColors.yellow,chartColors.green,chartColors.blue,chartColors.purple,chartColors.grey,chartColors.black],
						borderWidth: 1
					}]
				},
				options: {
					scales: {
						yAxes:[{
							ticks: {
								beginAtZero: true
							}
						}]
					}
				}
			});
		}
	});
}