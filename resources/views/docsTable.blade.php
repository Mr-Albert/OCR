@extends('layouts.app')

@section('content')
    <div class="row">
        

<meta name="_token" content="{{ csrf_token() }}">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div  class="panel-heading">
                    <div class="filters-container">
                        <div class="item1" style="  grid-column-start: 1; grid-column-end: 6;">
                            <textarea  id="seachTextArea" maxlength="100" rows="1" cols="10" style="color:black;resize: none;"
                                placeholder="Search / بحث"></textarea>
                            <button id="searchButton"></button>               
                            </div>
                            <div class="item2" style="  grid-column-start: 7; grid-column-end: 9;">Description</div>        
                            <div class="item3" style="  grid-column-start: 10; grid-column-end: 13;">Created on</div>        
                            <div class="item3" style="  grid-column-start: 14; grid-column-end: 17;">Created by</div>        
                            <div class="item3" style="  grid-column-start: 18; grid-column-end: 20;">Created by</div>        
                    </div>
                </div>
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

