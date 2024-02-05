<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title inertia>{{ config('app.SmsPro', 'SmsPro') }}</title>

 <!-- Fonts -->
 <link rel="preconnect" href="https://fonts.bunny.net">
 <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

 <!-- Scripts -->

 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
 integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
 integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

 <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>



 <style>
    
    

    #heure{
        color:royalblue;
        font-weight: bolder;
        font-size: 35px;
        text-align:right;
    }



        table thead th{
        text-align: center;
        color: gray;
        font-size: 22px;
        }

        h1{
            margin: 45px;
            text-align: center;

        }
        body{
            background: gainsboro;    
        }

        .table-responsive{
            align-content: center;
            padding: 20px;
            margin: 20PX;
            
            
        }


 </style>

</head>
<body>
    
    <h1 style="text-align: center; margin-top:5%"><strong style="color: royalblue">
        <label for="" style="color: orange">AlertS</label>msPro</strong>
    </h1>

    
    <div class="table-responsive">
        <div .class='content-heure'> <span id="heure"> </span></div>
        <table class="table tablesorter table-hover " id="tableau_api">
            <thead class="text-primary" >
                <tr>
                    <th style="background-color: royalblue; color:aliceblue" >StatModems</th>
                    <th style="background-color: royalblue;  color:aliceblue">Retards</th>
                    <th style="background-color: royalblue;  color:aliceblue">Nombre de sms </th>
                   
                  
                </tr>
            </thead>
            <tbody id="tableBody">
            </tbody>
        </table>

        <div>
            <a href="{{route('smsproalert')}}">
              <button type="button" class="btn btn-primary">Appercu du mail</button>
           </a>
    </div>
</body>

    <script src="/build/sms_js/smspro.js"></script>
    <script src="/build/sms_js/temps_reel.js"></script>
</html>