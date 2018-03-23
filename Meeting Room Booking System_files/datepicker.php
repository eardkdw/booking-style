$(function() {

  $.datepicker.setDefaults({
    showOtherMonths: true,
    selectOtherMonths: true,
    changeMonth: true,
    changeYear: true,
    duration: 'fast',
    showWeek: false,
    firstDay: 0,
    altFormat: 'yy-mm-dd',
    onSelect: function(dateText, inst) {datepickerSelect(inst);}
  });
  
});


function populateAltComponents(altId)
{
  var date = $('#' + altId).val().split('-');

  $('#' + altId + '_year').val(date[0]);
  $('#' + altId + '_month').val(date[1]);
  $('#' + altId + '_day').val(date[2]);
}


function datepickerSelect(inst, formId)
{
  var id = inst.id,
      datepickerInput = $('#' + id);

  populateAltComponents(id + '_alt');
  datepickerInput.blur();
  
  if (formId)
  {
    $('#' + formId).submit();
  }
  
  datepickerInput.trigger('datePickerUpdated');
}


var oldInitDatepicker = init;
init = function() {
  oldInitDatepicker.apply(this);

    $('span.dateselector').each(function() {
      var span = $(this);
      var prefix  = span.data('prefix'),
          minYear = span.data('minYear'),
          maxYear = span.data('maxYear'),
          formId  = span.data('formId');
      var dateData = {day:   parseInt(span.data('day'), 10),
                      month: parseInt(span.data('month'), 10),
                      year:  parseInt(span.data('year'), 10)};
      var unit;
      var initialDate = new Date(dateData.year,
                                 dateData.month - 1,                                   dateData.day);
      var disabled = span.find('select').first().is(':disabled'),
          baseId = prefix + 'datepicker';
      
      span.empty();

            $('<input>').attr('type', 'hidden')
                  .attr('id', baseId + '_alt')
                  .attr('name', prefix + '_alt')
                  .attr('disabled', 'disabled')
                  .val(dateData.year + '-' + dateData.month + '-' + dateData.day)
                  .appendTo(span);
            for (unit in dateData)
      {
        if (dateData.hasOwnProperty(unit))
        {
          $('<input>').attr('type', 'hidden')
                      .attr('id', baseId + '_alt_' + unit)
                      .attr('name', prefix + unit)
                      .val(dateData[unit])
                      .appendTo(span);
        }
      }
            $('<input>').attr('class', 'date')
                  .attr('type', 'text')
                  .attr('id', baseId)
                  .datepicker({altField: '#' + baseId + '_alt',
                               disabled: disabled,
                               yearRange: minYear + ':' + maxYear})
                  .datepicker('setDate', initialDate)
                  .change(function() {
                                            $(this).datepicker('setDate', $(this).val());
                      populateAltComponents(baseId + '_alt');
                      $(this).trigger('datePickerUpdated');
                    })
                  .appendTo(span);
                  
      if (formId.length > 0)
      {
        $('#' + baseId).datepicker('option', 'onSelect', function(dateText, inst) {
            datepickerSelect(inst, formId);
          });
      }
      
            span.css('visibility', 'inherit');
      
      $('.ui-datepicker').draggable();
    });
};

