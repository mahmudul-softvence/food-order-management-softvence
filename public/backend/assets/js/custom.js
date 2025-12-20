$(document).ready(function () {
    $(".chosen-select").chosen({
        width: "100%",
        placeholder_text_single: "Select Food",
        no_results_text: "No result found",
    });
});

$(".select2").select2({
    theme: "bootstrap-5",
    width: "100%",
    minimumResultsForSearch: 0,
});

$(document).ready(function () {
    $(".dropify").dropify();
});

$(document).ready(function () {
    $(".timepicker").timepicker({
        interval: 15,
        dynamic: false,
        dropdown: true,
        scrollbar: true,
    });
});

document.addEventListener("DOMContentLoaded", function () {
    var toastElList = [].slice.call(document.querySelectorAll(".toast"));
    var toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl, { delay: 5000 });
    });
    toastList.forEach((toast) => toast.show());
});
