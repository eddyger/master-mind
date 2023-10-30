<?php

namespace App\Controller;

use App\Component\MasterMindEngine;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MasterController extends AbstractController
{

    protected const MASTER_MIND_KEY = 'master_mind';

    public function __construct(protected MasterMindEngine $masterMindEngine, protected RequestStack $requestStack)
    {
    }

    #[Route('/', name:'app_home')]
    public function index() : Response
    {
        $masterMindEngine = $this->requestStack->getSession()->get(self::MASTER_MIND_KEY);
        if (null === $masterMindEngine){
            $this->masterMindEngine->newGame();
            $this->requestStack->getSession()->set(self::MASTER_MIND_KEY , $this->masterMindEngine);
        } else {
            $this->masterMindEngine = $masterMindEngine;
        }

        return $this->render('master_mind/index.html.twig', ['game' => $this->masterMindEngine->getGame()]);
    }

    #[Route('/new', name: 'app_new_game')]
    public function newGame(): Response
    {
        $this->masterMindEngine->newGame();
        $this->requestStack->getSession()->set(self::MASTER_MIND_KEY , $this->masterMindEngine);
        $this->requestStack->getSession()->set('hasWon' , false);


        return $this->redirectToRoute('app_home');
    }

    #[Route('/play', name: 'app_play', methods: ['POST'])]
    public function play(Request $request) : Response
    {
        $masterMindEngine = $this->requestStack->getSession()->get(self::MASTER_MIND_KEY);
        $this->masterMindEngine = $masterMindEngine;
        $hasWon = $this->masterMindEngine->play($request->request->all('color'));
        $this->requestStack->getSession()->set('hasWon', $hasWon);
        $isAttemptExceeded = $this->masterMindEngine->isAttemptExceeded();

        $this->requestStack->getSession()->set(self::MASTER_MIND_KEY , $this->masterMindEngine);
        if (!$hasWon && $isAttemptExceeded){
            $this->addFlash('resultat' , 'PERDU');
        }else{
            $this->addFlash('resultat' , $hasWon ? 'GAGNE' : 'ESSAYE ENCORE');
        }
        return $this->redirectToRoute('app_home');
    }

}
