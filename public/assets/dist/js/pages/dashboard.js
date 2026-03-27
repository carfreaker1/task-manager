/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/

/* global moment:false, Chart:false, Sparkline:false */
$(function () {
  'use strict'
  //-------------
 //- BAR CHART -
 //-------------
 function loadTaskProgres(){

    $.ajax({
        url: '/dashboard/project-progress',
        type: "GET",
        success: function(response){

            let labels = [];
            let percentages = [];

            response.forEach(function(item){
                labels.push(item.project);
                percentages.push(item.percentage);
            });

            var areaChartData = {
                labels  : labels,
                datasets: [
                    {
                        label               : 'Project Completion %',
                        backgroundColor     : 'rgba(60,141,188,0.9)',
                        data                : percentages
                    }
                ]
            }

            var barChartCanvas = $('#barChart').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)

            var barChartOptions = {
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true,  // 🔥 important
                    min: 0,             // force start from 0
                    max: 100,           // optional but recommended
                    stepSize: 10
                  }
                }]
              }
            };

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            });

        }
    });

};
    // Load on page start
    loadTaskProgres();
  
    // OPTIONAL: Auto refresh every 30 seconds
    setInterval(loadTaskProgres, 30000);
    function employeePerformance(){

      $.ajax({
          url: '/dashboard/employee-performance',
          type: "GET",
          success: function(response){
  
              let labels = [];
              let assigned = [];
              let completed = [];
              let pending = [];
  
              response.forEach(function(item){
                  labels.push(item.employee);
                  assigned.push(item.assigned);
                  completed.push(item.completed);
                  pending.push(item.pending);
              });
  
              var areaChartData = {
                  labels: labels,
                  datasets: [
                      {
                          label: 'Assigned Tasks',
                          backgroundColor: 'rgba(60,141,188,0.9)',
                          data: assigned
                      },
                      {
                          label: 'Completed Tasks',
                          backgroundColor: 'rgba(0,166,90,0.9)',
                          data: completed
                      },
                      {
                          label: 'Pending Tasks',
                          backgroundColor: 'rgba(243,156,18,0.9)',
                          data: pending
                      }
                  ]
              };
  
              var barChartCanvas = $('#barChartEPPerformance').get(0).getContext('2d');
  
              if (window.employeeChart) {
                  window.employeeChart.destroy();
              }
  
              var barChartOptions = {
                  responsive: true,
                  maintainAspectRatio: false,
                  scales: {
                      yAxes: [{
                          ticks: {
                              beginAtZero: true,
                              stepSize: 1
                          }
                      }]
                  }
              };
  
              window.employeeChart = new Chart(barChartCanvas, {
                  type: 'bar',
                  data: areaChartData,
                  options: barChartOptions
              });
          }
      });
  };
  
  // Initial Load
  employeePerformance();
  
  // 🔁 Auto refresh every 30 sec
  setInterval(employeePerformance, 30000);
 /* ================================
   STACKED BAR CHART – TASK STATUS
================================ */

var stackedCtx = $('#stackedBarChart').get(0).getContext('2d');
var stackedBarChart;

function loadStackedTaskStatus() {
  $.ajax({
    url: '/dashboard/task-status-stacked', // Laravel route
    type: 'GET',
    success: function (response) {

      var stackedBarChartData = {
        labels: response.labels,
        datasets: [
          {
            label: 'Pending',
            backgroundColor: '#f39c12',
            data: response.pending
          },
          {
            label: 'In Progress',
            backgroundColor: '#00c0ef',
            data: response.in_progress
          },
          {
            label: 'Completed',
            backgroundColor: '#00a65a',
            data: response.completed
          }
        ]
      };

      var stackedBarChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          xAxes: [{
            stacked: true
          }],
          yAxes: [{
            stacked: true,
            ticks: {
              beginAtZero: true,
              stepSize: 1
            }
          }]
        },
        legend: {
          position: 'top'
        }
      };

      // destroy old chart before redraw
      if (stackedBarChart) {
        stackedBarChart.destroy();
      }

      stackedBarChart = new Chart(stackedCtx, {
        type: 'bar',
        data: stackedBarChartData,
        options: stackedBarChartOptions
      });
    },
    error: function () {
      console.log('Failed to load stacked task status data');
    }
  });
}

// Initial load
loadStackedTaskStatus();

// Auto refresh every 30 seconds
setInterval(loadStackedTaskStatus, 30000);
})


$(function () {
  'use strict'

  // Make the dashboard widgets sortable Using jquery UI
  $('.connectedSortable').sortable({
    placeholder: 'sort-highlight',
    connectWith: '.connectedSortable',
    handle: '.card-header, .nav-tabs',
    forcePlaceholderSize: true,
    zIndex: 999999
  })
  $('.connectedSortable .card-header').css('cursor', 'move')

  // jQuery UI sortable for the todo list
  $('.todo-list').sortable({
    placeholder: 'sort-highlight',
    handle: '.handle',
    forcePlaceholderSize: true,
    zIndex: 999999
  })

  // bootstrap WYSIHTML5 - text editor
  $('.textarea').summernote()

  $('.daterange').daterangepicker({
    ranges: {
      Today: [moment(), moment()],
      Yesterday: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days': [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month': [moment().startOf('month'), moment().endOf('month')],
      'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate: moment()
  }, function (start, end) {
    // eslint-disable-next-line no-alert
    alert('You chose: ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
  })

  /* jQueryKnob */
  $('.knob').knob()

  // jvectormap data
  var visitorsData = {
    US: 398, // USA
    SA: 400, // Saudi Arabia
    CA: 1000, // Canada
    DE: 500, // Germany
    FR: 760, // France
    CN: 300, // China
    AU: 700, // Australia
    BR: 600, // Brazil
    IN: 800, // India
    GB: 320, // Great Britain
    RU: 3000 // Russia
  }
  // World map by jvectormap
  $('#world-map').vectorMap({
    map: 'usa_en',
    backgroundColor: 'transparent',
    regionStyle: {
      initial: {
        fill: 'rgba(255, 255, 255, 0.7)',
        'fill-opacity': 1,
        stroke: 'rgba(0,0,0,.2)',
        'stroke-width': 1,
        'stroke-opacity': 1
      }
    },
    series: {
      regions: [{
        values: visitorsData,
        scale: ['#ffffff', '#0154ad'],
        normalizeFunction: 'polynomial'
      }]
    },
    onRegionLabelShow: function (e, el, code) {
      if (typeof visitorsData[code] !== 'undefined') {
        el.html(el.html() + ': ' + visitorsData[code] + ' new visitors')
      }
    }
  })

  // Sparkline charts
  var sparkline1 = new Sparkline($('#sparkline-1')[0], { width: 80, height: 50, lineColor: '#92c1dc', endColor: '#ebf4f9' })
  var sparkline2 = new Sparkline($('#sparkline-2')[0], { width: 80, height: 50, lineColor: '#92c1dc', endColor: '#ebf4f9' })
  var sparkline3 = new Sparkline($('#sparkline-3')[0], { width: 80, height: 50, lineColor: '#92c1dc', endColor: '#ebf4f9' })

  sparkline1.draw([1000, 1200, 920, 927, 931, 1027, 819, 930, 1021])
  sparkline2.draw([515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921])
  sparkline3.draw([15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21])

  // The Calender
  $('#calendar').datetimepicker({
    format: 'L',
    inline: true
  })

  // SLIMSCROLL FOR CHAT WIDGET
  $('#chat-box').overlayScrollbars({
    height: '250px'
  })

  /* Chart.js Charts */
  // Sales chart
  var salesChartCanvas = document.getElementById('revenue-chart-canvas').getContext('2d')
  // $('#revenue-chart').get(0).getContext('2d');

  var salesChartData = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
      {
        label: 'Digital Goods',
        backgroundColor: 'rgba(60,141,188,0.9)',
        borderColor: 'rgba(60,141,188,0.8)',
        pointRadius: false,
        pointColor: '#3b8bba',
        pointStrokeColor: 'rgba(60,141,188,1)',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data: [28, 48, 40, 19, 86, 27, 90]
      },
      {
        label: 'Electronics',
        backgroundColor: 'rgba(210, 214, 222, 1)',
        borderColor: 'rgba(210, 214, 222, 1)',
        pointRadius: false,
        pointColor: 'rgba(210, 214, 222, 1)',
        pointStrokeColor: '#c1c7d1',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(220,220,220,1)',
        data: [65, 59, 80, 81, 56, 55, 40]
      }
    ]
  }

  var salesChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        gridLines: {
          display: false
        }
      }],
      yAxes: [{
        gridLines: {
          display: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart(salesChartCanvas, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: salesChartData,
    options: salesChartOptions
  })

  $(function () {

    var pieChartCanvas = $('#sales-chart-canvas').get(0).getContext('2d');
  
    var pieOptions = {
      legend: {
        display: true
      },
      maintainAspectRatio: false,
      responsive: true
    };
  
    // Create empty chart first
    var pieChart = new Chart(pieChartCanvas, {
      type: 'doughnut',
      data: {
        labels: ['Pending', 'In Progress', 'Completed', 'Overdue'],
        datasets: [{
          data: [0, 0, 0, 0], // empty initially
          backgroundColor: ['#f39c12', '#00c0ef', '#00a65a', '#f56954']
        }]
      },
      options: pieOptions
    });
  
    // AJAX function
    function loadTaskStatus() {
      $.ajax({
        url: '/dashboard/task-status', // your route
        type: 'GET',
        success: function (response) {
  
          // Update chart data
          pieChart.data.datasets[0].data = [
            response.pendingTask,
            response.inProgressTask,
            response.completedTask,
            response.overDueTask
          ];
  
          pieChart.update(); // refresh chart
        },
        error: function () {
          console.log('Failed to load task status data.');
        }
      });
    }
  
    // Load on page start
    loadTaskStatus();
  
    // OPTIONAL: Auto refresh every 30 seconds
    setInterval(loadTaskStatus, 30000);
  
  });

  // Sales graph chart
  var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d')
var salesGraphChart;

// function to load data via AJAX
function loadTaskCompletionTrend() {

  $.ajax({
    url: "/dashboard/task-completion-trend", // Laravel route
    type: "GET",
    success: function (response) {

      var salesGraphChartData = {
        labels: response.labels, // 👈 dynamic dates
        datasets: [
          {
            label: 'Tasks Completed',
            fill: false,
            borderWidth: 2,
            lineTension: 0,
            spanGaps: true,
            borderColor: '#efefef',
            pointRadius: 3,
            pointHoverRadius: 7,
            pointColor: '#efefef',
            pointBackgroundColor: '#efefef',
            data: response.values // 👈 dynamic counts
          }
        ]
      }

      var salesGraphChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            ticks: {
              fontColor: '#efefef'
            },
            gridLines: {
              display: false,
              color: '#efefef',
              drawBorder: false
            }
          }],
          yAxes: [{
            ticks: {
              stepSize: 1,
              fontColor: '#efefef'
            },
            gridLines: {
              display: true,
              color: '#efefef',
              drawBorder: false
            }
          }]
        }
      }

      // 🔁 destroy old chart before redraw
      if (salesGraphChart) {
        salesGraphChart.destroy();
      }

      salesGraphChart = new Chart(salesGraphChartCanvas, {
        type: 'line',
        data: salesGraphChartData,
        options: salesGraphChartOptions
      });
    }
  });
}

// Initial load
loadTaskCompletionTrend();

// ⏱ Auto refresh every 60 seconds
setInterval(loadTaskCompletionTrend, 60000);
  
})
