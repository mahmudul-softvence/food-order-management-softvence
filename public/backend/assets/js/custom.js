$(document).ready(function () {
    $('.chosen-select').chosen({
        width: '100%',
        placeholder_text_single: 'Select Food',
        no_results_text: 'No result found'
    });
});


$('.select2').select2({
    theme: 'bootstrap-5',
    width: '100%',
    minimumResultsForSearch: 0,
});
