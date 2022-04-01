<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Entity\Wish;
use App\Form\PersonneType;
use App\Form\WishType;
use App\Repository\PersonneRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/etudiant', name: 'etudiant')]
class EtudiantController extends AbstractController
{
    #[Route('/tirage', name: '_tirage')]
    public function tirage(
        EntityManagerInterface $em,
        PersonneRepository $personneRepository
    ): Response
    {
        $dateFinFormation = new DateTime('2022-07-01');

        // sélectionne les étudiants n'ayant pas encore été tiré au sort

        $prochains = $personneRepository->findNonTires();

        // cherche le vendredi d'après la date la plus grande à laquelle un gateau est ramené

        $etudiants = $personneRepository->findTires();

        $maxDate = new DateTime();
        if ($etudiants) {
            $maxDate = $etudiants[0]
                ->getDateDuGateau()
                ->modify('next friday');
        }

        // tirage du prochain si possible

        if ($maxDate < $dateFinFormation && !empty($prochains)) {
            $prochain = $prochains[rand(0, count($prochains))]
                ->setTireAuSort(true)
                ->setDateDuGateau($maxDate);

            $em->persist($prochain);
            $em->flush();
            $this->addFlash(
                'success',
                $prochain->getPrenom() . ' ' . $prochain->getNom() . 'a été désigné.');
        }
        return $this->redirectToRoute('etudiant_liste');
    }

    #[Route('/liste', name: '_liste')]
    public function liste(
        PersonneRepository $personneRepository
    ): Response
    {
        $etudiants = $personneRepository->findAll();
        $prochain = null;
        $vendredi = new DateTime();
        $vendredi->modify('next friday');

        // récupère le prochain étudiant à ramener un gateau

        foreach ($etudiants as $etudiant) {
            if ($etudiant->getTireAuSort() && $etudiant->getDateDuGateau() === $vendredi) {
                $prochain = $etudiant;
                break;
            }
        }
        return $this->render(
            'etudiant/liste.html.twig',
            compact("etudiants", "prochain", "vendredi")
        );
    }
    #[Route('/detail{id}', name: '_detail', requirements: ['id' => '\d+'])]
    public function detail(
        Personne $etudiant
    ): Response
    {
        return $this->render(
            'etudiant/detail.html.twig',
            compact("etudiant"));
    }
    #[Route('/ajout', name: '_ajout')]
    public function ajouter(
        EntityManagerInterface $em,
        Request $request
    ): Response
    {
        $personne = new Personne();
        $personneForm = $this->createForm(PersonneType::class, $personne);

        $personneForm->handleRequest($request);
        if ($personneForm->isSubmitted() && $personneForm->isValid()) {
            $personne->setTireAuSort(false);
            $em->persist($personne);
            $em->flush();
            $this->addFlash('success','L\'étudiant a bien été ajouté');
            return $this->redirectToRoute('etudiant_detail', ["id" => $personne->getId()]);
        }

        return $this->render(
            'etudiant/ajout.html.twig',
            ['personneForm' => $personneForm->createView()]);
    }


}
