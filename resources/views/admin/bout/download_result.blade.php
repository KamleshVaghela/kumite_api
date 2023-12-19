<!DOCTYPE html>
<html>
<head>
    <title>How To Generate Invoice PDF In Laravel 9 - Techsolutionstuff</title>
</head>
<style type="text/css">
    body{
        font-family: 'Roboto Condensed', sans-serif;
    }
    .m-0{
        margin: 0px;
    }
    .p-0{
        padding: 0px;
    }
    .pt-5{
        padding-top:5px;
    }
    .mt-10{
        margin-top:10px;
    }
    .text-center{
        text-align:center !important;
    }
    .w-100{
        width: 100%;
    }
    .w-50{
        width:50%;   
    }
    .w-85{
        width:85%;   
    }
    .w-15{
        width:15%;   
    }
    .logo img{
        width:200px;
        height:60px;        
    }
    .gray-color{
        color:#5D5D5D;
    }
    .text-bold{
        font-weight: bold;
    }
    .border{
        border:1px solid black;
    }
    table tr,th,td{
        border: 1px solid #d2d2d2;
        border-collapse:collapse;
        padding:7px 8px;
    }
    table tr th{
        background: #F4F4F4;
        font-size:15px;
    }
    table tr td{
        font-size:13px;
    }
    table{
        border-collapse:collapse;
    }
    .box-text p{
        line-height:10px;
    }
    .float-left{
        float:left;
    }
    .total-part{
        font-size:16px;
        line-height:12px;
    }
    .total-right p{
        padding-right:20px;
    }
</style>
<body>
<div class="head-title">
    <h3 class="text-center m-0 p-0"> {{ $compModel->name }} </h3>
    <h3 class="text-center m-0 p-0">
        Wado Kai Karate Do Federation of India
    </h3>
    <div style="clear: both;"></div>
</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left mt-10">
        <p class="m-0 pt-5 text-bold w-100">Coach Code - <span class="gray-color">{{ $coach->external_coach_code }}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Coach Name - <span class="gray-color">{{ $coach->external_coach_name }} </span></p>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-10">Sr.No</th>
            <th class="w-30">Name</th>
            <th class="w-10">Bout No</th>
            <th class="w-40">Category</th>
            <th class="w-10">Results</th>
        </tr>
        @forelse($participants as $key=>$rec)
            <tr align="center">
                <td>{{ $key+1 }}</td>
                <td>{{ $rec->full_name }}</td>
                <td>{{ $rec->bout_number }}</td>
                <td>{{ $rec->category }}</td>
                <td>{{ $rec->Result }}</td>
            </tr>
        @empty
            <p class="bg-danger text-white p-1">No Item data found</p>
        @endforelse
    </table>
</div>
</html>