$(document).ready(function () {
    'use strict';
    (function (setLineDash) {
        CanvasRenderingContext2D.prototype.setLineDash = function () {
            if (!arguments[0].length) {
                arguments[0] = [1, 0];
            }
            // Now, call the original method
            return setLineDash.apply(this, arguments);
        };
    })(CanvasRenderingContext2D.prototype.setLineDash);
    Function.prototype.bind = Function.prototype.bind || function (thisp) {
        var fn = this;
        return function () {
            return fn.apply(thisp, arguments);
        };
    };

    var charts = $(".waka-chart-canvas");
    charts.each(function (i, obj) {
        console.log(obj)
        var ctx = obj.getContext('2d');
        var graph = obj;

        var options = JSON.parse(graph.dataset.options)
        var chartdata = JSON.parse(graph.dataset.chartdata)

        var chartType = options.type
        console.log(options)
        var datas = {}
        if (chartType == 'pie' || 'doughnut') {
            var datas = {
                type: options.type,
                data: chartdata,
                options: {
                    maintainAspectRatio: false,
                    animation: false,
                },
            }
        } else if (!chart2axis) {
            var datas = {
                type: options.type,
                data: chartdata,
                options: {
                    maintainAspectRatio: false,
                    animation: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: (options.beginAtZero == '1')
                            }
                        }]
                    }
                }
            }
        } else {
            var datas = {
                type: options.type,
                data: chartdata,
                options: {
                    maintainAspectRatio: false,
                    animation: false,
                    scales: {
                        yAxes: [{
                            type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                            display: true,
                            position: 'left',
                            id: 'y_1',
                            ticks: {
                                beginAtZero: (options.beginAtZero == '1')
                            }

                        }, {
                            type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                            display: true,
                            position: 'right',
                            id: 'y_2',
                            ticks: {
                                beginAtZero: (options.beginAtZero == '1')
                            }
                        }

                        ],
                    }
                }
            }
        }
        var myChart = new Chart(ctx, datas);
    });
})