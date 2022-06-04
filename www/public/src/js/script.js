const showMenu = (toggleId, navbarId, bodyId)=>{
    const toggle = document.getElementById(toggleId),
    navbar = document.getElementById(navbarId),
    bodypadding = document.getElementById(bodyId)
  
    if(toggle && navbar){
      toggle.addEventListener('click', ()=>{
        navbar.classList.toggle('expander')
  
        bodypadding.classList.toggle('body-pd')
      })
    }
  }
  showMenu('nav-toggle','navbar','body-pd')
  
  const linkColor = document.querySelectorAll('.nav__link')
  function colorLink(){
    linkColor.forEach(l=> l.classList.remove('active'))
    this.classList.add('active')
  }
  linkColor.forEach(l=> l.addEventListener('click', colorLink))
  
  
  const linkCollapse = document.getElementsByClassName('collapse__link')
  var i
  
  for(i=0;i<linkCollapse.length;i++){
    linkCollapse[i].addEventListener('click', function(){
      const collapseMenu = this.nextElementSibling
      collapseMenu.classList.toggle('showCollapse')
  
      const rotate = collapseMenu.previousElementSibling
      rotate.classList.toggle('rotate')
    })
  }

// Dash

var ctxU = document.getElementById('chartU').getContext('2d');
var gradient = ctxU.createLinearGradient(0, 0, 0, 450);
gradient.addColorStop(0, 'rgba(255, 255,255, 0.5)');
gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
var myChart = new Chart(ctxU, {
  type: 'line',
  data: {
    labels: ["10/01/22","11/01/22","12/01/22","13/01/22","14/01/22","15/01/22"],//Axes abscisses
    datasets: [{
      label: "Nombre d'utilisateur unique",
      data: [12, 19, 3, 5, 2, 3],//Axes abscisses
      lineTension: 0,
      backgroundColor: gradient,
      pointBackgroundColor: '#FFF',
      borderWidth: 2,
      borderColor: '#FFF'
    }]
  },
  options:{
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: "#FFF",
          beginAtZero: true,
          tickLength: 10
        },
        gridLines: {
          color: "#FFF",
          drawOnChartArea: false
        }
      }],
      yAxes: [{
        ticks: {
          fontColor: "#FFF",
          beginAtZero: true
        },
        gridLines: {
          color: "#FFF",
          drawOnChartArea: false
        }
      }]  
    },
    tooltips: {
      titleFontColor: '#6FA7FF',
    }
  }
});
var ctxL = document.getElementById('chartL').getContext('2d');
var gradient = ctxL.createLinearGradient(0, 0, 0, 450);
gradient.addColorStop(0, 'rgba(255, 255,255, 0.5)');
gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
var myChart = new Chart(ctxL, {
  type: 'line',
  data: {
    labels: ["10/01/22","11/01/22","12/01/22","13/01/22","14/01/22","15/01/22"],//Axes abscisses
    datasets: [{
      label: "Nombre d'utilisateur unique",
      data: [12, 19, 3, 5, 2, 3],//Axes abscisses
      lineTension: 0,
      backgroundColor: gradient,
      pointBackgroundColor: '#FFF',
      borderWidth: 2,
      borderColor: '#FFF'
    }]
  },
  options:{
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: "#FFF",
          beginAtZero: true,
          tickLength: 10
        },
        gridLines: {
          color: "#FFF",
          drawOnChartArea: false
        }
      }],
      yAxes: [{
        ticks: {
          fontColor: "#FFF",
          beginAtZero: true
        },
        gridLines: {
          color: "#FFF",
          drawOnChartArea: false
        }
      }]  
    },
    tooltips: {
      titleFontColor: '#6FA7FF',
    }
  }
});
//Graph des presences
var ctx = document.getElementById('myChart').getContext('2d');
var gradient = ctx.createLinearGradient(0, 0, 0, 450);
gradient.addColorStop(0, 'rgba(255, 255,255, 0.5)');
gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
var myChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Absent","Present"],//Axes abscisses
    datasets: [{
      label: "Nombre d'utilisateur unique",
      backgroundColor: ["#E90000", "#00E933"],
      data: [23,195],//Axes abscisses
      borderColor: "#3E6F8D",
      borderWidth: 2,
    }]
  },
  options:{
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      labels:{
        fontColor: "white"
      }
    },
    tooltips: {
      titleFontColor: '#6FA7FF',
    }
  }
});