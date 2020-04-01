<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * Controller qui Permet de créer une annonce via un formulaire
     * 
     * @Route("/ads/new", name="ads_create")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager )
    {
        
        //On crée un nouvel objet Ad
        $ad = new Ad();
        //On crée un formulaire avec la fonction createForm et on lui passe la classe AdType et notre nouvel objet $ad
        $form  = $this->createForm(AdType::class, $ad);
        //Fonction qui permet de faie le lien entre les paramètre rentrés en requête et l'objet Ad
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        //pour chaques image du slider on ajoute les images à l'entité image avec l'id de l'annonce
            foreach($ad->getImages() as $image) { 
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());
        
            $manager->persist($ad);
            $manager->flush();
            
            //On créer un message flash pour informer l'utilisateur que l'envoi a bien fonctionné
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée!"
            );

            //Redirection après la création de l'annonce
            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'éditer une annonce grace au paramConverter passé dans les paramêtre de la fonction
     * 
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")
     *
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager  ){

        $form  = $this->createForm(AdType::class, $ad);
        //Fonction qui permet de faie le lien entre les paramêtre rentrés en requête et l'objet Ad
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //pour chaques image du slider on ajoute les images à l'entité image avec l'id de l'annonce
                foreach($ad->getImages() as $image) { 
                    $image->setAd($ad);
                    $manager->persist($image);
                }
            
                $manager->persist($ad);
                $manager->flush();
                
                //On créer un message flash pour informer l'utilisateur que l'envoi a bien fonctionné
                $this->addFlash(
                    'success',
                    "L'annonce <strong>{$ad->getTitle()}</strong> a bien été mise à jour!"
                );
    
                //Redirection après la création de l'annonce
                return $this->redirectToRoute('ads_show',[
                    'slug' => $ad->getSlug()
                ]);
            }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * Permet de supprimer une annonces
     * 
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la supprimer")
     * 
     * @param Ad $ad
     * @param ObjectManager $manger
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager)
    {
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée!"
        );

        return $this->redirectToRoute("ads_index");
    }

    /**
     * Permet d'afficher une seule annonce grace au paramConverter passé dans les paramêtre de la fonction
     * 
     * @Route("/ads/{slug}", name="ads_show")
     *
     * @return Response
     */
    public function show(Ad $ad)
    {
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

}
