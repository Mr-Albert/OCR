@extends('layouts.app')

@section('content')
    <div class="row">
<meta name="_token" content="{{ csrf_token() }}">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div id="scroller" class="panel-heading">Search</div>
                
                   <div id="table"></div>
                   <div id="dialog" title="Basic dialog" style="display: none;">
                        <p>File Description</p>
                        <input type="text" id="description"  />
                      </div>
            </div>
        </div>
    </div>
</div>

@endsection

