$(function(){
    var DatasetCourses = {
        "jsonarray": coursesListObj
	};
    
    var DatasetAssignementTypes = {
        "jsonarray": assignmentTypeObj
		};
    
    //Initialize multiselect
    $('#Courses').select2({
        theme: 'bootstrap4',
        data:DatasetCourses.jsonarray
      })
    
      //Initialize multiselect
    $('#AssignementTypes').select2({
        theme: 'bootstrap4',
        data:DatasetAssignementTypes.jsonarray
      })
      $('#Courses').val(DatasetCourses.jsonarray.map(x=>x.text)).trigger('change');
      $('#AssignementTypes').val(DatasetAssignementTypes.jsonarray.map(x=>x.text)).trigger('change');
    
});