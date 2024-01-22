<?php

namespace App\Http\Controllers;

use App\Mail\smsProMail;
use App\Mail\SmsAlertMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class smsprocontroller extends Controller
{

    // ************************ fonction permettant de retourner les données de mon main par API ****************************************

    public function Apimain()
       {
            $admins = [
                'ouattaraadamajordan@gmail.com',
            ];
            // Définir l'URL de l'API
            $apiUrl = 'http://smsstat.wicsoft.cloud/api/getsmsstatLocal';
        
            // Effectuer une requête HTTP GET pour récupérer les données
            $response = Http::get($apiUrl);
        
            // Vérifier si la requête a réussi (statut 2xx)
            if ($response->successful()) {
                // Obtenir les données JSON de la réponse
                $data = $response->json();
        
                // Vérifier si la clé 'queue' existe dans les données
                if (
                    isset($data['data2']['queue']) &&
                    is_array($data['data2']['queue']) &&
                    count($data['data2']['queue']) > 0
                ) {
                    // Vérifier si tous les 'q' ont une valeur qui commence par "fail"
                    $allFail = collect($data['data2']['queue'])->every(function ($queueItem) {
                        return isset($queueItem['q']) && Str::startsWith($queueItem['q'], 'f');
                    });
 
                    // dd($allFail);
        
                    if (!$allFail) {
                        // Tous les 'q' commencent par "fail", envoyer l'e-mail
                        Mail::to($admins)->send(new SmsAlertMail($data));
                        return response()->json(['success' => true, 'data' => $data]);
                    } else {
                        // Certains 'q' n'ont pas le préfixe "fail", retourner un message explicatif
                        return response()->json(['success' => false, 'message' => 'Les "q" ont tous le préfixe "fail", aucun e-mail envoyé.']);
                    }
                } else {
                    // Les données de la table "queue" n'existent pas, retourner un message explicatif
                    return response()->json(['success' => false, 'message' => 'Aucune donnée dans la table "queue"']);
                }
            } else {
                // Si La requête a échoué
                $errorCode = $response->status();
                return response()->json(['success' => false, 'error_code' => $errorCode]);
            }
        }

    // ********************************Fonction permettant de  de retourner les données de ma page newmain *********************************


    public function newmain(){

         // Définir l'URL de l'API
        $apiUrl = 'http://smsstat.wicsoft.cloud/api/getsmsstatLocal';

        // Effectuer une requête HTTP GET pour récupérer les données
        $response = Http::get($apiUrl);

        // Vérifier si la requête a réussi (statut 2xx)
        if ($response->successful()) {
            // Obtenir les données JSON de la réponse
            $data = $response->json();
            $tableData = json_decode($response, true);
        //   dd($data);  
            
            return view('layouts.newmain',compact('data'));
        }
   }

public function mail(){
    return view('emails.smsAlert');
}

//******************************************** Fonction permettant d'afficher mon main dans mon web *********************************************************** */ 


// ::::::::::::
// public function main() {
//     $admin = 'ouattaraadamajordan@gmail.com';
//     // Définir l'URL de l'API
//     $apiUrl = 'http://smsstat.wicsoft.cloud/api/getsmsstatLocal';

//     // Effectuer une requête HTTP GET pour récupérer les données
//     $response = Http::get($apiUrl);

//     // Vérifier si la requête a réussi (statut 2xx)
//     if ($response->successful()) {
//         // Obtenir les données JSON de la réponse
//         $data = $response->json();

//         // Vérifier si la clé 'queue' existe dans les données
//         if (isset($data['data2']['queue']) && is_array($data['data2']['queue']) && count($data['data2']['queue']) > 0) {
//             // Utiliser la méthode every pour vérifier si tous les "q" ont le préfixe "fail"
//             $allFail = collect($data['data2']['queue'])->every(function ($queueItem) {
//                 return isset($queueItem['q']) && strpos($queueItem['q'], 'fail') === 0;
//             });

//             // Si tous les "q" ont le préfixe "fail", afficher un message explicatif dans la console
//             if ($allFail) {
//                 return response()->json(['success' => false, 'message' => 'Toutes les valeurs de "q" dans la table "queue" ont le préfixe "fail".']);
//             }

//             // Envoyer l'e-mail
//             Mail::to($admin)->send(new SmsAlertMail($data));

//             // Vous pouvez retourner ou utiliser les données traitées comme nécessaire
//             return view('layouts.main', compact('data'));
//         } else {
//             // Les données de la table "queue" n'existent pas, retourner un message explicatif
//             return response()->json(['success' => false, 'message' => 'Aucune donnée dans la table "queue"']);
//         }
//     } else {
//         // Si La requête a échoué
//         $errorCode = $response->status();
//         return response()->json(['success' => false, 'error_code' => $errorCode]);
//     }
// }


// £££££££££££££££££££££££££££££££££££££
// public function main(){
//     $admins = [
//         'ouattaraadamajordan@gmail.com',
//         // 'cos@wic.ci',
//         // 'coulcoul.aziz@gmail.com',
//         // 'ouattaraadam171717@gmail.com',
//         // Ajoutez d'autres adresses e-mail au besoin
//     ];
//     // Définir l'URL de l'API
//     $apiUrl = 'http://smsstat.wicsoft.cloud/api/getsmsstatLocal';

//     // Effectuer une requête HTTP GET pour récupérer les données
//     $response = Http::get($apiUrl);

//     // Vérifier si la requête a réussi (statut 2xx)
//     if ($response->successful()) {
//         // Obtenir les données JSON de la réponse
//         $datas = $response->json();

//         // Filtrer les éléments de la table "queue" qui commencent par "GAIN"
//         $filtreurdata = collect($datas['data2']['queue'])->filter(function ($queueItem) {
//             return isset($queueItem['q']) && strpos($queueItem['q'], 'GAIN') === 0;
//         });

//         $valid= false;
//         $data = [];
//         foreach($filtreurdata as $tri)
//         {
//             if(isset($tri['d'])){
//              if($tri['d']['n60'] > 0 || $tri['d']['n120'] > 0 || $tri['d']['nplus'] > 0){
                  
//                 $valid = true ;
//                 $data[] = $tri ;
//              }
//             }
    
//         }

//         if($valid){
//             // dd($data);
//             Mail::to($admins)->send(new SmsAlertMail($data));
//             // Vous pouvez retourner ou utiliser les données traitées comme nécessaire
//             return view('layouts.main', compact('data'));

//         }else{
//             return response()->json(['success' => false, 'message' => 'Aucune donnée à inclure dans le mail.']);
//         }

    
//     } else {
//         // Si la requête a échoué
//         $errorCode = $response->status();
//         return response()->json(['success' => false, 'error_code' => $errorCode]);
//     }
// }



public function main()
{
    // $admin = 'ouattaraadamajordan@gmail.com';
    $apiUrl = 'http://smsstat.wicsoft.cloud/api/getsmsstatLocal';

    $response = Http::get($apiUrl);

    if ($response->successful()) {
        $datas = $response->json();

        // Filtrer les éléments de la table "queue" qui ont "q" égal à "GAIN"
        $data = array_filter($datas['data2']['queue'], function ($queueItem) {
            return isset($queueItem['q']) && Str::startsWith($queueItem['q'], 'G');
        });
       
        // Filtrer les éléments qui ont l'intervalle de temps ou le dernier élément (sms) en attente envoyé
        $data = array_filter($data, function ($queueItem) {
            return isset($queueItem['d']) && $this->isIntervalSent($queueItem['d']);
        });
        

        // Vérifier s'il reste des éléments dans la table "queue" après le filtre
        if (!empty($data)) {

            $adminOuatt= [
                'coulcoul.aziz@gmail.com',
                'ouattaraadamajordan@gmail.com',
                'co-gerante@groupegain.com',
                'charge-exploitation@groupegain.com',
            ];

            $adminCoul= [
                // 'ouattaraadamajordan@gmail.com' ,
                // 'bayokader9@gmail.com',
                
                'coulcoul.aziz@gmail.com',
                'ouattaraadamajordan@gmail.com',
                'cos@wic.ci',

            ];                   

            $adminBoss= [
                
                // 'bayokader9@gmail.com',
               

                'coulcoul.aziz@gmail.com',
                'ouattaraadamajordan@gmail.com',
                'cos@wic.ci',
                'co-gerante@groupegain.com',
                'charge-exploitation@groupegain.com',
                
            ];

            // Envoyer l'e-mail pour n60 si des données sont présentes
            $this->sendMailForInterval( $data, "n60", $adminOuatt);
            $this->sendMailForInterval( $data, "n120", $adminCoul );
            $this->sendMailForInterval( $data, "nplus", $adminBoss );

            return view('layouts.main', compact('data'));
        } else {
            // Aucune donnée à envoyer
            return response()->json(['success' => false, 'message' => 'Aucune donnée à envoyer.']);
        }
    } else {
        $errorCode = $response->status();
        return response()->json(['success' => false, 'error_code' => $errorCode]);
    }
}

// Fonction pour envoyer l'e-mail pour un intervalle spécifique si des données sont présentes
private function sendMailForInterval( $data, $interval, $recipient)
{
    $filteredData = array_filter($data, function ($queueItem) use ($interval) {
        return isset($queueItem['d'][$interval]) && $queueItem['d'][$interval] > 0 && $queueItem['d'][$interval] !== null;
    });

    if (!empty($filteredData)) {
        Mail::to($recipient)->send(new SmsAlertMail($filteredData));
    }
}

// Fonction pour vérifier si l'intervalle de temps ou le dernier élément (sms) en attente a été envoyé
private function isIntervalSent($d)
{
    $intervalsToCompare = ["n60", "n120", "nplus"];
    foreach ($intervalsToCompare as $interval) {
        if (isset($d[$interval]) && $d[$interval] > 0 && $d[$interval] !== null) {
            return true;
        }
    }
    return false;
}




// ********************************Fonction d'envoi de mails utilisabe dans un boutton(au click du boutton ) ****************************


public function smsproalert()
{

    $admins = [
        'ouattaraadamajordan@gmail.com',
        // 'cos@wic.ci',
        // 'coulcoul.aziz@gmail.com',
        // 'ouattaraadam171717@gmail.com',
        // Ajoutez d'autres adresses e-mail au besoin
    ];
    // Définir l'URL de l'API
    $apiUrl = 'http://smsstat.wicsoft.cloud/api/getsmsstatLocal';

    // Effectuer une requête HTTP GET pour récupérer les données
    $response = Http::get($apiUrl);

    // Vérifier si la requête a réussi (statut 2xx)
    if ($response->successful()) {
        // Obtenir les données JSON de la réponse
        $datas = $response->json();

        // Filtrer les éléments de la table "queue" qui commencent par "GAIN"
        $filteredQueue = collect($datas['data2']['queue'])->filter(function ($queueItem) {
            return isset($queueItem['q']) && strpos($queueItem['q'], 'GAIN') === 0;
        });

        $valid= false;
        $data = [];
        foreach($filteredQueue as $tri)
        {
            if(isset($tri['d'])){
             if($tri['d']['n60'] > 0 || $tri['d']['n120'] > 0 || $tri['d']['nplus'] > 0){
                  
                $valid = true ;
                $data[] = $tri ;
             }
            }
    
        }
         // Envoyer l'e-mail
        // Mail::to($admins)->send(new SmsAlertMail($data));
        return view('emails.smsAlert', compact('data'));

    } else {
        //Si La requête a échoué
        $errorCode = $response->status();
        return response()->json(['success' => false, 'error_code' => $errorCode]);
    }
}

    // public function main(){

            //     $admin = 'ouattaraadamajordan@gmail.com';
            //     // Définir l'URL de l'API
            //     $apiUrl = 'http://smsstat.wicsoft.cloud/api/getsmsstatLocal';
            
            //     // Effectuer une requête HTTP GET pour récupérer les données
            //     $response = Http::get($apiUrl);
            
            //     // Vérifier si la requête a réussi (statut 2xx)
            //     if ($response->successful()) {
            //         // Obtenir les données JSON de la réponse
            //         $data = $response->json();
            
            //          // Envoyer l'e-mail
            //         Mail::to($admin)->send(new SmsAlertMail($data));
            //         // return view('layouts.main', compact('data'));
                    

            //     // Vous pouvez retourner ou utiliser les données traitées comme nécessaire
            //      return response()->json(['success' => true, 'data' => $data]);

            
            //     } else {
            //         //Si La requête a échoué
            //         $errorCode = $response->status();
            //         return response()->json(['success' => false, 'error_code' => $errorCode]);
            //     }
            //     // return view('layouts.main');
    // }

//************************************************************************************************************************** */ 


// public function smsproalert()
// {
        //     $admin = 'ouattaraadamajordan@gmail.com';
        //     $apiUrl = 'http://smsstat.wicsoft.cloud/api/getsmsstatLocal';
        //     $responseData = file_get_contents($apiUrl);
        //     $tableData = json_decode($responseData, true);

        //     // Assurez-vous que $tableData['data2']['queue'] est défini et est un tableau
        //     $queueData = isset($tableData['data2']['queue']) && is_array($tableData['data2']['queue']) 
        //         ? $tableData['data2']['queue'] 
        //         : [];

        //     // Filtrer les éléments dont "n10" est supérieur à 0
        //     $filteredData = array_filter($queueData, function ($item) {
        //         return isset($item['d']['n10']) && $item['d']['n10'] > 0;
        //     });
            
        //     // Envoyer l'e-mail
        //     Mail::to($admin)->send(new SmsAlertMail($filteredData));
            
        //     return view('emails.smsAlert', compact('filteredData'));
// }    

// *****************************************
// public function smsproalert()
// {
        //     // Adresse e-mail du destinataire
        //     $admin = 'ouattaraadamajordan@gmail.com';

        //     // URL de l'API
        //     $apiUrl = 'http://smsstat.wicsoft.cloud/api/getsmsstatLocal';

        //     // Récupérer les données depuis l'API
        //     $responseData = file_get_contents($apiUrl);

        //     // DD($responseData);

        //     // Vérifier si la récupération des données a réussi
        //     if ($responseData !== false) {
        //         // Convertir les données JSON en tableau associatif
        //         $tableData = json_decode($responseData, true);

        //         // Vérifier si les données nécessaires sont présentes
        //         if (isset($tableData['data2']['queue']) && is_array($tableData['data2']['queue'])) {
        //             // Stocker les données dans une variable
        //             $filteredData = $tableData['data2']['queue'];
            
        //             // Envoyer l'e-mail avec les données
        //             Mail::to($admin)->send(new SmsAlertMail($filteredData));

        //             return view('emails.smsAlert', compact('filteredData'));

        //             // ... votre logique supplémentaire si nécessaire ...
        //         } else {
        //             // Gérer le cas où les données nécessaires ne sont pas présentes
        //             // Peut-être logger un avertissement ou envoyer une notification, etc.
        //         }
        //     } else {
        //        return 'erreur de reccuperation des donnees';
        //     }
// }
//µ****************************************

// public function smsproalert()
// {
        //     $admin = 'ouattaraadamajordan@gmail.com';
        //     $apiUrl = 'http://smsstat.wicsoft.cloud/api/getsmsstatLocal';
        //     $responseData = file_get_contents($apiUrl);
        //     $tableData = json_decode($responseData, true);

        //     // Assurez-vous que $tableData['data2']['queue'] est défini et est un tableau
        //     $filteredData = isset($tableData['data2']['queue']) && is_array($tableData['data2']['queue']) 
        //         ? $tableData['data2']['queue'] 
        //         : [];

        //         // dd( $filteredData);

        //     // Filtrer les éléments dont "n10" est supérieur à 0
            
        //     // Ajouter la logique pour trouver le dernier intervalle de temps non null
        //     foreach ($filteredData as &$item) {
        //         $lastSentInterval = collect($item['d'])
        //             ->reject(function ($value) {
        //                 return $value === null;
        //             })
        //             ->keys()
        //             ->last();

        //         // Ajouter le dernier intervalle trouvé à l'élément
        //         $item['lastSentInterval'] = $lastSentInterval;
        //     }
            
        //     // Envoyer l'e-mail
        //     Mail::to($admin)->send(new SmsAlertMail($filteredData));
            
        //     return view('emails.smsAlert', compact('filteredData'));
// }



// public function smsproalert(){

        //     $admin = 'ouattaraadamajordan@gmail.com';
        //     $apiUrl = 'http://smsstat.wicsoft.cloud/api/getsmsstatLocal';
        //     $responseData = file_get_contents($apiUrl);
        //     $tableData = json_decode($responseData, true);

        //     // Rendre la vue avant d'envoyer l'e-mail
        //     return view('emails.smsProMail', compact('tableData'))
        //         ->with('admin', $admin);  // Vous pouvez transmettre d'autres données à la vue si nécessaire

        //  



        // $admin='ouattaraadamajordan@gmail.com';
        // $apiUrl = 'http://smsstat.wicsoft.cloud/api/getsmsstatLocal';
        // $responseData = file_get_contents($apiUrl);
        // $tableData = json_decode($responseData, true);


        // Mail::to($admin)->send(new smsProMail($tableData));

        // return view('emails.smsProMail',compact('tableData'));
        // }
   //}


}
