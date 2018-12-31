
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
    

.filters-container {
  display: grid;
  grid-template-columns: repeat(6, 5%) repeat(14, 5%);
  grid-gap: auto;
  padding: auto;
}
.content-container
{
  display: grid;
  grid-template-columns: repeat(20, 5%);
;
}

.filters-container #searchButton {
    margin-left: -32px;
    height: 25px;
    width: 25px;
    background: transparent url(https://static.tumblr.com/ftv85bp/MIXmud4tx/search-icon.png) no-repeat 5px center;
    color: black;
    border: 0;
    -webkit-appearance: none;
}
.filters-container #searchButton:focus {
  outline: none;
}


.filters-container input,#classSearch {
	outline: none;
    overflow: auto;
    color:black;


}
.filters-container input,#classSearch {
	-webkit-appearance: textfield;
	-webkit-box-sizing: content-box;
	font-family: inherit;
	font-size: 100%;
    overflow:hidden;
}
.filters-container input::-webkit-search-decoration,
.filters-container input::-webkit-search-cancel-button {
	display: none; 
}

.filters-container input,#classSearch
 {
	border: solid 1px #ccc;
	padding: 5px 30px 5px 15px;
	width: 95px;
	
	-webkit-border-radius: 4em;
	-moz-border-radius: 4em;
	border-radius: 4em;
 }
 .filters-container #seachTextArea
 {	
	-webkit-transition: all 1.5s;
	-moz-transition: all 1.5s;
	transition: all 1.5s;
}
.filters-container #seachTextArea:focus {
	width: 80%;
	background-color: #fff;
	border-color: #66CC75;
	
	-webkit-box-shadow: 0 0 5px blue;
	-moz-box-shadow: 0 0 5px blue;
	box-shadow: 0 0 5px blue;
-webkit-transition: all .5s;
	-moz-transition: all .5s;
	transition: all .5s;
}


.filters-container input:-moz-placeholder {
	color: #999;
}
.filters-container input::-webkit-input-placeholder {
	color: #999;
}
circle:hover {
  stroke-width: 2px;
}

    </style>
<script src="{{asset('js/jQuery-File-Upload-master/js/vendor/jquery.ui.widget.js')}}"></script>
<script src="{{asset('js/jQuery-File-Upload-master/js/jquery.iframe-transport.js')}}"></script>
<script src="{{asset('js/jQuery-File-Upload-master/js/jquery.fileupload.js')}}"></script>
{{-- <link rel="stylesheet" href="{{ asset('js/grid-2.4.1/pqgrid.min.css')}}" /> --}}
{{-- <link rel="stylesheet" href="{{ asset('css/jquery-ui.theme.css')}}" /> --}}
<script src="{{ asset('js/uploader-master/src/js/jquery.dm-uploader.js')}}"></script>
{{-- <script type="text/javascript" src="{{ asset('js/grid-2.4.1/pqgrid.min.js')}}" ></script>    --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.17/d3.js"></script>

<script src="{{asset('js/docsTable.js')}}"></script>

<script type="text/javascript" src="{{ url('adminlte/plugins/daterangepicker') }}/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="{{ url('adminlte/plugins/daterangepicker') }}/daterangepicker.css" />



@endif

<script>
    window._token = '{{ csrf_token() }}';
</script>


<link rel="stylesheet" href="{{ url('libs/redmond') }}/jquery-ui.css" />

@yield('javascript')