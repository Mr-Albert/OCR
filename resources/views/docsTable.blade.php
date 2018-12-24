@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Search</div>
                   <div id="table"></div>
                   <div id="dialog" title="Basic dialog" style="display: none;">
                        <p>File Describtion</p>
                        <input type="text" id="describtion"  />
                      </div>
            </div>
        </div>
    </div>
</div>

@endsection

