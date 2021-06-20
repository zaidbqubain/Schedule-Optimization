$(function(){

//getting Canvas Component to draw the chart    
var ctx = $('#TimeUsage');


//Replace the Dataset with Json Object in the following format
//"jsonarray":[{ "XAxis": "XAxisValue1","YAxis":"YAxisValue1", ....},
//,{"XAxis": "XAxisValue2","YAxis":"YAxisValue2, .....} ,{}....]
//**each  chart has its own dataset: hence DatasetBar(Barchart), DatasetLine(LineChart)**//

var DatasetBar = {
    "jsonarray": eventDataChart2Obj
 };

 //BarchartData will be used to filter the data
 var BarchartData = DatasetBar;

 //getting XAxis Values for Bar-1
 var labels = DatasetBar.jsonarray.map(function(e) {
    return e.AssignementType;
 });

 //getting YAxis Values for Bar-1
 var data = DatasetBar.jsonarray.map(function(e) {
    return e.Actual;
 });

 //getting YAxis Values for Bar-2
 var data2 = DatasetBar.jsonarray.map(function(e) {
    return e.Standard;
 });

//Initializing Chart Component
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Standard',
            data: data,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(32, 8, 120, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(32, 8, 120, 0.2)'
            ],
            borderWidth: 1
        },{
            label: 'Actual',
            data: data2,
            backgroundColor: [
                'rgba(63, 235, 232, 0.2)',
                'rgba(130, 230, 23, 0.2)',
                'rgba(232, 145, 39, 0.2)',
                'rgba(184, 16, 13, 0.2)',
                'rgba(67, 56, 138, 0.2)',
                'rgba(158, 44, 184, 0.2)',
                'rgba(219, 33, 145, 0.2)',
                'rgba(21, 173, 89, 0.2)'
            ],
            borderColor: [
                'rgba(63, 235, 232, 0.2)',
                'rgba(130, 230, 23, 0.2)',
                'rgba(232, 145, 39, 0.2)',
                'rgba(184, 16, 13, 0.2)',
                'rgba(67, 56, 138, 0.2)',
                'rgba(158, 44, 184, 0.2)',
                'rgba(219, 33, 145, 0.2)',
                'rgba(21, 173, 89, 0.2)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                type: 'time',
                time: {
                    unit: 'minute'
                },
                ticks:{
                    source: 'labels',
                }
            }
        }
    }
});

    filterData();

    //On Courses Change Event
    $('#Courses').on('change', function () {
        filterData();
    });

    //On AssignementTypes Change Event
    $('#AssignementTypes').on('change', function () {
        
        filterData();
    });


    function filterData()
    {
        var CoursesSelected = $('#Courses').val();
        var AssingmenetsSelected = $('#AssignementTypes').val()

        BarchartData = DatasetBar.jsonarray.map(x=> 
            { 
                if(AssingmenetsSelected.includes(x.AssignementType)){
                if(CoursesSelected.includes(x.Course)){
                 return x;
                }else return {
                    "Standard": 0,
                    "Actual": 0,
                    "Course":x.Course,
                    "AssignementType": x.AssignementType
                 };
                }else return null;
            });

        BarchartData = BarchartData.filter(item=>item != null);
        myChart.data.labels = AssingmenetsSelected;
        myChart.data.datasets[0].data = BarchartData.map(e=>e.Actual);
        myChart.data.datasets[1].data = BarchartData.map(e=>e.Standard);
        myChart.update();

    }

});