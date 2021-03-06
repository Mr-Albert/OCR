
@inject('request', 'Illuminate\Http\Request')

<script src="{{url('adminlte/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>

<script src="{{ url('adminlte/js') }}/bootstrap.min.js"></script>

<script src="{{url('libs/DataTables/DataTables-1.10.16/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('libs/DataTables/Buttons-1.5.1/js/dataTables.buttons.min.js')}}"></script>

<script src="{{url('libs/DataTables/Buttons-1.5.1/js/buttons.flash.min.js')}}"></script>
<script src="{{url('libs/Stuk-jszip-9fb481a/dist/jszip.min.js')}}"></script>
<script src="{{url('libs/pdfmake-master/build/pdfmake.min.js')}}"></script>

<script src="{{url('libs/pdfmake-master/build/vfs_fonts.js')}}"></script>
<script src="{{url('libs/DataTables/Buttons-1.5.1/js/buttons.html5.min.js')}}"></script>
<script src="{{url('libs/DataTables/Buttons-1.5.1/js/buttons.print.min.js')}}"></script>
<script src="{{url('libs/DataTables/Buttons-1.5.1/js/buttons.colVis.min.js')}}"></script>
<script src="{{url('libs/DataTables/Select-1.2.5/js/dataTables.select.min.js')}}"></script>

<script src="{{url('adminlte/plugins/jQueryUI/jquery-ui.min.js')}}"></script>
<script src="{{url('libs/grid-2.4.1/pqgrid.min.js')}}"></script>
<script src="{{url('libs/select-master/pqselect.dev.js')}}"></script>

<link href="{{url('libs/grid-2.4.1/pqgrid.min.css')}}" type="text/css" rel="stylesheet">
<link href="{{url('libs/select-master/pqselect.dev.css')}}" type="text/css" rel="stylesheet">


<script src="{{ url('adminlte/js') }}/select2.full.min.js"></script>
<script src="{{ url('adminlte/js') }}/main.js"></script>

<script src="{{ url('adminlte/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/fastclick/fastclick.js') }}"></script>
<script src="{{ url('adminlte/js/app.min.js') }}"></script>
{{-- should be conditioned on the search pages only --}}
<script type="text/javascript" src="{{ url('js/moment.js') }}"></script>
 
<!-- Include Date Range Picker -->
<script type="text/javascript" src="{{ url('adminlte/plugins/daterangepicker') }}/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="{{ url('adminlte/plugins/daterangepicker') }}/daterangepicker.css" />


@if ($request->segment(1) =='DOCS')
{{-- <link rel="stylesheet" href="{{ asset('js/jquery-ui-1.12.1/jquery-ui.min.css')}}" /> --}}
{{-- <script src="{{ asset('js/jquery-3.3.1.min.js')}}"></script> --}}
{{-- <script src="{{ asset('js/jquery-ui-1.12.1/jquery-ui.js')}}"></script> --}}
<style> 
    [id^='pq-detail']{
        overflow: auto !important;
        max-height:400px;
    }
    em
    {
        font-style: italic;
        background-color: yellow;

    }
    </style>
<script src="{{asset('js/jQuery-File-Upload-master/js/vendor/jquery.ui.widget.js')}}"></script>
<script src="{{asset('js/jQuery-File-Upload-master/js/jquery.iframe-transport.js')}}"></script>
<script src="{{asset('js/jQuery-File-Upload-master/js/jquery.fileupload.js')}}"></script>
{{-- <link rel="stylesheet" href="{{ asset('js/grid-2.4.1/pqgrid.min.css')}}" /> --}}
{{-- <link rel="stylesheet" href="{{ asset('css/jquery-ui.theme.css')}}" /> --}}
<script src="{{ asset('js/uploader-master/src/js/jquery.dm-uploader.js')}}"></script>
{{-- <script type="text/javascript" src="{{ asset('js/grid-2.4.1/pqgrid.min.js')}}" ></script>    --}}
<script src="{{asset('js/docsTable.js')}}"></script>

<script type="text/javascript" src="{{ url('adminlte/plugins/daterangepicker') }}/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="{{ url('adminlte/plugins/daterangepicker') }}/daterangepicker.css" />



@endif

<script>
    window._token = '{{ csrf_token() }}';
</script>


<link rel="stylesheet" href="{{ url('libs/redmond') }}/jquery-ui.css" />

@yield('javascript')