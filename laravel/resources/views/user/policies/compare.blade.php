@extends('user.wrap')

@section('content')
    <div>
        <h3 style="margin: 10px;">Compare Policy: {{ $policy->manual_name }}</h3>
        <div class="diff-grid">
            <div class="diff-container">
                <div class="title">Default Version</div>
                {!! $default_content !!}
            </div>
            <div class="diff-container">
                <div class="title">Edited Version</div>
                @if ($noChanges)
                    <div class="message">No Changes Found</div>
                @else
                    {!! $diff !!}
                @endif
            </div>
        </div>
        
    </div>
@stop

@section('foot')
    @parent
    

    <style>
        .diff-grid {
            margin: 0 auto;
            width: 1024px;
            display: grid;
            grid-template-columns: 500px 500px;
            grid-gap: 5px;
        }

        .diff-container {
            width: 500px;
            background-color: #fff;
            color: #444;
            padding: 1rem;
            position: relative;
            padding-top: 40px;
        }

        .title {
            display: inline-block;
            position: absolute;
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            top: 0;
            left: 0;
            padding: 10px;
            width: 500px;
            background-color: var(--main-primary);
            color: #fff;
        }

        .message {
            text-align: center;
            margin-top: 25px;
            font-weight: bold;
            font-size: 25px;
        }
        /* https://github.com/caxy/php-htmldiff/blob/master/demo/codes.css */
        /* Difference Highlighting and Strike-through
        ------------------------------------------------ */
        ins {
            color: #333333;
            background-color: #eaffea;
            text-decoration: none;
        }
        
        del {
            color: #AA3333;
            background-color: #ffeaea;
            text-decoration: line-through;
        }

        /* Image Diffing
        ------------------------------------------------ */
        del.diffimg.diffsrc {
            display: inline-block;
            position: relative;
        }

        del.diffimg.diffsrc:before {
            position: absolute;
            content: "";
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                to left top,
                rgba(255, 0, 0, 0),
                rgba(255, 0, 0, 0) 49.5%,
                rgba(255, 0, 0, 1) 49.5%,
                rgba(255, 0, 0, 1) 50.5%
            ), repeating-linear-gradient(
                to left bottom,
                rgba(255, 0, 0, 0),
                rgba(255, 0, 0, 0) 49.5%,
                rgba(255, 0, 0, 1) 49.5%,
                rgba(255, 0, 0, 1) 50.5%
            );
        }

        /* List Diffing
        ------------------------------------------------ */
        /* List Styles */
        .diff-list {
            list-style: none;
            counter-reset: section;
            display: table;
        }

        .diff-list > li.normal,
        .diff-list > li.removed,
        .diff-list > li.replacement {
            display: table-row;
        }

        .diff-list > li > div{
            display: inline;
        }

        .diff-list > li.replacement:before,
        .diff-list > li.new:before {
            color: #333333;
            background-color: #eaffea;
            text-decoration: none;
        }

        .diff-list > li.removed:before{
            counter-increment: section;
            color: #AA3333;
            background-color: #ffeaea;
            text-decoration: line-through;
        }

        /* List Counters / Numbering */
        .diff-list > li.normal:before,
        .diff-list > li.removed:before,
        .diff-list > li.replacement:before {
            width: 15px;
            overflow: hidden;
            content: counters(section,".") ". ";
            display: table-cell;
            text-indent: -1em;
            padding-left: 1em;
        }

        .diff-list > li.normal:before,
        li.replacement + li.replacement:before,
        .diff-list > li.replacement:first-child:before{
            counter-increment: section;
        }
        ol.diff-list li.removed + li.replacement {
            counter-increment: none;
        }
        ol.diff-list li.removed + li.removed + li.replacement {
            counter-increment: section -1;
        }
        ol.diff-list li.removed + li.removed + li.removed + li.replacement {
            counter-increment: section -2;
        }
        ol.diff-list li.removed + li.removed + li.removed + li.removed + li.replacement {
            counter-increment: section -3;
        }
        ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
            counter-increment: section -4;
        }
        ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
            counter-increment: section -5;
        }
        ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
            counter-increment: section -6;
        }
        ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
            counter-increment: section -7;
        }
        ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
            counter-increment: section -8;
        }
        ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
            counter-increment: section -9;
        }
        ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement{
            counter-increment: section -10;
        }
        ol.diff-list li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.removed + li.replacement {
            counter-increment: section -11;
        }

        /* Exception Lists */
        ul.exception,
        ul.exception li:before {
            list-style: none;
            content: none;
        }
        .diff-list ul.exception ol {
            list-style: none;
            counter-reset: exception-section;
            /* Creates a new instance of the section counter with each ol element */
        }
        .diff-list ul.exception ol > li:before {
            counter-increment: exception-section;
            content:counters(exception-section, ".") ".";
        }

    </style>
@stop
