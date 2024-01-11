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


{{-- 
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

 


</style> --}}


<style>
    .interval-n60 {
        color: #d4ac0d;
    }

    .interval-n120 {
    color: red;
    }

    .interval-nplus {
        color:red;
    }

    .highlight {
        font-weight: bold;
    }
</style>


</head>
<body>

    <div class="container" style="margin:auto">
        
        <h1 style="text-align: center; margin-top:5%"><strong style="color: royalblue">
            <label for="" style="color: orange">AlertS</label>msPro</strong>
        </h1>

        <div class="table-responsive">
            
            <div>
                <span style="color: rgb(73, 71, 71) ; margin-bottom:5% font-weight:500 ;font-size:25px  ">
                    <label for="" style="color: red;margin-bottom:3% ;font-weight:bolder ;font-size:30px" > Alerte :</label> 
                    Longue durée des SMS dans la file d'attente 
                </span>
            </div><br><br>
            <div .class='content-heure'> <span id="heure"></span></div>
            
            <table style="width:100%; border-collapse: collapse;" id="tableau_api">
                <thead class="text-primary">
                    <tr>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2; text-align:center">StatModems</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2; text-align:center">Retards</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left; background-color: #f2f2f2; text-align:center">Nombre de sms</th>
                    </tr>
                </thead>
            <!-- ... Autres parties du code ... -->

            <tbody id="tableBody">
                {{-- Boucle Blade pour générer les lignes du tableau --}}
                @php
                    $allFail = collect($data)->every(function ($queueItem) {
                        return isset($queueItem['q']) && Str::startsWith($queueItem['q'], 'fail');
                    });
                @endphp
            
                {{-- Vérifier si tous les 'q' commencent par "fail", ne pas afficher les données --}}
                @if (!$allFail)
                    @foreach ($data as $queueItem)
                        {{-- Vérifier si 'd' est défini --}}
                        @if (isset($queueItem['d']) && !Str::startsWith($queueItem['q'], 'fail'))
                            @php
                                $intervalsToCompare = ["n60", "n120", "nplus"];
                                $lastSentInterval = collect($intervalsToCompare)->reduce(function ($lastInterval, $interval) use ($queueItem) {
                                    if (isset($queueItem['d'][$interval]) && $queueItem['d'][$interval] > 0 && $queueItem['d'][$interval] !== null) {
                                        return $interval;
                                    }
                                    return $lastInterval;
                                }, null);
            
                                switch ($lastSentInterval) {
                                    case 'n60':
                                        $intervalClass = 'interval-n60';
                                        $intervalText = '60 minutes';
                                        break;
                                    case 'n120':
                                        $intervalClass = 'interval-n120';
                                        $intervalText = '120 minutes';
                                        break;
                                    case 'nplus':
                                        $intervalClass = 'interval-nplus';
                                        $intervalText = '+120 minutes';
                                        break;
                                    default:
                                        $intervalClass = '';
                                        $intervalText = ''; // Vous pouvez ajuster cela en fonction de vos besoins
                                }
                            @endphp
            
                            {{-- Vérifier si un intervalle de temps valide a été trouvé --}}
                            @if ($lastSentInterval)
                                <tr class="{{ $intervalClass }} highlight">
                                    <td style="border: 1px solid #ddd; padding: 8px; font-size: 16px; font-weight: 400; text-align:center">{{ $queueItem['q'] }}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; font-size: 16px; text-align:center">{{ $intervalText }}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; font-size: 16px; text-align:center">{{ $queueItem['d'][$lastSentInterval] }} sms</td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                @else
                    {{-- Afficher un message si tous les 'q' commencent par "fail" --}}
                    <tr>
                        {{-- <td colspan="3" style="text-align: center; font-size: 18px; font-weight: bold;">Aucune donnée à afficher (tous les 'q' commencent par "fail").</td> --}}
                    </tr>
                @endif
            </tbody>
            

            </table>

        </div>
 </div>

</body>


</html>

