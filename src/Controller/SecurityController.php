<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class SecurityController extends AbstractController
{
    /**
     * @Route("/security", name="app_security")
     */
    public function index(): Response
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request,SessionInterface $session): Response
    {
        $session->clear();
        return $this->render('serveur/logout.html.twig', [
            'txt' => 'Vous avez bien quitté la session Merci!!'
        ]);
    }

        /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmation(Request $request,EntityManagerInterface $manager,SessionInterface $session): Response
    {
		$nom = $request->request->get("nom");
        $password = $request->request->get("password");
        $vs = $session -> get("nomsession");
        $utilisateur = $manager -> getRepository(Utilisateur::class) -> findOneBy([ 'Login' => $nom ]);
        //$utilisateur = $manager -> getRepository(Utilisateur::class)-> findOneById($userId);
        if($utilisateur == NULL){
            $txt = "Non valide!Vous n'avez pas le droit entre dans cette Page!!";
        }
        else{
            if($utilisateur -> getPassword() == $password){
                if($utilisateur->getId() == 1){
                    $txt = "Good Password Welcome Admin";
                    $userId = $utilisateur->getId();
                    $val=$userId;
                    $session -> set("nomsession",$val);
                    
                }
                else{
                    $txt = "Good Password! Welcome user";
                    $userId = $utilisateur->getId();
                    $val=44;
                    $session -> set("nomsession",$val);
                    $session->clear();
                }
            }
            else{
                $txt = "Bad Password!";
                $session->clear();
            }
        }

        return $this->render('serveur/confirmation.html.twig', [
            'title' => "Comfimation",
            'nom' => $nom,
            'txt' => $txt,
        ]);
    }
}
