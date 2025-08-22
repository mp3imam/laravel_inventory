<script src="{{ URL::asset('assets/libs/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/node-waves/node-waves.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/feather-icons/feather-icons.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/plugins/lord-icon-2.1.0.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins.min.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>

<script src="{{ URL::asset('assets/js/pages/form-input-spin.init.js') }}"></script>

<script src="{{ URL::asset('assets/js/pages/datatables.init.js') }}"></script>

<script src="{{ URL::asset('assets/libs/prismjs/prismjs.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/modal.init.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/form-validation.init.js') }}"></script>
<script src="{{ URL::asset('assets/libs/sortablejs/sortablejs.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/nestable.init.js') }}"></script>

<script src="{{ URL::asset('assets/js/app.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.nestable.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/sweetalerts.init.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ url('/assets/libs/choices.js/choices.js.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ URL::asset('assets/js/pages/select2.init.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script src="{{ URL::asset('assets/libs/@simonwep/@simonwep.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/form-pickers.init.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/rater-js/rater-js.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/pages/rating.init.js') }}"></script>

@yield('script')
@stack('scripts')
@yield('script-bottom')

<script>
    $(document).ready(function () {
        $("#btnFetch").click(function () {
            // disable button
            $(this).prop("disabled", true);
            // add spinner to button
            $(this).html(
                ` <span class="d-flex align-items-center">
                                <span class="spinner-border flex-shrink-0" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </span>
                                <span class="flex-grow-1 ms-2">
                                    Loading...
                                </span>
                            </span>`
            );
            setTimeout(function(){
            $(this).button('reset');
            $('#myForm').submit();
            },1000)
        });
    });
</script>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script>
    // var notificationsWrapper   = $('.dropdown-item');
    // var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
    // var notificationsCountElem = notificationsToggle.find('i[data-count]');
    // var notificationsCount     = parseInt(notificationsCountElem.data('count'));

    //   if (notificationsCount <= 0) {
    //     notificationsCount.hide();
    //   }


    var pusher = new Pusher('bd06cb9f99e8566439e1', {
      cluster: 'ap1'
    });

    var channel = pusher.subscribe('status-liked');
    channel.bind('App\\Events\\MessageSent', function(data) {
        // notificationsCount += 1;
        // notificationsCountElem.attr('data-count', notificationsCount);
        // notificationsCount.show();
        console.log(data.count());
    });
  </script>
