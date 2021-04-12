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

$('.waka-chart').each(function () {
    var $element = $(this);
    //var title = $element.data('title');
    console.log($element);
});
var ctx = document.getElementById('myChart').getContext('2d');
var graph = document.getElementById('myChart');

var options = JSON.parse(graph.dataset.options)
var chartdata = JSON.parse(graph.dataset.chartdata)

var datas = {
    type: options.type,
    data: chartdata,
    options: {
        maintainAspectRatio: false,
        animation: false,
    },
}
var myChart = new Chart(ctx, datas);