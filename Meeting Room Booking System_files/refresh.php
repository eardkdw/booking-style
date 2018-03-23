
var intervalId;

var refreshPage = function refreshPage() {
    if (!isHidden() && 
        !refreshPage.disabled &&
        !isMeteredConnection())
    {
      var data = {ajax: 1, 
                  day: refreshPage.args.day,
                  month: refreshPage.args.month,
                  year: refreshPage.args.year,
                  room: refreshPage.args.room,
                  area: refreshPage.args.area};
      if (refreshPage.args.timetohighlight !== undefined)
      {
        data.timetohighlight = refreshPage.args.timetohighlight;
      }
      
      $.post(refreshPage.args.page + '.php',
             data,
             function(result){
                 var table;
                                  if ((result.length > 0) && !isHidden() && !refreshPage.disabled)
                 {
                   table = $('table.dwm_main, div#dwm_main');
                   table.empty();
                   table.html(result);
                   createFloatingHeaders(table);
                   updateTableHeaders(table);
                   window.clearInterval(intervalId);
                   intervalId = undefined;
                   table.trigger('load');
                 }
               },
             'html');
    }    };

var turnOffPageRefresh = function turnOffPageRefresh() {
    refreshPage.disabled = true;
  };
  
  
var turnOnPageRefresh = function turnOnPageRefresh() {
    refreshPage.disabled = false;
  };
    
  
  
  var refreshVisChanged = function refreshVisChanged() {
      var pageHidden = isHidden();

      if (pageHidden !== null)
      {
                 if (typeof intervalId !== 'undefined')
        {
          window.clearInterval(intervalId);
          intervalId = undefined;
        }
        if (!pageHidden)
        {
          refreshPage();
        }
      }
    };
  

    var oldInitRefresh = init;
  init = function(args) {
    oldInitRefresh.apply(this, [args]);
    
    refreshPage.args = args;
    
        $('table.dwm_main, div#dwm_main').on('load', function() {
                if (typeof intervalId === 'undefined')
        {
          intervalId = setInterval(refreshPage, 60000);
        }
    
                var prefix = visibilityPrefix();
        if (document.addEventListener &&
            (prefix !== null) && 
            !init.refreshListenerAdded)
        {
          document.addEventListener(prefix + "visibilitychange", refreshVisChanged);
          init.refreshListenerAdded = true;
        }
      }).trigger('load');
  };
  