// Datepicker
function getDate(element) {
  var date;
  try {
    date = jQuery.datepicker.parseDate('mm/dd/yy', element.value);
  }
  catch(error) {
    date = null;
  }
  return date;
}
jQuery('#event-meta-inside #event-start-date, #event-meta-inside #event-end-date').datepicker({
  dateFormat: "mm/dd/yy"
  // minDate: 0
});
jQuery('#event-meta-inside #event-start-date').on('change', function() {
  jQuery('#event-meta-inside #event-end-date').datepicker('option', 'minDate', getDate(this));
});
jQuery('#event-meta-inside #event-end-date').on('change', function() {
  jQuery('#event-meta-inside #event-start-date').datepicker('option', 'maxDate', getDate(this));
});
function checkStartTime() {
  var dateArray = jQuery('#event-meta-inside #event-start-date').val().split('/');
  var time = jQuery('#event-meta-inside #event-start-time').val();
  if (time) {
    var hours = Number(time.match(/^(\d+)/)[1]);
    var minutes = Number(time.match(/:(\d+)/)[1]);
    var AMPM = time.match(/\s(.*)$/)[1];
    if (AMPM == "PM") hours = hours + 12;
    if (AMPM == "AM" && hours == 12) hours = hours - 12;
    var sHours = hours.toString();
    var sMinutes = minutes.toString();
    if (hours < 10) sHours = "0" + sHours;
    if (minutes < 10) sMinutes = "0" + sMinutes;
  }
  jQuery('#event-meta-inside #event-sortable-start').val(Number(dateArray[2]+dateArray[0]+dateArray[1]+(sHours ? sHours+sMinutes : '0000')));
  jQuery('#event-meta-inside #event-json-start').val(dateArray[2]+'-'+dateArray[0]+'-'+dateArray[1]+(sHours ? 'T'+sHours+':'+sMinutes : ''));
}
checkStartTime();
jQuery('#event-meta-inside #event-start-date, #event-meta-inside #event-start-time').on('change', function() {
  checkStartTime();
});
function checkEndTime() {
  var dateArray = jQuery('#event-meta-inside #event-end-date').val().split('/');
  var time = jQuery('#event-meta-inside #event-end-time').val();
  if (time) {
    var hours = Number(time.match(/^(\d+)/)[1]);
    var minutes = Number(time.match(/:(\d+)/)[1]);
    var AMPM = time.match(/\s(.*)$/)[1];
    if (AMPM == "PM") hours = hours + 12;
    if (AMPM == "AM" && hours == 12) hours = hours - 12;
    var sHours = hours.toString();
    var sMinutes = minutes.toString();
    if (hours < 10) sHours = "0" + sHours;
    if (minutes < 10) sMinutes = "0" + sMinutes;
  }
  jQuery('#event-meta-inside #event-sortable-end').val(Number(dateArray[2]+dateArray[0]+dateArray[1]+(sHours ? sHours+sMinutes : '0000')));
  jQuery('#event-meta-inside #event-json-end').val(dateArray[2]+'-'+dateArray[0]+'-'+dateArray[1]+(sHours ? 'T'+sHours+':'+sMinutes : ''));
}
checkEndTime();
jQuery('#event-meta-inside #event-end-date, #event-meta-inside #event-end-time').on('change', function() {
  checkEndTime();
});
