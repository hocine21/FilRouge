<?php

namespace App\Controller\api;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProduitController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/produits", name="api_produit_add", methods={"POST"})
     */
    public function addProduit(Request $request, ValidatorInterface $validator): Response
    {
        // Récupérer les données du formulaire
        $nomProduit = $request->get('nomProduit');
        $largeurProduit = $request->get('largeurProduit');
        $epaisseurProduit = $request->get('epaisseurProduit');
        $masseProduit = $request->get('masseProduit');
        $formeProduit = $request->get('formeProduit');
        $hauteurProduit = $request->get('hauteurProduit');
        $sectionProduit = $request->get('sectionProduit');
        $marge = $request->get('marge');
        $prixML = $request->get('prixML');

        // Récupérer le fichier uploadé
        /** @var UploadedFile $imageFile */
        $imageFile = $request->files->get('image');

        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $imageFile->getClientOriginalExtension();
            $newFilename = uniqid() . '.' . $extension;

            try {
                // Déplacer le fichier vers le répertoire des images
                $imageFile->move($this->getParameter('pictures_directory'), $newFilename);
            } catch (FileException $e) {
                return $this->json(['error' => 'Erreur lors du téléchargement de l\'image.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            // Créer un nouvel objet Produit
            $produit = new Produit();
            $produit->setNomProduit($nomProduit);
            $produit->setImage($newFilename);
            $produit->setLargeurProduit($largeurProduit !== null ? (float) $largeurProduit : null);
            $produit->setEpaisseurProduit($epaisseurProduit !== null ? (float) $epaisseurProduit : null);
            $produit->setMasseProduit($masseProduit !== null ? (float) $masseProduit : null);
            $produit->setFormeProduit($formeProduit ?: null); // FormeProduit is a string that can be null
            $produit->setHauteurProduit($hauteurProduit !== null ? (float) $hauteurProduit : null);
            $produit->setSectionProduit($sectionProduit !== null ? (float) $sectionProduit : null);
            $produit->setMarge($marge !== null ? (float) $marge : null);
            $produit->setPrixML($prixML !== null ? (float) $prixML : null);

            // Valider l'objet Produit
            $errors = $validator->validate($produit);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }
                return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
            }

            // Persister l'objet Produit en base de données
            $this->entityManager->persist($produit);
            $this->entityManager->flush();

            return $this->json(['success' => true, 'message' => 'Produit ajouté avec succès'], Response::HTTP_CREATED);
        } else {
            return $this->json(['error' => 'Image non fournie.'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/api/produits", name="api_produits_get", methods={"GET"})
     */
    public function getProduits(): Response
    {
        $repository = $this->entityManager->getRepository(Produit::class);
        $produits = $repository->findAll();

        // Transformer les objets Produit en un tableau associatif pour l'envoi JSON
        $produitsArray = [];
        foreach ($produits as $produit) {
            $produitsArray[] = [
                'id' => $produit->getId(),
                'nomProduit' => $produit->getNomProduit(),
                'image' => $produit->getImage(),
                'largeurProduit' => $produit->getLargeurProduit(),
                'epaisseurProduit' => $produit->getEpaisseurProduit(),
                'masseProduit' => $produit->getMasseProduit(),
                'formeProduit' => $produit->getFormeProduit(),
                'hauteurProduit' => $produit->getHauteurProduit(),
                'sectionProduit' => $produit->getSectionProduit(),
                'marge' => $produit->getMarge(),
                'prixML' => $produit->getPrixML(),
            ];
        }

        return $this->json($produitsArray);
    }
}
