<?php
/**
 *  exemple de controller pour traiter les lettres de motivation en automatique dans  un saas
 * le formulaire s'appelle CoverType
 * 
 * @author Yohann dev
 * @created: 2023-07-11
 */
namespace App\Controller;

use App\service\AIs;
use App\Form\CoverType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AppController extends AbstractController
{
    #[Route('/app', name: 'app_app', methods: ['GET', 'POST'])]
    public function index(Request $request ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->isPaiement()) {

        $form = $this->createForm(CoverType::class);	

        $form->handleRequest($request);
        $message = [];
        

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            /* ************** Ask AI **************************** */
            $api = new AIs;
            $ai  = 'TogetherAI';
            switch ($ai) {
                case 'OpenAI':
                    $OpenAIapiKey = $_ENV['OPENAI_API_KEY'];
                    $model = 'gpt-3.5-turbo'; //'gpt-4';
                    $message = $api->getOpenAI( $data, $OpenAIapiKey, $model);
                    break;
                case 'TogetherAI':
                    $TogetherAIapiKey = $_ENV['TOGETHER_KEY'];
                    //$model = 'mistralai/Mistral-7B-Instruct';
                    $modeltogether = 'meta-llama/Llama-3.3-70B-Instruct-Turbo';
                    $message = $api->getTogetherAI( $data, $TogetherAIapiKey, $modeltogether);
                    break;
            }

            // la ligne ci-dessous permet d'afficher dans la page web un résultat
            //dd($message);
        }

        return $this->render('app/index.html.twig', [
            'form' => $form,
            'message' => $message ?? null,
        ]);
    }
    else {
        return $this->redirectToRoute('app_home');
        }
    }
}
