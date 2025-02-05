<?php

namespace App\service;

use OpenAI;

class AIs
{
    public function getOpenAI($data, $apiKey, $model) :array
    {
        $client = OpenAI::client($apiKey);

        $result = $client->chat()->create([
            'model' => $model,
            'messages' => [
                ['role' => 'user', 'content' => 'Mon nom est ' . $data['nom'] . '. '],
                ['role' => 'user', 'content' => 'Mon prénom est ' . $data['prenom'] . '. ' ],
                ['role' => 'user', 'content' => 'Mon diplôme est ' . $data['diplome']  . '. '],
                ['role' => 'user', 'content' => 'Mon entreprise est ' . $data['entreprise']  . '. '],
                ['role' => 'user', 'content' => 'Mon poste cible est ' . $data['poste'] . '. ' ],
                ['role' => 'user', 'content' => 'Mon annonce est ' . $data['annonce']  . '. '],
                ['role' => 'user', 'content' => 'Ecrit une lettre de motivation convaincante et personalisée pour mon poste cible' ],
            ],  
        ]);
        // Récupération du message généré
        if (isset($result['choices'][0]['message']['content'])) {
            $message = [ 'content' => $result->toArray()['choices'][0]['message'] , 'error' => ''];
            return $message;
        } else {
            return [ 'content' => '', 'error' => $result['error']['message']];
        }
    }

    public function getTogetherAI($data, $apiKey, $model) :array
    {
        
        $url = "https://api.together.xyz/v1/chat/completions";
        $dataFormed = [
            "model" => $model, // Modèle gratuit
            "messages" => [
                ['role' => 'user', 'content' => 'Mon nom est ' . $data['nom'] . '. '],
                ['role' => 'user', 'content' => 'Mon prénom est ' . $data['prenom'] . '. ' ],
                ['role' => 'user', 'content' => 'Mon diplôme est ' . $data['diplome']  . '. '],
                ['role' => 'user', 'content' => 'Mon entreprise est ' . $data['entreprise']  . '. '],
                ['role' => 'user', 'content' => 'Mon poste cible est ' . $data['poste'] . '. ' ],
                ['role' => 'user', 'content' => 'Mon annonce est ' . $data['annonce']  . '. '],
                ['role' => 'user', 'content' => 'Ecrit une lettre de motivation convaincante et personalisée pour mon poste cible' ],
            ],
            "temperature" => 0.7,
            "max_tokens" => 500
        ];
        
        $headers = [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ];
        
        // Initialisation de cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataFormed));
        
        // Exécution de la requête
        $response = curl_exec($ch);
        curl_close($ch);
        
        // Décodage de la réponse JSON
        $responseData = json_decode($response, true);
        
        // Récupération du message généré
        if (isset($responseData['choices'][0]['message']['content'])) {
            $message = [ 'content' => $responseData['choices'][0]['message']['content'] , 'error' => ''];
            dump($message);
            return $message;
        } else {
            return [ 'content' => '', 'error' => $responseData['error']['message']];
        }
       
    }
}