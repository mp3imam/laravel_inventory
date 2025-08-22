@extends('layouts.master-without-nav')
@section('title')
    @lang('translation.signin')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
@section('content')

<div class="auth-page-wrapper pt-5">
    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <section class="section bg-light" id="creators">
                <div class="container">
                    <div class="p-3 bg-light rounded mb-4">
                        <div class="row g-2">
                            <label>Pilih Satker</label>
                            <input class="form-control" name="satker" id="satker" hidden/>
                            <select id='satker_id' name="satker_id" required></select>
                            <button class="btn btn-primary" onclick="submit()">Submit</button>
                        </div>
                    </div>
                </div><!-- end container -->
            </section>
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">&copy; <script>document.write(new Date().getFullYear())</script> MPP
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
</div>
@endsection

@section('script')
<script type="text/javascript">
    $('#satker_id').select2({
        ajax: {
          url: "{{ route('api.satker') }}",
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
                results: $.map(data.data, function (item) {
                    return {
                        text: item.name,
                        id: item.id
                    }
                })
            };
          },
          cache: true
        }
    });

    $('#satker_id').change(function() {
        $("#satker").val($("#satker_id option:selected").val());
    });

    function submit(){
        var satker = $("#satker").val();
        window.location.href = "{{ url('sistem_antrian') }}/"+satker;
    }
</script>
@endsection
