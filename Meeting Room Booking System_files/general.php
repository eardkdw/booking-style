var supportsDatalist = function supportsDatalist() {
        return ('list' in document.createElement('input')) &&
           ('options' in document.createElement('datalist')) &&
           (window.HTMLDataListElement !== undefined);
  };
  
var createFloatingHeaders = function createFloatingHeaders(tables) {
    tables.each(function() {
      var originalHeader = $('thead', this),
          existingClone = $('.floatingHeader', this).first(),
          clonedHeader;
            if (existingClone.length)
      {
        clonedHeader = existingClone;
      }
      else
      {
        clonedHeader = originalHeader.clone();
        clonedHeader.addClass('floatingHeader');
      }
            clonedHeader
          .css('width', originalHeader.width())
          .find('th')
              .css('box-sizing', 'border-box')
              .css('width', function (i) {
                  return originalHeader.find('th').get(i).getBoundingClientRect().width;
                });
      if (!existingClone.length)
      {
        clonedHeader.insertAfter(originalHeader);
      }
    });
  };
  

var updateTableHeaders = function updateTableHeaders(tables) {
    tables.each(function() {

        var el             = $(this),
            offset         = el.offset(),
            scrollTop      = $(window).scrollTop(),
            floatingHeader = $(".floatingHeader", this);
            
        if ((scrollTop > offset.top) && (scrollTop < offset.top + el.height()))
        {
          floatingHeader.show();
        } 
        else
        {
          floatingHeader.hide();
        }
                floatingHeader.css('left', offset.left - $(window).scrollLeft());
    });
  };
  

var oldInitGeneral = init;
init = function(args) {
  oldInitGeneral.apply(this, [args]);

    var logonForm = document.getElementById('logon');
  if (logonForm && logonForm.NewUserName)
  {
    logonForm.NewUserName.focus();
  }
  
    if (!lteIE6)
  {
    $('<input>').attr({
        type: 'hidden',
        name: 'datatable',
        value: '1'
      }).appendTo('#header_search');
      
    $('#user_list_link').each(function() {
        var href = $(this).attr('href');
        href += (href.indexOf('?') < 0) ? '?' : '&';
        href += 'datatable=1';
        $(this).attr('href', href);
      });
  }
  
    $('form input.default_action').each(function() {
      var defaultSubmitButton = $(this);
      $(this).parents('form').find('input').keypress(function(event) {
          if (event.which == 13)  // the Enter key
          {
            defaultSubmitButton.click();
            return false;
          }
          else
          {
            return true;
          }
        });
    });
    
  if (supportsDatalist())
  {
        $('input[list]').each(function() {
      var input = $(this),
          hiddenInput = $('<input type="hidden">');
      
            hiddenInput.attr('id', input.attr('id'))
                 .attr('name', input.attr('name'))
                 .val(input.val());
                 
      input.removeAttr('id')
           .removeAttr('name')
           .after(hiddenInput);
           
      input.change(function() {
        hiddenInput.val($(this).val());
      });

    });
    
        $('form:has(input[list]) input[type="submit"]').click(function() {
      $(this).closest('form')
             .find('input:not([name])')
             .not('input[type="submit"]')
             .each(function() {
                 if (!$(this).prop('required') &&
                     (typeof($(this).attr('pattern')) == 'undefined'))
                 {
                   $(this).val('');
                 }
               });
              
    });
    
  }
  else if (!lteIE6)
  {
     
    $('datalist').each(function() {
        var datalist = $(this);
        var options = [];
        datalist.parent().find('option').each(function() {
            var option = {};
            option.label = $(this).text();
            option.value = $(this).val();
            options.push(option);
          });
        var minLength = 0;
                  var breaks = [25,250,2500];
          var nOptions = options.length;
          var i=0;
          while ((i<breaks.length) && (nOptions >= breaks[i]))
          {
            i++;
            minLength++;
          }
                  var formInput = datalist.prev();
        formInput.empty().autocomplete({
            source: options,
            minLength: minLength
          });
                if (minLength === 0)
        {
          formInput.focus(function() {
              $(this).autocomplete('search', '');
            });
        }
      });
  }
  

  
  $('#Form1 input[type="submit"]').css('visibility', 'visible');

  
  var floatingTables = $('table#day_main, table#week_main');

  createFloatingHeaders(floatingTables);
  
  $(window)
        .resize(throttle(function() {
        createFloatingHeaders(floatingTables);
        updateTableHeaders(floatingTables);
      }, 100))
    .scroll(function() {
        updateTableHeaders(floatingTables);
      })
    .trigger('scroll');
    
};
