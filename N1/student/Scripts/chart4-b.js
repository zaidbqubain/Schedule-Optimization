$(function(){

//getting Canvas Component to draw the chart    
var ctx = $('#CourseTimeDistribution-b');



//Replace the Dataset with Json Object in the following format
//"jsonarray":[{ "XAxis": "XAxisValue1","YAxis":"YAxisValue1", ....},
//,{"XAxis": "XAxisValue2","YAxis":"YAxisValue2, .....} ,{}....]

var DatasetBar = {
    "jsonarray": pieChart2Obj
 };
 //getting XAxis Values for Bar-1
 var labels = DatasetBar.jsonarray.map(function(e) {
    return e.Course;
 });

 //getting YAxis Values for Bar-1
 var data = DatasetBar.jsonarray.map(function(e) {
    return e.Value;
 });

//Initializing Chart Component
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            label: 'Standard',
            data: data,
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 206, 86)',
                'rgb(58, 240, 12)'
            ],
            hoverOffset: 4
        }]
    }
});

});