$(function(){

//getting Canvas Component to draw the chart    
var ctx = $('#CompletionPerformance');

//Replace the Dataset with Json Object in the following format
//"jsonarray":[{ "XAxis": "XAxisValue1","YAxis":"YAxisValue1", ....},
//,{"XAxis": "XAxisValue2","YAxis":"YAxisValue2, .....} ,{}....]
//**each  chart has its own dataset: hence DatasetBar(Barchart), DatasetLine(LineChart)**//

var DatasetBar = {
    "jsonarray": eventDataObj
 };


 //BarchartData will be used to filter the data
 var BarchartData = DatasetBar;

 //getting the XAxis values from DatasetBar to set them as XAxis Labels
 var labels = DatasetBar.jsonarray.map(function(e) {
    return moment(e.Date).format('MM/DD').replace(/\b0/g, '');
 });

 //getting YAxis Values for Barchart
 var data = DatasetBar.jsonarray.map(function(e) {
    return e.CompletionRate;
 });


//Initializing Chart Component
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Assignment % Complete',
            data: data,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(32, 8, 120, 0.2)',
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
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(32, 8, 120, 0.2)',
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
        },
     /* {
        type: 'line',
            label: false,
		
            data: calcMovingAverage(BarchartData.jsonarray.map(x=>x.CompletionRate),3),
      } */
	  
	  ]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

//Initializing daterange filter
$('#Date').daterangepicker({
    timePicker: false,
    //set The default selected date here
    startDate: moment('4-1-2021'),
    endDate: moment('4-30-2021'),
    locale: {
        format: 'yyyy-MM-DD'
    }
},
//on date change event
    function (startDate, endDate) {
        var days = endDate.diff(startDate,'days');

        if(days > 30)
        {
            endDate = endDate.subtract((days - 30),'days');
            alert('only the first 30 days will display');
        }
        filterData(startDate,endDate);
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


    function filterData(startDate,endDate)
    {
        debugger;
        var CoursesSelected = $('#Courses').val();
        var AssingmenetsSelected = $('#AssignementTypes').val()
        if(!startDate && !endDate)
        {
            startDate = $("#Date").val().split(' - ')[0].trim();
            endDate = $("#Date").val().split(' - ')[1].trim()
        }

        BarchartData = DatasetBar.jsonarray.filter(x=> 
            { 
                if(new Date(x.Date) <= new Date(endDate)
                 && new Date(x.Date) >=new Date(startDate)
                 && CoursesSelected.includes(x.Course)
                 && AssingmenetsSelected.includes(x.AssignementType))
                 return typeof x == 'object';
            });

        myChart.data.labels = BarchartData.map(e=>moment(e.Date).format('MM/DD').replace(/\b0/g, ''));
        myChart.data.datasets[0].data = BarchartData.map(e=>e.CompletionRate);
        myChart.update();

    }

    //Calculate Moving Avarage Line
    function calcMovingAverage(data, window) {
        debugger;
        var steps = data.length - window;
        var result = [ ];
        var cnt = 0;
        var ave = 0; 
        var ind = 0;
        
       for (ind; ind < window; ++ind) {result.push(null)};
       /* for (ind; ind < data.length; ++ind) {
            ave = (data[ind] + data[ind-1] + data[ind-2]) / window;

          result.push(ave);
        }
		*/
            
        return result;
    }
	
	

});