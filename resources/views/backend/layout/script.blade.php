 <!-- Core -->
 <script src="{{ asset('backend/vendor/jquery/jquery-3.7.1.min.js') }}"></script>
 <script src="{{ asset('backend/vendor/@popperjs/core/dist/umd/popper.min.js') }}"></script>
 <script src="{{ asset('backend/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
 <script src="{{ asset('backend/vendor/onscreen/dist/on-screen.umd.min.js') }}"></script>
 <script src="{{ asset('backend/vendor/nouislider/dist/nouislider.min.js') }}"></script>
 <script src="{{ asset('backend/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js') }}"></script>
 <script src="{{ asset('backend/vendor/chartist/dist/chartist.min.js') }}"></script>
 <script src="{{ asset('backend/vendor/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js') }}"></script>
 <script src="{{ asset('backend/vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>
 <script src="{{ asset('backend/vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
 <script src="{{ asset('backend/vendor/chosen_v1.8.7/chosen.jquery.js') }}"></script>
 <script src="{{ asset('backend/vendor/select2/select2.min.js') }}"></script>
 <script src="{{ asset('backend/vendor/notyf/notyf.min.js') }}"></script>
 <script src="{{ asset('backend/vendor/simplebar/dist/simplebar.min.js') }}"></script>
 <script src="{{ asset('backend/assets/js/volt.js') }}"></script>
 <script src="{{ asset('backend/assets/js/custom.js') }}"></script>

 <script>
     document.addEventListener('DOMContentLoaded', function() {
         var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
         tooltipTriggerList.map(function(tooltipTriggerEl) {
             return new bootstrap.Tooltip(tooltipTriggerEl)
         })
     });
 </script>



 @yield('script')
