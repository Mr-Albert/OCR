
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
  grid-template-columns: repeat(20, auto);
  grid-gap: 10px;
  padding: 10px;
}


#searchButton {
    margin-left: -32px;
    height: 25px;
    width: 25px;
    background: transparent url(https://static.tumblr.com/ftv85bp/MIXmud4tx/search-icon.png) no-repeat 5px center;
    color: black;
    border: 0;
  z-index:-1;
    -webkit-appearance: none;
}
#searchButton:focus {
  outline: none;
}


#seachTextArea {
	outline: none;
    overflow: auto;


}
#seachTextArea {
	-webkit-appearance: textfield;
	-webkit-box-sizing: content-box;
	font-family: inherit;
	font-size: 100%;
  overflow:hidden;
}
#seachTextArea::-webkit-search-decoration,
#seachTextArea::-webkit-search-cancel-button {
	display: none; 
}


#seachTextArea {
	border: solid 1px #ccc;
	padding: 5px 30px 5px 15px;
	width: 95px;
	
	-webkit-border-radius: 4em;
	-moz-border-radius: 4em;
	border-radius: 4em;
	
	-webkit-transition: all 1.5s;
	-moz-transition: all 1.5s;
	transition: all 1.5s;
}
#seachTextArea:focus {
	width: 400px;
	background-color: #fff;
	border-color: #66CC75;
	
	-webkit-box-shadow: 0 0 5px blue;
	-moz-box-shadow: 0 0 5px blue;
	box-shadow: 0 0 5px blue;
-webkit-transition: all .5s;
	-moz-transition: all .5s;
	transition: all .5s;
}


#seachTextArea:-moz-placeholder {
	color: #999;
}
#seachTextArea::-webkit-input-placeholder {
	color: #999;
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