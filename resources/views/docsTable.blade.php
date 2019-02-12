@extends('layouts.app')

@section('content')
<style>
.pq-select-button.pq-no-capture.ui-state-default.ui-widget.ui-corner-all.pq-select-multiple
{
    margin-top:4px;
}
</style>
<div class="row">
        

<meta name="_token" content="{{ csrf_token() }}">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div  class="panel-heading">
                    <div class="filters-container">
                        <div class="item1" style="  grid-column-start: 1; grid-column-end: 6;">
                            {{-- Search --}}
                            <input  id="seachTextArea" maxlength="100" rows="1" cols="10" style="color:black;resize: none;"
                                placeholder="Search / بحث">
                            <button id="searchButton"></button>               
                        </div>
                        {{-- <div class="item2" style="  grid-column-start: 6; grid-column-end: 9;"> --}}
                            {{-- Describtion --}}
                        {{-- <input id="describtionSearch"  placeholder="Describtion" > </div>         --}}
                        {{-- <div class="item3" style="  grid-column-start: 9; grid-column-end: 12;"> --}}
                            {{-- Created by --}}
                        {{-- <input id="authorSearch"  placeholder="Created by"  ></div> --}}
                        <div class="item5" style="  grid-column-start: 12; grid-column-end: 13;">
                            {{-- Class --}}
                            {{-- <input id="classSearch" style="width:100%; "  placeholder="class" > --}}
                            <select id="classSearch" style="width:100%;color:black; " multiple>
                                    <option value="3">سرى للغاية</option>
                                    <option  value="2">سرى</option>
                                    <option  value="1">عادى</option>
                            </select>         
                        </div>
                        <div class="item4" style="  grid-column-start: 14; grid-column-end: 20">
                            {{-- Created on --}}
                        <input id="DateSearch"  style="width:100%; " placeholder="Created on" ></div>        
                               
                    </div>
                </div>

                <div id="tabs">
                        <ul>
                          <li><a href="#tabs-1">table</a></li>
                          <li><a href="#tabs-2">graph</a></li>
                        </ul>
                        <div id="tabs-1">
                                <div id="table" class="leftTable" style="grid-column-start: 1; grid-column-end: 12;">
                                    </div>
                        </div>
                        <div id="tabs-2">
                                <div id="chartArea" class="rightGraph" style="grid-column-start: 12; grid-column-end: 20;">
                                    </div>    
                                    </div>
                      </div>


                {{-- <div class="content-container">
                        <div id="table" class="leftTable" style="grid-column-start: 1; grid-column-end: 12;">
                        </div>
                        <div id="chartArea" class="rightGraph" style="grid-column-start: 12; grid-column-end: 20;">
                        </div>    
                </div> --}}





                    {{-- <div id="table"></div> --}}
                   <div id="dialog" title="Basic dialog" style="display: none;">
                        <p>File Description</p>
                        <input type="text" id="description"  />
                        <p>Class</p>
                        <select id="inputClass" style="width:100%;color:black; " >
                            <option value="3">سرى للغاية</option>
                            <option  value="2">سرى</option>
                            <option  value="1">عادى</option>
                        </select>         
                      </div>
            </div>
        </div>
    </div>
</div>

@endsection

