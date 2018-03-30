export default {

    EasyPieChart(){
        var elems = $('.easy-pie-chart');

        elems.each(function (index, element) {
            var color = $(this).data('color') ? $(this).data('color') : '#ffde00';

            $(this).easyPieChart({
                scaleColor: false,
                barColor: color,
                trackColor: '#f8f8f8',
                size: 80,
                onStep: function (from, to, percent) {
                    $(this.el).find('.percent').text(Math.round(percent));
                }
            });
        });
    },
    reloadPage(){
      location.reload()
    },
    MetisMenu(){
        $("#menu").metisMenu();
    },
    ValidateImageSize(fdata) {

      var maxSize = '1024';
       if (fdata && fdata[0]) {
        var fsize = fdata[0].size/1024;
        if(fsize > maxSize) {
           alert('Maximum file size exceed');
           return false;
        }
        else {
          return true;
        }
      }
    },
    ValidateImageDimension(fdata) {
      // Function used for Check image Dimensions
      let width = fdata.width
      let height = fdata.height
      alert('Wiodt'+width+'Height'+height)
    },
    Select2(){
        $(".ls-select2").select2();
    },
    Select2withoutSearch(){
        $(".ls-select2").select2({
            minimumResultsForSearch: Infinity
        });
    },
    BootstrapSelect(){
        $(".ls-bootstrap-select").selectpicker({
            iconBase: 'fa',
            tickIcon: 'fa-check',
            container: 'body'
        });
    },

    SwitchToggles(){
        var elems = $('.ls-switch');

        elems.each(function (index, element) {
            var color = $(this).data('color') ? $(this).data('color') : '#ffde00';
            var size = $(this).data('size') ? $(this).data('size') : 'default';

            var switchery = new Switchery(this, {
                color: color,
                size: size
            });
        });
    },

    TimePickers(){
        $('.ls-clockpicker').clockpicker({
            donetext: 'Done'
        });


        var elems = $('.ls-timepicker');

        elems.each(function (index, element) {
            var timeFormat = $(this).data('format') ? $(this).data('format') : 'g:ia';
            var showDuration = $(this).data('duration') ? $(this).data('duration') : false;

            $(this).timepicker({
                timeFormat: timeFormat,
                showDuration: showDuration,
                minTime: '2:00pm',
                maxTime: '11:30pm'
            });
        });


    },

    MultiSelect(){
        $('.ls-multi-select').multiSelect()
    },

    DatePicker(){
        $('.ls-datepicker').datepicker({autoclose: true});
    },

    Editors(){
        $('.ls-summernote').summernote();

        var editor = $('.ls-simplemde')[0];

        if (editor) {
            var simplemde = new SimpleMDE({element: editor});
        }

    },

    initPlugins(plugins){
        plugins.forEach((plugin) => {
            if(this.isFunction(this[plugin])){
                this[plugin]();
            }
        })
    },

    isFunction(functionToCheck) {
        var getType = {};
        return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
    },

    addstage(functiontoadd) {
        $('#add_stage').click(function(){
            $('#stage3').show();
        });
    },

    setCurrentDate() {
      $("#tournament_start_date").datepicker().datepicker("setDate", new Date());
      $("#tournament_end_date").datepicker().datepicker("setDate", new Date());
    },
    setTournamentDays(date1, date2){
         date1 = new Date(date1.split('/')[2],date1.split('/')[1]-1,date1.split('/')[0]);
        date2 = new Date(date2.split('/')[2],date2.split('/')[1]-1,date2.split('/')[0]);
        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        // TODO: here we add extra one day
        diffDays = diffDays + 1
        return diffDays
// return Math.floor(( Date.parse(date2) - Date.parse(date1) ) / 86400000);
    }
}
